<template>

    <q-card style="width: 100%; max-width: 80vw;" id="knowledge_card">
        <q-card-section class="row items-center ">
            <div class="text-h6">Внутренние нормативные документы</div>
            <q-space/>
            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-separator/>

        <q-card-section style="max-height: 80vh" class="scroll">
            <div class="q-gutter-md">
                <div class="text-h6"><a href="#"><b>Положение о Бюджетном комитете АО "Жасыл даму"</b></a> <q-btn icon="edit" size="sm" color="primary" round @click="showEdit()"></q-btn> </div>
                <div><b>Положение</b></div>
                <div>Владелец: <b>Департамент бюджетного планирования</b></div>
                <div>Орган утверждения: <b>Правление АО "Жасыл Даму"</b></div>
                <div>Дата утверждения: <b>2022-06-06</b></div>
                <div>Дата вступления в силу: <b>2022-06-06</b></div>
            </div>

            <q-separator class="q-my-lg"/>

            <div class="q-gutter-md">
                <div class="text-subtitle1">История</div>
                <div class="text-subtitle2">
                    <q-chip class="custom"><a href="#"><b>Положение о Бюджетном комитете АО "Жасыл даму" по состоянию на
                        2022-06-06</b></a></q-chip>
                    <q-chip class="custom"><a href="#"><b>Положение о Бюджетном комитете АО "Жасыл даму" по состоянию на
                        2022-06-06</b></a></q-chip>
                    <q-chip class="custom"><a href="#"><b>Положение о Бюджетном комитете АО "Жасыл даму" по состоянию на
                        2022-06-06</b></a></q-chip>

                </div>
            </div>

            <q-separator class="q-my-lg"/>

            <div class="q-gutter-md">
                <div class="text-subtitle1">Ознакомленные</div>
                <div class="text-caption">
                    <q-chip class="custom" size="12px">Сеюбергенова Дидар Сламовна</q-chip>
                    <q-chip class="custom" size="12px">Шалдыбаев Ержан Дауылулы</q-chip>
                    <q-chip class="custom" size="12px">Сеюбергенова Дидар Сламовна</q-chip>
                    <q-chip class="custom" size="12px">Шалдыбаев Ержан Дауылулы</q-chip>
                    <q-chip class="custom" size="12px">Сеюбергенова Дидар Сламовна</q-chip>
                    <q-chip class="custom" size="12px">Шалдыбаев Ержан Дауылулы</q-chip>
                    <q-chip class="custom" size="12px">Сеюбергенова Дидар Сламовна</q-chip>
                    <q-chip class="custom" size="12px">Шалдыбаев Ержан Дауылулы</q-chip>
                </div>
            </div>

        </q-card-section>

        <q-separator/>

        <q-card-actions align="right">
            <q-btn flat label="Закрыть" color="primary" v-close-popup/>
        </q-card-actions>
    </q-card>


    <q-dialog v-model="showPdfDialog" persistent
              :maximized="maximizedToggle"
              transition-show="slide-up"
              transition-hide="slide-down">

        <q-card class="bg-primary text-white">
            <q-bar>
                <q-space/>

                <q-btn dense flat icon="minimize" @click="maximizedToggle = false" :disable="!maximizedToggle">
                    <q-tooltip v-if="maximizedToggle" class="bg-white text-primary">Minimize</q-tooltip>
                </q-btn>
                <q-btn dense flat icon="crop_square" @click="maximizedToggle = true" :disable="maximizedToggle">
                    <q-tooltip v-if="!maximizedToggle" class="bg-white text-primary">Maximize</q-tooltip>
                </q-btn>
                <q-btn dense flat icon="close" v-close-popup>
                    <q-tooltip class="bg-white text-primary">Close</q-tooltip>
                </q-btn>
            </q-bar>

            <q-card-section class="q-pt-none">
                <div class="pdf-iframe">
                    <iframe
                        src="http://192.168.0.20:8000/storage/uploads/docs/regulation/1669260609_%D0%BF%D0%BE%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BE%20%D0%91%D1%8E%D0%B4%D0%B6%D0%B5%D1%82%D0%BD%D0%BE%D0%BC%20%D0%BA%D0%BE%D0%BC%D0%B8%D1%82%D0%B5%D1%82%D0%B5.pdf"/>
                </div>
            </q-card-section>
        </q-card>


    </q-dialog>

    <q-dialog v-model="showEditDialog" persistent>
        <knowledge-form />
    </q-dialog>

</template>

<script>

import KnowledgeForm from "@/views/knowledge/KnowledgeForm.vue";

export default {

    components: {KnowledgeForm},

    data() {
        return {
            showPdfDialog: true,
            maximizedToggle: false,
            showEditDialog: false
        }
    },

    methods: {
        showEdit(){
            this.showEditDialog = true
        }
    }
}
</script>

<style scoped>

</style>
