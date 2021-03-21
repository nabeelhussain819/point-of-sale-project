<template>
  <a-table :columns="columns" :data-source="data">
    <a slot="name" slot-scope="text">{{ text }}</a>
  </a-table>
</template>
<script>
const columns = [
  {
    title: 'Id',
    dataIndex: 'id',
    key: 'id',    
  },
  {
    title: 'description',
    dataIndex: 'description',
    key: 'description', 
      ellipsis: true,   
  },
  {
    title: 'Serial',
    dataIndex: 'serial',
    key: 'serial',
  },
  {
    title: 'Unit Prices',
    dataIndex: 'unit_price',
    key: 'unit_price 2',    
  },
  {
    title: 'Extended Price',
    dataIndex: 'extended_price',
    key: 'extended_price',
    
  },
];

const data = [
  {
    key: '1',
    name: 'John Brown',
    age: 32,
    address: 'New York No. 1 Lake Park, New York No. 1 Lake Park',
    tags: ['nice', 'developer'],
  },
  {
    key: '2',
    name: 'Jim Green',
    age: 42,
    address: 'London No. 2 Lake Park, London No. 2 Lake Park',
    tags: ['loser'],
  },
  {
    key: '3',
    name: 'Joe Black',
    age: 32,
    address: 'Sidney No. 1 Lake Park, Sidney No. 1 Lake Park',
    tags: ['cool', 'teacher'],
  },
];

export default {
  data() {
    return {
      data,
      columns,
    };
  },
};
</script>
