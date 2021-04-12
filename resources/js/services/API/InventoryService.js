// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `/inventory`;

function products(params = {}) {
    return request({
        url: `${baseUrl}/products`,
        params
    });
}

const InventoryService = {
    products
};

export default InventoryService
