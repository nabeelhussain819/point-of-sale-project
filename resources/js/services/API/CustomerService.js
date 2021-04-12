// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `/customers`;

function all(params = {}) {
    return request({
        url: `${baseUrl}`,
        params
    });
}

function search(params = {}) {
    return request({
        url: `${baseUrl}/search`,
        params
    });
}

function create(data) {
    return request({
        url: baseUrl,
        method: 'POST',
        data
    });
}

const CustomerService = {
    all, search,create
};

export default CustomerService
