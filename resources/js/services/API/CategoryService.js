// import * as constants from '../Constants'
import request from "../request";

const baseUrl = `/category`;

function all(params = {}) {
    return request({
        url: `${baseUrl}/all`,
        params
    });
}

const CategoryService = {
    all
};

export default CategoryService;
