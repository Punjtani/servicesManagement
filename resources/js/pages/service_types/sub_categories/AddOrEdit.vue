<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title">Sub Services</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" @submit.prevent="sub_category_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="form-group">
                                <label> Name</label>
                                <input type="text" v-model.trim = "$v.sub_category_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.sub_category_form.name.$error, 'is-valid':!$v.sub_category_form.name.$invalid}" placeholder="Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.sub_category_form.name.required">Field is Required</span>
                                    <span v-if="!$v.sub_category_form.name.minLength">Field length must be 2 Characters</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom_checkbox">
                                    <label>
                                        <input
                                            type="checkbox" v-bind:true-value="1"  v-bind:false-value="0"
                                            v-model.trim="$v.sub_category_form.is_independent.$model">
                                        <span class="box">Independent of apartment size</span>
                                    </label>
                                </div>
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.sub_category_form.is_independent.required">Field is Required</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom_checkbox">
                                    <label>
                                        <input
                                            type="checkbox" v-bind:true-value="1"  v-bind:false-value="0"
                                            v-model.trim="$v.sub_category_form.is_free_of_cost.$model">
                                        <span class="box">Free of cost service</span>
                                    </label>
                                </div>
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.sub_category_form.is_free_of_cost.required">Field is Required</span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button type="submit"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <button type="button" v-on:click="resetForm" data-bs-dismiss="modal"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
           
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
export default {
    name: "AddOrEdit",
    props : ['parentId','subCategoryId'],
    data() {
        return {
            sub_category_form : {
                 parent_category_id: "",
                name: "",
                is_independent: "1",
                is_free_of_cost: 0,
            },
            submit:false,
        }
    },
    validations: {
        sub_category_form : {
            name: {
                required,
                minLength: minLength(2),
            },
            is_independent: {
                required,
            },
            is_free_of_cost: {
                required,
            },


        }
    },
    mounted() {
        this.sub_category_form.parent_category_id = this.parentId;
    },

    watch: {
        'subCategoryId'(val) {
            this.id = val;
            if(this.id){
                this.getRecord(val);
            }
        }
    },

    methods: {
        getRecord(id) {
            axios.get('/api/sub-category/' + id+"/edit")
                .then((response) => {
                    // console.log("hiiiid")
                    this.sub_category_form = response.data.sub_category;

                });
        },
        sub_category_submit() {
            this.submit=true;
            this.$v.sub_category_form.$touch();
            if (this.$v.sub_category_form.$anyError) {
                return;
            }

            if(this.id){
                axios.put('/api/sub-category/'+this.id, this.sub_category_form)
                    .then(response => {
                        $("#subCategoryAdd").modal('hide');
                        this.$emit('closeEvent',"Sub Service has been updated successfully");
                        this.resetForm();
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }else {

                axios.post('/api/sub-category', this.sub_category_form)
                    .then(response => {
                        $("#subCategoryAdd").modal('hide');
                        this.$emit('closeEvent', "Sub Service  has been added successfully")
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
        },
        resetForm(){
            this.$v.sub_category_form.$reset();
            this.sub_category_form = {
                name:"",
                is_independent:1,
                is_free_of_cost:0,
            }
            this.$emit('formResetEvent','');
            this.submit=false;
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
