<template>
    <a-table :pagination="false" :columns="columns" :data-source="data">
        <a slot="name" class="text-capitalize" slot-scope="text">{{ text }}</a>
        <a slot="total" class="text-capitalize" slot-scope="text"
            >{{ text }}$</a
        >
        <div
            slot="filterDropdown"
            slot-scope="{
                setSelectedKeys,
                selectedKeys,

                column
            }"
            style="padding: 8px"
        >
            <a-input
                v-ant-ref="c => (searchInput = c)"
                :placeholder="`Search ${column.dataIndex}`"
                :value="selectedKeys[0]"
                style="width: 188px; margin-bottom: 8px; display: block"
                @change="
                    e => setSelectedKeys(e.target.value ? [e.target.value] : [])
                "
                @pressEnter="() => handleSearch(selectedKeys, column)"
            />
            <a-button
                type="primary"
                icon="search"
                size="small"
                style="width: 90px; margin-right: 8px"
                @click="() => handleSearch(selectedKeys, column)"
            >
                Search
            </a-button>
            <a-button
                size="small"
                style="width: 90px"
                @click="() => handleReset(selectedKeys, column)"
            >
                Reset
            </a-button>
        </div>

        <!-- Category search  -->
        <div
            slot="catgoryDropdown"
            slot-scope="{ setSelectedKeys, selectedKeys, column }"
            style="padding: 8px"
        >
            <a-select
                style="width: 100%"
                @change="value => handleSearch([value], column)"
            >
                <a-select-option
                    v-for="category in categories"
                    :key="category.id"
                >
                    {{ category.name }}
                </a-select-option>
            </a-select>
        </div>
        <!-- Category search  -->
        <!-- Department search  -->
        <div
            slot="departmentDropdown"
            slot-scope="{ setSelectedKeys, selectedKeys, column }"
            style="padding: 8px"
        >
            <a-select
                class="w-100"
                style="width: 100%"
                @change="value => handleSearch([value], column)"
            >
                <a-select-option
                    v-for="department in departments"
                    :key="department.id"
                >
                    {{ department.name }}
                </a-select-option>
            </a-select>
        </div>
        <!-- Deparment search  -->
    </a-table>
</template>
<script>
import CatgoryService from "../../../services/API/CategoryService";
import DepartmentService from "../../../services/API/DepartmentService";
export default {
    props: {
        data: {
            default: () => [],
            type: Array,
            required: true
        },
        columns: {
            default: () => [],
            type: Array,
            required: true
        }
    },
    data() {
        return {
            filters: {},
            categories: [],
            departments: []
        };
    },
    mounted() {
        this.fetchCategoryService();
        this.fetchDepartmentService();
    },
    methods: {
        fetchCategoryService() {
            CatgoryService.all().then(data => {
                this.categories = data;
            });
        },
        fetchDepartmentService() {
            DepartmentService.all().then(data => {
                this.departments = data;
            });
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            delete filters[column.key];
            this.setfilters(filters);
        },
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            // console.log("filters", this.filters);
            // this.fetchList(this.filters);
        }
    }
};
</script>
