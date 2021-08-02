// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/reports`;

function mainCategory(params = {}) {
    return request({
        url: `${baseUrl}/entity-search`,
        params
    });
}

function detail(id, params = {}) {
    return request({
        url: `${baseUrl}/${id}`,
        params
    });
}

const ReportsService = {
    mainCategory,
    detail
};

export default ReportsService;
