<template>
    <div class="modal-dialog" ref="modal" @keyup.right="next" @keyup.left="prev" tabindex="0">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> {{headingText}}</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
            <div class="modal_content_inner imagePopup">
                <div class="imagePopupContainer">
                    <img class="imagePopupInner" :src="'/'+imagePath">
                </div>
                    
            </div>
            <div v-if="images && images.length > 1">
                <a class="prev" v-on:click="prev">❮</a>
                <a class="next" v-on:click="next">❯</a>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
    },
    props : ['currentIndex','images','popupImagePath','headingText'],
    data() {
        return {
            imagePath:"",
            index:"",
        }
    },

    mounted() {
    },

    watch: {
        'popupImagePath'(val) {
            if(val){
                this.imagePath = val;
            }
        },
        'currentIndex'(val) {
            if(val){
                this.index = val;
            }
        },
    },

    methods: {
        resetForm(){
            this.$emit('closeEvent',"Image popup modal has been closed")
            $("#imagePopup").modal('hide');
        },
        next(){
            if(this.index != this.images.length - 1){
                this.index++;
            }else{
                this.index = 0;
            }
            this.imagePath = this.images[this.index].file_name;
        },
        prev(){
            if(this.index == 0){
                this.index = this.images.length - 1;
            }else{
                this.index--;
            }
            this.imagePath = this.images[this.index].file_name;
        }
    }
}
</script>
