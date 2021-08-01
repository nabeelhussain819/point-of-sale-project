// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/reports`;

function mainCategory(params = {}) {
    return request({
        url: `${baseUrl}/entity-search`,
        params
    });
}

const ReportsService = {
    mainCategory
};

export default ReportsService;
