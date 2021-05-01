<template>
    <div>
        <search @getData="setData" />
        <list :data="data" />
    </div>
</template>

<script>
import search from "./search";
import list from "./list";
export default {
    data() {
        return {
            data: []
        };
    },
    methods: {
        setData(data) {
            
            this.data = data.track;
        }
    },
    components: {
        search,
        list
    }
};
</script>
