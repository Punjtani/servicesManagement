<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Crew Members</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="saveChanges" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">                        
                        <div class="form_box">
                            <div  class="no_more_tables table_to_be_used mg_top_1rem"> 
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th class="text-center min_max_130">Percentage</th>
                                            <th class="text-center min_max_80">Lead</th>
                                            <th class="text_end_only_desktop min_max_130">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr  v-for="(crew, e_key) in form.employee_crew" :key="e_key">
                                        <td data-title="Member">{{ crew.employe.user.first_name }} {{ crew.employe.user.last_name }}</td>
                                        <td  data-title="Percentage" class="text-center">
                                            <input type="text" v-model="crew.percentage" class="form-control text-center form_control_small" placeholder="Percentage">
                                        </td>
                                        <td data-title="Lead" class="text-center min_max_80">
                                            <span v-if="crew.is_lead == 1">
                                            <input v-model="crew.is_lead" :id="e_key" checked type="checkbox"
                                                @change="check(e_key)">
                                            </span>
                                            <span v-if="crew.is_lead == 0">
                                            <input v-model="crew.is_lead" :id="e_key" type="checkbox"
                                                @change="check(e_key)">
                                            </span>
                                        </td>
                                        <td  data-title="Action" class="text-end min_max_130">
                                            <button class=" btn  font_12 font_weight_700 text_uppercase btn_width_full btn_extra_small" href="#" @click="removeMember(crew.id)">Delete</button>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                           

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttonss">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button  type="submit" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600"> Save</button>
                                        <button class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" data-bs-dismiss="modal" aria-label="Close"> Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            
        </div>
    </div>
</template>

<script>
import axios from "axios"
import { required } from 'vuelidate/lib/validators'
export default {
    name: "ViewModal",
    props : ['id'],
    /** Mounted hook */
    mounted() {

    },
    data() {
        return {
            form: {employee_crew: []}
        }
    },
    watch: {
        'id'(val) {
            this.id = val
            if(val != '') this.getRecord(val)
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/crew-employees/' + id + "/edit")
                .then((response) => {
                    this.form = response.data.data
                    this.form.employee_crew.forEach(c => {
                        if(c.is_lead == 1) c.checked = true
                        if(c.is_lead == 0) c.checked = false
                    })
                })
        },
        removeMember(id) {
            axios.delete('/api/crew-employees/' + id)
                .then(response => {
                    this.getRecord(this.id)
                })
                .catch(err => {
                    this.has_error = true
                })
        },
        saveChanges() {

            let data = []
            this.form.employee_crew.forEach(c => {
                data.push({
                    id: c.id,
                    crew_id: c.crew_id,
                    employee_id: c.employee_id,
                    percentage: c.percentage,
                    is_lead: c.is_lead
                })
            })

            axios.put('/api/crew-employees/update', {data: data})
                .then(response => {
                    $("#employee-crew-members-view-modal").modal('hide')
                    this.$emit('closeEvent',"Crew members has been updated successfully")
                })
                .catch(err => {
                    this.has_error = true
                })

        },
        check(index,event){


            this.form.employee_crew.forEach(c => {
                c.is_lead=0;
            })
            this.form.employee_crew[index].is_lead = 1;

            // console.log(this.form.employee_crew);
        }
    }
}
</script>

<style scoped>

</style>
