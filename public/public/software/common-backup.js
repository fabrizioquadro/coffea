var pass_original;
var login_original;

// Create Element.remove() function if not exist
if (!('remove' in Element.prototype)) {
    Element.prototype.remove = function() {
        if (this.parentNode) {
            this.parentNode.removeChild(this);
        }
    };
}

//jwts part
function getPrinter() {
    getAll();
    var printing = document.getElementById("Combobox1").selectedIndex;
    var myopt = "";
    if (printing == 0) {
        myopt = "4";
    } else if (printing == 1) {
        myopt = "1";
    } else if (printing == 2) {
        myopt = "2";
    } else if (printing == 3) {
        myopt = "0";
    }
    return "myopt=\"" + myopt + "\";";
}

function getside() {
    getAll();

    var insadd = "";
    var AccType = getAccessType();
    if (AccType == "java") {
        insadd = allinside;
    } else if (AccType == "remoteapp") {
        insadd = alloutside;
    }

    if (typeof page_configuration["applications_portal"] != 'undefined' && page_configuration["applications_portal"] != '') {
        insadd += "applications_portal_type = '" + AccType + "'; ";
        insadd += "applications_portal_return_url = '" + getApplicationsPortalReturnUrl() + "'; ";
    }

    if (!page_configuration["is_standard"]) {
        window.server = getSelectedServerIp();
        if (getSelectedServerPort() != "") {
            insadd += "serverownport = " + getSelectedServerPort() + "; ";
        }
        insadd += "sameasweb = 'no'; ";
    }
    insadd += "server = '" + window.server + "' ; ";
    insadd += "port = '" + window.port + "' ; ";

    return 'username=\'' + (page_configuration["is_webcredentials"] ? '@' : '') + document.getElementById("Editbox1").value + '\'; rdppass=\'' + document.getElementById("Editbox2").value + '\'; mydomain=\'' + document.getElementById("Editbox3").value + '\'; twofacode=\'' + document.getElementById("twofacode").value + '\'; ' + insadd;
}

var temppfad = window.location.pathname.split('/');
temppfad = temppfad[temppfad.length - 1];

function getAll() {
    var all = document.getElementsByTagName("input");
    var l = all.length;
    var alli;

    for (var i = 0; i < l; i++) {
        if (all[i].type != "password" && all[i].type != "submit" && all[i].id != "twofacode") {
            alli = all[i].value;
            if (all[i].type == "checkbox" || all[i].type == "radio") {
                alli = all[i].checked + "";
            } else if (all[i].type == "text") {
                alli = fixLeadingChar(alli);
            }
            jscreateCookie(temppfad + "" + all[i].name + "_" + all[i].id, alli, "1");
        }
    }

    all = document.getElementsByTagName("select");
    l = all.length;

    for (i = 0; i < l; i++) {
        jscreateCookie(temppfad + "" + all[i].name, all[i].options.selectedIndex, "365");
    }
}

function fixLeadingChar(alli) {
    for (; ;) {
        var sk = alli.substring(0, 1);
        var A = {
            0: "@",
            1: "*",
            2: "+",
            3: "=",
            4: "?",
            5: ",",
            6: ";",
            7: ":",
            8: "[",
            9: "]",
            10: "<",
            11: ">",
            12: "|"
        };
        for (var P in A) {
            if (sk == A[P]) {
                alli = alli.substring(1);
                continue;
            }
        }
        break;
    }
    return alli;
}

function setAll() {
    initCookies();

    var all = document.getElementsByTagName("input");
    var l = all.length;
    var me = "";
    for (var i = 0; i < l; i++) {
        if (all[i].type != "password" && all[i].type != "submit" && all[i].type != "button" && all[i].id != "twofacode") {
            me = jsreadCookie(temppfad + "" + all[i].name + "_" + all[i].id);
            if (me) {
                if (all[i].type == "checkbox" || all[i].type == "radio") {
                    if (me == "true") {
                        all[i].checked = true;
                    } else {
                        all[i].checked = false;
                    }
                } else {
                    all[i].value = me;
                }
            }
        }
    }

    all = document.getElementsByTagName("select");
    l = all.length;
    for (i = 0; i < l; i++) {
        me = jsreadCookie(temppfad + "" + all[i].name);
        if (me) {
            all[i].options.selectedIndex = me;
        }
    }

    initHtmlPage();

    mainPortalInit();

    pass_original = window.pass;
    login_original = window.user;

    getOwnImplementation();

    if (getAccessType() == "remoteapp2") {
        remoteAppPluginPopinShow();
    } else {
        remoteAppPluginPopinHide();
    }

    if (page_configuration["show_windows_password_reset_button"]) {
        document.getElementById("windows-password-reset-button").style.display = "block";
    }

    if (!page_configuration["show_windows_password_reset_button"] && !page_configuration["show_windows_password_expiration_alert"]) {
        document.getElementById("open-expiration-password-reminder").remove();
        document.getElementById("expiration-password-reminder").remove();
        document.getElementById("windows-password-reset-button").remove();
    }
    else if (!page_configuration["show_windows_password_reset_button"]) {
        document.getElementById("windows-password-reset-button").remove();
    }
    else if (!page_configuration["show_windows_password_expiration_alert"]) {
        document.getElementById("open-expiration-password-reminder").remove();
        document.getElementById("expiration-password-reminder").remove();
    }

}

function getOwnImplementation() {
    window.user = fixLeadingChar(window.user);

    if (page_configuration["remember_credentials"]) {
        if (window.user) { document.getElementById("Editbox1").value = window.user; }
        if (window.pass) { document.getElementById("Editbox2").value = window.pass; }
        if (window.domain) { document.getElementById("Editbox3").value = window.domain; }
    }

    if (twoStepStatus !== "disabled") {
        onLoginTyped();
    }
}

function CheckKey(e) {
    if (!e) {
        if (window.event) {
            e = window.event;
        } else {
            return;
        }
    }

    if (typeof (e.keyCode) == 'number') {
        e = e.keyCode;
    } else if (typeof (e.which) == 'number') {
        e = e.which;
    } else if (typeof (e.charCode) == 'number') {
        e = e.charCode;
    } else {
        return;
    }
    if (!window.cplogon) {
        return;
    }
    if (e == 13) {
        if (document.getElementById("Editbox1").value == "") {
            alert("Insert Login please!");
            return;
        }
        if (document.activeElement !== document.getElementById("twofacode")) {
            cplogon();
        }
    }
}

function setWindowVariables() {
    if (getAccessType() == "html5" || getAccessType() == "remoteapp2") {
        getAll();
        window.user = (page_configuration["is_webcredentials"] ? "@" : "") + document.getElementById("Editbox1").value;
        window.pass = document.getElementById("Editbox2").value;
        window.domain = document.getElementById("Editbox3").value;
        window.code = document.getElementById("twofacode").value;
        window.cmdline = cmdline.replace("%WEBCREDENTIAL%", document.getElementById("Editbox1").value);
        if (page_configuration["is_standard"]) {
            window.server = server;
            window.serverhtml5 = serverhtml5;
        } else {
            window.server = getSelectedServerIp();
            window.serverhtml5 = getSelectedServerIp();
            if (getSelectedServerPort() != "") {
                window.porthtml5 = getSelectedServerPort();
                window.serverownport = getSelectedServerPort();
            }
        }
    }
}

