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

function all(params) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const PurchaseOrderService = {
    create,
    all
};

export default PurchaseOrderService;
