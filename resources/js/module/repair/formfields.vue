<template>
  <a-skeleton :loading="loading">
    <a-form
      :form="form"
      :label-col="{ span: 24 }"
      :wrapper-col="{ span: 24 }"
      @submit="handleSubmit"
    >
      <a-row :gutter="24">
        <a-col :span="24"> <a-divider>Add Customer Detail</a-divider> </a-col>

        <a-col :span="4">
          <a-form-item label="Customer Name">
            <a-select
              mode="tags"
              :filter-option="filterOption"
              :maxTagCount="maxCustomer"
              @search="customerSearch"
              @select="customerSelect"
              :loading="customerSearchLoading"
              v-decorator="[
                'customer_name',
                {
                  rules: [{ required: true, message: 'Please input your customer!' }],
                  initialValue: repair.customer && repair.customer.name,
                },
              ]"
            >
              <a-select-option
                v-for="customer in customers"
                :key="customer.id.toString()"
                :customer="customer"
              >
                {{ customer.name }}
              </a-select-option>
            </a-select>
          </a-form-item></a-col
        >

        <a-col :span="4">
          <a-form-item label="Customer Number">
            <a-input
              type="number"
              v-decorator="[
                'phone',
                {
                  initialValue: repair.customer && repair.customer.phone,
                  rules: [
                    { required: true, message: 'Please input your customer_number!' },
                  ],
                },
              ]"
            /> </a-form-item
        ></a-col>

        <a-col :span="4">
          <a-form-item label="Total Cost">
            <a-input
              type="number"
              v-decorator="[
                'total_cost',
                {
                  initialValue: repair.total_cost,
                  rules: [{ required: true, message: 'Please input your total cost!' }],
                },
              ]"
            /> </a-form-item
        ></a-col>

        <a-col :span="4">
          <a-form-item label="Advance Cost">
            <a-input
              type="number"
              @search="customerSearch"
              v-decorator="[
                'advance_cost',
                {
                  initialValue: repair.advance_cost,
                  rules: [{ required: true, message: 'Please input your advance cost!' }],
                },
              ]"
            /> </a-form-item
        ></a-col>

        <a-col :span="4">
          <a-form-item label="Status">
            <a-select
              v-if="isCreated"
              v-decorator="[
                `status`,
                {
                  initialValue: repair.status,
                  rules: [{ required: true, message: 'Please select your gender!' }],
                },
              ]"
              placeholder="Select a option and change input text above"
            >
              <a-select-option v-for="status in statuses" :key="status.id">
                {{ status.name }}</a-select-option
              >
            </a-select>
            <span v-else>
              <a-tag color="volcano"> Pending </a-tag>
            </span>
          </a-form-item>
        </a-col>

        <a-col :span="4">
          <a-form-item label="Action">
            <a-button type="dashed" v-on:click="addItem">Add Item</a-button>
          </a-form-item>
        </a-col>

        <a-col :span="24"> <a-divider>Add Items</a-divider> </a-col>
        <!-- ------------------------- Item Loop should be in seperate components------------------------- -->

        <div v-for="(product, r) in row" :key="r">
          <a-col :span="4">
            <a-form-item label="Device Type">
              <a-select
                :data-key="r"
                v-decorator="[
                  `productItem[${r}][device_type_id]`,
                  {
                    initialValue: repair.customer && repair.customer.phone,
                    rules: [{ required: true, message: 'Please select your gender!' }],
                  },
                ]"
                placeholder="Select a option and change input text above"
                @select="loadProducts(r)"
              >
                <a-select-option v-for="dT in deviceType" :key="dT.id">
                  {{ dT.name }}</a-select-option
                >
              </a-select>
            </a-form-item>
          </a-col>
          <a-col :span="4">
            <a-form-item label="Brand">
              <a-select
                v-decorator="[
                  `productItem[${r}][brand_id]`,
                  {
                    initialValue: product.brand_id,
                    rules: [{ required: true, message: 'Please select your brand!' }],
                  },
                ]"
                placeholder="Select a option and change input text above"
                @select="loadProducts(r)"
              >
                <a-select-option v-for="brand in brands" :key="brand.id">
                  {{ brand.name }}
                </a-select-option>
              </a-select>
            </a-form-item>
          </a-col>

          <a-col :span="4">
            <a-form-item label="Model">
              <a-select
                v-decorator="[
                  `productItem[${r}][product_id]`,
                  {
                    initialValue: product.product_id,
                    rules: [{ required: true, message: 'Please select your model!' }],
                  },
                ]"
                placeholder="Select a option and change input text above"
              >
                <a-select-option v-for="product in products[r]" :key="product.id">
                  {{ product.name }}</a-select-option
                >
              </a-select>
            </a-form-item>
          </a-col>
          <a-col :span="4">
            <a-form-item label="Issue">
              <a-select
                v-decorator="[
                  `productItem[${r}][issue_id]`,
                  {
                    initialValue: product.issue_id,
                    rules: [{ required: true, message: 'Please select your issue!' }],
                  },
                ]"
                placeholder="Select a option and change input text above"
              >
                <a-select-option v-for="issue in issues" :key="issue.id">
                  {{ issue.name }}</a-select-option
                >
              </a-select>
            </a-form-item>
          </a-col>
          <a-col :span="4">
            <a-form-item label="IMEI">
              <a-input
                v-decorator="[
                  `productItem[${r}][device_unique_number]`,
                  {
                    initialValue: product.device_unique_number,
                    rules: [{ required: true, message: 'Please input your customer!' }],
                  },
                ]"
              />
            </a-form-item>
          </a-col>
          <a-col :span="4">
            <a-form-item label="Prices">
              <a-input
                :step="0.1"
                :min="0.1"
                type="number"
                v-decorator="[
                  'productItem[${r}][total_cost]',
                  {
                    initialValue: product.total_cost,
                    rules: [{ message: 'Please input your Cost!' }],
                  },
                ]"
              >
                <a-icon suffix slot="suffix" type="dollar" />
              </a-input>
            </a-form-item>
          </a-col>
        </div>
        <!-- ------------------------- Item Loop should be in seperate components------------------------- -->
        <a-col :span="12">
          <a-form-item :wrapper-col="{ span: 2 }">
            <a-button type="primary" html-type="submit"> Submit </a-button>
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
  </a-skeleton>
