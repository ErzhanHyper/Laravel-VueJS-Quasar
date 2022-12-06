<template>
    <q-card style="width: 960px; max-width: 80vw;">

        <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Ссылки НПА</div>
            <q-space/>
            <q-btn icon="close" flat round dense v-close-popup/>
        </q-card-section>

        <q-card-section>
            <q-input label="Наименование" v-model="npa.name"/>
            <q-input label="ссылка НПА"  v-model="npa.link"/>
            <div class="text-right q-mt-md">
                <q-btn color="primary" @click="storeNPA()" icon="save" label="Добавить"></q-btn>
            </div>
        </q-card-section>
        <q-card-section v-if="items.length > 0">
            <div v-for="(item, i) in items" :key="i" class="mb-2">
                <q-separator class="q-my-sm"/>
                <a :href="item.link" target="_blank">{{ i + 1 }}) {{ item.name }}</a>
                <q-btn round size="xs" color="red" class="q-ml-md" @click="removeNPA(item.id)" icon="delete" />
            </div>
        </q-card-section>
    </q-card>
</template>

<script>
import Api from "@/utils/api";

export default {

    data(){
        return{
            items: [],
            npa: {}
        }
    },

    methods: {

        getNPAList(){
            Api.post('npa/list').then(res => {
                this.items = res.data
            })
        },

        storeNPA(){
            Api.post('npa/store', this.npa).then(() => {
                this.getNPAList()
            })
        },

        removeNPA(id){
            Api.post('npa/'+id+'/delete').then(() => {
                this.getNPAList()
            })
        }

    },

    mounted(){
        this.getNPAList();
    }
}
</script>

<style scoped>

</style>
