//verion 6.0

window.server_follow = false;
var winnamefull = window.name;
var stopexec = false; 

function resetWinname() { try { if(window.winnamefull && window.winnamefull.length > 0 && window.name != window.winnamefull) { window.name = window.winnamefull; } } catch(e) { } }

function forFromFrame() {
  var tolook = "fromfails";
  var windowwinname = window.location.href.indexOf(tolook);
  if(windowwinname > -1) { 
   var realname = window.name;
   try {
     var fz = 9;
     if(window.location.href.indexOf("?" + tolook) > -1) { windowwinname--; fz++; }
     var vkor = window.location.href.substring(windowwinname + fz);
     if(window.name == realname) { window.name = vkor; } else { window.forFromFrame(); return; }
     window.stopexec = true; window.onload = function() { }
     window.location.replace(window.location.href.substring(0, windowwinname));
     try { if(vkor != window.name) { window.name = vkor; } } catch(e) { } 
   } catch(ex) { }
  }
} window.forFromFrame();

function onFollowServer() {
   if(window.server_follow && !window.stopexec) { 
     var myxhrport = document.location.port;
     if(myxhrport.length < 1 || myxhrport < 1) { 
      myxhrport = 80; 
      if(document.location.protocol.indexOf('https') > -1) { myxhrport = 443; } 
     }
     var prt = myxhrport || 80;
     var realserv = window.location.hostname + ":" + prt;
     if(window.server.length < 1) { return; }
     if(window.webport && window.webport.length > 0 && window.webport > 0) { prt = window.webport; }
     var myserv = window.server + ":" + prt;
     if(realserv == myserv) { return; }
     window.resetWinname();
     var secerr = window.location.search;
     if(secerr == "") { secerr = "?"; }
     secerr += "fromfails" + window.name; 
     var newlocation = (document.location.protocol.indexOf('https') > -1 ? "https" : "http") + "://" + myserv + window.location.pathname + secerr; 
     window.location.replace(newlocation);
     window.resetWinname();
   }
}

function checkDomainToServer() {
     var myxhrport = document.location.port;
     if(myxhrport.length < 1 || myxhrport < 1) { 
      myxhrport = 80; 
      if(document.location.protocol.indexOf('https') > -1) { myxhrport = 443; } 
     }
     var prt = myxhrport || 80;
     var realserv = window.location.hostname + ":" + prt;
     if(window.server.length < 1) { return false; }
     if(window.webport && window.webport.length > 0 && window.webport > 0) { prt = window.webport; }
     var myserv = window.server + ":" + prt;
     if(realserv == myserv) { return false; }
     return true;
}

function checkWithInterval(mya) {
  setTimeout(function() {
    if(!mya.isalready) { 
      if(mya.status < 2) { 
        window.checkWithInterval(mya);
      } else if(mya.status == 3) {
        try { mya.onerror(); } catch(fs) { }
      } else { try { mya.onload(); } catch(fs) { } }
    } 
  }, 300);
}

function checkServerThird() { 
   var w = window;
   var l = w.location;
   var s = w.server;
   var d = s.indexOf("~~");
   if(d == 0 || d == 1) { 
     w.server = "";
     if(d == 0) { s = " " + s; }
     s = "/" + s.substring(1);
     if(l.pathname.indexOf(s) != 0) { 
       w.stopexec = true;
       w.resetWinname();
       l.replace(l.href.replace(l.pathname, s + l.pathname));
       w.resetWinname();
       return true;
     }
   }
   if(l.pathname.indexOf("~~") == 1) { w.server = ""; }
   return false;
}

function loadFixJavaError(bl) {
  if(bl.indexOf("https://") == 0) {
    var mya = document.getElementById("myappletid");
    if(mya) {
       mya.isalready = 0;
       mya.onload = mya.onstop = function() { mya.isalready = 1; mya.onload = mya.onstop = mya.onerror = function() { }; }
       mya.onerror = function() {
         mya.onload = mya.onstop = mya.onerror = function() { }
         if(!mya.isalready) { 
           mya.isalready = 2;
           var javaappdiv=document.getElementById("javaappdiv");
           if(javaappdiv) { 
             var blnew = bl.substring(8);
             var bl1 = blnew.indexOf("/");
             if(bl1 > -1) { 
               var blnew1 = blnew.substring(0, bl1);
               var blnew2 = blnew.substring(bl1);
               bl1 = blnew1.indexOf(":");
               if(bl1 > -1) { 
                  blnew = "http://" + blnew;
               } else { 
                  blnew = "http://" + blnew1 + ":443" + blnew2;                  
               }
               var inht = window.inhalttoadd;
               while(inht.indexOf(bl) > -1) { inht = inht.replace(bl, blnew); }
               window.javasslerror = window.compatmode = true;
               javaappdiv.innerHTML = inht;
             }
           }
         }
       }
      window.checkWithInterval(mya);
    }
  }
}
///////////
var portrefer = false;

function mainPortalInit() {
 var tosplit = "";

 var waserrored = false;
 if(window.name != "") {
  try { tosplit = unescape(jsdecode64(window.name.replace(/_/g,'='))); } catch(e) { waserrored = true; } 
 } else { waserrored = true; }

 if(window.opener != null && waserrored) {
  try {
   if(window.opener != null && window.opener.window.opforfalse !== undefined) {
     tosplit = unescape(jsdecode64(window.opener.window.opforfalse.replace(/_/g,'='))); 
   } else { return false; }
  } catch(e) { return false; } 
 }
 if(tosplit.indexOf("randomnum") > -1) { 
  try { eval(tosplit); } catch(e) { return false; }  
 } else {
  return false;
 }
 window.portrefer = true;
 return true;
}

function jwtsclickLinkBefore(tosplit, documhtml) {
 var randm=Math.floor(Math.random()*1000);
 window.opforfalse = jsencode64(escape("var randomnum = " + randm + ";" + tosplit)).replace(/=/g, '_');
 return documhtml + "?" + 1;
}

function jwtsclickLinkAfter() {
 var documhtml = document.location.pathname.split("/");
 documhtml = documhtml[documhtml.length - 1];
 var tosplit = "";

 var waserrored = false;
 if(window.name != "") {
  try { tosplit = unescape(jsdecode64(window.name.replace(/_/g,'='))); } catch(e) { waserrored = true; } 
 } else { waserrored = true; }

 if(window.opener != null && waserrored) {
  try {
   if(window.opener != null && window.opener.window.opforfalse !== undefined) {
     tosplit = unescape(jsdecode64(window.opener.window.opforfalse.replace(/_/g,'='))); 
   } else { return false; }
  } catch(e) { return false; } 
 }
 try { eval(tosplit); } catch(e) { return false; }  
 try { window.onFollowServer(); } catch(zk) { }  
 return true;
}

function jscreateCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function jsreadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

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
        base64 = base64  +
            jsb64array.charAt(enc1) +
            jsb64array.charAt(enc2) +
            jsb64array.charAt(enc3) +
            jsb64array.charAt(enc4);
        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    } while (i < input.length);
    return base64;
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
            if (enc != null) {
                if (end > start) {
                    utftext += string.slice(start, end);
                }
                utftext += enc;
                start = end = n + 1;
            }
        }
        if(end > start) {
            utftext += string.slice(start, stringl);
        }
        return utftext;
};