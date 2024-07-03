<template>
    <div class = "row row_spacing_5">
        <div  class="col-sm-6 cccccccccccc">
            <div class="form-group"  v-if="table_data.data_filter_options">
            <!--<multiselect  class=" text_capitalize" deselectLabel="" v-model.trim="table_data.data_filter" placeholder="Select Date"  :select-label="''" :options="table_data.data_filter_options" :searchable="true" :allow-empty="true"></multiselect>-->
            <datepicker
                placeholder = "Select date"
                format="MM/dd/yyyy"
                input-class="form-control"
                v-model.trim="table_data.data_filter"
                :typeable="true"
                >
            </datepicker>
            </div>
        </div>
        <div  class="col-sm-6 vvvvvv" >
            <div class="form-group">
            <multiselect  class=" text_capitalize" deselectLabel="" v-model.trim="table_data.status_filter"  placeholder="Select Status" :select-label="''" :options="table_data.status_filter_options" :searchable="true" :allow-empty="true"></multiselect>
            </div>
        </div>
       
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
import Datepicker from 'vuejs-datepicker';
export default {
    
    name: "FilterPage",
    props: ['table_data'],
    components : {
        'Multiselect': Multiselect,
        'Datepicker': Datepicker
    },
    mounted() {
    },
    watch: {
        'table_data.status_filter'(val) {
            this.searchField();
        },
        'table_data.data_filter'(val) {
            this.searchField();
        },
    },
    methods: {
        searchField() {
            this.$emit('changetable', this.table_data)
        },

    }
}
</script>

<style scoped>

</style>
