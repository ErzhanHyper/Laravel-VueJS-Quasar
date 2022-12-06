<template>
    <b>Коллектив АО "Жасыл даму"</b>
    <div class="main-chat-item" >
        <q-scroll-area :thumb-style="thumbStyle" :bar-style="barStyle" style="height:400px;">
            <div class="q-pa-md row justify-center">
                <div style="width: 100%;">
                    <q-chat-message
                        name="Я"
                        avatar="https://cdn.quasar.dev/img/avatar3.jpg"
                        :text="['hey, how are you?']"
                        stamp="7 minutes ago"
                        sent
                        bg-color="amber-7"
                    />
                    <q-chat-message
                        name="Jane"
                        avatar="https://cdn.quasar.dev/img/avatar5.jpg"
                        :text="[
          'doing fine, how r you?',
          'I just feel like typing a really, really, REALLY long message to annoy you...'
        ]"
                        size="6"
                        stamp="4 minutes ago"
                        text-color="white"
                        bg-color="primary"
                    />
                    <q-chat-message
                        name="Jane"
                        avatar="https://cdn.quasar.dev/img/avatar5.jpg"
                        :text="['Did it work?']"
                        stamp="1 minutes ago"
                        size="8"
                        text-color="white"
                        bg-color="primary"
                    />
                </div>
            </div>
        </q-scroll-area>
    </div>
</template>

<script>
export default {

    setup() {
        return {

            thumbStyle: {
                right: '4px',
                borderRadius: '7px',
                backgroundColor: '#00c168',
                width: '4px',
                opacity: 0.75
            },

            barStyle: {
                right: '2px',
                borderRadius: '9px',
                backgroundColor: '#00c168',
                width: '8px',
                opacity: 0.2
            },

        }
    }
}
</script>

<style scoped>

</style>
