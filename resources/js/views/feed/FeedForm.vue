<template>
    <q-card style="width: 1200px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">События</div>
            <q-space/>
            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section style="max-height: 70vh" class="scroll ">
            <div class="row q-gutter-md q-pb-md">
                <div class="col">
                    <select-feed-category :category="item.category"/>
                </div>
                <div class="col">
                    <select-employee v-model="item.employee"/>
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
                <q-space/>
                <div class="q-gutter-sm">
                    <q-btn label="Сохранить" @click="storeData" color="primary" v-if="type === 'create'" />
                    <q-btn label="Обновить" @click="saveData" color="primary" v-if="type === 'update'" />
                    <!--<q-btn label="Удалить" @click="removeDialog = true" color="red" v-if="type === 'update'"/>-->
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

import {getFeedDetail, saveFeedDetail, storeFeed, getFeedCategory} from "@/services/feed";
import TextEditor from "@/components/TextEditor.vue";
import {Notify} from "quasar";
import SelectEmployee from "@/components/form_components/SelectEmployee.vue";
import SelectFeedCategory from "@/components/form_components/SelectFeedCategory.vue";

export default {
    props: ['id', 'type'],

    components: {
        SelectFeedCategory,
        SelectEmployee,
        TextEditor,
    },

    data() {
        return {
            selected_employee: {},
            loaded: false,
            showBanner: false,
            errors: [],
            removeDialog: false,
            item: {
                publish: 0,
                category: {},
                employee: {},
                text: '',
                date: ''
            },
        }
    },

    methods: {

        removeData() {

        },

        saveData() {
            console.log(this.params)
            saveFeedDetail(this.id, this.item).then((response) => {
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

        storeData() {
            this.showBanner = false
            storeFeed(this.item).then((response) => {
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
            getFeedDetail(this.id).then((response) => {
                this.item = response
            }).catch((reject) => {
            }).finally(() => {
            });
        },

    },

    mounted() {
        if (this.type === 'update') {
            this.getData();
        }
    }

}
</script>

