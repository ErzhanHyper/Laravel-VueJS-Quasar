<template>
    <q-card style="width: 1200px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Обучающие материалы</div>

            <q-space/>

            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section style="max-height: 70vh" class="scroll ">
            <div class="row q-pb-md">
                <div class="col">
                    <q-input
                        filled
                        v-model="item.name"
                        label="Наименование *"
                    />
                </div>
            </div>

            <div class="row q-pb-md">
                <div class="col">
                    <q-select
                        filled
                        v-model="item.catalog"
                        :options="catalogs"
                        option-value="id" :option-label="(item) => item.name"
                        label="Раздел"
                    />
                </div>
                <q-btn :icon="(!showInput) ? 'add' : 'remove'" color="primary" @click="showInput = !showInput"/>
            </div>

            <div class="row q-pb-md" v-if="showInput">
                <div class="col">
                    <q-input
                        filled
                        v-model="item.text"
                        label="Новый раздел *"
                    />
                </div>
                <q-btn icon="add" color="primary" @click="addCatalog"/>
            </div>

            <div class="row  q-gutter-md q-pb-md">
                <div class="col col-md-6">
                    <q-file color="teal" filled v-model="item.file" label="Прикрепить файл">
                        <template v-slot:prepend>
                            <q-icon name="cloud_upload"/>
                        </template>
                    </q-file>
                </div>
                <div class="col">
                    <a :href="item.file" target="_blank" v-if="item.file && (typeof this.item.file === 'string')">
                        <q-btn icon="description" label="Файл" color="dark"/>
                    </a>
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

import {storeLearning, storeLearningCatalog, getLearningCatalog} from "@/services/learning";
import {Notify} from "quasar";

export default {

    props: ['catalog'],

    data() {
        return {

            item: {
                catalog: this.catalog,
                catalog_id: this.catalog.id
            },

            errors: [],
            catalogs: [],

            showBanner: false,
            showInput: false,
            btnLoading: false

        }
    },

    methods: {

        storeData() {
            this.btnLoading = true
            this.showBanner = false
            storeLearning(this.item).then((response) => {
                emitter.emit('learning_dialog');
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => [
                this.btnLoading = false
            ])
        },

        getCatalog() {
            getLearningCatalog(this.params).then((response) => {
                this.catalogs = response
            })
        },

        addCatalog(){
            storeLearningCatalog({catalog: this.item.text}).then((response) => {
                this.getCatalog()
                this.item.catalog = response.data
                emitter.emit('learning_dialog');
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => [
                this.btnLoading = false
            ])
        },

    },



    created() {
        this.getCatalog()
    }
}
</script>

<style scoped>

</style>