</template>

<script>
import DeviceTypeService from "./services/API/DeviceTypeService";
import BrandService from "./services/API/BrandService";
import ProductService from "./services/API/ProductService";
import IssueTypeService from "./services/API/IssueTypeService";
import RepairService from "./services/API/RepairService";
import CustomerService from "./services/API/CustomerService";
import { isEmpty, filterOption } from "./services/helpers";
export default {
  props: {
    repairId: { default: null },
  },
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addRepair" }),
      productItem: [],
      row: [],
      deviceType: [],
      brands: [],
      products: [{}],
      issues: [],
      loading: false,
      repair: {},
      isCreated: false,
      statuses: [],
      customers: [],
      maxCustomer: 1,
      filterOption,
      customerSearchLoading: false,
    };
  },
  mounted() {
    this.loadDeviceItem();
    this.loadBrands();
    this.fetchIssues();
    this.fetchRepair(this.repairId);
  },
  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          this.isCreated ? this.update(values) : this.save(values);
        }
      });
    },
    update(values) {
      RepairService.update(this.repairId, values).then((response) => {
        this.$notification.open({
          message: "Updated",
          description: response.message,
        });

        this.$emit("close", false);
      });
    },
    save(values) {
      RepairService.create(values).then((response) => {
        this.$notification.open({
          message: "Created",
          description: response.message,
        });

        this.$emit("close", false);
      });
    },
    addItem() {
      this.row = [...this.row, []];
    },
    loadDeviceItem() {
      DeviceTypeService.all().then((deviceType) => {
        this.deviceType = deviceType;
      });
    },
    loadBrands() {
      BrandService.all().then((brands) => {
        this.brands = brands;
      });
    },
    loadProducts(index) {
      let formfields = this.form.getFieldsValue();
      let { brand_id, device_type_id } = formfields.productItem[index];

      if (!isEmpty(brand_id) && !isEmpty(device_type_id)) {
        this.fetchProducts({ brand_id: brand_id, device_type_id: device_type_id }, index);
      }
    },
    fetchIssues() {
      IssueTypeService.all().then((issues) => {
        this.issues = issues;
      });
    },
    fetchStatues() {
      RepairService.statuses().then((statuses) => {
        this.statuses = statuses;
      });
    },
    fetchProducts(params, index) {
      let $this = this;
      ProductService.deviceBrand(params).then((products) => {
        $this.products.splice(index, 0, products);
      });
    },
    fetchRepair(repairId) {
      if (repairId) {
        this.isCreated = true;
        this.loading = true;
        this.fetchStatues();
        RepairService.show(repairId)
          .then((repair) => {
            this.repair = repair;
            this.row = repair.related_products;
          })
          .then(() => (this.loading = false));
      }
    },
    customerSearch(value, event) {
      if (value.length > 3) {
        this.customerSearchLoading = true;
        CustomerService.search({ search: value }).then((customers) => {
          this.customers = customers.data;
        });
        this.customerSearchLoading = false;
      }
    },
    customerSelect(value, option) {
      if (option.data.attrs.customer) {
        this.form.setFieldsValue({ phone: option.data.attrs.customer.phone });
      }
    },
  },
};
</script>
