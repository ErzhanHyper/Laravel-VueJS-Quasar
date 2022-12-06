<template>
    <q-form id="main_filter">

        <div class="rows q-gutter-md">

            <q-input bottom-slots v-model="filter.name" label="Наименование" dense style="width: 290px">
                <template v-slot:append>
                    <q-icon name="close" @click="filter.name = ''" class="cursor-pointer"/>
                </template>
            </q-input>

            <q-input bottom-slots v-model="filter.agency" label="Орган утверждения" dense @click="showSelect = true" @blur="showSelect = false"
                     style="width: 290px; position: relative">
                <template v-slot:append>
                    <q-icon name="close" @click="filter.agency = ''" class="cursor-pointer"/>
                </template>
                <template v-slot:prepend v-if="showSelect">
                    <div class="q-pa-md" style="top: 40px; position: absolute; z-index: 10; background: #fff; font-size: 12px;">
                        <q-list dense bordered padding class="rounded-borders">
                            <q-item clickable v-ripple v-for="el in agency_list" @click="setAgency(el.name)">
                                <q-item-section>
                                    {{ el.name }}
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </div>
                </template>
            </q-input>


            <q-select
                dense
                :model-value="filter.department"
                v-model="filter.department"
                use-input
                hide-selected
                fill-input
                input-debounce="0"
                :options="options"
                :option-value="'id'"
                :option-label="'name'"
                label="Владелец"
                @filter="filterFn"
                style="width: 290px;"

            >
                <template v-slot:no-option>
                    <q-item>
                        <q-item-section class="text-grey">
                            No results
                        </q-item-section>
                    </q-item>
                </template>
            </q-select>

            <q-select
                dense
                :model-value="filter.file_type"
                v-model="filter.file_type"
                use-input
                input-debounce="0"
                label="Тип ВНД"
                :options="docs_type_list"
                @filter="filterFn"
                :option-value="'id'"
                :option-label="'name'"
                style="width: 220px;"
            >
                <template v-slot:no-option>
                    <q-item>
                        <q-item-section class="text-grey">
                            Нет результатов
                        </q-item-section>
                    </q-item>
                </template>
            </q-select>


            <q-input v-model="filter.date_approve" dense type="date" hint="Дата утверждения" style="width: 116px"/>
            <q-input v-model="filter.date_start" dense type="date" hint="Дата вступления в силу" style="width: 116px"/>

        </div>

        <q-separator class="q-mt-lg"/>
        <div class="q-pt-lg q-gutter-sm">
            <q-btn class="bg-success" text-color="white" size="12px" @click="setFilter()">Применить фильтр</q-btn>
            <q-btn class="bg-warning" text-color="white" size="12px" @click="resetFilter()">Сбросить фильтр</q-btn>
        </div>

    </q-form>
</template>

<script>

import API from '../utils/api'
import {ref} from 'vue'

export default {

    data() {
        return {
            position: 1,
            employee_select: [],
            docs_type_list: [],
            departments: [],
            showSelect: false,
            agency_list: [],
            options: this.departments,
            filter: {
                date_approve: '',
                date_start: '',
                name: '',
                agency: '',
                department: null,
                department_id: null,
                file_type_id: null,
                file_type:null
            },
        }
    }
    ,

    methods: {

        filterFn(val, update) {
            if (val === '') {
                update(() => {
                    this.options = this.departments
                })
                return
            }

            update(() => {
                const needle = val.toLowerCase()
                this.options = this.departments.filter(v => v.toLowerCase().indexOf(needle) > -1)
            })
        },

        setAgency(val) {
            this.filter.agency = val
            this.showSelect = false
        },

        getDepartments() {
            API.post('department/all').then(response => {
                this.departments = response.data
            })
        },

        getAgency() {
            API.post('docs/agency/all').then(response => {
                this.agency_list = response.data
            })
        },

        getDocsType() {
            API.post('docs/type/all').then(response => {
                this.docs_type_list = response.data
            })
        },

        setFilter() {
            this.filter.department_id = (this.filter.department) ? this.filter.department.id : null
            this.filter.file_type_id = (this.filter.file_type) ? this.filter.file_type.id : null
            this.$emitter.emit("main_filter_event", this.filter);
        },

        resetFilter() {
            this.filter = {}
            this.$emitter.emit("main_filter_event", this.filter);
        },

    },

    created() {
        this.getAgency()
        this.getDocsType()
        this.getDepartments()
    }

}
</script>
