<template>
    <a-skeleton :loading="loading">
        <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }" @submit="handleSubmit"
            class="print-repair-container">
            <a-row :gutter="24">
                <a-col :span="24">
                    <a-divider>Add Customer Detail</a-divider>
                </a-col>

                <a-col :span="4">
                    <a-form-item label="Customer Name">
                        <a-select mode="tags" :filter-option="filterOption" :maxTagCount="maxCustomer"
                            @search="customerSearch" @select="customerSelect" :loading="customerSearchLoading"
                            :disabled="!isEmpty(repair.customer)" v-decorator="[
                                'customer_id',
                                {
                                    rules: [
                                        {
                                            required: true,
                                            message:
                                                'Please input your customer!'
                                        }
                                    ],
                                    initialValue:
                                        repair.customer && repair.customer.name
                                }
                            ]">
                            <a-select-option v-for="customer in customers" :key="customer.id.toString()"
                                :customer="customer">
                                {{ customer.name_phone }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :span="4">
                    <a-form-item label="Customer Number">
                        <a-input type="number" :disabled="!isEmpty(repair.customer)" v-decorator="[
                            'phone',
                            {
                                initialValue:
                                    repair.customer &&
                                    repair.customer.phone,
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please input your customer_number!'
                                    }
                                ]
                            }
                        ]" />
                    </a-form-item>
                </a-col>

                <a-col :span="4">
                    <a-form-item label="Total Cost">
                        <a-input @change="handleTotal" :min="0" type="number" :disabled="isCreated" v-decorator="[
                            'total_cost',
                            {
                                initialValue: repair.total_cost,
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please input your total cost!'
                                    }
                                ]
                            }
                        ]" />
                    </a-form-item>
                </a-col>

                <a-col :span="4">
                    <a-form-item label="Advance Cost">
                        <a-input :disabled="true" :max="maxTotal" :min="0" type="number" v-decorator="[
                            'advance_cost',
                            {
                                initialValue: repair.advance_cost
                            }
                        ]" />
                    </a-form-item>
                </a-col>

                <a-col :span="4">
                    <a-form-item label="Status">
                        <a-select v-if="isCreated" v-decorator="[
                            `status`,
                            {
                                initialValue: repair.status,
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please select your gender!'
                                    }
                                ]
                            }
                        ]" placeholder="Select a option and change input text above">
                            <a-select-option v-for="status in statuses" :key="status.id">
                                {{ status.name }}</a-select-option>
                        </a-select>
                        <span v-else>
                            <a-tag color="volcano"> Pending </a-tag>
                        </span>
                    </a-form-item>
                </a-col>

                <a-col class="no-print" :span="4" v-if="!repair.status">
                    <a-form-item label="Action">
                        <a-button type="dashed" v-on:click="addItem">Add Item</a-button>
                    </a-form-item>
                </a-col>

                <a-col :span="24">
                    <a-divider>Add Items</a-divider>
                </a-col>
                <!-- ------------------------- Item Loop should be in seperate components------------------------- -->

                <div v-for="(product, r) in row" :key="r">
                    <a-col :span="4">
                        <a-form-item label="Device Type">
                            <a-input type="hidden" v-decorator="[
                                `productItem[${r}][id]`,
                                {
                                    initialValue: product.id
                                }
                            ]" />
                            <a-input v-decorator="[
                                `productItem[${r}][device_type]`,
                                {
                                    initialValue: product.device_type
                                }
                            ]" />
                            <!-- <a-select
                                :filterOption="true"
                                :filter-option="filterOption"
                                mode="tags"
                                :maxTagCount="1"
                                :data-key="r"
                                v-decorator="[
                                    `productItem[${r}][device_type_id]`,
                                    {
                                        initialValue: getStringId(
                                            product.device_type_id
                                        )
                                    }
                                ]"
                                placeholder="Select a option and change input text above"
                                @search="deviceTypeSearch"
                                @select="loadProducts(r)"
                            >
                                <a-select-option
                                    v-for="dT in deviceType"
                                    :key="dT.id.toString()"
                                >
                                    {{ dT.name }}</a-select-option
                                >
                            </a-select> -->
                        </a-form-item>
                    </a-col>
                    <a-col :span="4">
                        <a-form-item label="Brand">
                            <!-- <a-select
                                :showSearch="true"
                                :filter-option="filterOption"
                                mode="tags"
                                :maxTagCount="1"
                                @search="deviceTypeSearch"
                                v-decorator="[
                                    `productItem[${r}][brand_id]`,
                                    {
                                        initialValue: getStringId(
                                            product.brand_id
                                        )
                                    }
                                ]"
                                placeholder="Select a option and change input text above"
                                @select="loadProducts(r)"
                            >
                                <a-select-option
                                    v-for="brand in brands"
                                    :key="brand.id.toString()"
                                >
                                    {{ brand.name }}
                                </a-select-option>
                            </a-select> -->
                            <a-input v-decorator="[
                                `productItem[${r}][brand]`,
                                {
                                    initialValue: product.brand
                                }
                            ]" />
                        </a-form-item>
                    </a-col>

                    <a-col :span="4">
                        <a-form-item label="Model">
                            <!-- 3. Model section remove X from in repair when new repair is created Afzaal, 1/14/2022 2:46 AM-->
                            <a-select :showSearch="true" :filter-option="filterOption" mode="tags" :maxTagCount="1"
                                @search="fetchProducts" v-decorator="[
                                    `productItem[${r}][product]`,
                                    {
                                        initialValue: product.product
                                    }
                                ]" :disabled="isCreated" placeholder="Select a option and change input text above">
                                <a-select-option v-for="product in productLIST" :key="product.name">
                                    {{ product.name }}</a-select-option>
                            </a-select>
                            <!-- <a-input
                                v-decorator="[
                                    `productItem[${r}][product]`,
                                    {
                                        initialValue: product.product
                                    }
                                ]"
                            /> -->
                        </a-form-item>
                    </a-col>
                    <a-col :span="4">
                        <a-form-item label="Issue">
                            <a-select :showSearch="true" :filter-option="filterOption" mode="tags" :maxTagCount="1"
                                @search="issueSearch" v-decorator="[
                                    `productItem[${r}][issue_id]`,
                                    {
                                        rules: [
                                            {
                                                required: true,
                                                message:
                                                    'Please input your customer!'
                                            }
                                        ],
                                        initialValue: getStringId(
                                            product.issue_id
                                        )
                                    }
                                ]" placeholder="Select a option and change input text above">
                                <a-select-option v-for="issue in issues" :key="issue.id.toString()">
                                    {{ issue.name }}</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="4">
                        <a-form-item label="IMEI">
                            <a-input v-decorator="[
                                `productItem[${r}][device_unique_number]`,
                                {
                                    initialValue:
                                        product.device_unique_number
                                }
                            ]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="3">
                        <a-form-item label="Parts">
                            <a-select :showSearch="true" :filter-option="filterOption" v-decorator="[
                                `productItem[${r}][product_id]`,
                                {
                                    initialValue: getStringId(
                                        product.product_id
                                    )
                                }
                            ]" placeholder="Select a option and change input text above">
                                <a-select-option v-for="product in products" :key="product.product_id.toString()">
                                    {{ product.product.name }}</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col class="no-print" :span="1">
                        <a-form-item label="Action">
                            <a-button @click="removeRow(r)" type="link">
                                <a-icon type="delete"></a-icon>
                            </a-button>
                        </a-form-item>
                    </a-col>
                </div>
                <!-- ------------------------- Item Loop should be in seperate components------------------------- -->
                <a-col :span="4">
                    <a-form-item label="Add Payables">
                        <a-input
                            :max="!Number(repair.advance_cost) ? maxTotal : Number(repair.advance_cost) + Number(maxTotal)"
                            :step="0.01" type="number" v-decorator="[
                                'received_amount',
                                {

                                    rules: [
                                        {
                                            validator: (
                                                rule,
                                                value,
                                                callback
                                            ) =>
                                                validateTotal(
                                                    rule,
                                                    value,
                                                    callback
                                                )
                                        },
                                        {
                                            required: true,
                                            message:
                                                'Please input your Add Payable! '
                                        }
                                    ]
                                }
                            ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item label="Discount">
                        <a-input :max="maxTotal" :min="0" :disabled="!isCreated" type="number" v-decorator="[
                            'discount',
                            {
                                rules: []
                            }
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item label="Additional Charge">
                        <a-input :disabled="!isCreated" v-decorator="['additional_charge', {}]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item label="Payment Method">
                        <a-checkbox v-decorator="[
                            'pay_by_card',
                            {

                                rules: []
                            }
                        ]">
                            Pay By card
                        </a-checkbox>{{ repair.pay_by_card }}

                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item label="Comment">
                        <a-input v-decorator="[
                            'comment',
                            {
                                rules: []
                            }
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item label="Action" :wrapper-col="{ span: 12 }">
                        <a-button type="primary" class="no-print" html-type="submit">
                            Submit
                        </a-button>

                        <a-button v-if="isCreated" @click="print" class="no-print ml-2" type="default">Print
                        </a-button>
                    </a-form-item>
                </a-col>

                <a-col :span="24">
                    <a-alert v-if="advanceCompare" type="error"
                        message="Please Adjust Advance Cost Equals To Total Cost" banner />
                </a-col>

            </a-row>
        </a-form>
        <schedules :repair="repair" v-if="isCreated" />
    </a-skeleton>
