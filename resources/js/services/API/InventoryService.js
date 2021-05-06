// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/inventory`;

function products(params = {}) {
    return request({
        url: `${baseUrl}/products`,
        params
    });
}

function list(params = {}) {
    return request({
        url: `${baseUrl}/fetch`,
        params
    });
}

function all(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const InventoryService = {
    products,
    list,
    all
};

export default InventoryService;
