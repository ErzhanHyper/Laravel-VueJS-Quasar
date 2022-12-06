<template>

    <div class="greeting-chat">
        <div class="chat-section" id="chat_section">

            <div class="chat-box-wrapper" v-for="(item, i) in messages" :key="i">

                <div v-if="item.feed_id === id">

          <span class="right chat-box" v-if="item.employee_id === employee.id">
            <span class="text">
              <span>{{ item.message }}</span>
              <span class="date">{{ $moment(item.created_at).format('YYYY-MM-DD HH:mm') }}</span>
              <span style="cursor:pointer;" @click="deleteMessage(item.id)"><q-icon size="xs" name="delete"/></span>
            </span>
          </span>

                    <span class="left chat-box" v-else>
            <span class="username"> {{
                    (item.employee) ? item.employee.lastname + ' ' + item.employee.firstname : ''
                }}</span>
            <span class="text">
             <span>{{ item.message }}</span>
              <span class="date">{{ $moment(item.created_at).format('YYYY-MM-DD HH:mm') }}</span>
            </span>

          </span>
                </div>

            </div>


        </div>

        <div class="chat-send">
            <div class="message">
                <input placeholder="Сообщения" v-model="params.message" @keydown.enter="sendMessage"/>


                <button @click="sendMessage()">
                    <q-icon name="send" size="sm"/>
                </button>
            </div>
        </div>
    </div>

</template>

<script>

import Api from "@/./utils/api";
import EmojiPicker from 'vue-emoji-picker'

export default {

    props: ['id'],

    components: {
        EmojiPicker
    },

    data() {
        return {
            messages: [],
            employee: JSON.parse(localStorage.getItem('employee')),
            isLoad: true,
            params: {
                message: '',
                feed_id: this.id
            }
        }
    },

    methods: {

        insert(emoji) {
            this.params.message += emoji
        },

        deleteMessage(id) {
            Api.post('message/delete/feed/' + id).then((res) => {
                this.getMessages()
                this.$socket.emit('feed_chat', res)
            })
        },

        sendMessage() {
            Api.post('message/send/feed', this.params).then((res) => {
                this.params.message = ''
                this.getMessages()
                this.$socket.emit('feed_chat', res)
            })
        },

        async getMessages() {
            await Api.post('message/get/feed', this.params.feed_id).then(res => {
                this.messages = res.data
            }).finally(() => {
                this.isLoad = false
                document.getElementById('chat_section').scrollTop = document.getElementById('chat_section').scrollHeight
            })
        }
    },

    created() {
        this.getMessages()
    },

    mounted() {
        let self = this
        this.sockets.subscribe("chat_message_send", function () {
            self.getMessages()
        })
    }

}

</script>
