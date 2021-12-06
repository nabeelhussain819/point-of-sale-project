<template>
    <a-card title="Inventory">
        <list @fetch="setFetch" @showSerialNumber="showSerialNumber" />
        <a-modal
            width="80%"
            :destroyOnClose="true"
            :title="getTitle()"
            :visible="visible"
            :footer="null"
            @cancel="showModal(false)"
        >
            <Bin @onClose="showModal" :inventory="inventory" />
        </a-modal>
    </a-card>
</template>

<script>
import List from "./list";
import Bin from "./bin";
import Serials from "./../product/serials";
import { isEmpty } from "../../services/helpers";
export default {
    components: { List, Serials, Bin },
    data() {
        return {
            selectedProduct: {},
            product_id: null,
            visible: false,
            inventory: null,
            fetch: () => {}
        };
    },
    methods: {
        setFetch(fetch) {
            this.fetch = fetch;
        },
        getTitle() {
            let inventory = this.inventory;
            if (!isEmpty(inventory)) {
                return `${inventory.product.name}`;
            }
        },
        showSerialNumber(inventory) {
            this.showModal(true);
            this.inventory = inventory;
            this.selectedProduct = {
                id: inventory.product.id,
                stock_bin_id: inventory.bin.id
            };
        },
        showModal(visible) {
            this.visible = visible;
            if (!visible) {
                this.fetch();
            }
        }
    }
};
</script>
