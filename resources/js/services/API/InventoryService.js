// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/inventory`;

function products(params = {}) {
    return request({
        url: `${baseUrl}/products`,
        params,
    });
}

function list(params = {}) {
    return request({
        url: `${baseUrl}/fetch`,
        params,
    });
}

function all(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params,
    });
}

function search(params = {}) {
    return request({
        url: `${baseUrl}/search`,
        params,
    });
}

function changeBin(id, data) {
    return request({
        url: `${baseUrl}/${id}/change-bin`,
        method: "PUT",
        data,
    });
}

function productQuantity(params = {}) {
    return request({
        url: `${baseUrl}/product-quantity`,
        params,
    });
}

function getBackFromVendor(params = {}) {
    return request({
        url: `${baseUrl}/back-from-vendor`,
        params,
        method: "Post",
    });
}

const InventoryService = {
    products,
    list,
    all,
    changeBin,
    search,
    productQuantity,
    getBackFromVendor,
};

export default InventoryService;
