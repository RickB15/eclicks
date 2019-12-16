function copyCode() {
    const copyElement = document.getElementById("embed-code");
    const copyText = document.getElementById('target').innerHTML;

    navigator.clipboard.writeText(copyText).then(function () {
        if (!isEmpty(document.getElementById('alert'))) {
            document.getElementById('alert').remove();
        }

        let alert = document.createElement('DIV');
        let closeButton = document.createElement("BUTTON");
        let closeIcon = document.createElement("SPAN");

        setAttributes(alert, {
            id: 'alert',
            class: "alert alert-success alert-dismissible fade show",
            role: "alert"
        });
        setAttributes(closeButton, {
            type: "button",
            class: "close",
            data_dismiss: "alert",
            aria_label: "Close"
        });
        setAttributes(closeIcon, {
            aria_hidden: "true"
        });

        alert.innerHTML = 'Copied the code correctly';
        closeIcon.innerHTML = '&times;'

        closeButton.appendChild(closeIcon);
        alert.appendChild(closeButton);
        copyElement.appendChild(alert);

        setTimeout(() => {
            $("#alert").alert('close');
        }, 6000); //6 sec in ms
    }, function (err) {
        alert('Async: Could not copy text: ', err);
    });
}

$(function(){
    createTag();
});

function createTag() {
    const eventTitle = document.getElementById('inputGroupEmbedCode').value;
    let tag;
    if (!isEmpty(eventTitle)) {
        tag =
            "<!-- emebeded value --> \r\n" +
            "<div style='position: relative; width: 100%; min-height: 100vh; overflow: auto;'> \r\n" +
            "\t<iframe src='" + base_url + 'appointment/' + cs_username + '/' + encodeURI(eventTitle).replace('%20', '-').toLowerCase() + "' frameborder='0' width='100%' scrolling='yes' height='100%' style='position: absolute;'></iframe> \r\n" +
            "</div>";
    } else {
        tag =
            "<!-- emebeded value --> \r\n" +
            "<div style='position: relative; width: 100%; min-height: 100vh; overflow: auto;'> \r\n" +
            "\t<iframe src='" + base_url + 'appointment/' + cs_username + "' frameborder='0' width='100%' scrolling='yes' height='100%' style='position: absolute;'></iframe> \r\n" +
            "</div>";
    }
    
    document.getElementById('target').innerHTML = escapeHTML(tag)
}

function escapeHTML(html) {
    return html.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/(\r\n|\n|\r)/gm, "<br>");;
}