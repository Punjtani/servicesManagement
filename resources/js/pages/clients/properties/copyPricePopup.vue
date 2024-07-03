<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">Are you sure you want to copy all the Prices from General Pricing?</h5>
                <p class="text-center font_weight_600">Please keep in mind, by copying you will replace the all customised Prices of this Property.</p>
                <div class="text-center">
                    <button @click="copy_record(1)"  class="btn btn_blue"> YES </button>
                    <button @click="copy_record(0)" class="btn btn_blue"> No </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['propertyId'],
    name: "CopyPricing",

    methods: {
        copy_record(val) {
            $("#copyPriceModel").modal('hide');
            this.$emit('closeEvent',val)
        }
    }
}
</script>
