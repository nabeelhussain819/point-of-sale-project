// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `/refund`;

function all(params = {}) {
    return request({
        url: `${baseUrl}/fetch`,
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


function show(id) {
    return request({
        url: `${baseUrl}/${id}`
    });
}


const RefundServices = {
    all, create, show,
};

export default RefundServices