</template>

<script>
import DeviceTypeService from "../../services/API/DeviceTypeService";
import BrandService from "../../services/API/BrandService";
import ProductService from "../../services/API/ProductService";
import InventoryService from "../../services/API/InventoryService";
import IssueTypeService from "../../services/API/IssueTypeService";
import RepairService from "../../services/API/RepairService";
import CustomerService from "../../services/API/CustomerService";
import schedules from "./schedules";
import {
    isEmpty,
    filterOption,
    getStringId,
    errorNotification
} from "../../services/helpers";

export default {
    components: {
        schedules
    },
    props: {
        repairId: { default: null }
    },
    data() {
        return {
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addRepair" }),
            productItem: [],
            row: [],
            deviceType: [],
            brands: [],
            products: [],
            issues: [],
            loading: false,
            repair: {},
            isCreated: false,
            statuses: [],
            customers: [],
            maxCustomer: 1,
            filterOption,
            customerSearchLoading: false,
            isEmpty,
            getStringId,
            maxTotal: 0,
            Advance: 0,
            advanceCompare: false,
            productLIST: []
        };
    },
    mounted() {
        // this.loadDeviceItem();
        // this.loadBrands();
        this.fetchIssues();
        this.fetchRepair(this.repairId);

        if (!this.isCreated) {
            this.addItem();
        }
        this.fetchProductRequest();
    },
    methods: {
        validateTotal(rule, value, callback, key) {
            // let values = this.form.getFieldsValue();
            // let total_payable = Number(value) + Number(values.advance_cost);
            // if (Number(values.total_cost) < Number(total_payable)) {
            //     return callback("values not greater than the total");
            // }
            // if (
            //     values.status === "COLLECTED" &&
            //     Number(values.total_cost) !== Number(total_payable)
            // ) {
            //     this.advanceCompare = true;
            //     return callback(
            //         "Please Adjust Advance Cost Equals To Total Cost"
            //     );
            // } else {
            //     this.advanceCompare = false;
            // }
            // // if (!isEmpty(value)) {
            // //     let prices = this.getMin();
            // //     if (prices > value) {
            // //         callback(
            // //             "Please add value greater than sub total  $" + prices
            // //         );
            // //     }
            // // }
            callback();
        },

        print() {
            window.print();
        },
        fetchProductRequest(params) {
            InventoryService.search({
                ...params,
                has_serial: false,
                quantity: true
            }).then(products => {
                this.products = products;
            });
        },
        modelSearch(keyword) {
            this.fetchProductRequest({ search: keyword, isRepair: true });
        },
        handleSubmit(e) {
            e.preventDefault();

            this.form.validateFields((err, values) => {
                if (!err) {
                    this.isCreated ? this.update(values) : this.save(values);
                }
            });
        },
        removeRow(key) {
            let row = this.row;

            this.updateRow(row.filter((producd, index) => index !== key));
        },
        updateRow(row) {
            row = JSON.stringify(row);
            this.row = JSON.parse(row);
        },
        update(values) {
            // if (values.status === "COLLECTED") {
            //     if (values.advance_cost !== values.total_cost) {
            //         this.advanceCompare = true;
            //         return false;
            //     }
            // }

            delete values.customer_id;
            RepairService.update(this.repairId, values)
                .then(response => {
                    this.$notification.open({
                        message: "Updated",
                        description: response.message
                    });

                    this.$emit("close", false);
                })
                .catch(error => {
                    errorNotification(this, error);
                });
        },
        save(values) {
            RepairService.create(values)
                .then(response => {
                    this.$notification.open({
                        message: "Created",
                        description: response.message
                    });

                    this.$emit("close", false);
                })
                .catch(error => {
                    console.log(error);
                    errorNotification(this, error);
                });
        },
        addItem() {
            this.row = [...this.row, {}];
        },
        loadDeviceItem() {
            DeviceTypeService.all().then(deviceType => {
                this.deviceType = deviceType;
            });
        },
        loadBrands() {
            BrandService.all().then(brands => {
                this.brands = brands;
            });
        },
        loadProducts(index) {
            return false; // as stupid person give the wrong requirment and I have to change it on 4:30 AM
            let formfields = this.form.getFieldsValue();
            let { brand_id, device_type_id } = formfields.productItem[index];

            if (!isEmpty(brand_id) && !isEmpty(device_type_id)) {
                this.fetchProducts(
                    { brand_id: brand_id, device_type_id: device_type_id },
                    index
                );
            }
        },
        issueSearch(search) {
            // DeviceTypeService.search({ search }).then(deviceType => {
            //     this.deviceType = deviceType;
            // });
        },
        deviceTypeSearch(search) {
            // DeviceTypeService.search({ search }).then(deviceType => {
            //     this.deviceType = deviceType;
            // });
        },
        fetchIssues() {
            IssueTypeService.all().then(issues => {
                this.issues = issues;
            });
        },
        fetchStatues() {
            RepairService.statuses().then(statuses => {
                this.statuses = statuses;
            });
        },
        fetchProducts(params, index) {
            ProductService.getAll(params).then(products => {
                this.productLIST = products;
            });
        },
        fetchRepair(repairId) {
            if (repairId) {
                this.isCreated = true;
                this.loading = true;
                this.fetchStatues();

                RepairService.show(repairId)
                    .then(repair => {
                        this.repair = repair;
                        this.row = repair.related_products;
                        this.maxTotal = repair.total_cost;
                        this.Advance = repair.advance_cost;
                    })
                    .then(() => (this.loading = false))
                    .then(() => {
                        let productsId = this.row.map(
                            product => product.product_id
                        );
                        if (productsId != [null] && !isEmpty(productsId)) {
                            this.fetchProductRequest({
                                id: productsId,
                                isRepair: true
                            });
                        }
                    });

            }
        },
        customerSearch(value, event) {
            if (value.length > 1) {
                this.customerSearchLoading = true;
                CustomerService.search({ search: value }).then(customers => {
                    this.customers = customers.data;
                });
                this.customerSearchLoading = false;
            }
        },
        customerSelect(value, option) {
            if (option.data.attrs.customer) {
                this.form.setFieldsValue({
                    phone: option.data.attrs.customer.phone
                });
            }
        },
        handleTotal(e, v) {
            this.maxTotal = e.target.value;

        }
    }
};
</script>

<style lang="scss">
@media print {
    .print-repair-container {
        width: 100%;
        font-size: 16px !important;

        .ant-form-item-label {
            height: 60px !important;
        }

        .ant-select-selection {
            border: none !important;
        }

        .ant-modal-close-x {
            display: none;
        }

        .ant-form label {
            font-size: 16px !important;
        }
    }

    .ant-divider,
    .ant-modal-close-x,
    .anticon-close {
        display: none !important;
    }

    .no-print,
    .no-print * {
        display: none !important;
    }
}
</style>
