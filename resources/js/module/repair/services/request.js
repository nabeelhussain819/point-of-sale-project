import axios from 'axios'

const client = axios.create()

const request = function (options) {
    const onSuccess = function (response) {
        return response.data;
    };

    const onError = function (err) {
        if (err.response) {
            // Request was made but server responded with something other than 2xx
            // console.error('Status:', err.response.status);
            // console.error('Data:', err.response.data);
            // console.error('Headers:', err.response.headers);
            if (err.response.status === 401) {
                window.location = '/login'
            }
        } else {
            console.error('Error Message:', err.message);
        }

        //return Promise.reject(err.response || err.message);
        return Promise.reject(err);
    };

    return client(options)
        .then(onSuccess)
        .catch(onError);
};

export default request;