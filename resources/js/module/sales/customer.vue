<template>
  <a-card hoverable>
    <div v-if="!isCreated">
      <a-button slot="extra" icon="user-add" type="primary">Add Customer</a-button>
    </div>
    <span v-else>
      <a-button slot="extra" type="link">edit</a-button>
      <div slot="title">
        <span> Naveed Nasib</span> <br /><a-icon type="phone" /> 03323606922
      </div>
    </span>
  </a-card>
</template>
<script>
export default {
  data() {
    return {
      isCreated: false,
    };
  },
};
</script>
