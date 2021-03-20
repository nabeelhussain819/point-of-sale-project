import request from '../request'

const baseUrl = `issue-type`;

function all(params = {}) {
    return request({
        url: `${baseUrl}`,
        params
    });
}

const  IssueTypeService = {
    all
};

export default IssueTypeService
