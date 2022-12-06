<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn color="success" @click="exportExcel" rounded icon="file_download">Экспорт</q-btn>
            <q-btn color="primary" round icon="add"/>
        </div>
    </div>

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left" v-for="column in columns"><span class="text-subtitle2"> {{ column }}</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items" :key="i">
            <td class="text-left" @click="showAdditional(item)">
                № {{ item.contract_id }}
            </td>
            <td class="text-left">{{ item.date }}</td>
            <td class="text-left"><a :href="item.file" target="_blank" download v-if="item.file">{{ item.name }}</a> <span v-else>{{ item.name }}</span></td>
            <td class="text-left">{{ (item.agent) ? item.agent.name : '' }} | {{ (item.agent) ? item.agent.bin : '' }}
            </td>
            <td class="text-left">{{ item.amount }} &#8376;</td>
            <td class="text-left">c {{ item.date_start }} по {{ item.date_end }}</td>
        </tr>
        </tbody>
    </q-markup-table>

</template>

<script>
import Api from "@/utils/api";
import FileDownload from "js-file-download";
import {getContractList} from "@/services/contract";

export default {
    components: {},
    data() {
        return {
            columns: ['Номер договора', 'Дата', 'Наименование', 'Контрагент', 'Сумма', 'Срок действия (с, по)'],
            params: {},
            items: [],
            dialog: 0,
            data: {},
        }
    },

    methods: {
        showAdditional(item) {
            this.dialog = 1
            this.data = item
        },

        getContractList() {
            getContractList(this.params).then((response) => {
                this.items = response
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        exportExcel() {
            Api.get('export/docs/contract', {
                params: this.params,
                responseType: 'arraybuffer'
            }).then(response => {
                FileDownload(response.data, 'Договора.xlsx')
            })
        },

    },

    mounted() {
        this.getContractList()

    }
}
</script>
