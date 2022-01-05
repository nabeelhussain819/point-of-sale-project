// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/orders`;

function create(data) {
    return request({
        url: baseUrl,
        method: "POST",
        data
    });
}

function all(params = {}) {
    return request({
        url: `${baseUrl}/fetch`,
        params
    });
}

function show(id) {
    return request({
        url: `${baseUrl}/${id}`
    });
}

function print() {
    return request({
        url: `${baseUrl}/printableDetail`
    });
}

function getIds(params = {}) {
    return request({
        url: `${baseUrl}/getIds`,
        params
    });
}

const OrderService = {
    create,
    all,
    show,
    print,
    getIds
};

export default OrderService;
