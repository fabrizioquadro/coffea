
// Display Remote App Client Plugin installation details
var remoteapp2_showpopin = true; // Set to 'false' to hide the details pop-in - in this case the plugin download/installation instructions will never be shown to the users

// Cookie Check for Remote App Client installation
var remoteapp2_cookiecheck = true; // Set to 'false' to disable cookie check - in this case we will assume the plugin is already installed and never show the plugin download/installation instructions

// Remote Desktop Server
var remoteapp2_server = '';
var remoteapp2_port = '443';

// Windows Authentication
var remoteapp2_user = '';
var remoteapp2_psw = '';
var remoteapp2_domain = '';

// Force low settings (good for slow connections)
var remoteapp2_speed = 'high'; // Set to 'low' for slow connections, set to 'high' for fast connections

// Optionnal Command Line Parameters
var remoteapp2_apppath = '';

// Seamless/RemoteApp mode
var remoteapp2_wallp = 'green';
var remoteapp2_seamless = 'off';
var remoteapp2_remoteapp = 'on';

// Screen
var remoteapp2_color = '32';
var remoteapp2_full = '2';
var remoteapp2_width = '';
var remoteapp2_height = '';
var remoteapp2_scale = '100';
var remoteapp2_smartsizing = '1';
var remoteapp2_dualscreen = 'off';
var remoteapp2_span = 'off';

// Disks mapping (The C: disk is required for printing)
var remoteapp2_disk = '1';
var remoteapp2_selectdisk = ''; // NOTICE: Set to '' to select all clients disks, or set to the list of client disks to share (for instance 'C:;H:;L:;' to select C:,H: and L: disks)

// Printing
var remoteapp2_printer = 'on';
var remoteapp2_preview = 'on';
var remoteapp2_default = 'off';
var remoteapp2_defaultsystem = 'off';
var remoteapp2_select = 'off';
var remoteapp2_pagescaling = 'noscale';

// Hardware
var remoteapp2_com = '0';
var remoteapp2_smartcard = '0';
var remoteapp2_serial = 'off';
var remoteapp2_usb = 'off';
var remoteapp2_usbfx = 'off';
var remoteapp2_sound = 'on';
var remoteapp2_playremotesound = 'locally'; // locally/remotely/no - specify where remote session sound is played on
var remoteapp2_directx = 'off';

// Miscellaneous
var remoteapp2_alttab = '0';
var remoteapp2_firewall = '1';
var remoteapp2_localtb = '0';
var remoteapp2_lock = 'off';
var remoteapp2_rdp5 = 'off';
var remoteapp2_reset = 'off';
var remoteapp2_gatewayhostname = '';
var remoteapp2_gatewayusagemethod = '';

// Security
var remoteapp2_useasrdg = 'off';

// Application portal
var remoteapp2_appportal_timeout_status = false;
var remoteapp2_appportal_timeout_delay = 0;

// --------------------------------- Warning: do not modify anything below this line ---------------------------------

