<template>
    <q-select filled v-model="item" :options="items" option-value="id" :option-label="(item) => item.name" label="Тип ВНД" />
</template>

<script>
import {getKnowledgeTypes} from "@/services/knowledge";

export default {

    props: ['item'],

    data(){
        return{
            items: [],
        }
    },

    methods: {
        getList(){
            getKnowledgeTypes().then((response) => {
                this.items = response
            });
        }
    },

    created(){
        this.getList()
    }
}
</script>

