// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `repair`;

function all(params = {}) {
    return request({
        url:  `${baseUrl}/fetch`,
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

function update(id, data) {
    return request({
        url: `${baseUrl}/${id}`,
        method: 'PATCH',
        data
    });
}


function show(id) {
    return request({
        url:  `${baseUrl}/${id}`        
    });
}

const RepairService = {
    all, create, update,show
};

export default RepairService
