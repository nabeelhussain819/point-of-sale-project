// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `product`;

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

const ProductService = {
    deviceBrand,all
};

export default ProductService
