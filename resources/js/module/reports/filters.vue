<template>
    <div>
        <a-form
            :form="form"
            :layout="formLayout"
            @submit="handleSubmit"
            class="print-repair-container"
        >
            <a-row :gutter="24">
                <a-col :span="24">
                    <a-form-item label="Duration">
                        <a-range-picker
                            v-decorator="[
                                'dateTime',
                                {
                                    initialValue: [
                                        getPastMoment(365),
                                        moment().set({ h: 11, m: 59 })
                                    ]
                                }
                            ]"
                            :ranges="{
                                Today: [moment(), moment()],
                                Week: [getPastMoment(7), moment()],
                                Year: [getPastMoment(365), moment()],
                                Month: [getPastMoment(30), moment()]
                            }"
                            format="MM/DD/YYYY"
                            @change="dateRangeChange"
                        /> </a-form-item
                ></a-col>

                <a-col :span="24">
                    <a-form-item label="">
                        <a-button
                            type="primary"
                            class="no-print"
                            html-type="submit"
                        >
                            Submit
                        </a-button>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </div>
</template>

<script>
import moment from "moment";

import { EVENT_REPORT_FILTERS } from "../../services/constants";
import { isEmpty } from "../../services/helpers";
export default {
    data() {
        return {
            formLayout: "vertical",
            form: this.$form.createForm(this, { name: "addRepair" }),
            dateFormat: "YYYY/MM/DD",
            monthFormat: "YYYY/MM",
            params: {}
        };
    },
    methods: {
        moment,
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    if (!isEmpty(values.dateTime)) {
                        let date = values.dateTime;
                        values.date_range = [
                            date[0]
                                .set({ h: 0, m: 0 })
                                .format("YYYY-MM-DDTHH:mm:ss"),
                            date[1]
                                .set({ h: 23, m: 59 })
                                .format("YYYY-MM-DDTHH:mm:ss")
                        ];
                    }
                    this.$emit("getParams", values);
                    this.$eventBus.$emit(EVENT_REPORT_FILTERS, values);
                }
            });
        },
        getPastMoment(days) {
            return moment()
                .subtract(days, "days")
                .set({ h: 0, m: 0 });
        },
        dateRangeChange(dates, dateStrings) {
            this.params = { ...this.params, start: dates[0], end: dates[1] };
            // console.log("From: ", dates[0], ", to: ", dates[1]);
            // console.log("From: ", dateStrings[0], ", to: ", dateStrings[1]);
        },
        filters() {
            this.$emit("getFilters", this.params);
        }
    }
};
</script>
