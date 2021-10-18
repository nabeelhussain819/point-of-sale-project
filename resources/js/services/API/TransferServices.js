// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/inventory-management/stock-transfer`;

function all(params) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

function store(data) {
    return request({
        url: baseUrl,
        method: "POST",
        data
    });
}

function destroy(id) {
    return request({
        url: `${baseUrl}/${id}`,
        method: "Delete"
    });
}

const TransferServices = {
    all,
    store,
    destroy
};

export default TransferServices;
