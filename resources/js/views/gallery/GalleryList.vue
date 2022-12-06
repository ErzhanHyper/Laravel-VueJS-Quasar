<template>
    <div>

        <div class="list-actions text-right">
            <div class="q-pa-md q-gutter-sm">
                <q-btn round color="primary" icon="add"/>
            </div>
        </div>

        <q-list bordered separator>
            <q-item clickable v-ripple v-for="(item, index) in items" :key="index"
                    :to="'/gallery/'+item.id+'/show'">
                <q-item-section>
                    <div class="item flex items-center justify-between">
                         <span>
                             <q-icon name="folder" size="60px" color="warning"/>{{ item.name }}
                         </span>
                        <q-badge rounded color="black" label="3"/>
                    </div>
                </q-item-section>
            </q-item>
        </q-list>

    </div>
</template>

<script>


import {getGalleryList} from "@/services/gallery";

export default {
    data() {
        return {
            items: [],
        }
    },

    methods: {
        getList() {
            getGalleryList(this.params).then((response) => {
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
