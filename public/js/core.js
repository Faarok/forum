const CoreJs = {
    ajax: (type, url, data) => {
        return $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json'
        });
    },
    toast: (type, jsonData, timeOut = 5000) => {
        let html = '';
        let title = '';
        switch(type)
        {
            case 'error':
                title = 'Erreur :';
                html = `
                    <div>
                        <h4>${title}</h4>
                        <p>Error: ${jsonData.message}</p>
                        <p>File: ${jsonData.file}</p>
                        <p>Line: ${jsonData.line}</p>
                    </div>
                `;
                break;
            case 'success':
                title = 'Succès !';
                html = `
                    <div>
                        <h4>${title}</h4>
                        <p>${jsonData.message}</p>
                    </div>
                `;
                break;
        }

        return toastr[type](html, {timeOut: timeOut});
    }
}

export { CoreJs };