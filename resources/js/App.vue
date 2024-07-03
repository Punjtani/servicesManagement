<template>
    <RootComponent></RootComponent>
</template>

<script>
import RootComponent from './templates/RootComponent';

export default {
    components: {
        RootComponent: RootComponent
    },
    mounted() {

    },
    methods: {
        consoleMe(test) {
            // console.log('Event from child triggerred: ', test);
        }
    }
}
</script>
