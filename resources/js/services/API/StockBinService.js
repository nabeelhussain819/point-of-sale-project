// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/stock-bin`;

function get(params = {}) {
    return request({
        url: `${baseUrl}/get`,
        params
    });
}

const StockBinService = {
    get
};

export default StockBinService;
