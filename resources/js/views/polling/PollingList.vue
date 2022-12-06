<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add"/>
        </div>
    </div>
    <div class="q-pa-md row items-start q-gutter-md">
        <q-card v-for="item in items" bordered flat class="fullwidth-card">

            <q-card-section class="row items-center">
                <router-link :to="'/polling/'+item.id+'/show'">
                    <span class="text-h6"><b>{{ item.title }}</b></span>
                </router-link>
                <q-space/>
                <q-chip :label="$moment(item.created_at).format('YYYY-MM-DD')"/>
                <q-btn round color="primary" icon="edit" size="sm"/>
            </q-card-section>
            <q-card-section class="row items-center" v-if="item.description">
                {{ item.description }}
            </q-card-section>
        </q-card>
    </div>


</template>

<script>

import {getPollingList} from "@/services/polling";

export default {

    data() {
        return {
            items: [],
        }
    },

    methods: {
        getList() {
            getPollingList(this.params).then((response) => {
                this.items = response.data
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    created() {
        this.getList();
    }

}
</script>


<style lang="scss">

</style>
