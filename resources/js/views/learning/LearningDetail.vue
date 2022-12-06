<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add" @click="dialogAdd = true"/>
            <q-btn round color="red" icon="delete" @click="dialogDeleteCatalog = true"/>
        </div>
    </div>

    <q-markup-table flat bordered cell :wrap-cells="true" v-if="items.length > 0">
        <thead>
        <tr>
            <th class="text-left" v-for="column in columns"><span class="text-subtitle2"> {{ column }}</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items">
            <td class="text-left ">
                <a :href="item.file" target="_blank"><span class="text-subtitle1"><q-icon name="description" size="sm"/> {{ item.name }}</span></a>
            </td>
            <td class="text-left">
                <div class="q-gutter-md">
                    <q-checkbox label="Ознакомлен(а)" v-model="item.checked"
                                v-if="!item.checked"
                                @update:model-value="storeViewer(item.id)"/>
                    <q-chip size="12px" v-else>Вы ознакомлены</q-chip>
                    <q-btn color="red" icon="delete" round size="sm" @click="removeData(item.id)"/>
                </div>
            </td>
        </tr>
        </tbody>
    </q-markup-table>

    <q-dialog v-model="dialogDeleteCatalog">
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <div>Вы действительно хотите удалить запись?</div>
                <q-space/>
                <q-btn icon="close" flat round dense v-close-popup/>
            </q-card-section>
            <q-card-section>
                <q-btn label="Удалить" @click="removeData" color="red"/>
            </q-card-section>
        </q-card>
    </q-dialog>

    <q-dialog v-model="dialogAdd" transition-show="rotate" transition-hide="rotate">
        <learning-form :catalog="catalog"/>
    </q-dialog>

</template>

<script>


import {getLearningCatalogDetail, storeLearningDetailViewer} from "@/services/learning";
import LearningForm from "@/views/learning/LearningForm.vue";

export default {

    props: ['id'],

    components: {LearningForm},

    data() {
        return {
            items: [],
            catalog: [],
            columns: ['Наименование', 'Действие'],
            dialogDeleteCatalog: false,
            dialogAdd: false
        }
    },

    methods: {
        getList() {
            this.items = [];
            getLearningCatalogDetail(this.id).then((response) => {
                this.catalog = response.catalog
                this.items = response.files
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        storeViewer(id) {
            storeLearningDetailViewer({learning_id: id}).then((response) => {
                this.getList()
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        removeData(id) {

        }

    },

    created() {
        this.getList()
    },

    mounted() {
        emitter.on('learning_dialog', () => {
            this.dialogAdd = false
            this.getList()
        })
    }

}
</script>

<style scoped>

</style>
