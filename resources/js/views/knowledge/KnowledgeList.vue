<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn rounded color="purple" label="Ссылки НПА" icon="information" @click="dialogNpa = true"/>
            <q-btn round color="primary" icon="add" @click="dialogAdd = true"/>
        </div>
    </div>

    <main-filter />

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left" v-for="column in columns"><span class="text-subtitle2"> {{ column }}</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items">
            <td class="text-left"> <a href="#" class="link" @click="showItemDialog(item)"> <q-icon name="description"/> {{ item.name }}</a> </td>
            <td class="text-left">{{ (item.file_type) ? item.file_type.name : '-' }}</td>
            <td class="text-left">{{ (item.docs_regulation.department) ? item.docs_regulation.department.name : '-' }}</td>
            <td class="text-left">{{ item.docs_regulation.agency }}</td>
            <td class="text-left">{{ item.docs_regulation.date_approve }}</td>
            <td class="text-left">{{ item.docs_regulation.date_start }}</td>
        </tr>
        </tbody>
    </q-markup-table>

    <div class="row justify-center q-mt-md">
        <q-pagination
            :min="1"
            :max="totalPage"
            color="grey-8"
            @click="getList()"
            v-model="page"
        />
    </div>


    <q-dialog v-model="dialogNpa" persistent>
        <npa-form />
    </q-dialog>

    <q-dialog v-model="dialogAdd" persistent>
        <knowledge-form />
    </q-dialog>

    <q-dialog v-model="dialogShow" persistent>
        <knowledge-detail />
    </q-dialog>


</template>

<script>

import {getKnowledgeList} from "@/services/knowledge";
import MainFilter from "@/components/MainFilter.vue";
import NpaForm from "@/components/NpaForm.vue";
import KnowledgeForm from "@/views/knowledge/KnowledgeForm.vue";
import KnowledgeDetail from "@/views/knowledge/KnowledgeDetail.vue";
export default {

    components: {
        KnowledgeDetail,
        KnowledgeForm,
        MainFilter,
        NpaForm
    },

    data() {
        return {
            columns: ['Наименование', 'Тип', 'Владелец', 'Орган Утверждения', 'Дата утверждения', 'Дата вступления в силу'],
            items: [],

            params: {},

            page: 1,
            totalPage: 1,

            isLoad: true,
            dialogNpa: false,
            dialogAdd: false,
            dialogShow: false
        }
    },

    methods: {

        showItemDialog(item){
            this.dialogShow = true
            console.log(item)
        },

        getList() {
            this.items = [];
            this.params.page = this.page
            getKnowledgeList(this.params).then((response) => {
                this.items = response.data
                this.totalPage = response.meta.last_page
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        getFilter() {
            this.$emitter.on('main_filter_event', (filter) => {
                this.params = filter
                this.params.page = this.page
                this.getList()
            })
        },


    },

    mounted() {
        this.getList();
        this.getFilter();
    }
}
</script>

