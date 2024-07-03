<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">Confirmation ?</h5>
                <p class="text-center font_weight_600">Are you sure to {{type}}?</p>
                <div class="text-center">
                    <button @click="confirm(1)"  class="btn btn_blue"> Yes </button>
                    <button @click="confirm(0)" class="btn btn_blue"> No </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['type'],
    name: "confirmModel",

    methods: {
        confirm(val) {
            $("#confirmModel").modal('hide');
            this.$emit('closeEvent',val)
        }
    }
}
</script>