function iOSTabBug(p, a, k, success) {
  try {
    if(!success || !window.navigator.userAgent.match(/(iPhone)|(iPad)/)) { return false; }
    try { success.close(); } catch(ex) { }
    var EL = document.createElement("div");
    var S = EL.style;
    S.visibility = S.overflow = "hidden";
    S.height = S.width = "1px";
    S.left = S.top = "-100px";
    S.zIndex = -100;  
    var D = document;
    if(D.body && D.body.appendChild) { D.body.appendChild(EL); } else { return false; }
    var INR = "innerHTML";
    EL[INR] = "<a target=\"_blank\" href=\"" + a + "#" + (p ? window.encodeURI(p) + ":" : "") + k + "\"></a>"; 
  } catch(as) { return false; } 
  try { EL.childNodes[0].click(); } catch(ex) { return false; }

  window.setTimeout(function() {
    var UNDEFINED = void 0;
    EL[INR] = "<br />"; 
    try { 
      try { EL.style.display = "none"; } catch(ex) { }
      while(EL.hasChildNodes()) { EL.removeChild(EL.lastChild); }
      var TEST_PARENT = EL.parentNode;
      if(TEST_PARENT && TEST_PARENT.style == UNDEFINED) { TEST_PARENT = false; }
      if(TEST_PARENT) { try { TEST_PARENT.removeChild(EL); } catch(ka) { } }
    } catch(em) { }
  }, 100);  
  return true;
};

function startInsideOutside() {
    if (document.getElementById("Editbox1").value == "") {
        alert("Insert Login please!");
        return;
    }

    var hostGateway = '';
    var remoteAppUrl = location.host;
    var appServerPathMatch = location.pathname.match(/(^\/~~[^/]+)\/?/i);
    if (appServerPathMatch != null && appServerPathMatch.length > 1) {
        remoteAppUrl += appServerPathMatch[1];
    }

    var splitGateway;
    if (!page_configuration["is_standard"]) {
        if (serversListingType == "userassigned") { // => ip:port/~~srvX
            var select = document.getElementById("select-server");
            hide("span-credentials-ko");
            if (select.options.length > 0
                && select.options[select.selectedIndex] !== undefined) {
                var selectedGateway = select.options[select.selectedIndex].value;
                if (selectedGateway) {
                    splitGateway = selectedGateway.split('/~~');
                    // Returned URL contains port to redirect connection to
                    // In case of reverse proxied URL, it needs to be stripped down 
                    if (splitGateway.length == 2) {
                        var servernamesplit = selectedGateway.split('/~~')[1].split(':');
                        if (servernamesplit.length == 2) {
                            hostGateway = window.location.protocol + "//" + splitGateway[0] + (window.location.port != '' ? ':' + window.location.port : '') + '/~~' + servernamesplit[0] + "/";
                            remoteAppUrl = splitGateway[0] + (window.location.port != '' ? ':' + window.location.port : '') + '/~~' + servernamesplit[0];
                        }
                        else {
                            hostGateway = window.location.protocol + "//" + splitGateway[0] + ":" + window.location.port + "/~~" + splitGateway[1] + "/";
                            remoteAppUrl = splitGateway[0] + (window.location.port != '' ? ':' + window.location.port : '') + '/~~' + splitGateway[1];                            
                        }
                    }
                }
            }
            else {
                // LB is off or all app servers are down + no server assigned to current user => if we do nothing, it will continue below and open a session on the gateway
                var credentialsKo = document.getElementById("span-credentials-ko");
                credentialsKo.innerHTML = translate("No application server available");
                show("span-credentials-ko");
                return;
            }
        }
        else {
            // loadbalanced => ip/~~srvX:port
            splitGateway = getSelectedServerIp().split('/~~');
            if (splitGateway.length == 2) {
                hostGateway = window.location.protocol + "//" + splitGateway[0] + ":" + window.location.port + "/~~" + splitGateway[1] + "/";
                remoteAppUrl = splitGateway[0] + (window.location.port != '' ? ':' + window.location.port : '') + '/~~' + splitGateway[1];
            }
        }
    }

    var p = '';
    if (typeof page_configuration["applications_portal"] != 'undefined' && page_configuration["applications_portal"] != '') {
        p = page_configuration["applications_portal"];
    }
    var AccType = getAccessType();
    if (AccType == "java") {
        if (p == '') {
            p = 'software/javaconnect.html';
            window.name = " " + window.opforfalse;
            if (cpwin != false) {
                cpwin.name = window.opforfalse;
                cpwin.location.href = hostGateway + jwtsclickLinkBefore(getside(), p);
            } else {
                window.open(hostGateway + jwtsclickLinkBefore(getside(), p), window.opforfalse);
            }
        } else {
            h = jwtsclickLinkBefore(getside(), p) + '#';
            window.name = window.opforfalse;
            location.href = hostGateway + h;
        }
    } else if (AccType == "remoteapp") {
        if (p == '') {
            p = 'software/remoteapp.html';
            window.name = " " + window.opforfalse;
            if (cpwin != false) {
                cpwin.name = window.opforfalse;
                cpwin.location.href = hostGateway + jwtsclickLinkBefore(getside(), p);
            } else {
                window.open(hostGateway + jwtsclickLinkBefore(getside(), p), window.opforfalse);
            }
        } else {
            h = jwtsclickLinkBefore(getside(), p) + '#';
            window.name = window.opforfalse;
            location.href = hostGateway + h;
        }
    } else if (AccType == "remoteapp2") {
        if (p == '') {
            remoteApp2Connect(remoteAppUrl);
        } else {
            h = jwtsclickLinkBefore(getside() + "remoteapp2_url = '" + remoteAppUrl + "'; ", p) + '#';
            window.name = window.opforfalse;
            location.href = hostGateway + h;
        }
        /////
    } else if (AccType == "html5") {
        try {
            if (window.document.activeElement) {
                window.document.activeElement.blur();
                window.focus();
            }
        } catch (exc) { }
        var func = function () {
            var k = forHTML5();
            window.name = " " + k;

            if (p == '') {
                p = hostGateway + 'software/html5.html';
                if (window.openinsamewindow) {
                    if (window.self) {
                        window.self.location.href = p;
                    } else {
                        window.location.href = p;
                    }
                } else if (cpwin != false) { 
                    cpwin.name = k;
                    cpwin.location.href = p;
                } else { 
                    var success = false;
                    if (window.open) {
                        var wl = false;
                        try {
                            try {
                                if (window.navigator && navigator.userAgent && navigator.userAgent.match(" CriOS")) {
                                    success = window.open("about:blank", '_blank'); //Chrome on iOS needs _blank
                                } else {
                                    success = window.open("about:blank", k);
                                }
                                wl = true;
                            } catch (ex) {
                                success = window.open(p, k);
                            }
                            if (iOSTabBug(p, hostGateway + '../indexHELPER.html', k, success)) {
                               success = wl = false;
                            }
                            if (success) {
                                success.name = k;
                                if (wl) {
                                    wl = success.location;
                                }
                            } else if (wl) {
                                wl = window.location;
                            }
                            if (wl) {
                                if (wl.replace) {
                                    wl.replace(p);
                                } else if (wl.assign) {
                                    wl.assign(p);
                                } else {
                                    wl.href = p;
                                }
                            }
                        } catch (exc) { }
                    }
                }
            } else {
                location.href = hostGateway + p + '#';
            }

            if (!page_configuration["remember_credentials"]) {
                document.getElementById("Editbox1").value = "";
                document.getElementById("Editbox2").value = "";
                window.user = "";
                window.pass = "";
            }
        };
        if (window.navigator && window.navigator.userAgent && window.navigator.userAgent.indexOf(" Chrome/") > -1 && window.navigator.userAgent.indexOf(" Android") > -1) {
            setTimeout(func, 0);
        } else {
            func();
        }
    }
}

function forHTML5() {
    var randm = Math.floor(Math.random() * 1000);
    return jsencode64(escape('var randomnum = ' + randm + ';window.cmdline=\'' + window.cmdline + '\';window.user=\'' + window.user + '\';window.pass=\'' + escapeBackslash(window.pass) + '\';window.code=\'' + window.code + '\';window.server=\'' + window.serverhtml5 + '\';window.port=\'' + window.porthtml5 + '\';window.webport=\'' + window.porthtml5 + '\';window.lang=\'' + window.lang + '\';window.domain=\'' + window.domain + '\';applications_portal_return_url=\'' + getApplicationsPortalReturnUrl() + '\';')).replace(/=/g, '_');
}

function escapeBackslash(replaceableString) {
    if (replaceableString == null || replaceableString == "" || replaceableString.indexOf('\\') == -1) {
        return replaceableString;
    }
    var parts = replaceableString.split('\\');
    var output = parts.join('\\\\');
    return output;
}

