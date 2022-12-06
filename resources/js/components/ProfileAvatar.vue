<template>

    <q-card flat  class="profile_photo q-py-sm flex items-center justify-center" style="width: 320px; height: auto;cursor:pointer;" @click="dialog = true">
        <img :src="(croppedImage) ? croppedImage : ''" width="300" height="300" />
        <q-btn color="red lighten-2" size="sm" label="Фото" icon-right="upload" class="full-width"/>
    </q-card>

    <q-dialog v-model="dialog">
        <q-card flat bordered>
            <q-card-section class="text-h5 grey lighten-2">Фото</q-card-section>
            <q-card-section>
                <q-file
                    label="Загрузить фото"
                    filled
                    v-model="file"
                    @update:model-value="uploadPhoto()"
                >
                    <template v-slot:prepend>
                        <q-icon name="photo"/>
                    </template>
                </q-file>
            </q-card-section>

            <q-card-section v-if="show">
                <div style="width:500px;height:500px">
                    <vue-cropper autoCrop :img="img" ref="cropper" centerBox fixed :fixedNumber="[1,1]"/>
                </div>
            </q-card-section>

            <q-card-section>
                <q-btn class="full-width" @click="getCropData()" label="Сохранить" color="primary" icon="camera"></q-btn>
            </q-card-section>

        </q-card>
    </q-dialog>


</template>

<script>
import 'vue-cropper/dist/index.css'
import {VueCropper} from "vue-cropper";

export default {

    props: ['getPhoto', 'setPhoto'],

    components: {
        VueCropper,
    },
    data() {
        return {
            dialog: false,
            img: '',
            file: null,
            croppedImage: '',
            show: false,
        }
    },

    methods: {

        uploadPhoto() {
            this.show = true
            this.img = URL.createObjectURL(this.file);
        },

        getCropData() {
            this.$refs.cropper.getCropData(data => {
                this.croppedImage = data
                this.getPhoto(this.croppedImage);
                this.dialog = false
            })
        }
    },

    mounted() {
        this.croppedImage = this.setPhoto
    }
}
</script>

<style lang="scss">


</style>
