// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `orders`;

function create(data) {
    return request({
        url: baseUrl,
        method: 'POST',
        data
    });
}

const OrderService = {
    create,
};

export default OrderService
