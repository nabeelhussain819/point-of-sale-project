// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `customers`;

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

const CustomerService = {
    all, search
};

export default CustomerService
