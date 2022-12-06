<template>
    <div class="q-pa-md row items-start q-gutter-md" id="main_feed">
        <div class="main_feed_wrapper" id="main_feed_items">
            <div class="news" v-for="(item, index) in feeds" :key="index">
                <!--<div class="text-subtitle-1 q-mb-3"><b>{{ item.title }}</b></div>-->
                <div class="group_left">
                    <img :src="item.image"
                         :class="(item.type === 'chat') ? 'avatar' : ''"
                         width="400px"
                         v-if="isImage(item.image)"
                    >
                </div>

                <div class="group_right">
                    <p v-html="item.text"></p>
                    <span class="date">{{ $moment(item.date).format('YYYY-MM-DD') }}</span>
                    <div class="docs" v-if="!isImage(item.image) && item.image">
                        <q-btn @click="showDialog(item)">Открыть</q-btn>
                    </div>
                </div>

<!--                <feed-chat v-if="item.type === 'chat'" :id="item.id"/>-->
                <main-chat />
            </div>
        </div>
    </div>
</template>

<script>

import Api from '@/./utils/api'
import FeedChat from "@/./components/chats/FeedChat.vue";
import MainChat from "@/./components/chats/MainChat.vue";

export default {

    components: {
        MainChat
    },

    data: () => ({
        news: [],
        feeds: [],
        polling: [],
        active: false,
        new_message: [],
        pdf: '',
        dialog: false,
        news_id: null,
        checked: false,
        page: 1,
        isSend: false,
    }),

    methods: {

        isImage(url) {
            return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url);
        },

        async getFeedsList() {
            if (this.isSend) return
            this.isSend = true
            await Api.post('feeds/all', {page: this.page}).then(response => {
                if (Array.isArray(response.data.data)) {
                    response.data.data.map((el) => {
                        this.feeds.push(el)
                    });
                } else {
                    Object.keys(response.data.data).map((key) => {
                        this.feeds.push(response.data.data[key])
                    });
                }
            }).finally(() => {
            });

        },

        storeViewer() {
            Api.post('news/viewer/store', {id: this.news_id}).then(res => {
                console.log(res)
                this.checked = true
            })
        }
    },

    created() {
        this.getFeedsList()
    },
}

</script>
