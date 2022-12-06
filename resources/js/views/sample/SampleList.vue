<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add" @click="dialogAdd = true"/>
        </div>
    </div>

    <q-markup-table>
        <thead>
        <tr>
            <th class="text-left">Наименования</th>
            <th class="text-left">Дата загрузки</th>
            <th class="text-left">Действие</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in items" :key="index">
            <template v-if="item.files">
                <td>
                    <a :href="(item.files) ? item.files.file : ''" target="_blank" download >
                        <q-chip style="cursor: pointer;" class="custom subtitle-1">
                            <q-icon class="q-mr-2" name="description"/>
                            {{ item.name }}
                        </q-chip>
                    </a>

                </td>
                <td>{{ $moment(item.created_at).format('YYYY-MM-DD HH:mm:ss') }}</td>
                <td>
                    <q-btn color="red" icon="delete" round size="sm"/>
                </td>
            </template>
        </tr>
        </tbody>
    </q-markup-table>

    <q-dialog v-model="dialogAdd" transition-show="rotate" transition-hide="rotate">
        <sample-form/>
    </q-dialog>

</template>


<script>

import {getSampleList} from "@/services/sample";
import SampleForm from "@/views/sample/SampleForm.vue";

export default {
    components: {SampleForm},
    data() {
        return {
            items: [],
            search: '',
            editAccess: false,
            dialogAdd: false,
        }
    },

    methods: {

        getList() {
            getSampleList(this.params).then((response) => {
                this.items = response
            }).catch((reject) => {
            }).finally(() => {
            });
        },


    },

    created() {
        this.getList()
    },

    mounted() {
        emitter.on('sample_dialog', () => {
            this.dialogAdd = false
            this.getList()
        })
    }
}
</script>
