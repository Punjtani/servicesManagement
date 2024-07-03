<template>
    <input :disabled="role == roleData.client" type="number" step="any" v-model.trim="displayValue" class="form-control" placeholder="Price" @keyup="editPrice()" />
</template>

<script>
export default {
    name:"CurrencyInput",
    props:["value","role","roleData"],
    data() {
        return {
            isInputActive: false
        }
    },
    computed:{
        displayValue: {
            get: function() {
                setTimeout(() => {
                    
                }, 100);
                return Number(this.value).toFixed(2)
            },
            set: function(modifiedValue) {
                // // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue)
                // // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                // // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // // $emit the event so that parent component gets it
                this.$emit('input', newValue)
            }
        }
    },
    methods:{
        editPrice(){
            this.$parent.editPrice();
        }

    }   
}
</script>