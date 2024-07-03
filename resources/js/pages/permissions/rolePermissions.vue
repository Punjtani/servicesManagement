<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6 col-8"><h1 class="h4 mb-0">Permissions</h1></div>
                <navigation></navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>


            <div v-for="item in role" :key="item.id">
                <h4 class="heading_with_bg">{{item.name}}</h4>
                <div class="permission-div">
                <div class="row">
                    <div class="col col-md-4"><strong>Module</strong>
                        </div>
                    <div class="col col-md-8">
                        <div class="row">
                           <div class="col col-md-3 pl-10"><strong>View</strong> </div>
                           <div class="col col-md-3 pl-0"><strong>Create</strong> </div>
                           <div class="col col-md-3 pl-0"><strong>Update</strong></div>
                           <div class="col col-md-3 pl-0"><strong>Delete</strong></div>
                        </div>
                    </div>
                </div>
                <div class="row main-class" v-for="(menu,index) in menus" :key="index">
                    <div class="col col-md-4">
                        {{menu.name}}
                    </div>
                    <div class="col col-md-8">
                        <div class="row">
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="menu.name" :checked="menu.can_view"  v-on:click = "onCheck(item, 'can_view',menu.name,$event.target.checked)">
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="menu.name" :checked="menu.can_create"  v-on:click = "onCheck(item, 'can_create',menu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="menu.name" :checked="menu.can_update"  v-on:click = "onCheck(item, 'can_update',menu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="menu.name" :checked="menu.can_delete"  v-on:click = "onCheck(item, 'can_delete',menu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                    <!-- sub menu -->
                    <div v-if="menu.hasSubMenu">
                    <div class="row sub-nav" v-for="(subMenu,subIndex) in menu.sub" :key="subIndex">
                    <div class="col col-md-4 ml-2">
                        * {{subMenu.name}}
                    </div>
                    <div class="col col-md-8">
                        <div class="row">
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="subMenu.name" :checked="subMenu.can_view"  v-on:click = "onCheck(item, 'can_view',subMenu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="subMenu.name" :checked="subMenu.can_create"  v-on:click = "onCheck(item, 'can_create',subMenu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="subMenu.name" :checked="subMenu.can_update"  v-on:click = "onCheck(item, 'can_update',subMenu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                            <div class="col col-md-3">
                            <div class="inline_checkbox checkbox_in_three">
                                <div class="custom_checkbox" >
                                <label>
                                <input type="checkbox" :name="subMenu.name" :checked="subMenu.can_delete"  v-on:click = "onCheck(item, 'can_delete',subMenu.name,$event.target.checked)" >
                                <span class="box"></span>
                                </label>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                </div>
                </div></div>
                    <!-- end sub menu -->
                </div>
            <!-- <div class="inline_checkbox checkbox_in_three">
                    <div class="custom_checkbox" v-for="permission in permissions" :key="permission.id" >
                        <label>
                             <input type="checkbox" name="permission.name" :checked="item.permissions.includes(permission.id)"  v-on:click = "onCheck(item, permission.id)" >
                            <span class="box">{{permission.slug}}</span>
                        </label>
                    </div>
                </div> -->
             </div>

        </div>
    </div>
</template>





<script>

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";

import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import Navigation from "../../components/Navigation";
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'Navigation': Navigation,
    },
    data() {
        return {
            id:  this.$route.params.id ,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            role : [],
            permissions : [],
            role_permissions: [],
            menus:[]
        }
    },
    mounted() {
        this.refreshRecord();
    },
    methods: {
        refreshRecord() {
            this.loading=true;

            this.getRolePermissions();

        },

        getRolePermissions() {
            if(this.id){
                // console.log("hello");
                axios.get('/api/role-permissions?role_id='+this.id)
                .then((response) => {
                    this.role_permissions = response.data.role_permissions;
                    this.role = response.data.role;
                    this.permissions = response.data.permissions;
                    this.menus=response.data.menus
                    this.getRecord(this.id);
                }).catch(err => {
                    // console.log(err);
                });
            }else{
                axios.get('/api/role-permissions')
                .then((response) => {
                    this.role_permissions = response.data.role_permissions;
                    this.role = response.data.role;
                    this.permissions = response.data.permissions;
                }).then(() => {
                    this.selectedPermissions();
                    this.loading=false;
                }).catch(err => {
                    // console.log(err);
                });
            }


        },
        getRecord(id) {
            axios.get('/api/role-permissions/' + id+"/edit")
                .then((response) => {
                    this.role = [response.data.role];

                }).then(() => {
                    this.selectedPermissions();
                    this.loading=false;
                }).catch(err => {
                    // console.log(err);
                });
        },
        selectedPermissions(){
            // console.log(this.role);
            this.role.forEach(element => {
                element.permissions=[];
                this.role_permissions.some(function(field){
                    if(field.role_id == element.id){
                        if(!element.permissions.includes(field.permission_id)){
                            element.permissions.push(field.permission_id);
                        }
                    }
                })
            });
        },
       onCheck(role,permission,module,checked){
            // var exist = role.permissions.includes(module);
            var permission = {permission:permission,module : module,checked:checked}
            axios.post('/api/role-permissions/'+ role.id, permission)
                    .then((response) => {
                        // console.log(response);
                    }).catch(err => {
                        // console.log(err);
                    });
            // if(!exist){
            //     role.permissions.push(module)
            //     axios.post('/api/role-permissions/'+ role.id, permission)
            //         .then((response) => {
            //             // console.log(response);
            //         }).catch(err => {
            //             // console.log(err);
            //         });

            // }else if(exist){

            //     const index = role.permissions.indexOf(module);
            //     if (index > -1) {
            //         role.permissions.splice(index, 1);
            //     }
            //     axios.delete('/api/role-permissions/'+ role.id + '/'+ module)
            //         .then((response) => {
            //             // console.log(response);
            //         }).catch(err => {
            //             // console.log(err);
            //         });
            // }
       },

    }
}


</script>