function addevents() { }

//jwts part end

// CP part start
var isLoginOk = false;
var passwordStatus = "ko";
var xhrLoginIsRunning = false;

var cpwin = false;

var serversListingType = "unknown";

function isMobileOrTablet() {
    var check = false;
    (function (a) {
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
    })(navigator.userAgent || navigator.vendor || window.opera);
    return check;
}

function initHtmlPage() {
    if (page_configuration["show_domain"]) {
        show("tr-domain", !window.iecondition ? "table-row" : "inline");
    }
    if (page_configuration["show_password"]) {
        show("tr-password", !window.iecondition ? "table-row" : "inline");
    }
    if (!page_configuration["show_eye"]) {
        document.getElementById("password-visibility").style.visibility = "hidden";
    }

    access_types = page_configuration["access_type"].split("+");
    if (access_types.length > 1) {
        show("accesstypeuserpanel", "block");
        for (var i = 0; i < access_types.length; i++) {
            switch (access_types[i]) {
                case "java":
                    if (!isMobileOrTablet()) {
                        show("label_accesstypeuserchoice_java");
                    }
                    break;
                case "remoteapp":
                    if (!isMobileOrTablet()) {
                        show("label_accesstypeuserchoice_remoteapp");
                    }
                    break;
                case "remoteapp2":
                    if (!isMobileOrTablet()) {
                        show("label_accesstypeuserchoice_remoteapp2");
                    }
                    break;
                case "html5":
                    show("label_accesstypeuserchoice_html5");
                    break;
            }
        }
    }

    translatePage();
    //enableLogonButton();
}

function initCookies() {
    if (!page_configuration["remember_credentials"]) {
        jscreateCookie = function (name, value, days) { };
        jsreadCookie = function (name) { };
        document.cookie = temppfad + 'Login_Editbox1=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        document.cookie = temppfad + 'username_Editbox1=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        document.cookie = temppfad + 'Domain_Editbox3=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
}

function getAccessType() {
    var accesstypeuserchoice;
    if (isMobileOrTablet()) {
        return "html5";
    }
    if (page_configuration["access_type"] == "java" || page_configuration["access_type"] == "remoteapp" || page_configuration["access_type"] == "remoteapp2" || page_configuration["access_type"] == "html5") {
        return page_configuration["access_type"];
    } else {
        if ("forms" in document && "logonform" in document.forms) {
            accesstypeuserchoice = document.forms['logonform'].elements['accesstypeuserchoice'];
        } else {
            accesstypeuserchoice = [];
            var ps = document.getElementById("logonformid");
            var ps2 = ps.getElementsByTagName("*");
            if (ps2) {
                for (var i2 = ps2.length - 1; i2 > -1; i2--) {
                    if (ps2[i2].getAttribute("name") == 'accesstypeuserchoice') {
                        accesstypeuserchoice.push(ps2[i2]);
                    }
                }
            }
        }
        for (var i = 0; i < accesstypeuserchoice.length; i++) {
            if (accesstypeuserchoice[i].checked) {
                return accesstypeuserchoice[i].value;
            }
        }
    }
    return "";
}

function onPasswordTyped() { /* Kept for legacy bug fixing and to avoid having to regenerate the index.html web access page */ }

function onPassword2Focused() { /* Kept for legacy bug fixing and to avoid having to regenerate the index.html web access page */ }

function onPasswordFocused() {
    passwordStatus = "ko";
    document.getElementById("Editbox2").value = "";
}

function onLoginTyped() {

    // Clear password field when a new login is typed and a session has already been opened
    // in order to prevent opening a new session with the previous credential when calling checkLogin()
    if (passwordStatus === "ok") {
        passwordStatus = "ko";
        document.getElementById("Editbox2").value = "";
    }

    checkLogin();
    getTwoStepStatus();

    if (!page_configuration["is_standard"]) {
        initLoadBalancing();
    }
}

function checkLogin() {
    var login = document.getElementById("Editbox1").value.toLowerCase();
    passwordStatus = "unknown";
    if (login !== "") {
        if (serversListingType === "userassigned") {
            loadServersList((page_configuration["is_webcredentials"] ? "@" : "") + login);
        }
        else {
            isLoginOk = true;
            hide("span-login-ko");
            show("span-login-ok");

            if (!page_configuration["is_standard"] && !page_configuration["hide_server_list"]) {
                show("select-server");
            }
            refreshDisplayAndOpenSession();
        }
    }
    else {
        isLoginOk = false;
        hide("select-server");
        refreshDisplayAndOpenSession();
    }
}

function cplogon() {
    if (location.pathname.substring(1) === "preview.html") {
        return;
    }

    if (twoStepStatus === "denied") {
        return;
    }

    setWindowVariables();

    // if is PWA then open on same window
    if ((window.matchMedia('(display-mode: standalone)').matches) || (window.navigator.standalone) || (document.referrer.indexOf('android-app://') > -1)) {
        window.openinsamewindow = true;
    }

    //Ensure "select-server" tag has been displayed before the user connects if password is not shown or empty and configuration is not standard
    if (page_configuration["is_webcredentials"] && (!page_configuration["show_password"] || document.getElementById("Editbox2").value == '') && !page_configuration["is_standard"] && document.getElementById("Editbox1").value !== '' && $('#select-server').css('display') == 'none') {
        initLoadBalancing();
        return;
    }

    var shouldOpenTab = typeof page_configuration["applications_portal"] == 'undefined' || page_configuration["applications_portal"] == '';
    var verificationCode = document.getElementById("twofacode").value;
    if (verificationCode === "" && twoStepStatus !== "disabled") {
        shouldOpenTab = false;
    }

    // if we need to open the connexion client in a new tab/popup
    if (shouldOpenTab) {
        // The following is to avoid a forbidden window.open on an async event, 
        // because window.open must be called directly on the click event.
        var AccType = getAccessType();
        if (AccType == "java" || AccType == "remoteapp" || AccType == "remoteapp2") {
            jwtsclickLinkBefore(getside(), "");
            n = opforfalse;
        } else if (AccType == "html5") {
            n = forHTML5();
        }
        if (AccType != "remoteapp2" && !window.openinsamewindow && cpwin !== false) {
            cpwin = window.open("./software/pleasewait.html", n);
            cpwin.document.title = document.title;
        }
    }

    var login = document.getElementById("Editbox1").value;
    if (login !== "") {
        isLoginOk = true;
        hide("span-login-ko");
        show("span-login-ok");
    }
    else {
        isLoginOk = false;
    }

    checkPassword();
}

function checkPassword() {
    var domain = document.getElementById("Editbox3").value.toLowerCase();
    var login = document.getElementById("Editbox1").value.toLowerCase();
    var password = document.getElementById("Editbox2").value;
    var verificationCode = document.getElementById("twofacode").value;

    if (isLoginOk && login !== ""
        && (password !== "" || !page_configuration["is_webcredentials"] || page_configuration["allow_empty_password"])) {
        if (verificationCode === "" && twoStepStatus === "activated-app") {
            validateCode();
        }
        else if (verificationCode === "" && twoStepStatus === "activated-sms") {
            requestVerificationCodeBySms();
        }
        else if (verificationCode === "" && twoStepStatus === "activated-email") {
            requestVerificationCodeByEmail();
        }
        else {
            validateCredentials(login, password, domain, verificationCode);
        }
    }
    else {
        passwordStatus = "ko";
        document.getElementById("twofacode").value = "";

        refreshDisplayAndOpenSession();
    }
}

function validateCredentials(login, password, domain, verificationCode) {
    disableLogonButton();

    if (page_configuration["is_webcredentials"]) {
        login = "@" + login;
    }
    password = encodeURIComponent(password).replace("~", "%7E").replace("!", "%21").replace("*", "%2A").replace("(", "%28").replace(")", "%29").replace("'", "%27");
    verificationCode = encodeURIComponent(verificationCode);

    try {
        xhrPassword = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrPassword = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrPassword = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) { }
        }
    }

    xhrPassword.onreadystatechange = validateCredentialsCallback;
    xhrPassword.open("POST", "./cgi-bin/hb.exe", true);
    xhrPassword.send("action=cp&l=" + login.toLowerCase() + "&p=" + password + "&d=" + domain + "&f=" + verificationCode + "&t=" + new Date().getTime());
}

