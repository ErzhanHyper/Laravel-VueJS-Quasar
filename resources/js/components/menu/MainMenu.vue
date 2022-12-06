<template>
    <div>
        <q-scroll-area style="height: calc(100vh - 100px);">
            <q-list bordered separator>
                <template v-for="(item) in items">

                    <q-item clickable v-ripple class="menu-item q-pr-sm" :to="item.link">
                        <q-item-section avatar>
                            <q-icon :name="item.icon" :style="'color:'+item.color+';'"/>
                        </q-item-section>

                        <q-item-section>
                            {{ item.name }}
                            <q-item-label caption>{{ item.label }}</q-item-label>
                        </q-item-section>

                    </q-item>

                </template>
            </q-list>
        </q-scroll-area>
    </div>
</template>


<script>

import section from "../../constants/section";

export default {

    data() {
        return {
            items: section.items
        }
    }

}

</script>


<style>

</style>
