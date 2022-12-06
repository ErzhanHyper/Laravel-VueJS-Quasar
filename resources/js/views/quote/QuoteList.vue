<template>
    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add"/>
        </div>
    </div>

    <div class="q-pa-md row items-start q-gutter-md">
        <q-card v-for="item in items" bordered flat class="q-pa-sm fullwidth-card" >
            <q-card-section class="row items-center q-pb-none">
                <q-chip :label="$moment(item.created_at).format('YYYY-MM-DD')"/>
                <q-space/>
                <q-btn round color="primary" icon="edit" size="sm"/>
            </q-card-section>

            <q-card-section class="row items-center q-pb-none">
                <span class="text-subtitle1">{{ item.text }}</span>
            </q-card-section>
        </q-card>
    </div>

</template>

<script>

import {getQuoteList} from "@/services/quote";

export default {
    data() {
        return {
            items: [],
            totalPage: 1,
            page: 1,
            params: {}
        }
    },

    methods: {
        getList() {
            this.items = [];
            this.params.page = this.page
            getQuoteList(this.params).then((response) => {
                this.items = response
            }).catch((reject) => {
            }).finally(() => {
            });
        },
    },

    mounted() {
        this.getList()
    }
}
</script>

<style scoped>

.quotes {
    margin: 20px;
    background: #de844d1a;
    padding: 10px;
    border-radius: 10px;
    display: flex;
}

.quote {
    padding: 10px;
    border: 1px dashed lightgray;
    position: relative;
    border-radius: 10px;
    font-family: Candara;
}

</style>
