<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add" @click="dialogAdd = true"/>
        </div>
    </div>

    <q-list bordered separator>
        <q-item clickable v-ripple v-for="(item, index) in items" :key="index"
                :to="'/docs/learning/'+item.id+'/show'">
            <q-item-section>
                <div class="item flex items-center justify-between">
                         <span>
                             <q-icon name="folder" size="60px" color="warning"/>  {{ item.name }}
                         </span>
                    <q-badge rounded color="black" label="3"/>
                </div>
            </q-item-section>
        </q-item>
    </q-list>

    <q-dialog v-model="dialogAdd" transition-show="rotate" transition-hide="rotate">
        <learning-form :catalog="catalog"/>
    </q-dialog>

</template>

<script>


import {getLearningCatalog} from "@/services/learning";
import LearningForm from "@/views/learning/LearningForm.vue";

export default {
    components: {LearningForm},
    data() {
        return {
            items: [],
            dialogAdd: false,
            catalog: {}
        }
    },

    methods: {
        getList() {
            getLearningCatalog(this.params).then((response) => {
                this.items = response
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    created() {
        this.getList()
    }

}
</script>

<style scoped>

</style>
