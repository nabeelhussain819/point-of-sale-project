// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/inventory-management/stock-transfer`;

function all(params) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const TransferServices = {
    all
};

export default TransferServices;
