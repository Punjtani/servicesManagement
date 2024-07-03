<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <router-link v-bind:to="'/employees'" class="nav-link" id="employee-tab" v-bind:class="{ active: employeeActive}"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Employee Management
                </router-link>
            </li>
            <li class="nav-item" role="presentation">
                <router-link v-bind:to="'/crews'" class="nav-link" id="crew-tab" v-bind:class="{ active: crewActive}"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Crew Management
                </router-link>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    name: "TopNavigation",
    props: ['currentComponent'],
    data() {
        return {
            crewActive: false,
            employeeActive: false,
        }
    },
    mounted() {
        if (this.currentComponent == 'crew') {
            this.crewActive = true;
        }
         if (this.currentComponent == 'employee') {
            this.employeeActive = true;
        }
        
    }
}
</script>

<style scoped>

</style>
