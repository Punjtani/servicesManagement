<template>
    <div class="search_form_clients">
        <div class="custom__searchs">
            <div class="row g-0">
                <div class="col-8">
                    <input type="text" v-model="table_data.search" class="form-control" placeholder="Search..">
                </div>
                <div class="col-4">
                    <button class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600" @click="searchField">
                        <!-- <svg class="arrows_icon">-->
                        <!--   <use xlink:href="#double_right_arrows_angles"/>-->
                        <!--  </svg>-->
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "SingleFieldSearch",
    props: ['table_data'],
    mounted() {
    },
    methods: {
        searchField() {
            this.$emit('changetable', this.table_data)
        },
        // clearSearch(event){
        //     if(event.target.value == ""){
        //         this.searchField();
        //     }
        // }

    },
    watch: {
        'table_data.search'(val){
            this.searchField();
        }
    }
}
</script>

<style scoped>

</style>
