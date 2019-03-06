function notify(message, type, delay){
    delay = delay===undefined ? 4000 : delay;

    new Noty({
        type: type,
        layout: 'topCenter',
        text: message,
        timeout: delay,
        progressBar: true
    }).show();
}