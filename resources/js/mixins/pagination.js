export default {
  data() {
    return {
      pagination: {
        // current: 1,
        pageSize: 10,
        // total: 0,
        // showTotal: () => `Total ${this.pagination.total}`,
        onChange: (current, pageSize) => this.pageSelect(current, pageSize),
      },
    };
  },
  methods: {
    setPagination(data) {
      const pagination = { ...this.pagination };
      pagination.current = data.current_page;
      pagination.page = data.current_page;
      pagination.pageSize = data.per_page;
      pagination.total = data.total;
      this.pagination = pagination;
    },
    pageSelect(current, pageSize) {
      const pagination = { ...this.pagination };
      pagination.current = current;
      pagination.page = current;
      pagination.pageSize = pageSize;
      this.pagination = pagination;
      this.fetch();
    },
  },
};
