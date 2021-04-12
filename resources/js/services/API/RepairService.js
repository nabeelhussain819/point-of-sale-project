// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `/repair`;

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

// function update(id, data) {
//     return request({
//         url: `${baseUrl}/${id}`,
//         data,
//         method: 'PATCH',
//     });
// }

const RepairService = {
    all, create, update, show, statuses ,update
};

export default RepairService
