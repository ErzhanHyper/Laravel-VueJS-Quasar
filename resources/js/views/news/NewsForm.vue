<template>
    <q-card style="width: 1200px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Новости</div>

            <q-space/>

            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section style="max-height: 70vh" class="scroll ">
            <div class="row q-pb-md">
                <div class="col">
                    <q-input
                        filled
                        v-model="item.title"
                        label="Заголовок *"
                    />
                </div>
            </div>

            <div class="row q-gutter-md q-pb-md">
                <div class="col col-md-6">
                    <q-file color="teal" filled v-model="item.image" label="Изображение">
                        <template v-slot:prepend>
                            <q-icon name="image"/>
                        </template>
                    </q-file>
                </div>
                <div class="col">
                    <a :href="item.image" target="_blank" v-if="item.image && type === 'create'">
                        <q-btn icon="image" label="Изображение" color="dark"/>
                    </a>
                </div>
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
                    <a :href="item.file" target="_blank" v-if="item.file && (typeof this.item.image === 'string')">
                        <q-btn icon="description" label="Файл" color="dark"/>
                    </a>
                </div>
            </div>

            <div class="row q-pb-md">
                <div class="col">
                    <text-editor :edit-text="item.text" v-model="item.text"/>
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
                <q-toggle v-model="item.publish" label="Отображать"/>
                <q-toggle v-model="item.chat" label="Показывать чат"/>
                <q-toggle v-model="item.imgFullWidth" label="Изображение на всю ширину"/>
                <q-space/>
                <div class="q-gutter-sm">
                    <q-btn label="Сохранить" @click="storeData" color="primary" v-if="type === 'create'"/>
                    <q-btn label="Обновить" @click="saveData" color="primary" v-if="type === 'update'"/>
<!--                    <q-btn label="Удалить" @click="removeDialog = true" color="red" v-if="type === 'update'"/>-->
                </div>
            </div>
        </q-card-section>

    </q-card>

    <q-dialog v-model="removeDialog">
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <div>Вы действительно хотите удалить запись?</div>
                <q-space/>
                <q-btn icon="close" flat round dense v-close-popup/>
            </q-card-section>

            <q-card-section>
                <q-btn label="Удалить" @click="removeData" color="red" v-if="type === 'update'"/>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

<script>

import {getNewsDetail, saveNewsDetail, storeNews} from "@/services/news";
import TextEditor from "@/components/TextEditor.vue";
import {Notify} from "quasar";

export default {
    props: ['id', 'type'],

    components: {TextEditor},

    data() {
        return {
            loaded: false,
            showBanner: false,
            errors: [],
            removeDialog: false,
            item: {
                publish: 0,
                text: '',
                title: '',
                chat: 0,
                imgFullWidth: 0
            },
        }
    },

    methods: {

        removeData() {

        },

        saveData() {
            saveNewsDetail(this.id, this.item).then((response) => {
                this.item = response
                emitter.emit('news_dialog');
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                console.log(reject)
                this.errors = reject
                this.showBanner = true
            }).finally(() => {
            });
        },

        storeData() {
            this.showBanner = false
            storeNews(this.item).then((response) => {
                this.item = response
                emitter.emit('news_dialog');
                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => {
            });
        },

        getData() {
            getNewsDetail(this.id).then((response) => {
                this.item = response
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    mounted() {
        if (this.type === 'update') {
            this.getData();
        }
    }

}
</script>


<style lang="scss">

</style>
