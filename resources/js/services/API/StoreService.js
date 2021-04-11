// import * as constants from '../Constants'
import request from '../request'

const baseUrl = `store`;

function currentTax(params = {}) {
    return request({
        url: `${baseUrl}/tax`,
        params
    });
}


const StoreService = {
    currentTax
};

export default StoreService