function remoteApp2Connect(remoteAppUrl) {
	if (remoteapp2_cookiecheck) {
		remoteAppCookie = '; ' + document.cookie;
		remoteAppCookieParts = remoteAppCookie.split('; remoteappplugin=');
		if (remoteAppCookieParts.length != 2 || remoteAppCookieParts.pop().split(';').shift() != 'installed') {
			remoteAppPluginPopinShow();
		} else {
			remoteAppPluginPopinHide();
		}
	}
	
	try {
		remoteAppXhr = new XMLHttpRequest();
	} catch(e) {
		try {
			remoteAppXhr = new ActiveXObject('MSXML2.XMLHTTP');
		} catch(e) {
			try {
				remoteAppXhr = new ActiveXObject('Microsoft.XMLHTTP');
			} catch(e) { }
		}
	}

	if (window.server != undefined && window.server != '' && window.server != '127.0.0.1' && window.server != 'localhost') { remoteapp2_server = window.server; }
	if (window.port != undefined && window.port != '') { remoteapp2_port = window.port; }
	if (window.serverownport != undefined && window.serverownport != '') { remoteapp2_port = window.serverownport; }
	if (window.user != undefined && window.user != '') { remoteapp2_user = window.user; }
	if (window.pass != undefined && window.pass != '') { remoteapp2_psw = window.pass; }
	if (window.domain != undefined && window.domain != '') { remoteapp2_domain = window.domain; }
	if (window.cmdline != undefined && window.cmdline != '') { remoteapp2_apppath = window.cmdline; }
	
	if (remoteapp2_server == '') { remoteapp2_server = remoteAppUrl; }
	remoteapp2_server = remoteapp2_server.indexOf(':') > - 1 ? remoteapp2_server.substr(0, remoteapp2_server.indexOf(':')) : remoteapp2_server; // remove port (if present)
	
	if (remoteAppUrl.indexOf(':') < remoteAppUrl.indexOf('/~~')) { remoteapp2_server = remoteAppUrl; } // do not remove port/appserver for AssignedServers (srv:PORT/~~XX) so ConnectionClient connects directly to the targeted Application Server
	
	remoteAppPostBody = '&server=' + encodeURIComponent(remoteapp2_server) + '&port=' + encodeURIComponent(remoteapp2_port);
	remoteAppPostBody += '&user=' + encodeURIComponent(remoteapp2_user) + '&psw=' + encodeURIComponent(remoteapp2_psw) + '&domain=' + encodeURIComponent(remoteapp2_domain);
	remoteAppPostBody += '&apppath="' + encodeURIComponent(remoteapp2_apppath) + '"';
	remoteAppPostBody += '&speed=' + encodeURIComponent(remoteapp2_speed);
	remoteAppPostBody += '&wallp=' + encodeURIComponent(remoteapp2_wallp) + '&seamless=' + encodeURIComponent(remoteapp2_seamless) + '&remoteapp=' + encodeURIComponent(remoteapp2_remoteapp);
	remoteAppPostBody += '&color=' + encodeURIComponent(remoteapp2_color) + '&full=' + encodeURIComponent(remoteapp2_full) + '&width=' + encodeURIComponent(remoteapp2_width) + '&height=' + encodeURIComponent(remoteapp2_height) + '&scale=' + encodeURIComponent(remoteapp2_scale) + '&smartsizing=' + encodeURIComponent(remoteapp2_smartsizing) + '&dualscreen=' + encodeURIComponent(remoteapp2_dualscreen) + '&span=' + encodeURIComponent(remoteapp2_span);
	remoteAppPostBody += '&disk=' + encodeURIComponent(remoteapp2_disk) + '&selectdisk="' + encodeURIComponent(remoteapp2_selectdisk) + '"';
	remoteAppPostBody += '&printer=' + encodeURIComponent(remoteapp2_printer) + '&preview=' + encodeURIComponent(remoteapp2_preview) + '&default=' + encodeURIComponent(remoteapp2_default) + '&defaultsystem=' + encodeURIComponent(remoteapp2_defaultsystem) + '&select=' + encodeURIComponent(remoteapp2_select) + '&pagescaling=' + encodeURIComponent(remoteapp2_pagescaling);
	remoteAppPostBody += '&com=' + encodeURIComponent(remoteapp2_com) + '&smarcard=' + encodeURIComponent(remoteapp2_smartcard) + '&serial=' + encodeURIComponent(remoteapp2_serial) + '&usb=' + encodeURIComponent(remoteapp2_usb) + '&usbfx=' + encodeURIComponent(remoteapp2_usbfx) + '&sound=' + encodeURIComponent(remoteapp2_sound) + '&playremotesound=' + encodeURIComponent(remoteapp2_playremotesound) + '&directx=' + encodeURIComponent(remoteapp2_directx);
	remoteAppPostBody += '&alttab=' + encodeURIComponent(remoteapp2_alttab) + '&firewall=' + encodeURIComponent(remoteapp2_firewall) + '&localtb=' + encodeURIComponent(remoteapp2_localtb) + '&lock=' + encodeURIComponent(remoteapp2_lock) + '&rdp5=' + encodeURIComponent(remoteapp2_rdp5) + '&reset=' + encodeURIComponent(remoteapp2_reset);
	remoteAppPostBody += '&gatewayhostname=' + encodeURIComponent(remoteapp2_gatewayhostname) + '&gatewayusagemethod=' + encodeURIComponent(remoteapp2_gatewayusagemethod);
	remoteAppPostBody += '&useasrdg=' + encodeURIComponent(remoteapp2_useasrdg);
	remoteAppPostBody += '&ploadbalancing=' + encodeURIComponent(getCurrentUrlPort());
	
	remoteAppProtocol = 'remoteapp';
	if (window.location.protocol == 'https:') {
		remoteAppProtocol = 'remoteapps';
	}
	remoteAppId = hex_sha1(remoteapp2_server + remoteapp2_port + remoteapp2_user + remoteapp2_psw + remoteapp2_domain + remoteapp2_apppath + Math.floor(Math.random()*9999999) + new Date().getTime());

	remoteAppXhr.onreadystatechange = function() {
		if (remoteAppXhr.readyState==4 && remoteAppXhr.status==200) {
			location.replace(remoteAppProtocol + '://' + remoteAppUrl + '/' + remoteAppId);
		}
	};

  reverseProxyServerName = remoteapp2_server.indexOf('/~~') > - 1 ? remoteapp2_server.substr(remoteapp2_server.indexOf('/~~')) : '';
  
	remoteAppXhr.open('POST', location.origin + reverseProxyServerName + '/cgi-bin/hb.exe?action=remoteapppost', true);
	remoteAppXhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	remoteAppXhr.send('action=remoteapppost&id=' + remoteAppId + remoteAppPostBody);

  if (document.getElementById("Editbox2") != null) {
    document.getElementById("Editbox2").value = "";
  }
}

