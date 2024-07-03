<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>
            <form autocomplete="off" @submit.prevent="submitSettingsForm" method="post">
                <div class="modal-bodys">
                    <div class="form_box">
                        <div class="table_area_heads">


                            <div class="row">
                                <div class="col-sm-6 col-8">
                                    <h1 class="h4 heading_text">Settings</h1>
                                </div>
                                <Navigation></Navigation>

                            </div>
                            <!--<div class="col-sm-6 text-end">
                                    <a @click="$router.back()"><button class="btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600">Back</button></a>
                                </div>-->

                        </div>
                        <div class="row align-items-center">
                            <div>
                                <hr class="custom__hr" />
                                <div class="no_more_tables table_to_be_used mg_top_half_rem add_paint_specs_table">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-title="Name">ClientId(QBO)</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.client_id
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">ClientSecretKey(QBO)</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.client_secret
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">RedirectUrl(QBO)</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.redirect_url
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Environment(QBO)</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <select name="environment" id="environment" v-model.trim="
                                                            form.environment
                                                        " class="form-control">
                                                            <option value="Development">
                                                                Development
                                                            </option>
                                                            <option value="Production">
                                                                Production
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Authorize(QBO)</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <a href="/qb"
                                                            class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">{{ txtSync }}</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Mail Settings</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-title="Name">Mailer</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_mailer
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Host</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_host
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Port</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_port
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Username</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_username
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Password</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_password
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Encryption</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_encryption
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail From Address</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_from_address
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail CC Address</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_cc_address
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail BCC Address</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_bcc_address
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail Admin Address</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_admin_address
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td data-title="Name">Mail From Name</td>
                                                <td data-title="Value">
                                                    <div class="form-group">
                                                        <input min="0" class="form-control" v-model.trim="
                                                            form.mail_from_name
                                                        " type="text" />
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="inline_buttonss">
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <button type="submit"
                                        class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">
                                        Save
                                    </button>
                                    <!--<a @click="$router.back()"><button type="cancel" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Cancel</button></a>-->
                                </div>
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
import Loader from "../LoaderSpinner";
import Navigation from "../../components/Navigation";

export default {
    name: "PaintSpecs",
    components: {
        Loader: Loader,
        Navigation: Navigation,
    },
    data() {
        return {
            successmsg: "",
            loading: false,
            txtSync: "",
            form: {
                client_id: "",
                client_secret: "",
                redirect_url: "",
                environment: "",
                mail_mailer: "",
                mail_host: "",
                mail_port: "",
                mail_username: "",
                mail_password: "",
                mail_encryption: "",
                mail_from_address: "",
                mail_cc_address: "",
                mail_bcc_address: "",
                mail_admin_address: "",
                mail_from_name: "",

            },
        };
    },

    mounted() {
        this.loading = true;
        this.getRecord();
        this.txtSync = "Authorize Now";
    },
    methods: {
        submitSettingsForm() {
            this.loading = true;
            axios
                .post("/api/settings/store", this.form)
                .then((response) => {
                    this.loading = false;
                    this.successmsg = "Settings has been updated";
                    setTimeout(() => {
                        this.successmsg = "";
                        // this.$router.back();
                    }, 3000);
                })
                .catch((error) => {
                    this.has_error = true;
                    this.loading = false;
                });
        },
        getRecord(id) {
            axios.get("/api/settings").then((response) => {
                var settings = response.data.settings;
                this.loading = false;
                this.form.client_id = settings.client_id;
                this.form.client_secret = settings.client_secret;
                this.form.redirect_url = settings.redirect_url;
                this.form.environment = settings.environment;
                this.form.mail_mailer = settings.mail_mailer;
                this.form.mail_host = settings.mail_host;
                this.form.mail_port = settings.mail_port;
                this.form.mail_username = settings.mail_username;
                this.form.mail_password = settings.mail_password;
                this.form.mail_encryption = settings.mail_encryption;
                this.form.mail_from_address = settings.mail_from_address;
                this.form.mail_cc_address = settings.mail_cc_address;
                this.form.mail_bcc_address = settings.mail_bcc_address;
                this.form.mail_admin_address = settings.mail_admin_address;
                this.form.mail_from_name = settings.mail_from_name;
            });
            this.checkSyncStatus();
        },
        checkSyncStatus() {
            axios.get('/api/client/check-qb/status')
                .then((response) => {
                    if (response.data.isSynced) {
                        this.txtSync = "Authorized"
                    } else {
                        this.txtSync = "Authorize Now"
                    }
                });
        }
    },
};
</script>

<style scoped></style>