function validateCredentialsCallback() {
    if (xhrPassword.readyState == 4) {
        if (xhrPassword.status == 200) {
            lastCredentialsCheckStatus = "";

            var response = new Object();

            try {
                response = JSON.parse(xhrPassword.responseText);

                // Note: validateCode(qrCodeData) currently only takes string as qrcode data, and no json object
                // If it is a legacy twofa activation response, then the response contained directly the QR code data in a json format (keys =  "secret" and "qrcode")
                // As a result it has been properly parsed below
                // We reform a new response
                if (response.qrcode !== undefined) {
                    response.QRCode = JSON.stringify(response);
                    response.Status = "display-2fa-qrcode";
                }
                // New twofa activation response create a json key with the QR code data which is the qrcode data json itself
                else if (response.QRCode !== undefined) {
                    response.QRCode = JSON.stringify(response.QRCode);
                }
            }
            catch (ex) {
                // Response Legacy format
                // the status is the response itself
                console.log("legacy response detected since we cannot parse response: " + xhrPassword.responseText);
                response.Status = xhrPassword.responseText;
            }

            if (response.Status.startsWith("ok")) {
                passwordStatus = "ok";
                if (isValidatingVerificationCode) {
                    document.getElementById("twofaclose").click();
                }

                if (response.Password !== undefined && page_configuration["show_windows_password_expiration_alert"]) {
                    showExpirationPasswordAlertPopin(response.Password.IsExpired, response.Password.ExpiresInDays);
                    preventrefreshDisplayAndOpenSession = true;
                }
            }
            else if (response.Status.startsWith("ko") || response.Status.startsWith("disabled-by-security-check")) {
                passwordStatus = "ko";
                lastCredentialsCheckStatus = "ko";
                if (isValidatingVerificationCode) {
                    document.getElementById("twofacode").value = "";
                    document.getElementById("twofaclose").click();
                }
            }
            else if (response.Status.startsWith("no-web-for-admin")) {
                passwordStatus = "no-web-for-admin";
                if (isValidatingVerificationCode) {
                    document.getElementById("twofacode").value = "";
                    document.getElementById("twofaclose").click();
                }
            }
            else if (response.Status.startsWith("too-many-failed-attempts")) {
                passwordStatus = "too-many-failed-attempts";
                if (isValidatingVerificationCode) {
                    document.getElementById("twofacode").value = "";
                    document.getElementById("twofaclose").click();
                }
            }
            else if (response.Status.startsWith("display-2fa-qrcode") || (response.QRCode !== undefined && response.QRCode.startsWith("{"))) {
                passwordStatus = "twofa-activation";
                validateCode(response.QRCode);
            }
            if (!preventrefreshDisplayAndOpenSession) {
                refreshDisplayAndOpenSession();
            }
        }
        enableLogonButton();
    }
}

function refreshDisplayAndOpenSession() {
    var login = document.getElementById("Editbox1").value.toLowerCase();
    var password = document.getElementById("Editbox2").value;

    // Login status
    if (!xhrLoginIsRunning) {
        if (isLoginOk) {
            hide("span-login-ko");
            show("span-login-ok");
        }
        else {
            hide("span-login-ok");
            if (login !== "") {
                show("span-login-ko");
            }
            else {
                hide("span-login-ko");
            }
        }
    }

    // Password status
    if (passwordStatus === "ok" || passwordStatus === "twofa-activation") {
        hide("span-password-ko");
        if (page_configuration["show_password"]) {
            show("span-password-ok");
        }
    }
    else if (passwordStatus !== "unknown") {
        hide("span-password-ok");
        if (page_configuration["show_password"]) {
            show("span-password-ko");
        }
    }

    // Open session or close please wait tab
    if (isLoginOk && passwordStatus == "ok") {
        hide("span-credentials-ko");
        if (serversListingType === "unknown" && !page_configuration["is_standard"]) {
            if (cpwin !== false) {
                cpwin.close();
            }
            cpwin = false;
        }
        else {
            startInsideOutside();
            document.getElementById("twofacode").value = "";
        }
    }
    else if (passwordStatus !== "unknown") {
        // Specific error messages
        var invalidCredentialsMessage = translate("Invalid credentials");
        var tooManyAttemptsMessage = translate("Too many failed attempts.") + "<br/>" + translate("Please retry later.");
        var noWebForAdminMessage = translate("Admin not allowed to connect via the web portal");

        if (login !== ""
            && (password !== "" || !page_configuration["is_webcredentials"] || page_configuration["allow_empty_password"])
            && passwordStatus !== "twofa-activation") {
            var credentialsKo = document.getElementById("span-credentials-ko");
            if (passwordStatus === "too-many-failed-attempts" && credentialsKo.innerHTML !== tooManyAttemptsMessage) {
                credentialsKo.innerHTML = tooManyAttemptsMessage;
            }
            else if (passwordStatus === "no-web-for-admin" && credentialsKo.innerHTML !== noWebForAdminMessage) {
                credentialsKo.innerHTML = noWebForAdminMessage;
            }
            else if (credentialsKo.innerHTML !== invalidCredentialsMessage) {
                credentialsKo.innerHTML = invalidCredentialsMessage;
            }
            show("span-credentials-ko");
        }
        else {
            hide("span-credentials-ko");
        }

        if (cpwin != false) {
            cpwin.close();
        }
        cpwin = false;
    }

    // Prevent default credentials to be marked as invalid
    var loginWithPrefix = "";
    if (login) {
        loginWithPrefix = (page_configuration["is_webcredentials"] ? "@" : "") + login;
    }

    if ((loginWithPrefix === login_original.toLowerCase() && password === pass_original && lastCredentialsCheckStatus !== "ko")
        || passwordStatus == "unknown") {
        hide("span-credentials-ko");
        hide("span-password-ko");
        hide("span-password-ok");
    }
}

function loadServersList(login) {
    xhrLoginIsRunning = true;
    document.getElementById("Editbox1").disabled = true;

    try {
        xhrLogin = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrLogin = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrLogin = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) {
                return "";
            }
        }
    }

    xhrLogin.onreadystatechange = processServersList;
    xhrLogin.open("POST", "./cgi-bin/hb.exe", true);
    xhrLogin.send("action=srvassigned" + "&t=" + new Date().getTime() + "&d=" + document.getElementById("Editbox3").value + "&l=" + (page_configuration["is_webcredentials"] ? "@" : "") + document.getElementById("Editbox1").value);
}

function processServersList() {
    if (xhrLogin.readyState == 4) {
        if (serversListingType != "loadbalanced") {
            resetDropDownMenu();
        }
        if (xhrLogin.status == 200 && xhrLogin.responseText != 'KO') {
            isLoginOk = true;
            hide("span-login-ko");
            show("span-login-ok");
            servers = xhrLogin.responseText.split("\r\n").sort().filter(function (el) { return el; });

            if (serversListingType != "loadbalanced") {
                    show("select-server");
                displayServersList(servers);    
            }
        } else {
            isLoginOk = false;
            hide("span-login-ok");
            show("span-login-ko");
            hide("select-server");
        }
        xhrLoginIsRunning = false;
        document.getElementById("Editbox1").disabled = false;
    }
}

function displayServersList(servers) {
    if (page_configuration["hide_server_list"] && servers.length === 1) {
		hide("select-server");
	}
	else 
	{
		show("select-server");
	}
    for (var i in servers) {
        serverData = servers[i].split('|');
        if (serverData.length >= 2) {
            serverName = serverData[0];
            serverIPPort = serverData[1].split(':');
            serverport = "";
            if (serverIPPort.length > 1) {
                serverport = serverIPPort[1];
            }
            addServerToDropDownMenu(serverName, serverIPPort[0], serverport);
        }
    }
}

