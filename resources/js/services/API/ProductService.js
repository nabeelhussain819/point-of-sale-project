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

function getAll(params = {}) {
    return request({
        url: `${baseUrl}/getAll`,
        params
    });
}

function getSerials(id, params) {
    return request({
        url: `${baseUrl}/serials/${id}`,
        params
    });
}

function validateSerial(params, cancelToken = null) {
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
    validateSerial,
    getAll
};

export default ProductService;
