<template>
    <q-select filled v-model="category" :options="categories" option-value="id" :option-label="(item) => item.name" label="События" />
</template>

<script>
import {getFeedCategory} from "@/services/feed";

export default {

    props: ['category'],

    data(){
        return{
            categories: [],
        }
    },

    methods: {
        getCategory(){
            getFeedCategory().then((response) => {
                this.categories = response
            });
        }
    },

    created(){
        this.getCategory()
    }
}
</script>

