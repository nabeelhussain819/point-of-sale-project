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

function destroy(id) {
    return request({
        url: `${baseUrl}/${id}`,
        method: "Delete"
    });
}

const PurchaseOrderService = {
    create,
    all,
    destroy
};

export default PurchaseOrderService;
