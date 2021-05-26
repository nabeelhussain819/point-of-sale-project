// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/vendor`;

function search(params, cancelToken = null) {
    return request({
        url: `${baseUrl}/search/`,
        cancelToken,
        params
    });
}

const VendorService = {
    search
};

export default VendorService;
