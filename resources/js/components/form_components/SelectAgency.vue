<template>
    <q-input filled v-model="item" label="Орган утверждения" clearable @click="showSelect = true"
             @blur="showSelect = false"
             @input="$emit('update:modelValue', $event.target.value)"
             style="position: relative">
        <template v-slot:append v-if="showSelect">
            <div style="width: 100%; left: 0; top:55px; position: absolute; z-index: 10; background: #fff; font-size: 12px;">
                <q-list dense bordered padding class="rounded-borders">
                    <q-item clickable v-ripple v-for="el in items" @click="$emit('update:modelValue', setItem(el.name))">
                        <q-item-section >
                            {{ el.name }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </div>
        </template>
    </q-input>
</template>

<script>
import {getKnowledgeAgency} from "@/services/knowledge";
import { ref } from "vue";

export default {

    setup() {
        const item = ref("");
        return { item };
    },

    data(){
        return{
            items: [],
            showSelect: false,
        }
    },

    methods: {
        getList(){
            getKnowledgeAgency().then((response) => {
                this.items = response
            });
        },

        setItem(val) {
            this.showSelect = false
            return val;
        },
    },

    created(){
        this.getList()
    }
}
</script>

