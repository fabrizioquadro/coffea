window.onaftersplashscreen = function() {
    const isIE = !!document.documentMode;
    var backgroundColor = localStorage.getItem('backgroundColor');
    var backgroundImage = localStorage.getItem('backgroundImage');
    var textColor = localStorage.getItem('textColor');
    var logo = localStorage.getItem('logo');
    var el = document.querySelector('.blur');
    if (el != null) {
        if (backgroundColor != null) {
            el.style.background = backgroundColor;
        }
        if (isIE) {
            var bgColor = el.currentStyle.backgroundColor;
            var newColor = bgColor.replace(/ 0\.2\) /, '1)');
            el.style.backgroundColor = newColor;
        }
        if (textColor != null) {
            el.style.color = textColor;
        }
    }
    el = document.querySelector('.container');
    if (el != null && backgroundImage != null) {
        el.style.background = "url(../" + backgroundImage + ")";
    }
    el = document.querySelector('.logo');
    if (el != null) {
        if (typeof logo === 'undefined' || logo === null) {
            logo = "../logo.png"; // by default
        }
        if (!logo.includes('/'))
        {
            logo = "../" + logo;
        }
        else if (logo.startsWith('software/'))
        {
            logo = logo.substring('software/'.length);
        }
        el.src = logo;
    }
};