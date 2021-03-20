// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `product`;

function deviceBrand(params = {}) {
    return request({
        url: `${baseUrl}/device-brand`,
        params
    });
}


const ProductService = {
    deviceBrand
};

export default ProductService
