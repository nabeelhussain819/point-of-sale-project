<template>
    <div>
        <a-row>
            <a-col :span="12">
                <a-form-item label="Vendor">
                    <a-select
                        :show-search="true"
                        placeholder="Start typing to find Vendour"
                        style="width: 200px"
                        :default-active-first-option="false"
                        :show-arrow="false"
                        :filter-option="false"
                        :not-found-content="null"
                        :loading="vendorLoading"
                        v-decorator="[
                            `vendor_id`,
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert return to!'
                                    }
                                ]
                            }
                        ]"
                        @search="handleSearch"
                        @change="handleChange"
                    >
                        <a-select-option v-for="d in vendors" :key="d.id">
                            {{ d.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col :span="12">
                <a-form-item label="Start Date"
                    ><a-date-picker
                        v-decorator="[
                            'expected_date',
                            {
                                rules: [{ required: true }]
                            }
                        ]"
                    />
                </a-form-item>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import axios from "axios";
import VendorService from "../../services/API/VendorService";
import { isEmpty } from "../../services/helpers";
export default {
    data() {
        return {
            vendors: {},
            vendorLoading: false
        };
    },
    methods: {
        cancelSearch() {
            if (this.cancelSource) {
                this.cancelSource.cancel(
                    "Start new search, stop active search"
                );
            }
        },
        handleSearch(value) {
            if (!isEmpty(value)) {
                this.cancelSearch();
                this.cancelSource = axios.CancelToken.source();
                this.vendorLoading = true;
                return VendorService.search(
                    {
                        search: value
                    },
                    this.cancelSource.token
                )
                    .then(response => {
                        this.vendors = response.data;
                        this.cancelSource = null;

                        return;
                    })
                    .catch(e => {
                        console.log(e.response);
                    })
                    .finally(() => (this.vendorLoading = false));
            }
        },
        handleChange(value) {
            this.value = value;
            fetch(value, data => (this.data = data));
        }
    }
};
</script>
