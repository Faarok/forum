const CoreJs = {
    ajax: (type, url, data) => {
        return $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json'
        });
    }
}

export { CoreJs };