function getSelectedServerIp() {
    var select = document.getElementById("select-server");
    if (select.selectedIndex < 0) {
        return '';
    }

    var selectedValue = select.options[select.selectedIndex].value;
    if (!selectedValue) {
        return '';
    }

    ipport = selectedValue.split(':');
    return ipport[0];
}

function getSelectedServerPort() {
    var select = document.getElementById("select-server");
    if (select.selectedIndex < 0) {
        return '';
    }

    var selectedValue = select.options[select.selectedIndex].value;
    if (!selectedValue) {
        return '';
    }

    ipport = selectedValue.split(':');
    serverport = "";
    if (ipport.length > 1) {
        serverport = ipport[1];
    }
    return serverport;
}

function initLoadBalancing() {
    try {
        xhrLoadBalancing = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrLoadBalancing = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrLoadBalancing = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) {
                return "";
            }
        }
    }

    xhrLoadBalancing.onreadystatechange = processLoadBalancing;
    xhrLoadBalancing.open("POST", "./cgi-bin/hb.exe", true);
    xhrLoadBalancing.send("action=lb&t=" + new Date().getTime() + "&d=" + document.getElementById("Editbox3").value + "&l=" + (page_configuration["is_webcredentials"] ? "@" : "") + document.getElementById("Editbox1").value);
}

function processLoadBalancing() {
    if (xhrLoadBalancing.readyState == 4) {
        if (xhrLoadBalancing.status == 200) {
            if (xhrLoadBalancing.responseText == "loadbalancing-off") {
                serversListingType = "userassigned";
                checkLogin();
            } else {
                serversListingType = "loadbalanced";
                s = xhrLoadBalancing.responseText.split("|");
                lessLoadedServerName = s[1];
                lessLoadedServerAddress = s[2];
                lessLoadedServerPort = s[4];
                if (page_configuration["hide_server_list"]) {
                    hide("select-server");
                }
                resetDropDownMenu();
                addServerToDropDownMenu(lessLoadedServerName, lessLoadedServerAddress, lessLoadedServerPort);
                disableDropDownMenu();
            }
        }
    }
}

function resetDropDownMenu() {
    select = document.getElementById("select-server");
    select.options.length = 0;
}

function addServerToDropDownMenu(serverName, serverAddress, serverPort) {
    if (serverPort != "") {
        serverPort = ":" + serverPort;
    }
    select = document.getElementById("select-server");
    select.options[select.options.length] = new Option(serverName, serverAddress + serverPort);
}

function disableDropDownMenu() {
    select = document.getElementById("select-server");
    select.disabled = "disabled";
}

function getApplicationsPortalReturnUrl() {
    var re = new RegExp(/^.*\//);
    return re.exec(window.location.href);
}

function hide(id) {
    if (document.getElementById(id)) {
        document.getElementById(id).style.display = "none";
    }
}

function show(id, disp) {
    if (typeof (disp) === 'undefined') {
        disp = "inline";
    }
    if (document.getElementById(id)) {
        document.getElementById(id).style.display = disp;
    }
}

function translate(sentence) {
    var language = navigator.language || navigator.userLanguage || "";
    language = language.toLowerCase();
    var translation = sentence;
    var languageIndex = language.substring(0, 2);

    if (languageIndex == 'zh') {
        if (language.substr(3, 2) == 'tw') {
            languageIndex = 'zh-tw';
        } else {
            languageIndex = 'zh-cn';
        }
    }

    if (languageIndex == 'pt') {
        if (language.substr(3, 2) == 'BR') {
            languageIndex = 'pt-br';
        } else {
            languageIndex = 'pt-br';
        }
    }

    if (translations === undefined || (translations !== undefined && translations['en'] === undefined)) {
        return translation;
    }

    if (translations['en'][sentence] !== undefined) {
        translation = translations['en'][sentence];
    }
    if (translations[languageIndex] !== undefined && translations[languageIndex][sentence] !== undefined) {
        translation = translations[languageIndex][sentence];
    }
    return translation;
}

function translatePage() {
    //Error translation
    document.getElementById("span-credentials-ko").innerHTML = translate("Invalid credentials");

    // 2FA Pop-up translation
    document.getElementById("sp-title").innerHTML = translate("Protect your account with 2-step verification");
    document.getElementById("sp-app").innerHTML = translate("Display the verification code using an authentication app");
    document.getElementById("sp-appstep1").innerHTML = translate("Open the authenticator app on your mobile phone.");
    document.getElementById("sp-appstep2").innerHTML = translate("Scan the QR code displayed below:");
    document.getElementById("sp-sms").innerHTML = translate("Or receive your verification code via SMS");
    document.getElementById("sp-smsstep1").innerHTML = translate("Type your phone number below, using the international phone numbers format (e.g. +14155552671):");
    document.getElementById("sp-smsstep2").innerHTML = translate("Click Receive SMS button to register your phone number and receive your verification code.");
    document.getElementById("sp-register").value = translate("Receive SMS");
    document.getElementById("sp-validate").innerHTML = translate("Validate your verification code");
    document.getElementById("sp-verify").value = translate("Validate");
    document.getElementById("twofaqrcode").alt = translate("No QR code was generated! Please enter your credentials on the logon page.");

    document.getElementById("windows-password-reset-button").value = translate("Reset your Windows password");
    document.getElementById("sp-title-reset-windows-password").innerHTML = translate("Reset your Windows password");
    document.getElementById("sp-full-username").placeholder = translate("Your username") + " - Ex: CORP\\johndoe";
    document.getElementById("sp-old-password").placeholder = translate("Your old password");
    document.getElementById("sp-new-password").placeholder = translate("Your new password");
    document.getElementById("sp-confirm-new-password").placeholder = translate("Confirm your new password");
    document.getElementById("reset-windows-password-choice-validate").value = translate("Validate");

    document.getElementById("sp-title-expiration-password-reminder").innerHTML = translate("Expiration password reminder");
    document.getElementById("change-password-now").innerHTML = translate("Would you like to change it now?");
    document.getElementById("password-expiration-choice-change-password").value = translate("Yes");
    document.getElementById("password-expiration-choice-connect").value = translate("No");

    document.getElementById("sp-email").innerHTML = translate("Or receive your verification code via e-mail");
    document.getElementById("sp-emailstep1").innerHTML = translate("Enter your e-mail address below:");
    document.getElementById("sp-emailstep2").innerHTML = translate("Click Send e-mail button to register your e-mail and receive your verification code.");
    document.getElementById("sp-sendemail").value = translate("Send e-mail");

    // Windows plugin
    if (document.getElementById("sp-windowsplugin") != null) {
        document.getElementById("sp-windowsplugin").innerHTML = translate("Windows plugin not found.");
    }
    if (document.getElementById("sp-windowsplugin-actions") != null) {
        document.getElementById("sp-windowsplugin-actions").innerHTML = translate("Install the plugin and \'Log in\' again");
    }
}

// CP part end

// Change password part start

var preventrefreshDisplayAndOpenSession = false;
var xhrResetPasswordIsRunning = false;

function showResetWindowsPasswordPopin() {
    preventrefreshDisplayAndOpenSession = false;
    var resetWindowsPasswordPopinLink = document.getElementById("open-reset-windows-password");
    resetWindowsPasswordPopinLink.click();
}

function closeResetPasswordPopin() {
    preventrefreshDisplayAndOpenSession = false;
    var closePopin = document.getElementById("reset-windows-password-close");
    if (closePopin) {
        closePopin.click();
    }
}

function closePasswordExpirationReminderPopin() {
    preventrefreshDisplayAndOpenSession = false;
    var closePopin = document.getElementById("expiration-password-reminder-close");
    if (closePopin) {
        closePopin.click();
    }
}

function showExpirationPasswordAlertPopin(passwordIsExpired, passwordExpiresInDays) {
    var expirationPasswordReminderPopinLink = document.getElementById("open-expiration-password-reminder");
    if (passwordIsExpired) {
        document.getElementById("password-expiring").innerHTML = translate("Your windows password has expired!");
    }
    else {
        document.getElementById("password-expiring").innerHTML = translate("Your windows password expires in %DAYS% days.").replace("%DAYS%", passwordExpiresInDays);
    }
    expirationPasswordReminderPopinLink.click();
}

function changeWindowsPassword() {
    preventrefreshDisplayAndOpenSession = false;

    var formInfo = new Object();

    if (isValidWindowsPasswordResetForm(formInfo)) {
        if (xhrResetPasswordIsRunning) {
            document.getElementById("reset-windows-password-error").innerHTML = translate("A reset password request is currently being processed, please wait.");
            document.getElementById("reset-windows-password-error").style.display = "block";
        }
        else {
            sendResetPasswordRequest(formInfo);
        }
    }
}

function isValidWindowsPasswordResetForm(formInfo) {
    if (document.getElementById("sp-full-username").value == "") {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: username field is empty!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-full-username").focus();
        return false;
    }
    if (document.getElementById("sp-old-password").value == "") {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: old password field is empty!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-old-password").focus();
        return false;
    }
    if (document.getElementById("sp-new-password").value == "") {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: new password field is empty!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-new-password").focus();
        return false;
    }
    if (document.getElementById("sp-new-password").value == document.getElementById("sp-old-password").value) {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: old password and new password are identical!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-new-password").focus();
        return false;
    }
    if (document.getElementById("sp-confirm-new-password").value == "") {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: confirm new password field is empty!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-confirm-new-password").focus();
        return false;
    }
    if (document.getElementById("sp-new-password").value !== document.getElementById("sp-confirm-new-password").value) {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: new password and confirmed one don't match!");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-confirm-new-password").focus();
        return false;
    }

    // Parsing full username
    var fullUsernameSplit = document.getElementById("sp-full-username").value.split("\\");
    if (fullUsernameSplit.length > 2) {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: cannot parse full username due to invalid character");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-full-username").focus();
        return false;
    }
    if (document.getElementById("sp-full-username").value.indexOf("/") > -1) {
        document.getElementById("reset-windows-password-error").innerHTML = translate("Error: invalid character in username provided");
        document.getElementById("reset-windows-password-error").style.display = "block";
        document.getElementById("sp-full-username").focus();
        return false;
    }
    if (fullUsernameSplit.length == 2) {
        formInfo.domain = fullUsernameSplit[0];
        formInfo.username = fullUsernameSplit[1];
    }
    else {
        formInfo.domain = "";
        formInfo.username = fullUsernameSplit[0];
    }

    var domainTagValue = document.getElementById("Editbox3").value.toLowerCase();
    if (formInfo.domain == "" && domainTagValue !== "") {
        formInfo.domain = domainTagValue;
    }

    formInfo.oldpassword = document.getElementById("sp-old-password").value;
    formInfo.newpassword = document.getElementById("sp-new-password").value;

    document.getElementById("reset-windows-password-error").style.display = "none";
    document.getElementById("reset-windows-password-error").innerHTML = "";

    return true;
}

