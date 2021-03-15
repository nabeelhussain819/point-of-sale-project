<template>
  <a-form
    :form="form"
    :label-col="{ span: 24 }"
    :wrapper-col="{ span: 24 }"
    @submit="handleSubmit"
  >
    <a-row :gutter="24">
      <a-col :span="24"> <a-divider>Add Customer Detail</a-divider> </a-col>

      <a-col :span="10">
        <a-form-item label="Customer Name">
          <a-input
            v-decorator="[
              'customer_name',
              { rules: [{ required: true, message: 'Please input your customer!' }] },
            ]"
          /> </a-form-item
      ></a-col>

      <a-col :span="10">
        <a-form-item label="Customer Number">
          <a-input
            type="number"
            v-decorator="[
              'customer_number',
              {
                rules: [
                  { required: true, message: 'Please input your customer_number!' },
                ],
              },
            ]"
          /> </a-form-item
      ></a-col>

      <a-col :span="4">
        <a-form-item label="Action">
          <a-button type="dashed" v-on:click="addItem">Add Item</a-button>
        </a-form-item>
      </a-col>

      <a-col :span="24"> <a-divider>Add Items</a-divider> </a-col>
      <!-- ------------------------- Item Loop should be in seperate components------------------------- -->

      <div v-for="r in row" :key="r">
        <a-col :span="4">
          <a-form-item label="Device Type">
            <a-select
              :data-key="r"
              v-decorator="[
                `productItem[${r}][device_type_id]`,
                { rules: [{ required: true, message: 'Please select your gender!' }] },
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
                { rules: [{ required: true, message: 'Please select your brand!' }] },
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
                { rules: [{ required: true, message: 'Please select your model!' }] },
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
                { rules: [{ required: true, message: 'Please select your issue!' }] },
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
                { rules: [{ required: true, message: 'Please input your customer!' }] },
              ]"
            />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="Prices">
            <a-input
              :step="0.1"
              type="number"
              v-decorator="[
                'total_cost',
                { rules: [{ message: 'Please input your Cost!' }] },
              ]"
            >
              <a-icon suffix slot="suffix" type="dollar" />
            </a-input>
          </a-form-item>
        </a-col>
      </div>
      <!-- ------------------------- Item Loop should be in seperate components------------------------- -->
      <a-col :span="12">
        <a-form-item :wrapper-col="{ span: 12, offset: 5 }">
          <a-button type="primary" html-type="submit"> Submit </a-button>
        </a-form-item>
      </a-col>
    </a-row>
  </a-form>
</template>

<script>
import DeviceTypeService from "./services/API/DeviceTypeService";
import BrandService from "./services/API/BrandService";
import ProductService from "./services/API/ProductService";
import IssueTypeService from "./services/API/IssueTypeService";
import RepairService from "./services/API/RepairService";

import { isEmpty } from "./services/helpers";
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addRepair" }),
      productItem: [],
      row: 0,
      deviceType: [],
      brands: [],
      products: [{}],
      issues: [],
    };
  },
  mounted() {
    this.loadDeviceItem();
    this.loadBrands();
    this.fetchIssues();
  },
  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          RepairService.create(values).then((response) => {
            console.log(response);
          });
          console.log("Received values of form: ", values);
        }
      });
    },
    handleSelectChange(value) {
      console.log(value);
      // this.form.setFieldsValue({
      //   note: `Hi, ${value === "male" ? "man" : "lady"}!`,
      // });
    },
    addItem() {
      this.row = this.row + 1;
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
    fetchProducts(params, index) {
      let $this = this;
      ProductService.deviceBrand(params).then((products) => {
        $this.products.splice(index, 0, products);
      });
    },
  },
};
</script>
