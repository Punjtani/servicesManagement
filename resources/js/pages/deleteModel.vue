<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_content_inner">
                <h5 class="text-center font_weight_600">Are you sure you want to delete this {{type}} ?</h5>
                <p class="text-center font_weight_600">Please keep in mind, by deleting you will loss the all related data to this {{type}}</p>
                <div class="text-center">
                    <button @click="delete_record(1)"  class="btn btn_blue"> Yes </button>
                    <button @click="delete_record(0)" class="btn btn_blue"> No </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['type'],
    name: "deleteModel",

    methods: {
        delete_record(val) {
            $("#deleteModel").modal('hide');
            this.$emit('closeEvent',val)
        }
    }
}
</script>
