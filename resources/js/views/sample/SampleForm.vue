<template>
    <q-card style="width: 1200px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Шаблоны документов</div>
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

            <div class="row  q-gutter-md q-pb-md">
                <div class="col">
                    <q-file color="teal" filled v-model="item.file" label="Прикрепить файл">
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
            <div class="text-right flex">
                <q-space/>
                <div class="q-gutter-sm">
                    <q-btn label="Сохранить" color="primary" @click="storeData" :loading="loadingBtn"/>
                </div>
            </div>
        </q-card-section>

    </q-card>
</template>

<script>
import {Notify} from "quasar";
import {storeSample} from "@/services/sample";

export default {

    data(){
        return{
            item: {},
            errors: [],

            loadingBtn: false,
            showBanner: false,
        }
    },

    methods: {
        storeData(){
            this.showBanner = false
            this.loadingBtn = true
            storeSample(this.item).then((response) => {
                emitter.emit('sample_dialog');
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => {
                this.loadingBtn = false
            });
        }
    }
}
</script>

<style scoped>

</style>
