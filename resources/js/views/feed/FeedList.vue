<template>
        <div class="list-actions text-right">
            <div class="q-pa-md q-gutter-sm">
                <q-btn round color="primary" icon="add"/>
            </div>
        </div>

        <div class="q-pa-md row items-start q-gutter-md">
            <q-card v-for="item in items" bordered class="q-mb-lg fullwidth-card">

                <q-card-section class="row items-center ">
                    <router-link :to="'/feed/'+item.id+'/edit'">
                        <span class="text-h6"><b>{{ item.category.name }}</b></span>
                    </router-link>
                    <q-space/>
                    <q-chip :label="$moment(item.created_at).format('YYYY-MM-DD')"/>
                    <q-btn round color="primary" icon="edit" size="sm"  @click="showData({type: 'update', id: item.id})"/>
                </q-card-section>

                <q-separator class="q-my-sm"/>

                <q-card-section>
                    <div class="text" v-html="item.text"></div>
                </q-card-section>
            </q-card>
        </div>

        <q-dialog v-model="dialog" transition-show="rotate" transition-hide="rotate">
            <feed-form :type="type" :id="news_id"/>
        </q-dialog>
</template>

<script>

import {getFeedList} from "@/services/feed";
import FeedForm from './FeedForm.vue';

export default {
    components: {
        FeedForm
    },
    data() {
        return {
            dialog: false,
            items: [],
            page: 1,
            params: {}
        }
    },

    methods: {
        showData(value) {
            this.news_id = value.id
            this.type = value.type
            this.dialog = true
        },

        getList() {
            getFeedList(this.params).then((response) => {
                this.items = response.data
            }).catch((reject) => {
            }).finally(() => {
            });
        },
    },

    created() {
        this.getList();
    }

}

</script>


<style lang="scss">

.list-actions {
    margin-bottom: 20px;
    padding-bottom: 10px;
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    width: 100%;

    .v-btn {
        margin-left: 10px;
    }
}


.opacity {
    opacity: .2;
    transition: .3s;
    pointer-events: none;
}

</style>
