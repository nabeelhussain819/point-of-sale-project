// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/store`;

function currentTax(params = {}) {
    return request({
        url: `${baseUrl}/tax`,
        params
    });
}

function get(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const StoreService = {
    currentTax,
    get
};

export default StoreService;
