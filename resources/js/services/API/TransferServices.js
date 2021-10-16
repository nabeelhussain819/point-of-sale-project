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

const TransferServices = {
    all,
    store
};

export default TransferServices;
