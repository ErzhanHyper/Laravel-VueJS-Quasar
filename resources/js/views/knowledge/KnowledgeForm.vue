<template>
    <q-card style="width: 1200px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">База знаний (ВНД)</div>
            <q-space/>
            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section style="max-height: 70vh" class="scroll ">
            <div class="row q-pb-md">
                <div class="col">
                    <q-input filled v-model="item.name" label="Наименование *"/>
                </div>
            </div>

            <div class="row q-pb-md q-gutter-md">
                <div class="col">
                    <select-type-knowledge v-model="item.type"/>
                </div>
                <div class="col">
                    <select-agency v-model="item.agency"/>
                </div>
                <div class="col">
                    <q-input v-model="item.date_approve" filled type="date" hint="Дата утверждения"/>
                </div>
                <div class="col">
                    <q-input v-model="item.date_start" filled type="date" hint="Дата вступления в силу"/>
                </div>
            </div>

            <div class="row q-pb-md q-gutter-md">
                <div class="col">
                    <select-department v-model="item.department"/>
                </div>
            </div>

            <div class="row  q-gutter-md q-pb-md">
                <div class="col">
                    <q-file color="teal" filled v-model="item.static" label="Прикрепить документ">
                        <template v-slot:prepend>
                            <q-icon name="cloud_upload"/>
                        </template>
                    </q-file>
                </div>

                <div class="col">
                    <q-file color="teal" filled v-model="item.dynamic" label="Динамическая версия документа">
                        <template v-slot:prepend>
                            <q-icon name="cloud_upload"/>
                        </template>
                    </q-file>
                </div>
            </div>

        </q-card-section>

        <q-card-section>
            <q-banner inline-actions class="text-white bg-red" id="main_banner" v-if="showBanner">
                <div v-for="error in errors">
                    <span v-for="e in error">{{ e }}</span>
                </div>
            </q-banner>
        </q-card-section>

        <q-card-section>
            <q-space/>
            <div class="q-gutter-sm">
                <q-btn label="Сохранить" color="primary" @click="storeData" :loading="btnLoading"/>
            </div>
        </q-card-section>

    </q-card>

</template>

<script>
import SelectTypeKnowledge from "@/components/form_components/SelectTypeKnowledge.vue";
import SelectAgency from "@/components/form_components/SelectAgency.vue";
import SelectDepartment from "@/components/form_components/SelectDepartment.vue";
import {storeKnowledge} from "@/services/knowledge";
import {Notify} from "quasar";

export default {
    components: {SelectAgency, SelectTypeKnowledge, SelectDepartment},
    data() {
        return {
            item: {},

            errors: [],
            types: [],

            showBanner: false,
            btnLoading: false
        }
    },

    methods: {
        storeData() {
            // this.item.department_id = item.department.id
            this.showBanner = false
            storeKnowledge(this.item).then((response) => {
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => {
            });
        }
    }
}
</script>

<style scoped>

</style>
