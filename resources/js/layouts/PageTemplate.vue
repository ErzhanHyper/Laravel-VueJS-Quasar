<template>
    <div class="page" id="main_content">

        <div class="page-title d-flex justify-space-between align-center" id="page_title">
            <div class="title">
                {{ $t('main.' + $route.name) }}
            </div>
        </div>

        <div class="page-content">
            <q-scroll-area style="height: calc(100vh - 180px);" :thumb-style="thumbStyle" :bar-style="barStyle">

                <q-banner inline-actions class="text-white bg-red" id="main_banner" v-if="show">
                    <div>You have lost connection to the internet. This app is offline.</div>
                    <div>You have lost connection to the internet. This app is offline.</div>
                    <div>You have lost connection to the internet. This app is offline.</div>
                    <template>
                        <q-btn flat color="white" label="x" @click="show = false"/>
                    </template>
                </q-banner>

                <router-view/>

                <div class="text-center">
                    <q-circular-progress
                        indeterminate
                        rounded
                        size="50px"
                        color="secondary"
                        class="q-ma-md"
                        v-if="isLoad"
                    />
                </div>

            </q-scroll-area>
        </div>
    </div>
</template>

<script>
import mitt from 'mitt';
const emitter = mitt();

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
    },

    data() {
        return {
            isLoad: true,
            show: false,
        }
    },

    mounted() {
        this.$emitter.on('contentLoaded', (value) => {
            this.isLoad = value
        })
    }

}
</script>