function sendResetPasswordRequest(formInfo) {
    xhrResetPasswordIsRunning = true;

    try {
        xhrResetPassword = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrResetPassword = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrResetPassword = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) {
                return "";
            }
        }
    }

    xhrResetPassword.onreadystatechange = processResetPasswordResponse;
    xhrResetPassword.open("POST", "./cgi-bin/hb.exe", true);
    xhrResetPassword.send("action=resetpassword" + "&t=" + new Date().getTime() + "&d=" + formInfo.domain + "&l=" + formInfo.username + "&p=" + encodeURIComponent(formInfo.oldpassword) + "&newpassword=" + encodeURIComponent(formInfo.newpassword));
}

function processResetPasswordResponse() {
    if (xhrResetPassword.readyState == 4) {
        if (xhrResetPassword.status == 200) {
            try {
                data = JSON.parse(xhrResetPassword.responseText);
                if (data.Status == "OK") {
                    closeResetPasswordPopin();
                    alert(translate("Windows password changed! Please retry connecting with your new password."));
                }
                else if (data.Status == "KO") {
                    document.getElementById("reset-windows-password-error").innerHTML = translate(data.ErrorMessage);
                    document.getElementById("reset-windows-password-error").style.display = "block";
                }
                else if (data.Status == "too-many-failed-attempts") {
                    document.getElementById("reset-windows-password-error").innerHTML = translate("Too many failed attempts");
                    document.getElementById("reset-windows-password-error").style.display = "block";
                }
                else if (data.Status == "disabled-by-security-check") {
                    document.getElementById("reset-windows-password-error").innerHTML = translate("Invalid credentials");
                    document.getElementById("reset-windows-password-error").style.display = "block";
                }
                else if (data.Status == "no-web-for-admin") {
                    document.getElementById("reset-windows-password-error").innerHTML = translate("Admin not allowed to connect via the web portal");
                    document.getElementById("reset-windows-password-error").style.display = "block";
                }
                else {
                    document.getElementById("reset-windows-password-error").innerHTML = translate("An internal error occured");
                    document.getElementById("reset-windows-password-error").style.display = "block";
                }
            } catch (e) {
                console.log("An error occured while processing reset password request: couldn't parse in json format the following response:");
                console.log(xhrResetPassword.responseText);
            }
            console.log("process reset password response = " + xhrResetPassword.responseText);
        }
        else {
            console.log("An error occured while processing reset password request: ");
            console.log("status: " + xhrResetPassword.status);
        }
        xhrResetPasswordIsRunning = false;
    }
}

// Change password part end

// 2FA part start

var twoStepStatus = "";
var isValidatingVerificationCode = false;
var lastCredentialsCheckStatus = "";

var xhrTwoStepStatus;
var xhrVerifyCode;
var xhrRequestCode;

function getTwoStepStatus() {
    if (location.pathname.substring(1) === "preview.html") {
        return;
    }

    var requestdDomain = document.getElementById("Editbox3").value.toLowerCase();
    var requestLogin = document.getElementById("Editbox1").value.toLowerCase();
    if (requestLogin === "") {
        return;
    }

    if (page_configuration["is_webcredentials"]) {
        requestLogin = '@' + requestLogin;
    }

    try {
        xhrTwoStepStatus = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrTwoStepStatus = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrTwoStepStatus = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) { }
        }
    }

    hide("span-credentials-ko");

    xhrTwoStepStatus.onreadystatechange = getTwoStepStatusCallback;
    xhrTwoStepStatus.open("POST", "./cgi-bin/hb.exe", true);
    xhrTwoStepStatus.send("action=twofa&d=" + requestdDomain + "&l=" + requestLogin);
}

function getTwoStepStatusCallback() {
    if (xhrTwoStepStatus.readyState === 4) {
        enableLogonButton();
        if (xhrTwoStepStatus.status === 200) {
            twoStepStatus = xhrTwoStepStatus.responseText;
            if (twoStepStatus === "denied") {
                var credentialsKo = document.getElementById("span-credentials-ko");
                var content = translate("Access from the Web portal is denied to user %USERNAME%.") + "<br/><br/>" + translate("Please enable two factor authentication.");
                content = content.replace("%USERNAME%", document.getElementById("Editbox1").value.toLowerCase());
                credentialsKo.innerHTML = content;
                show("span-credentials-ko");
            }
        }
    }
}

