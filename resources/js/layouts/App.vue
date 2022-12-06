<template>
    <router-view/>
</template>

<script>

import mitt from 'mitt';

const emitter = mitt();

export default {

    mounted() {
        // localStorage.setItem('project_employees_active', JSON.stringify([]))
        emitter.on('loggedOut', (evt) => {
            console.log('logged out app')
            // this.$router.push('/login')
        });
    },

}

</script>
