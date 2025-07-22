function displayMessage(message, isClassicTheme) {
    $('#apps').empty();

    if (isClassicTheme) {
        for (i = 0; i < numberOfAppsPerLine; i++) {
            $('#apps').append(emptyapp);
        }
        $('#apps').append('<div style="clear:both;"> </div>');
    }

    $('#apps').append('<br><div style="text-align:center;width:400px;">' + message + '</div><br>');

    if (isClassicTheme) {
        for (i = 0; i < numberOfAppsPerLine; i++) {
            $('#apps').append(emptyapp);
        }
        $('#apps').append('<div style="clear:both;"> </div>');
    }
}

/* <GET parameters> */
function getParameter(paramName) {
    var searchString = window.location.search.substring(1);
    var parameters = searchString.split("&");
    var keyValue;
    for (var i = 0; i < parameters.length; i++) {
        keyValue = parameters[i].split("=");
        if (keyValue[0] == paramName) {
            return unescape(keyValue[1]);
        }
    }
    return null;
}
/* </GET parameters> */

var appsData = [];

var prefixIfGateway = '';
var splitGateway = window.server ? window.server.split('/~~') : "";
if (splitGateway.length == 2) {
    prefixIfGateway = "/";
}

function escapePassword(toeval, password, key) {
    var escapedPassword = password;
    // Escaping backslash so they do not get removed by eval() 
    escapedPassword = escapedPassword.replace(/\\/g, "\\\\");
	
	// Escaping single quote which causes an unexpected token: numeric literal error when evaluated by eval()
    escapedPassword = escapedPassword.replace(/'/g, "\\'");
		
    // Escaping double quote which causes an unexpected token: numeric literal error when evaluated by eval()
    escapedPassword = escapedPassword.replace(/"/g, "\\\"");

    // replace() can interpret the searchValue as a regexp thus failing on password with $, ^ etc.
    // so using split().join() instead
    return toeval.split(password).join(escapedPassword);
}

function displayAssignedApps(isClassicTheme) {
    emptyapp = '<div class="app"><a href="#" class="appbutton"><img style="width:32px; height:32px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAAA1JREFUGFdj+P//PwMACPwC/ohfBuAAAAAASUVORK5CYII=" /><br><span> &nbsp; </span></a></div>';
    displayMessage('Loading your applications...', isClassicTheme);

    if (getParameter('preview') == 'on') {
        action = 'appspreview';

        window.user = "";
        window.pass = "";
        window.code = "";
        window.server = "";
    } else {
        action = 'assigned';

        toeval = window.name.replace(/_/g, '=');
        toeval = jsdecode64(toeval);

        if (toeval.length > 10) {
            try {
                var passwordValues = toeval.match(/window.pass='(.+)';window.code/);
                if (passwordValues !== null && passwordValues.length > 0) {
                    toeval = escapePassword(toeval, passwordValues[1], "window.pass");
                } else {
                    passwordValues = toeval.match(/rdppass='(.+)'; mydomain/);
                    if (passwordValues !== null && passwordValues.length > 0) {
                        toeval = escapePassword(toeval, passwordValues[1], "rdppass");
                    }
                }
                eval(toeval);
            } catch (ex) { // When returning to this page after opening HTML5 client in the same browser tab
                window.location.href = window.location.origin;
            }
        } else {
            window.server = "127.0.0.1";
            window.serverhtml5 = "127.0.0.1";
            window.port = "3389";
            window.porthtml5 = "3389";
        }

        /* <GET parameters> */
        if (getParameter('user') != null) { window.username = getParameter('user'); window.user = window.username; }
        if (getParameter('pwd') != null) { window.rdppass = getParameter('pwd'); window.pass = window.rdppass; }
        if (getParameter('code') != null) { window.code = getParameter('code'); window.code = window.twofacode; }
        if (getParameter('domain') != null) { window.mydomain = getParameter('domain'); window.domain = window.mydomain; }
        if (getParameter('server') != null) { window.server = getParameter('server'); window.serverhtml5 = window.server; }
        if (getParameter('port') != null) { window.port = getParameter('port'); window.porthtml5 = window.port; }
        if (getParameter('type') != null) { window.applications_portal_type = getParameter('type'); }
        /* </GET parameters> */

        if (typeof window.applications_portal_type != 'undefined' && (window.applications_portal_type == 'java' || window.applications_portal_type == 'remoteapp' || window.applications_portal_type == 'remoteapp2')) {
            window.user = window.username;
            window.pass = window.rdppass;
            window.domain = window.mydomain;
            window.code = window.twofacode;
        }
    }

    // https://support.tsplus.net/a/tickets/86378
    var nonEscapedPassword = window.pass;
    if (action == 'assigned' && window.pass.indexOf('\\') > -1) {
        nonEscapedPassword = window.pass.replace(/\\\\/g, "\\");
    }

    // Note: there is no "/" prefix in order to handle the reverse-proxy case (when URL is http://gateway/~~srvX and we want to call http://gateway/~~srvX/cgi-bin and NOT http://gateway/cgi-bin).
    $.post('./cgi-bin/hb.exe', { 'action': action, 'l': window.user, 'd': window.domain, 'p': nonEscapedPassword, 'f': window.code, 's': window.server, 't': new Date().getTime() }, function (data, textStatus, jqXHR) {
        isPreview = false;
        if (getParameter('preview') == 'on') { isPreview = true; }

        appsData = eval(data);
        if (appsData.length == 0) {
            displayMessage('No application is currently assigned to you, or the logon typed is invalid.<br><br>Please contact your Administrator.', isClassicTheme);
        } else if (appsData.length == 1 && appsData[0].ErrorMessage) {
            displayMessage(appsData[0].ErrorMessage, isClassicTheme);
        } else {
            displayFolder('', isClassicTheme);
        }
    });
}

function displayFolder(folderName, isClassicTheme) {
    $('#apps').empty();
    var folders = [];

    j = 0;
    if (folderName != '') { // SUB-folder: display "parent" icon
        displayApplication(j, isClassicTheme, 'displayFolder(\'\', ' + isClassicTheme + ')', 'arrow_up.ico', '..');
        j++;
    }
    for (i = 0; i < appsData.length; i++) {
        appNameClean = appsData[i].ApplicationName.replace(/\\\"/g, "\""); // replace all \" by "

        if (appsData[i].ApplicationFolder == folderName) {
            displayApplication(j, isClassicTheme, 'launch(' + i + ')', appsData[i].ApplicationIcon, appNameClean);
            j++;
        } else {
            if (folderName == '' && $.inArray(appsData[i].ApplicationFolder, folders) < 0) { // ROOT folder: display folder icon (if not already done)
                folders.push(appsData[i].ApplicationFolder);
                displayApplication(j, isClassicTheme, 'displayFolder(\'' + appsData[i].ApplicationFolder + '\', ' +  isClassicTheme + ')', 'folder_table.ico', appsData[i].ApplicationFolder);
                j++;
            }
        }
    }

    if (isClassicTheme) {
        while (j % numberOfAppsPerLine != 0) {
            $('#apps').append(emptyapp);
            j++;
        }
        $('#apps').append('<div style="clear:both;"> </div>');
    }
}

function displayApplication(j, isClassicTheme, clickHandler, icon, text) {
    if (isClassicTheme) {
        if (j > 0 && j % numberOfAppsPerLine == 0) {
            $('#apps').append('<div style="clear:both;"> </div>');
        }
        $('#apps').append('<div class="app"><a href="#" class="appbutton"' + (isPreview ? '' : ' onclick="' + clickHandler + '"') + '><img style="width:32px; height:32px;" src="' + prefixIfGateway + 'software/html5/imgs/topmenu/' + icon + '" alt="' + text + '" /><br/><span>' + text + '</span></a></div>');
    } else {
        $('#apps').append('<div class="app"><a href="#" class="appbutton"' + (isPreview ? '' : ' onclick="' + clickHandler + '"') + '><img src="' + prefixIfGateway + 'software/html5/imgs/topmenu/' + icon + '" alt="' + text + '"><br><span>' + text + '</span></a></div>');
    }
}

function forHTML5(appname, apppath, appstartup, appcmdline) {
    var randm = Math.floor(Math.random() * 1000);
    var toadd = "window.randomnum=\'" + randm + "\';";

    toadd += "window.user=\'" + window.user + "\';";
    toadd += "window.pass=\'" + window.pass + "\';";
    toadd += "window.code=\'" + window.code + "\';";
    toadd += "window.server=\'" + window.server + "\';";
    toadd += "window.port=\'" + window.port + "\';";
    toadd += "window.webport=\'" + window.webport + "\';";
    toadd += "window.lang=\'" + window.lang + "\';";
    toadd += "window.domain=\'" + window.domain + "\';";

    toadd += "window.cmdline=\'" + apppath + "|" + appstartup + "|" + appcmdline + "\';";
    toadd += "document.title=\'" + appname + "\';";

    return toadd;
}

function forRemoteApp2(appname, apppath, appstartup, appcmdline) {
    window.cmdline = apppath + "|" + appcmdline;
    window.cmdline = window.cmdline.replace(new RegExp('\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\', 'g'), "\\");
}

function getoutside(appname, apppath, appstartup, appcmdline) {
    var alloutside = "dummyvaro=false; ";

    if (typeof window.switcher != 'undefined') { alloutside += "switcher=" + window.switcher + "; "; }
    if (typeof window.bpp_all != 'undefined') { alloutside += "bpp_all=" + window.bpp_all + "; "; }
    if (typeof window.bpp_all_mac != 'undefined') { alloutside += "bpp_all_mac='" + window.bpp_all_mac + "'; "; }
    if (typeof window.sameasweb != 'undefined') { alloutside += "sameasweb='" + window.sameasweb + "'; "; }

    if (typeof window.server != 'undefined') { alloutside += "server='" + window.server + "'; "; }
    if (typeof window.serverownport != 'undefined') { alloutside += "serverownport=" + window.serverownport + "; "; }
    if (typeof window.user != 'undefined') { alloutside += "username='" + window.user + "'; "; }
    if (typeof window.pass != 'undefined') { alloutside += "rdppass='" + window.pass + "'; "; }
    if (typeof window.domain != 'undefined') { alloutside += "mydomain='" + window.domain + "'; "; }

    if (typeof window.interval_print_option != 'undefined') { alloutside += "interval_print_option='" + window.interval_print_option + "'; "; }

    alloutside += "cmdline='" + apppath + "|" + appstartup + "|" + appcmdline + "'; ";
    alloutside += "document.title='" + appname + "'; ";

    return alloutside;
}

function getinside(appname, apppath, appstartup, appcmdline) {
    var allinside = "dummyvari=false; ";

    if (typeof window.switcher != 'undefined') { allinside += "switcher=" + window.switcher + "; "; }
    if (typeof window.bpp_all != 'undefined') { allinside += "bpp_all=" + window.bpp_all + "; "; }
    if (typeof window.sameasweb != 'undefined') { allinside += "sameasweb='" + window.sameasweb + "'; "; }
    if (typeof window.insidecheck != 'undefined') { allinside += "insidecheck=" + window.insidecheck + "; "; }
    if (typeof window.soundup != 'undefined') { allinside += "soundsup=" + window.soundup + "; "; }
    if (typeof window.mapsup != 'undefined') { allinside += "mapsup=" + window.mapsup + "; "; }
    if (typeof window.activex != 'undefined') { allinside += "activex=" + window.activex + "; "; }

    if (typeof window.server != 'undefined') { allinside += "server='" + window.server + "'; "; }
    if (typeof window.serverownport != 'undefined') { allinside += "serverownport=" + window.serverownport + "; "; }
    if (typeof window.user != 'undefined') { allinside += "username='" + window.user + "'; "; }
    if (typeof window.pass != 'undefined') { allinside += "rdppass='" + window.pass + "'; "; }
    if (typeof window.domain != 'undefined') { allinside += "mydomain='" + window.domain + "'; "; }

    if (typeof window.interval_print_option != 'undefined') { allinside += "interval_print_option='" + window.interval_print_option + "'; "; }

    allinside += "cmdline='" + apppath + "|" + appstartup + "|" + appcmdline + "'; ";
    allinside += "document.title='" + appname + "'; ";

    return allinside;
}

var childrenWindows = [];

var lastLaunchTime = [];
if (!Date.now) {
    Date.now = function now() {
        return new Date().getTime();
    };
}

function launch(appIndex) {
    if (typeof lastLaunchTime[appIndex] == 'undefined' || lastLaunchTime[appIndex] + 9 <= Math.floor(Date.now() / 1000)) {
        lastLaunchTime[appIndex] = Math.floor(Date.now() / 1000);

        appname = appsData[appIndex].ApplicationName;
        apppath = appsData[appIndex].ApplicationPath;
        appstartup = appsData[appIndex].ApplicationStartup;
        appcmdline = appsData[appIndex].ApplicationCmdline;
        if (appcmdline.indexOf("\"", appcmdline.length - 1) !== -1) { // appcmdline ends with a " => we double it (\") to get it back on server side
            appcmdline += "\\\"";
        }

        var childurl, childname;

        var prefixGateway = '';
        var splitGateway = window.server ? window.server.split('/~~') : "";
        if (splitGateway.length == 2) {
            prefixGateway = "/~~" + splitGateway[1] + "/";
        }

        if (typeof window.applications_portal_type != 'undefined' && window.applications_portal_type == 'java') {
            window.name = " " + window.opforfalse;
            childurl = jwtsclickLinkBefore(getinside(appname, apppath, appstartup, appcmdline), prefixGateway + 'software/javaconnect.html');
            childname = window.opforfalse;
        } else if (typeof window.applications_portal_type != 'undefined' && window.applications_portal_type == 'remoteapp') {
            window.name = " " + window.opforfalse;
            childurl = jwtsclickLinkBefore(getoutside(appname, apppath, appstartup, appcmdline), prefixGateway + 'software/remoteapp.html');
            childname = window.opforfalse;
        } else if (typeof window.applications_portal_type != 'undefined' && window.applications_portal_type == 'remoteapp2') {
            childurl = '';
            forRemoteApp2(appname, apppath, appstartup, appcmdline);
            remoteApp2Connect(remoteapp2_url);
        } else {
            childurl = prefixGateway + 'software/html5.html';
            childname = my64Enc(forHTML5(appname, apppath, appstartup, appcmdline));
        }

        if (childurl != '') {
			// If in PWA display mode, launch in a new PWA window instead of a new browser tab
			if((window.matchMedia('(display-mode: standalone)').matches) || (window.navigator.standalone) || (document.referrer.indexOf('android-app://') > -1)) {
				child = window.open(childurl, childname, "popup-1");
			}
			else {
				child = window.open(childurl, childname);
			}
            childrenWindows[childrenWindows.length] = child;
        }
    }
}

// Automatically log off after 8 hours. This is the default value when 2FA is activated.
$(document).ready(function () {
    var delay = 1000 * 60 * 60 * 8;

    if (remoteapp2_appportal_timeout_status !== undefined && remoteapp2_appportal_timeout_status
        && remoteapp2_appportal_timeout_delay !== undefined && remoteapp2_appportal_timeout_delay > 0) {
            delay = 1000 * 60 * remoteapp2_appportal_timeout_delay;
    }

    setTimeout(function () {
        window.name = '';
        window.location.replace(
            window.applications_portal_return_url ?
                window.applications_portal_return_url : window.location.href.replace('_applications.html', '.html'));
    }, delay);
});

//------------------------------

var jsb64array = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function jsdecode64(input) {
    var output = "";
    var hex = "";
    var chr1, chr2, chr3 = "";
    var enc1, enc2, enc3, enc4 = "";
    var i = 0;
    input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
    do {
        enc1 = jsb64array.indexOf(input.charAt(i++));
        enc2 = jsb64array.indexOf(input.charAt(i++));
        enc3 = jsb64array.indexOf(input.charAt(i++));
        enc4 = jsb64array.indexOf(input.charAt(i++));
        chr1 = (enc1 << 2) | (enc2 >> 4);
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        chr3 = ((enc3 & 3) << 6) | enc4;
        output = output + String.fromCharCode(chr1);
        if (enc3 != 64) {
            output = output + String.fromCharCode(chr2);
        }
        if (enc4 != 64) {
            output = output + String.fromCharCode(chr3);
        }
        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    } while (i < input.length);
    return unescape(output);
}

function jsencode64(input) {
    var base64 = "";
    var hex = "";
    var chr1, chr2, chr3 = "";
    var enc1, enc2, enc3, enc4 = "";
    var i = 0;
    do {
        chr1 = input.charCodeAt(i++);
        chr2 = input.charCodeAt(i++);
        chr3 = input.charCodeAt(i++);
        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        enc4 = chr3 & 63;
        if (isNaN(chr2)) {
            enc3 = enc4 = 64;
        } else if (isNaN(chr3)) {
            enc4 = 64;
        }
        base64 = base64 +
            jsb64array.charAt(enc1) +
            jsb64array.charAt(enc2) +
            jsb64array.charAt(enc3) +
            jsb64array.charAt(enc4);
        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    } while (i < input.length);
    return base64;
}

function base64_encode(data) {
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];
    if (!data) {
        return data;
    }
    data = utf8_encode(data + '');
    do {
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);
        bits = o1 << 16 | o2 << 8 | o3;
        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;
        tmp_arr[ac++] = jsb64array.charAt(h1) + jsb64array.charAt(h2) + jsb64array.charAt(h3) + jsb64array.charAt(h4);
    } while (i < data.length);
    enc = tmp_arr.join('');
    var r = data.length % 3;
    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function utf8_encode(argString) {
    var string = (argString + '');
    var utftext = '',
        start, end, stringl = 0;
    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;
        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192, (c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }
    if (end > start) {
        utftext += string.slice(start, stringl);
    }
    return utftext;
}

function my64Enc(a) { if (a.length < 1) { return ""; } else { return jsencode64(escape(a)).replace(/=/g, '_'); } }

function my64Dec(a) { if (a.length < 1) { return ""; } else { return jsdecode64(a.replace(/_/g, '=')); } }

function jwtsclickLinkBefore(tosplit, documhtml) {
    var randm = Math.floor(Math.random() * 1000);
    window.opforfalse = jsencode64(escape("var randomnum = " + randm + ";" + tosplit)).replace(/=/g, '_');
    return documhtml + "?" + 1;
}
