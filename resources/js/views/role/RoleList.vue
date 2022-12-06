<template>

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left" v-for="column in columns"><span class="text-subtitle2"> {{ column }}</span></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items">
            <td class="text-left">{{ item.description }}</td>
            <td class="text-left">{{ item.name }}</td>
            <td class="text-left"> <q-chip text-color="white" :color="(item.status === 1) ? 'success' : 'red' " >{{ (item.status === 1) ? 'Активный' : 'Не активный' }} </q-chip> </td>
        </tr>
        </tbody>
    </q-markup-table>

</template>

<script>
import {getRoleList} from "@/services/role";

export default {
    data(){
        return{
            columns: ['Наименование', 'Код', 'Статус'],
            items: []
        }
    },

    methods:{
        getItems(){
            getRoleList().then((response) => {
                this.items = response
            }).catch((reject) => {
            }).finally(() => {
            });
        },
    },

    mounted(){
        this.getItems()
    }
}
</script>

<style scoped>

</style>
