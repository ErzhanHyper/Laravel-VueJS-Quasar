<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add" @click="showData({type: 'create'})"/>
        </div>
    </div>

    <div class="q-pa-md row items-start q-gutter-md">
        <q-card v-for="item in items" bordered class="q-mb-lg fullwidth-card">


            <q-card-section class="row items-center q-pb-none">
                <router-link :to="'/news/'+item.id+'/edit'">
                    <span class="text-h6"><b>{{ item.title }}</b></span>
                </router-link>
                <q-space/>
                <q-chip :label="$moment(item.created_at).format('YYYY-MM-DD')"/>
                <q-btn round color="primary" icon="edit" size="sm" @click="showData({type: 'update', id: item.id})"/>
            </q-card-section>

            <q-separator class="q-my-sm"/>

            <q-card-section class="q-pb-md">
                <div class="text" v-html="item.text"></div>
                <q-img :src="item.image" width="200px" class="q-mr-md" v-if="item.image"/>
            </q-card-section>

        </q-card>
    </div>


    <q-dialog v-model="dialog" transition-show="rotate" transition-hide="rotate">
        <news-form :type="type" :id="news_id"/>
    </q-dialog>

</template>

<script>

import {getNewsList} from "@/services/news";
import NewsForm from './NewsForm.vue';

export default {

    components: {
        NewsForm
    },

    data() {
        return {
            dialog: false,
            items: [],
            news_id: null,
            type: 'create'
        }
    },

    methods: {

        showData(value) {
            this.news_id = value.id
            this.type = value.type
            this.dialog = true
        },

        getList() {
            getNewsList(this.params).then((response) => {
                this.items = response.data
                this.totalPage = response.meta.last_page
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    created() {
        this.getList();
    },

    mounted(){
        emitter.on('news_dialog', () => {
            this.dialog = false
            this.getList()
        })
    }

}
</script>


<style lang="scss">

</style>
