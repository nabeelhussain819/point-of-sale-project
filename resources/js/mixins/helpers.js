export default {
    data() {
        return {
            uuid: 0,
            uuidString: "uuid-"
        };
    },
    methods: {
        getUid() {
            this.uuid = this.uuid + 1;
            return this.uuidString + this.uuid;
        }
    }
};
