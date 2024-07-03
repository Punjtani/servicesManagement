<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Preview</h4>
                <button type="button" class="btn-close" @click="resetForm()"  aria-label="Close"></button>
            </div>
                <div class="modal-body" >
<!--                     <embed :src="pdfUrl" width="100%" height="500" type='application/pdf'>-->
                    <div v-html="previewContent"></div>
                </div>
                <div class="modal-footer"></div>
        </div>
    </div>
</template>

<script>

export default {
    props : ['url','previewContent'],
    data() {
        return {
            pdfUrl:''
        }
    },

    mounted() {
    },

    watch: {
        'url'(val) {
            if(val != ''){
                this.pdfUrl = val;
            }
        },
        'previewContent'(val) {
            this.previewContent = val
        }
    },

    methods: {

        resetForm(){
            this.$forceUpdate();
            this.$emit('closeEvent',"Pdf has been closed")
            $("#viewQuotePdf").modal('hide');
        },
    }
}
</script>
