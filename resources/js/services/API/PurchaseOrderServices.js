// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/purchase-order`;

function create(data) {
    return request({
        url: baseUrl,
        method: "POST",
        data
    });
}

const PurchaseOrderService = {
    create
};

export default PurchaseOrderService;
