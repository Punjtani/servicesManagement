<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">This service has already been completed?</h5>
                <p class="text-center font_weight_600">You are not allowed to add new services to that category.</p>
<!--                <p class="text-center font_weight_600">Please keep in mind, by adding new service, the job will be reopened for employee to start work.</p>-->
                <div class="text-center">
<!--                    <button @click="updateStatus(1)"  class="btn btn_blue"> Add new service </button>-->
                    <button @click="updateStatus(0)" class="btn btn_blue"> close </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['serviceStatusData'],
    name: "serviceStatusModal",

    methods: {
        updateStatus(val) {
            $("#serviceStatusModal").modal('hide');
            this.$emit('closeEvent',val)
        }
    }
}
</script>
