<template>
<div class="dashboard-pagination">
        <div style="float: left; margin-right: 30px">
        <select name="" id="perPageCount" @change="callBack(true)" v-model="perPageCount">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>

    </div>
    <div style="float: right; margin-right: 30px">
        <button :disabled="table_data.current_page  == 1"
                class="btn btn_x_small btn_orange border_radius_5 text-uppercase font_14 " @click="firstPage()">
            <svg class="arrows_icon">
                <use xlink:href="#left_arrowheads_couple"/>
            </svg>
        </button>
        <button :disabled="table_data.current_page  ==  1"
                class="btn btn_x_small btn_orange border_radius_5 text-uppercase font_14 " @click="previousPage()">
            <svg class="arrows_icon">
                <use xlink:href="#chevron_pointing_to_the_left"/>
            </svg>
        </button>
        {{ table_data.current_page }} / {{ table_data.last_page }}
        <button :disabled="table_data.current_page  ==  table_data.last_page"
                class="btn btn_x_small btn_orange border_radius_5 text-uppercase font_14 " @click="nextPageRecord()">

            <svg class="arrows_icon">
                <use xlink:href="#right-chevron"/>
            </svg>
        </button>
        <button :disabled="table_data.current_page  ==  table_data.last_page"
                class="btn btn_x_small btn_orange border_radius_5 text-uppercase font_14 " @click="lastPageRecord()">
            <svg class="arrows_icon">
                <use xlink:href="#double_right_arrows_angles"/>
            </svg>
        </button>
    </div>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: ['table_data'],
    data(){
    return{
     perPageCount:25,
     oldCountValue:25
    }
    },
    mounted() {
    },
    methods: {
        firstPage() {
            this.table_data.current_page = 1;
            this.callBack();
        },
        previousPage() {
            this.table_data.current_page = (this.table_data.current_page - 1);
            this.callBack();
        },
        nextPageRecord() {
            this.table_data.current_page = (this.table_data.current_page + 1);
            this.callBack();

        },
        lastPageRecord() {
            this.table_data.current_page = this.table_data.last_page;
            this.callBack();
        },
        callBack(isPageCountChange) {
            if(isPageCountChange)
            {
                if(this.perPageCount > this.oldCountValue) {
                    const oldRecordsCounts = this.oldCountValue * this.table_data.current_page;
                    this.table_data.current_page = Math.ceil(oldRecordsCounts / this.perPageCount);
                }
                this.oldCountValue = this.perPageCount;
            }
            this.table_data.per_page=this.perPageCount
            this.$emit('changetable', this.table_data)
        }
    }
}
</script>

<style scoped>

</style>
