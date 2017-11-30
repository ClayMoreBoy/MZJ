function LAlert(text, style) {
    $.mobile.loading("show", {
        text: text,
        textVisible: true,
        theme: style ? style : 'a',
        textonly: true,
        html: ''
    });
    setTimeout(function () {
        $.mobile.loading("hide");
    }, 2000);
}