function getCurrentUrlPort() {
  if (location.port !== '') {
    return location.port;
  }
  else if (location.protocol == 'https:') {
    return 443;
  }
  else {
    return 80;
  }
}

function remoteAppDownloadPlugin() {
	d = new Date();
    d.setTime(d.getTime() + (4*365*24*60*60*1000));
    document.cookie = 'remoteappplugin=installed; expires=' + d.toUTCString() + '; path=/';
	
	location.href = 'RemoteAppClient/Setup-RemoteAppClient.exe';
}

function remoteAppPluginPopinShow() {
	if (remoteapp2_showpopin) {
		// Check Cookie
		if (remoteapp2_cookiecheck) {
			remoteAppCookie = '; ' + document.cookie;
			remoteAppCookieParts = remoteAppCookie.split('; remoteappplugin=');
			if (remoteAppCookieParts.length != 2 || remoteAppCookieParts.pop().split(';').shift() != 'installed') {
				// Show Popin
				if (document.getElementById("divcenter_remoteapp2install")) {
					document.getElementById("divcenter_remoteapp2install").style.display = "block";
				}
			}
		}
	}
}

function remoteAppPluginPopinHide() {
	if (document.getElementById("divcenter_remoteapp2install")) {
		document.getElementById("divcenter_remoteapp2install").style.display = "none";
	}
}

