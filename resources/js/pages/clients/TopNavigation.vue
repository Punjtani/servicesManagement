<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li  v-if="parent == 'null'" class="nav-item" role="presentation">
                <router-link v-bind:to="'/view-client/' + clientId" class="nav-link" id="home-tab" v-bind:class="{ active: clientActive}"
                            type="button" role="tab" aria-controls="home" aria-selected="true">General Info
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link v-bind:to="'/client-property/' + clientId" class="nav-link" id="profile-tab" v-bind:class="{ active: propertyActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Properties
                </router-link>
            </li>
             <li class="nav-item" role="presentation">
                <router-link v-bind:to="'/client-quotes/' + clientId" class="nav-link" id="quote-tab" v-bind:class="{ active: quoteActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Quotes
                </router-link>
            </li>
            <li class="nav-item" role="presentation" v-if="parent=='null'">
                <router-link v-if="" v-bind:to="'/client/'+clientId+'/all-staff'" class="nav-link"  v-bind:class="{ active: staffActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Staff
                </router-link>
            </li>
<!--            <li v-if="role == roleData.admin || role == roleData.super_admin" class="nav-item" role="presentation">-->
<!--                <router-link :to="{name:'StaffRoles',params:{id:clientId}}" class="nav-link"  v-bind:class="{ active: staffRolesActive}"-->
<!--                            type="button" role="tab" aria-controls="profile" aria-selected="false">Staff Roles-->
<!--                </router-link>-->
<!--            </li>-->
            <li v-if="role == roleData.admin || role == roleData.super_admin" class="nav-item" role="presentation">
                <router-link :to="{name:'ClientBilling',params:{id:clientId}}" class="nav-link"  v-bind:class="{ active: billingActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Billing Information
                </router-link>
            </li>
            <!--<li class="nav-item" role="presentation">
                <router-link v-bind:to="'/client-history/' + clientId">
                    <button class="nav-link"  v-bind:class="{ active: historyActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">History
                    </button>
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link v-bind:to="'/client-paintspecs/' + clientId">
                    <button class="nav-link"  v-bind:class="{ active: paintspecsActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Paint Specs
                    </button>
                </router-link>
            </li>-->
        </ul>
    </div>
</template>
<script>
import roleData from '../../data.js';
export default {
    name: "TopNavigation",
    props: ['clientId', 'currentComponent'],
    data() {
        return {
            parent: "",
            propertyActive: false,
            quoteActive: false,
            clientActive: false,
            staffActive: false,
            supplierActive: false,
            historyActive: false,
            paintspecsActive: false,
            billingActive: false,
            staffRolesActive: false,
            role:"",
            roleData:roleData,
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.parent = localStorage.getItem("parent");

        if (this.currentComponent == 'properties') {
            this.propertyActive = true;
        }
         if (this.currentComponent == 'quotes') {
            this.quoteActive = true;
        }
        if (this.currentComponent == 'clientEdit') {
            this.clientActive = true;
        }
        if (this.currentComponent == 'staff') {
            this.staffActive = true;
        }
        if (this.currentComponent == 'supplier') {
            this.supplierActive = true;
        }
        if (this.currentComponent == 'history') {
            this.historyActive = true;
        }
        if (this.currentComponent == 'paintspecs') {
            this.paintspecsActive = true;
        }
        if (this.currentComponent == 'clientBilling') {
            this.billingActive = true;
        }
        if (this.currentComponent == 'StaffRoles') {
            this.staffRolesActive = true;
        }
    }
}
</script>

<style scoped>

</style>