function validateCode(qrCodeData) {
    if (location.pathname.substring(1) === "preview.html") {
        return;
    }

    var appactivation = document.getElementById("sp-appactivation");
    var smsactivation = document.getElementById("sp-smsactivation");
    var emailactivation = document.getElementById("sp-emailactivation");

    if (qrCodeData && qrCodeData.startsWith("{")) {
        var qrCodeImage = document.getElementById("twofaqrcode");
        var qrCodeCaption = document.getElementById("qrcodecaption");

        var data = JSON.parse(qrCodeData);
        qrCodeImage.src = data.qrcode;
        qrCodeImage.alt = data.secret;
        qrCodeCaption.innerText = translate("Secret key:") + " " + data.secret;

        appactivation.style.display = "block";
        if (twoStepStatus === "not-activated-apponly") {
            smsactivation.style.display = "none";
            emailactivation.style.display = "none";
        }
        else if (twoStepStatus === "not-activated-app-or-email") {
            smsactivation.style.display = "none";
            emailactivation.style.display = "block";
        }
        else if (twoStepStatus === "not-activated-app-or-sms"){
            smsactivation.style.display = "block";
            emailactivation.style.display = "none";
        }
        else if (twoStepStatus === "not-activated"){
            smsactivation.style.display = "block";
            emailactivation.style.display = "block";
        }
    }
    else {
        appactivation.style.display = "none";
        smsactivation.style.display = "none";
        emailactivation.style.display = "none";
    }

    var phoneNumberInput = document.getElementById("sp-phonenumber");
    phoneNumberInput.value = "";

    if (!isValidatingVerificationCode) {
        isValidatingVerificationCode = true;
        var twofaPopinLink = document.getElementById("open-twofa");
        twofaPopinLink.click();
    }

    setTimeout(function initVerificationCode() {
		if (twoStepStatus === "not-activated-apponly") {
			var spapp = document.getElementById("sp-app");
            spapp.click();
        }
        var verificationCode = document.getElementById('twofacode');
        verificationCode.className = "valid";
        verificationCode.value = "";
        verificationCode.blur();
        verificationCode.focus();

        // replace #verify url with / so that if the user refreshes the page on the 2fa code asking page it redirects him the main page
        // history.PushState close the two-fa popup on IE11 (userAgent contains "Trident")
        if (!(/trident/ig.test(window.navigator.userAgent))) {
            window.history.pushState("", "", location.pathname);
        }
    }, 100);
}

function validate2faKeyUpHandler(e) {
	// On "Enter"
	if (e.keyCode === 13) {
		verifyCode();
	}
}

function verifyCode() {
    var twofaCode = document.getElementById("twofacode");
    var twofaError = document.getElementById("twofaerror");

    if (!twofaCode || !twofaError) {
        return;
    }

    var codeRegExp = RegExp(/^([0-9]){4,12}$/);
    if (twofaCode.value.length === 0 || !codeRegExp.test(twofaCode.value)) {
        twofaCode.className = "invalid";
        twofaError.innerHTML = translate("Please enter a valid confirmation code.");
        twofaError.className = "error active";
    }
    else {
        twofaCode.className = "valid";
        twofaError.innerHTML = "";
        twofaError.className = "error";

        disableVerifyButton();

        if (twoStepStatus === "not-activated" 
         || twoStepStatus === "not-activated-apponly"
         || twoStepStatus === "not-activated-app-or-email"
         || twoStepStatus === "not-activated-app-or-sms") {
            var requestdDomain = document.getElementById("Editbox3").value.toLowerCase();
            var requestLogin = document.getElementById("Editbox1").value.toLowerCase();
            if (page_configuration["is_webcredentials"]) {
                requestLogin = '@' + requestLogin;
            }

            try {
                xhrVerifyCode = new XMLHttpRequest();
            } catch (e) {
                try {
                    xhrVerifyCode = new ActiveXObject('MSXML2.XMLHTTP');
                } catch (e) {
                    try {
                        xhrVerifyCode = new ActiveXObject('Microsoft.XMLHTTP');
                    } catch (e) { }
                }
            }

            xhrVerifyCode.onreadystatechange = verifyCodeActivationCallback;
            xhrVerifyCode.open("POST", "./cgi-bin/hb.exe", true);
            xhrVerifyCode.send("action=twofa&d=" + requestdDomain + "&l=" + requestLogin + "&f=" + encodeURIComponent(twofaCode.value));
        }
        else {
            cplogon();
        }
    }
}

function verifyCodeActivationCallback() {
    if (xhrVerifyCode.readyState === 4) {
        if (xhrVerifyCode.status === 200) {

            twoStepStatus = xhrVerifyCode.responseText;

            if (twoStepStatus === "activated-app" || twoStepStatus === "activated-sms" || twoStepStatus === "activated-email")
            {
                verifyCodeActivationSuccess();
            }
            else {
                verifyCodeActivationError();
            }
        }
    }
}

function verifyCodeActivationError() {
    var twofaCode = document.getElementById("twofacode");
    twofaCode.className = "invalid";

    var twofaError = document.getElementById("twofaerror");
    twofaError.innerHTML = translate("An error occured while validating your 2-step verification code. Please retry or contact your administrator.");
    twofaError.className = "error active";
}

function verifyCodeActivationSuccess() {
    var twofaCode = document.getElementById("twofacode");
    twofaCode.className = "valid";

    var twofaError = document.getElementById("twofaerror");
    twofaError.innerHTML = "";
    twofaError.className = "error";

    alert(translate("You are all set. From now on, you will use your authenticator app or receive your verification code by SMS or e-mail to sign in your remote session."));

    var passwordField = document.getElementById("Editbox2");
    passwordField.value = "";
    twofaCode.value = "";

    hide("span-password-ok");
    hide("span-password-ko");
    hide("span-credentials-ko");

    var closeTwofaPopin = document.getElementById("twofaclose");
    if (closeTwofaPopin) {
        closeTwofaPopin.click();
    }
}

function requestVerificationCodeBySms() {
    if (location.pathname.substring(1) === "preview.html") {
        return;
    }

	var sp_register_btn = document.getElementById("sp-register");
	var smsError = document.getElementById("sms-error");

	if (document.getElementById("sp-phonenumber").value === '') {
		smsError.innerHTML = translate("Please enter a phone number");
	} 

	sp_register_btn.value = translate("SMS Sent");
	sp_register_btn.disabled = true;
	sp_register_btn.style.cursor = "wait";
	
    var phoneNumber = "";
    var requestdDomain = document.getElementById("Editbox3").value.toLowerCase();
    var requestLogin = document.getElementById("Editbox1").value.toLowerCase();
    var requestPassword = encodeURIComponent(document.getElementById("Editbox2").value).replace("~", "%7E").replace("!", "%21").replace("*", "%2A").replace("(", "%28").replace(")", "%29").replace("'", "%27");

    if (page_configuration["is_webcredentials"]) {
        requestLogin = '@' + requestLogin;
    }

    if (twoStepStatus === "not-activated" || twoStepStatus === "not-activated-app-or-sms") {
        phoneNumber = encodeURIComponent(document.getElementById("sp-phonenumber").value);
    }

    try {
        xhrRequestCode = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrRequestCode = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrRequestCode = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) { }
        }
    }

    xhrRequestCode.onreadystatechange = requestVerificationCodeBySmsCallback;
    xhrRequestCode.open("POST", "./cgi-bin/hb.exe", true);
    xhrRequestCode.send("action=twofa&d=" + requestdDomain + "&l=" + requestLogin + "&p=" + requestPassword + "&n=" + phoneNumber);
}

function requestVerificationCodeByEmail() {
    if (location.pathname.substring(1) === "preview.html") {
        return;
    }

	var sp_register_btn = document.getElementById("sp-sendemail");
	var emailError = document.getElementById("sp-emailaddresserror");

	if (document.getElementById("sp-emailaddress").value === '') {
		emailError.innerHTML = translate("Please enter an e-mail");
	} 

	sp_register_btn.value = translate("Email Sent");
	sp_register_btn.disabled = true;
	sp_register_btn.style.cursor = "wait";
	
    var email = "";
    var requestdDomain = document.getElementById("Editbox3").value.toLowerCase();
    var requestLogin = document.getElementById("Editbox1").value.toLowerCase();
    var requestPassword = encodeURIComponent(document.getElementById("Editbox2").value).replace("~", "%7E").replace("!", "%21").replace("*", "%2A").replace("(", "%28").replace(")", "%29").replace("'", "%27");

    if (page_configuration["is_webcredentials"]) {
        requestLogin = '@' + requestLogin;
    }

    if (twoStepStatus === "not-activated" || twoStepStatus === "not-activated-app-or-email") {
        email = encodeURIComponent(document.getElementById("sp-emailaddress").value);
    }

    try {
        xhrRequestCode = new XMLHttpRequest();
    } catch (e) {
        try {
            xhrRequestCode = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                xhrRequestCode = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) { }
        }
    }

    xhrRequestCode.onreadystatechange = requestVerificationCodeByEmailCallback;
    xhrRequestCode.open("POST", "./cgi-bin/hb.exe", true);
    xhrRequestCode.send("action=twofa&d=" + requestdDomain + "&l=" + requestLogin + "&p=" + requestPassword + "&e=" + email);
}

