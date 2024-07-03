<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Invoice</h4>
 <button type="button" class="btn-close" @click="resetForm()" aria-label="Close"></button>
</div>

            <div class="modal-body">
                
                <!--                    <iframe :src="pdfUrl" title='SOME_TITLE' />-->
                <!--                     <embed :src="pdfUrl" width="100%" height="500" type='application/pdf' >-->
                <object v-if="pdfUrl" :data="pdfUrl" type="application/pdf" width="100%" height="500">
                    <p>Your web browser doesn't support pdf.
                        Instead you can <a :href="pdfUrl" download="invoice.pdf" title="ttile" name="names">click here to
                            download the PDF file.</a></p>
                </object>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import roleData from '../../data.js';
export default {
    props: ['url'],
    data() {
        return {
            pdfUrl: ''
        }
    },

    mounted() {
    },

    watch: {
        'url'(val) {
            if (val != '') {
                this.pdfUrl = val;
            }
        }
    },

    methods: {
        downloadInvoice() {
            axios.get('/api/download-invoice/' + this.id, {
                responseType: 'blob'
            }).then((response) => {
                if (this.invoiceView) {
                    var url = URL.createObjectURL(new Blob([response.data], {
                        type: 'application/pdf'
                    }));
                    this.url = url;
                    // window.open(url, '_blank');
                    this.invoiceView = false;
                    url = '';
                } else {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'invoice.pdf');
                    document.body.appendChild(fileLink);
                    fileLink.click()
                    fileLink.parentNode.removeChild(fileLink);
                }
            });
        },
        resetForm() {
            this.$forceUpdate();
            this.$emit('closeEvent', "Invoice Pdf has been closed")
            $("#viewInvoice").modal('hide');
        },
    }
}
</script>
