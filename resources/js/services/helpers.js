/*
* isEmpty can check isEmpty condition to multiple levels of object or array.
* */
export const isEmpty = (value, depth = 1, level = 0) => {
    if (level === depth) {
        return false;
    }
    return value === undefined ||
        value === null ||
        (typeof value === 'object'
            && (Object.keys(value).length === 0
                || Object.keys(value).every(key => isEmpty(value[key], depth, level + 1))))
        || (typeof value === 'string' && value.trim().length === 0)
};


export const filterOption = (input, option) => {
    return (
        option.componentOptions.children[0].text
            .toLowerCase()
            .indexOf(input.toLowerCase()) >= 0
    );
};

export const errorNotification = ($this, err) => {

    let genricError = err.response.data.errors;

    let description = "";
    if (!isEmpty(genricError)) {
        for (var key in genricError) {
            description += `${genricError[key][0]},`;
        }
    }
    let genericException = err.response.data;
    if (!isEmpty(genericException)) {
        description = genericException.message;
    }

    $this.$notification.open({
        message: `Error`,
        description: () => description,
        placement: "bottomLeft",
        class: 'error-notification'
    });
}

export const notification = ($this, description, message = "success") => {
    $this.$notification.open({
        message,
        description: () => description,
        placement: "bottomLeft",
    });
}

export const objectToArray = (objOfObjs) => {
    return Object.entries(objOfObjs).map(e => e[1]);
}