// SHA1 part start
var hexcase = 0;
var b64pad  = "";
function hex_sha1(s) { return rstr2hex(rstr_sha1(str2rstr_utf8(s))); }
function b64_sha1(s) { return rstr2b64(rstr_sha1(str2rstr_utf8(s))); }
function any_sha1(s, e) { return rstr2any(rstr_sha1(str2rstr_utf8(s)), e); }
function hex_hmac_sha1(k, d) { return rstr2hex(rstr_hmac_sha1(str2rstr_utf8(k), str2rstr_utf8(d))); }
function b64_hmac_sha1(k, d) { return rstr2b64(rstr_hmac_sha1(str2rstr_utf8(k), str2rstr_utf8(d))); }
function any_hmac_sha1(k, d, e) { return rstr2any(rstr_hmac_sha1(str2rstr_utf8(k), str2rstr_utf8(d)), e); }
function sha1_vm_test() {
  return hex_sha1("abc").toLowerCase() == "a9993e364706816aba3e25717850c26c9cd0d89d";
}
function rstr_sha1(s) {
  return binb2rstr(binb_sha1(rstr2binb(s), s.length * 8));
}
function rstr_hmac_sha1(key, data) {
  var bkey = rstr2binb(key);
  if(bkey.length > 16) bkey = binb_sha1(bkey, key.length * 8);
  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++) {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }
  var hash = binb_sha1(ipad.concat(rstr2binb(data)), 512 + data.length * 8);
  return binb2rstr(binb_sha1(opad.concat(hash), 512 + 160));
}
function rstr2hex(input) {
  try { hexcase } catch(e) { hexcase=0; }
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var output = "";
  var x;
  for(var i = 0; i < input.length; i++) {
    x = input.charCodeAt(i);
    output += hex_tab.charAt((x >>> 4) & 0x0F)
           +  hex_tab.charAt( x        & 0x0F);
  }
  return output;
}
function rstr2b64(input) {
  try { b64pad } catch(e) { b64pad=''; }
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var output = "";
  var len = input.length;
  for(var i = 0; i < len; i += 3) {
    var triplet = (input.charCodeAt(i) << 16)
                | (i + 1 < len ? input.charCodeAt(i+1) << 8 : 0)
                | (i + 2 < len ? input.charCodeAt(i+2)      : 0);
    for(var j = 0; j < 4; j++) {
      if(i * 8 + j * 6 > input.length * 8) output += b64pad;
      else output += tab.charAt((triplet >>> 6*(3-j)) & 0x3F);
    }
  }
  return output;
}
function rstr2any(input, encoding) {
  var divisor = encoding.length;
  var remainders = Array();
  var i, q, x, quotient;
  var dividend = Array(Math.ceil(input.length / 2));
  for(i = 0; i < dividend.length; i++) {
    dividend[i] = (input.charCodeAt(i * 2) << 8) | input.charCodeAt(i * 2 + 1);
  }
  while(dividend.length > 0) {
    quotient = Array();
    x = 0;
    for(i = 0; i < dividend.length; i++) {
      x = (x << 16) + dividend[i];
      q = Math.floor(x / divisor);
      x -= q * divisor;
      if(quotient.length > 0 || q > 0)
        quotient[quotient.length] = q;
    }
    remainders[remainders.length] = x;
    dividend = quotient;
  }
  var output = "";
  for(i = remainders.length - 1; i >= 0; i--)
    output += encoding.charAt(remainders[i]);
  var full_length = Math.ceil(input.length * 8 / (Math.log(encoding.length) / Math.log(2)))
  for(i = output.length; i < full_length; i++)
    output = encoding[0] + output;
  return output;
}
function str2rstr_utf8(input) {
  var output = "";
  var i = -1;
  var x, y;
  while(++i < input.length) {
    x = input.charCodeAt(i);
    y = i + 1 < input.length ? input.charCodeAt(i + 1) : 0;
    if(0xD800 <= x && x <= 0xDBFF && 0xDC00 <= y && y <= 0xDFFF) {
      x = 0x10000 + ((x & 0x03FF) << 10) + (y & 0x03FF);
      i++;
    }
    if(x <= 0x7F)
      output += String.fromCharCode(x);
    else if(x <= 0x7FF)
      output += String.fromCharCode(0xC0 | ((x >>> 6 ) & 0x1F),
                                    0x80 | ( x         & 0x3F));
    else if(x <= 0xFFFF)
      output += String.fromCharCode(0xE0 | ((x >>> 12) & 0x0F),
                                    0x80 | ((x >>> 6 ) & 0x3F),
                                    0x80 | ( x         & 0x3F));
    else if(x <= 0x1FFFFF)
      output += String.fromCharCode(0xF0 | ((x >>> 18) & 0x07),
                                    0x80 | ((x >>> 12) & 0x3F),
                                    0x80 | ((x >>> 6 ) & 0x3F),
                                    0x80 | ( x         & 0x3F));
  }
  return output;
}
function str2rstr_utf16le(input) {
  var output = "";
  for(var i = 0; i < input.length; i++)
    output += String.fromCharCode( input.charCodeAt(i)        & 0xFF,
                                  (input.charCodeAt(i) >>> 8) & 0xFF);
  return output;
}
function str2rstr_utf16be(input) {
  var output = "";
  for(var i = 0; i < input.length; i++)
    output += String.fromCharCode((input.charCodeAt(i) >>> 8) & 0xFF,
                                   input.charCodeAt(i)        & 0xFF);
  return output;
}
function rstr2binb(input) {
  var output = Array(input.length >> 2);
  for(var i = 0; i < output.length; i++)
    output[i] = 0;
  for(var i = 0; i < input.length * 8; i += 8)
    output[i>>5] |= (input.charCodeAt(i / 8) & 0xFF) << (24 - i % 32);
  return output;
}
function binb2rstr(input) {
  var output = "";
  for(var i = 0; i < input.length * 32; i += 8)
    output += String.fromCharCode((input[i>>5] >>> (24 - i % 32)) & 0xFF);
  return output;
}
function binb_sha1(x, len) {
  x[len >> 5] |= 0x80 << (24 - len % 32);
  x[((len + 64 >> 9) << 4) + 15] = len;
  var w = Array(80);
  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;
  var e = -1009589776;
  for(var i = 0; i < x.length; i += 16) {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    var olde = e;
    for(var j = 0; j < 80; j++) {
      if(j < 16) w[j] = x[i + j];
      else w[j] = bit_rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
      var t = safe_add(safe_add(bit_rol(a, 5), sha1_ft(j, b, c, d)),
                       safe_add(safe_add(e, w[j]), sha1_kt(j)));
      e = d;
      d = c;
      c = bit_rol(b, 30);
      b = a;
      a = t;
    }
    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
    e = safe_add(e, olde);
  }
  return Array(a, b, c, d, e);
}
function sha1_ft(t, b, c, d) {
  if(t < 20) return (b & c) | ((~b) & d);
  if(t < 40) return b ^ c ^ d;
  if(t < 60) return (b & c) | (b & d) | (c & d);
  return b ^ c ^ d;
}
function sha1_kt(t) {
  return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
         (t < 60) ? -1894007588 : -899497514;
}
function safe_add(x, y) {
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}
function bit_rol(num, cnt) {
  return (num << cnt) | (num >>> (32 - cnt));
}
// SHA1 part end
