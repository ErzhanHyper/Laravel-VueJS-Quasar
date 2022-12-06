<template>
    <div class="q-pa-md">

        <q-carousel
            swipeable
            animated
            v-model="slide"
            thumbnails
            infinite
            arrows
            transition-prev="slide-down"
            transition-next="slide-up"
            vertical
            :fullscreen="fullscreen"
        >
            <template v-for="(item, index) in items" :key="index">
                <q-carousel-slide :name="index + 1" :img-src="item.file" v-if="isImage(item.file)"/>
            </template>

            <template v-slot:control>
                <q-carousel-control
                    position="bottom-right"
                    :offset="[18, 18]"
                >
                    <q-btn
                        push round dense color="white" text-color="primary"
                        :icon="fullscreen ? 'fullscreen_exit' : 'fullscreen'"
                        @click="fullscreen = !fullscreen"
                    />
                </q-carousel-control>
            </template>

        </q-carousel>

        <q-carousel
            animated
            v-model="slide1"
            infinite
        >
            <template v-for="(item, index) in items" :key="index">
                <q-carousel-slide :name="1" v-if="!isImage(item.file)" >
                    <q-video
                        class="absolute-full"
                        :src="item.file"
                    />
                </q-carousel-slide>
            </template>

        </q-carousel>

    </div>
</template>

<script>

import {getGalleryData} from "@/services/gallery";

export default {

    props: ['id'],

    data() {
        return {
            slide: 1,
            slide1: 1,
            fullscreen: false,
            items: []
        }
    },

    methods: {

        isImage(url) {
            return /\.(JPEG|PNG|JPG|jpg|jpeg|png|webp|avif|gif|svg)$/.test(url);
        },

        getData() {
            getGalleryData(this.id).then((response) => {
                this.items = response.gallery
            }).catch((reject) => {
            }).finally(() => {
            });
        }
    },

    created() {
        this.getData()
    }
}
</script>

<style scoped>

</style>
