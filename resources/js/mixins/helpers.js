export default {
  data() {
    return {
      uuid: 0,
      uuidString: "uuid-",
    };
  },
  methods: {
    getUid() {
      this.uuid = this.uuid + 1;
      return this.uuidString + this.uuid;
    },
    /**
     * Checks if a JavaScript value is empty
     * @example
     *    isEmpty(null); // true
     *    isEmpty(undefined); // true
     *    isEmpty(''); // true
     *    isEmpty([]); // true
     *    isEmpty({}); // true
     * @param {any} value - item to test
     * @returns {boolean} true if empty, otherwise false
     */
    isEmpty(value) {
      return (
        value === null || // check for null
        value === undefined || // check for undefined
        value === "" || // check for empty string
        (Array.isArray(value) && value.length === 0) || // check for empty array
        (typeof value === "object" && Object.keys(value).length === 0) // check for empty object
      );
    },
  },
};
