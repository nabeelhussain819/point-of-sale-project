// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/department`;

function all(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const DepartmentService = {
    all
};

export default DepartmentService;
