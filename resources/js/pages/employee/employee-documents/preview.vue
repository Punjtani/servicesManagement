<template>
    <div  class="modal-dialog" ref="modal" tabindex="0">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Preview Employee Document</h4>
 <button type="button" class="btn-close" @click="resetForm()"  aria-label="Close"></button>
</div>
            <div class="modal_content_inner imagePopup">
                <div v-if="documentType != 'pdf'">
                    <div class="imagePopupContainer">
                        <img class="imagePopupInner" :src="'/'+documentPath">
                    </div>
                </div>  
                <div v-else>
                    <embed :src="'/'+documentPath" width="100%" height="500" type='application/pdf'>
                </div>                 
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
    },
    props : ['documentType','documentPath'],
    data() {
        return {
        }
    },

    mounted() {
    },

    watch: {
    },

    methods: {
        resetForm(){
            this.$emit('previewCloseEvent')
            $("#viewDocument").modal('hide');
        },
    }
}
</script>
