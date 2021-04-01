// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `finance`;

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

function get(id) {
    return request({
        url: `${baseUrl}/${id}`,
    });
}

function update(id, data) {
    return request({
        url: `${baseUrl}/${id}`,
        method: 'PATCH',
        data
    });
}

function show(id) {
    return request({
        url: `${baseUrl}/${id}`
    });
}

function statuses(params = {}) {
    return request({
        url: `${baseUrl}/statuses`,
        params
    });
}

const FinanceService = {
    all, create, update, show, statuses, update, get
};

export default FinanceService
