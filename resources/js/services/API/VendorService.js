// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/vendor`;

function search(params, cancelToken = null) {
    return request({
        url: `${baseUrl}/search/`,
        cancelToken,
        params,
    });
}

function getList(params) {
    return request({
        url: `${baseUrl}/list/`,
        params,
    });
}

function getRefundedList(params) {
    return request({
        url: `${baseUrl}/refunded-list/`,
        params,
    });
}
const VendorService = {
    search,
    getList,
    getRefundedList,
};

export default VendorService;
