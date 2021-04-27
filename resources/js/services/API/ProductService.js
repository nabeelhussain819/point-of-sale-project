// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/product`;

function deviceBrand(params = {}) {
    return request({
        url: `${baseUrl}/device-brand`,
        params
    });
}

function all(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

function getSerials(id) {
    return request({
        url: `${baseUrl}/serials/${id}`
    });
}

function validateSerial( params, cancelToken = null) {
    return request({
        url: `${baseUrl}/validate-serial/`,
        cancelToken,
        params
    });
}

const ProductService = {
    deviceBrand,
    all,
    getSerials,
    validateSerial
};

export default ProductService;
