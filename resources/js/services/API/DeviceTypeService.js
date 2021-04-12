// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `/deviceType`;

function all(params = {}) {
    return request({
        url: baseUrl,
        params
    });
}


const DeviceTypeService = {
    all
};

export default DeviceTypeService
