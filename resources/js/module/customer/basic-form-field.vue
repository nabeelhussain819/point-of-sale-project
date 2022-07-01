<template>
    <div>
        <a-col :span="6">
            <a-form-item label="Customer Name">
                <!-- ------------------- -->

                <a-select mode="tags" :filter-option="filterOption" :maxTagCount="maxCustomer"
                    @select="onCustomerSelect" @search="customerSearch" :loading="customerSearchLoading" v-decorator="[
                        'customer_id',
                        {
                            initialValue: selectedCustomer.name,
                        },
                    ]">
                    <a-select-option v-for="customer in customers" :key="customer.id.toString()" :customer="customer">
                        {{ customer.name }}
                    </a-select-option>
                </a-select>
                <a-input type="hidden" v-decorator="[
                    'customer_name'
                ]" />
                <!-- ------------------------------ -->
            </a-form-item>
        </a-col>
        <a-col :span="6">
            <a-form-item label="Customer Phone">
                <a-input v-decorator="[
                    'customer_phone',
                    {
                        initialValue: selectedCustomer.phone,
                    },
                ]" />
            </a-form-item>
        </a-col>

        <a-col :span="12">
            <a-form-item label="Customer Address">
                <a-input v-decorator="[
                    'customer_address',
                    {
                        initialValue: selectedCustomer.address,
                    },
                ]" />
            </a-form-item>
        </a-col>
    </div>
</template>
<script>
import { filterOption, isEmpty } from "../../services/helpers";
import CustomerService from "../../services/API/CustomerService";
export default {
    props: {
        form: {
            default: () => { },
        },
    },
    data() {
        return {
            visible: false,
            customers: [],
            filterOption,
            selectedCustomer: [],
            customerSearchLoading: false,
            maxCustomer: 1,
            currentCustomer: null,
        };
    },
    methods: {
        isCreated() {
            return !isEmpty(this.currentCustomer);
        },
        onCustomerSelect(value, options) {
            // on selectif the current customer is from database
            let selectedCustomer = options.data.attrs.customer;
            if (!isEmpty(selectedCustomer) && !isEmpty(selectedCustomer.phone)) {
                this.currentCustomer = selectedCustomer;
                this.form.setFieldsValue({
                    customer_phone: selectedCustomer.phone,
                    customer_name: selectedCustomer.name,
                    customer_address: selectedCustomer.address,
                });
            } else {
                this.currentCustomer = null;
                this.form.setFieldsValue({ phone: null });
            }
        },
        customerSearch(searchText, event) {
            if (!isEmpty(searchText) && searchText.length > 1) {
                this.customerSearchLoading = true;
                CustomerService.search({ search: searchText })
                    .then((customers) => {
                        this.customers = customers.data;
                    })
                    .catch((err) => {
                        console.log("error", err.response.data);
                    })
                    .finally(() => {
                        this.customerSearchLoading = false;
                    });
                this.customerSearchLoading = false;
            }
        },
    },
};
</script>