function requestVerificationCodeBySmsCallback() {
	
	var sp_register_btn = document.getElementById("sp-register");
	sp_register_btn.disabled = false;
	sp_register_btn.value = translate("Receive SMS");
	sp_register_btn.style.cursor = "pointer";
	var smsError = document.getElementById("sms-error");
	smsError.innerHTML = '';
	

    if (xhrRequestCode.readyState === 4) {
        if (xhrRequestCode.status === 200) {
            if (xhrRequestCode.responseText === "denied") {
                requestVerificationCodeBySmsDenied();
            }
            else if (xhrRequestCode.responseText === "disabled") {
                requestVerificationCodeBySmsDisabled();
            }
            else {
                requestVerificationCodeBySmsSuccess();
            }
        }
    }
}

function requestVerificationCodeByEmailCallback() {
	
	var sp_sendemail_btn = document.getElementById("sp-sendemail");
	sp_sendemail_btn.disabled = false;
	sp_sendemail_btn.value = translate("Send e-mail");
	sp_sendemail_btn.style.cursor = "pointer";
	var emailError = document.getElementById("sp-emailaddresserror");
	emailError.innerHTML = '';
	

    if (xhrRequestCode.readyState === 4) {
        if (xhrRequestCode.status === 200) {
            if (xhrRequestCode.responseText === "denied") {
                requestVerificationCodeByEmailDenied();
            }
            else if (xhrRequestCode.responseText === "disabled") {
                requestVerificationCodeByEmailDisabled();
            }
            else {
                requestVerificationCodeByEmailSuccess();
            }
        }
    }
}

function requestVerificationCodeBySmsDenied() {
	
	var smsactivation = document.getElementById("sp-smsactivation");
    if (smsactivation.style.display !== "none") {
		var smsError = document.getElementById("sms-error");
		smsError.innerHTML = translate("An error occurred while sending your 2-step verification code by SMS. Please retry or contact your administrator.");
		smsError.className = "error active";
	}
    passwordStatus = "ko";
    refreshDisplayAndOpenSession();
}

function requestVerificationCodeBySmsDisabled() {
    if (!isValidatingVerificationCode) {
        validateCode();
    }

    var smsactivation = document.getElementById("sp-smsactivation");
    if (smsactivation.style.display !== "none") {
        var phoneNumberInput = document.getElementById("sp-phonenumber");
        phoneNumberInput.className = "invalid";

        phoneNumberInput.value = "";
        phoneNumberInput.focus();

        var phoneNumberError = document.getElementById("sp-phonenumbererror");
        phoneNumberError.innerHTML = translate("Failed to send SMS verification code. Please ensure that your configured phone number is a valid phone number following E.164 format for international phone numbers (e.g. +14155552671). Otherwise, please contact your administrator.");
        phoneNumberError.className = "error active";
    }
    else {
        var verificationCode = document.getElementById('twofacode');
        verificationCode.className = "invalid";
        verificationCode.focus();

        var verificationError = document.getElementById("twofaerror");
        verificationError.innerHTML = translate("Failed to send SMS verification code. Please ensure that your configured phone number is a valid phone number following E.164 format for international phone numbers (e.g. +14155552671). Otherwise, please contact your administrator.");
        verificationError.className = "error active";
    }
}

function requestVerificationCodeBySmsSuccess() {
    if (!isValidatingVerificationCode) {
        validateCode();
    }

    var smsactivation = document.getElementById("sp-smsactivation");
    if (smsactivation.style.display !== "none") {
        var phoneNumberInput = document.getElementById("sp-phonenumber");
        phoneNumberInput.className = "valid";

        var phoneNumberError = document.getElementById("sp-phonenumbererror");
        phoneNumberError.innerHTML = "";
        phoneNumberError.className = "error";
    }

    var twofaCode = document.getElementById("twofacode");
    twofaCode.focus();
}

function requestVerificationCodeByEmailDenied() {
	
	var emailactivation = document.getElementById("sp-emailactivation");
    if (emailactivation.style.display !== "none") {
		var emailError = document.getElementById("sp-emailaddresserror");
		emailError.innerHTML = translate("An error occurred while sending your 2-step verification code by e-mail. Please retry or contact your administrator.");
		emailError.className = "error active";
	}
    passwordStatus = "ko";
    refreshDisplayAndOpenSession();
}

function requestVerificationCodeByEmailDisabled() {
    if (!isValidatingVerificationCode) {
        validateCode();
    }

    var emailactivation = document.getElementById("sp-emailactivation");
    if (emailactivation.style.display !== "none") {
        var emailInput = document.getElementById("sp-emailaddress");
        emailInput.className = "invalid";

        emailInput.value = "";
        emailInput.focus();

        var emailError = document.getElementById("sp-emailaddresserror");
        emailError.innerHTML = translate("An error occurred while sending your 2-step verification code by e-mail. Please retry or contact your administrator.");
        emailError.className = "error active";
    }
    else {
        var verificationCode = document.getElementById('twofacode');
        verificationCode.className = "invalid";
        verificationCode.focus();

        var verificationError = document.getElementById("twofaerror");
        verificationError.innerHTML = translate("An error occurred while sending your 2-step verification code by e-mail. Please retry or contact your administrator.");
        verificationError.className = "error active";
    }
}

function requestVerificationCodeByEmailSuccess() {
    if (!isValidatingVerificationCode) {
        validateCode();
    }

    var emailactivation = document.getElementById("sp-emailactivation");
    if (emailactivation.style.display !== "none") {
        var emailInput = document.getElementById("sp-emailaddress");
        emailInput.className = "valid";

        var emailError = document.getElementById("sp-emailaddresserror");
        emailError.innerHTML = "";
        emailError.className = "error";
    }

    var twofaCode = document.getElementById("twofacode");
    twofaCode.focus();
}

function exitVerification() {
    if (isValidatingVerificationCode) {
        isValidatingVerificationCode = false;
    }
}

function onChangeTrim(element) {
    if (element && element.tagName) {
        var tagName = element.tagName.toLowerCase();
        if (tagName === 'input') {
            var trimedInput = element.value.replace(/\s+/g, '');
            element.value = trimedInput;
        }
    }
}

function disableLogonButton() {
    var buttonLogOn = document.getElementById("buttonLogOn");
    if (buttonLogOn) {
        buttonLogOn.onclick = function () {
            return false;
        };
        buttonLogOn.style.cursor = "wait";
    }
}

function enableLogonButton() {
    var buttonLogOn = document.getElementById("buttonLogOn");
    if (buttonLogOn) {
        buttonLogOn.onclick = cplogon;
        buttonLogOn.style.cursor = "pointer";
    }
}

function disableVerifyButton() {
    var verifyButton = document.getElementById("sp-verify");
    if (verifyButton) {
        verifyButton.onclick = function () { return false; };
        verifyButton.style.cursor = "wait";
        setTimeout(enableVerifyButton, "2000");
    }
}

function enableVerifyButton() {
    var verifyButton = document.getElementById("sp-verify");
    if (verifyButton) {
        verifyButton.onclick = verifyCode;
        verifyButton.style.cursor = "pointer";
    }
}

if (!String.prototype.startsWith) {
    String.prototype.startsWith = function (search, pos) {
        return this.substr(!pos || pos < 0 ? 0 : +pos, search.length) === search;
    };
}

// 2FA part end