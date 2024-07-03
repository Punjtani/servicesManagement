<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">No {{type}} available for selected job</h5>
                <div class="text-center">
                    <button @click="closeModal()"  class="btn btn_blue"> Okay </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['type'],
    mounted(){
    },
    methods: {
        closeModal() {
            this.$emit('closeEvent');
            $("#assignInfo").modal('hide');
        }
    }
}
</script>
<style scoped>
</style>
