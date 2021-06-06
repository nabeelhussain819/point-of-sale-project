// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/tracking`;

function get(search) {
    return request({
        url: `${baseUrl}/${search}`
    });
}

function show(id) {
    return request({
        url: `${baseUrl}/by-id/${id}`
    });
}

const TrackingService = {
    get,show
};

export default TrackingService;
