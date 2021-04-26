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

const InventoryService = {
    products,
    list
};

export default InventoryService;
