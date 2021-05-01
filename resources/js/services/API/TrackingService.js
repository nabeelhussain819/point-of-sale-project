// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/tracking`;

function get(search) {
    return request({
        url: `${baseUrl}/${search}`
    });
}

const TrackingService = {
    get
};

export default TrackingService;
