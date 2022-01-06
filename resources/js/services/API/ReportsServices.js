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

function getReportSerials(params = {}) {
    return request({
        url: `${baseUrl}/get-report-serial`,
        params
    });
}

function getSalesStats(params = {}) {
    return request({
        url: `${baseUrl}/get-sales-stats`,
        params
    });
}

function getFinanceStats(params = {}) {
    return request({
        url: `${baseUrl}/get-finance-stats`,
        params
    });
}

function getRepairStats(params = {}) {
    return request({
        url: `${baseUrl}/get-repair-stats`,
        params
    });
}

const ReportsService = {
    mainCategory,
    detail,
    getReportSerials,
    getSalesStats,
    getFinanceStats,
    getRepairStats
};

export default ReportsService;
