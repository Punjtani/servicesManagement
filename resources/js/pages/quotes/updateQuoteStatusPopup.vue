<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">Are you sure you want to update Quote status?</h5>
<!--                <p class="text-center font_weight_600">Please keep in mind, by canceling this service, all the sub services will be also removed from the list.</p>-->
                <div class="text-center">
                    <button @click="updateData(1)"  class="btn btn_blue"> Yes </button>
                    <button @click="closeModal()" class="btn btn_blue"> No </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['type'],
    name: "updateQuoteModal",

    methods: {
        updateData(val) {
            $("#updateQuoteModal").modal('hide');
            this.$emit('closeEvent',val)
        },
        closeModal() {
            $("#updateQuoteModal").modal('hide');

        }
    }
}
</script>
