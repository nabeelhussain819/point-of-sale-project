// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/deviceType`;

function all(params = {}) {
    return request({
        url: baseUrl,
        params
    });
}

function search(params, cancelToken = null) {
    return request({
        url: `${baseUrl}/search/`,
        cancelToken,
        params
    });
}

const DeviceTypeService = {
    all,
    search
};

export default DeviceTypeService;
