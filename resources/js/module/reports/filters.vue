<template>
    <div>
        <a-form
            :form="form"
            :layout="formLayout"
            @submit="handleSubmit"
            class="print-repair-container"
        >
            <a-row :gutter="24">
                <a-col :span="12">
                    <a-form-item label="Duration">
                        <a-range-picker
                            v-decorator="[
                                'date_range',
                                {
                                    initialValue: [getPastMoment(365), moment()]
                                }
                            ]"
                            :ranges="{
                                Today: [moment(), moment()],
                                Week: [getPastMoment(7), moment()],
                                Year: [getPastMoment(365), moment()],
                                Month: [getPastMoment(30), moment()]
                            }"
                            format="YYYY/MM/DD"
                            @change="dateRangeChange"
                        /> </a-form-item
                ></a-col>

                <a-col :span="8">
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
export default {
    data() {
        return {
            formLayout: "vertical",
            form: this.$form.createForm(this, { name: "addRepair" }),
            dateFormat: "YYYY/MM/DD",
            monthFormat: "YYYY/MM"
        };
    },
    methods: {
        moment,
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    console.log("values", values);
                }
            });
        },
        getPastMoment(days) {
            return moment().subtract(days, "days");
        },
        dateRangeChange(dates, dateStrings) {
            console.log("From: ", dates[0], ", to: ", dates[1]);
            console.log("From: ", dateStrings[0], ", to: ", dateStrings[1]);
        }
    }
};
</script>
