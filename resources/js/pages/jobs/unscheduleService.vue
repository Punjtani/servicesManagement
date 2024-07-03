<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 v-if="serviceName" class="text-center font_weight_600">Are you sure you want to unschedule {{serviceName}}?</h5>
                <h5 v-else class="text-center font_weight_600">Are you sure you want to unschedule all services?</h5>
                <div class="text-center">
                    <button @click="unscheduleService(1)"  class="btn btn_blue"> YES </button>
                    <button @click="unscheduleService(0)" class="btn btn_blue"> No </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['serviceName'],
    name: "UnscheduleModel",
    methods: {
        unscheduleService(val) {
            $("#UnscheduleAllServicesModel").modal('hide');
            $("#UnscheduleServiceModel").modal('hide');
            this.$emit('closeEvent',val)
        }
    }
}
</script>
