<template>
    <q-card style="width: 1200px; max-width: 80vw;">
        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Заявка</div>
            <q-space/>
            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section style="max-height: 70vh" class="scroll ">
            <div class="row q-pb-md">
                <div class="col">
                    <q-editor v-model="item.text" min-height="5rem"/>
                </div>
            </div>

            <div class="row q-pb-md">
                <div class="col">
                    <q-select label="Кому" v-model="item.department" :options="departments" option-value="id"
                              option-label="name"/>
                </div>
                <div class="col">
                    <q-file color="teal" multiple filled v-model="item.files" label="Файл">
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
                <q-btn label="Отправить" color="primary" @click="storeData" :loading="btnLoading"/>
            </div>
        </q-card-section>

    </q-card>

</template>

<script>
import {getApplicationCategory, storeApplication} from "@/services/application";
import {Notify} from "quasar";

export default {
    data() {
        return {
            btnLoading: false,
            showBanner: false,

            errors: [],
            departments: [],

            item: {
                department: null,
                text: '',
                files: []
            }
        }
    },

    methods: {
        storeData() {
            this.showBanner = false
            this.btnLoading = true

            this.item.category = (this.item.department) ? this.item.department.id : null

            const formData = new FormData();
            for (const i of Object.keys(this.item.files)) {
                formData.append('files[]', this.item.files[i])
            }

            for (let [key, value] of Object.entries(this.item)) {
                formData.append(key, value)
            }

            storeApplication(formData).then((response) => {

                Notify.create({
                    message: 'Сохранено',
                    color: 'success'
                })
                emitter.emit('application_dialog');

            }).catch((reject) => {
                this.errors = reject
                this.showBanner = true
            }).finally(() => {
                this.btnLoading = false
            });
        },

        getCategory() {
            getApplicationCategory().then((response) => {
                this.departments = response
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    created() {
        this.getCategory()
    }
}
</script>

<style scoped>

</style>
