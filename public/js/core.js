const CoreJs = {
    ajax: (type, url, data) => {
        return $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json'
        });
    },
    toastError: (errorJson, timeOut = 5000) => {
        return toastr.error(`
                <div>
                    <h4>Error: ${errorJson.message}</h4>
                    <p>File: ${errorJson.file}</p>
                    <p>Line: ${errorJson.line}</p>
                </div>
            `,
            'Erreur AJAX', {timeOut: timeOut}
        );
    }
}

export { CoreJs };