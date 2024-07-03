<template>
    <div>

        <div class="attachments_all">
           <div class="row align-items-center attachments_box">
                <div class="col-6 col-md-6 cols_5">
                    <div class="form-group">
                        <div class="file_upload">
                            <input class="form-control" :accept="accept" type="file" style="opacity: 0;" @change="uploaded"
                                :multiple="multiple" name="uploaded_file" ref="uploaded_file"/>
                            <img v-if="placeholder != 'button'" src="/images/image_placeholder_upload_File.png" alt="image_placeholder_upload_File">
                            <button v-if="placeholder == 'button'" class="btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600">Upload Attachments</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" v-if="filenames">
                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Attachments</h6>
            </div>
            <div class="row align-items-center no-gutters attachments_box">
                <div class="col-6 col-md-6 cols_5" v-for="(item1, index1) in mergedFiles">

                        <div class="img__box">
                            <img :src="'/'+item1">
                            <span class="del_icon" @click="deleteImage(index1)">
                        <svg class="close_sidebar_icon" style="color: white"><use xlink:href="#remove"/></svg></span>
                        </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UploadFileComponent",
    props: ['multiple', 'api', 'files', 'accept','placeholder','serviceId'],
    components: {},
    data() {
        return {
            filenames: null,
            mergedFiles: null,
            removedFiles: []
        }
    },
    watch:{
        'files'(val){
            this.mergedFiles = val;
        },
    },
    mounted() {
        this.mergedFiles = this.files;
    },
    methods: {
        uploaded() {
            this.filenames = this.$refs.uploaded_file.files
            const filesArray = Array.from(this.filenames)
            var invalid = false;
            filesArray.forEach(data => {
                if(!this.accept.includes(data.type)){
                    invalid = true;
                    // console.log('invalid');
                }
            })
            if(invalid){
                this.$emit('invalid', 'File type is invalid');
                return;
            }
            let formData = new FormData();
            for (let i = 0; i < this.filenames.length; i++) {
                formData.append('files[]', this.filenames[i])
            }
            axios.post(this.api, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    if (this.multiple) {
                        this.filenames = response.data.filenames;
                        this.mergedFiles = this.mergedFiles ? this.mergedFiles.concat(this.filenames) :  this.filenames;
                        if(this.serviceId){
                            this.$emit('uploaded', this.mergedFiles,this.serviceId);
                        }else{
                            this.$emit('uploaded', this.mergedFiles)
                        }
                    } else {
                        this.filenames = response.data.filenames[0];
                        this.mergedFiles = [];
                        this.mergedFiles = this.mergedFiles.concat(this.filenames);
                        if(this.serviceId){
                            this.$emit('uploaded', response.data.filenames[0],this.serviceId)
                        }else{
                            this.$emit('uploaded', response.data.filenames[0])
                        }
                    }
                })
                .catch(err => {
                    // console.log(err)
                })
        },
        deleteImage(index) {
            this.removedFiles.push(this.mergedFiles[index]);
            this.mergedFiles.splice(index, 1);
            if(this.serviceId){
                this.$emit('uploaded', this.mergedFiles,this.serviceId, this.removedFiles)
            }else{
                this.$emit('uploaded', this.mergedFiles, this.removedFiles)
            }
        }
    },
}
</script>

<style scoped>
.img__box {
    position: relative
}

.img__box:hover::after {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    left: 0;
    bottom: 0;
    background-color: #00000040;
}

.img__box .del_icon {
    position: absolute;
    top: 4px;
    right: 4px;
    padding: 0px;
    color: #fff;
    font-weight: bold;
    font-size: 14px;
    z-index: 222;
    cursor: pointer;
}

.img__box .del_icon .close_sidebar_icon {
    width: 20px;
    height: 20px;
    fill: darkred;
    z-index: 222;
}

.img__box:hover .del_icon .close_sidebar_icon {
    fill: darkred;
    width: 22px;
    height: 22px;
}
</style>
