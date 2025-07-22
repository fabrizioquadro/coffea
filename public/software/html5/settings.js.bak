W.applysplashscreencustomizations = function(){var e=document.createElement('script');e.type='text/javascript',e.async=!1,e.src='html5/splash.js';var t=!1;try{t=document.getElementsByTagName('head')[0]}catch(e){}!t&&document.head&&document.head.appendChild&&(t=document.head),t.appendChild(e)}(); //this event is required for the customization of creative's theme splashscreen

W.user = "";
W.pass = "";
W.server = "";

W.portvnc = "5900"; //performs well with DFMirage and TightVNC Server

W.domain = ""; //domain, if present
W.lang = "as_browser"; //initial values "as_browser" or "as_gateway" or own like "de_de", "en_us", "fr_fr"

W.fallbacklangtable = "en_us"; //fallback lang table if char not found in main language table

W.autolocalhost = true; //or false to disable it, if webserver is same as in browser address, assume it is localhost, disable if experiencing issues on 127.0.0.1

W.clipboard = "yes"; //or "no"
W.clipansiconvert = ""; //conversation table for ansi clip text, no value means default, auto means default system or windows-1252

W.clippasteimage = true; //or false to disable it, paste images into clipboard if supported

W.clippasteimagewhiletext = true; //or false to avoid pasting image while text in clipboard available at same time.

W.showClipMenu = "yes"; //yes or "no"
W.filetransfer = "yes"; //yes or "no"
W.filelisting = "all"; //no, all, pc, mobile, ios

W.filehtmluploadbutton = "all"; //no, all, pc, mobile, ios - is also fast access upload button for quick file upload to rdp session

W.filerdpuploadbutton = "all"; //no, all, pc, mobile, ios - is also rdp session side upload to user's browser

W.disabledraganddrop = "no"; //by yes the files can not be uploaded by drag and drop anymore

W.disableopenonclient = "no"; //by yes disable open on client via //tsclient/WebFile/openonclient/

W.disablechanneleval = "no"; //by yes disable JavaScript code execution sent from rdp session via WebIme channel!

W.fake_file_extension = false; //if set to true and file dialog forced, this will add fake file extension on downloadable files

W.dropboxonnewfile = 2; //when you create new file inside rdp session,
                      //2 try to download file/refresh dropbox

W.filedropfinalclose = "yes"; //by yes close file manager on finished upload when file manager was in hidden state previously,

W.fileAddGetStamp = "yes"; //by yes add time stamp to downloadable file to avoid browser caching.

W.f5_for_refresh = true; //send F5 after file upload to refresh programs listening to F5 button

W.fileviaframe = true; //to avoid popups on Websockets files will be downloaded via iframe, except //tsclient/WebFile/openonclient/

W.openonclipblur = false; //when set to true and clipboard data arrived, the changing focus of window will popup the window to access clipboard

W.rdpfilesdelete = "yes"; //or "no" delete user files stored on the gateway after exiting rdp session

W.attachloginonfolder = "no"; //to access files remotely the login name can be attached to unique folder for admin purpose

W.sharedfolder = "no"; //or yes.. include sharedfolder to be accessible by every user

W.xhrreverse = false; //use xhr instead websockets, if recognized the usage of reverse proxies in environment, it may help 50/50,
                   //this option does not ensure the auto recognizing of the reverse proxy usage by browser

W.disablewebsocket = false; //IMPORTANT, disabling Websockets is very dangerous due instability of used XHR-polling instead
                          //Do not touch, if Websocket connection can be established! Xhr-polling helps sometimes by reverse proxies

W.disablexhrclients = false; //xhr clients will be disabled if browser supports websockets connection

W.forcesslforxhr = false; //force ssl by xhr-polling for better stability on long ping distances
                       //so if user calls http page, it reloads the page to https alternative on same port
                       //after https://server.com:80/software/html5.html

W.forcealways_ssl = false; //if set to true, the page will always forward to https address enforcing secured connection (on same port)

W.startfolder = "software"; //this variable will be changed automatically for subpath processing

W.forcePcMouse = false; //on mobile devices will be active PC mouse mode, screen touchmode will be deactivated

W.showinputbutton = true; //for fast input access of mobile keyboard show input field when user enters an

W.InputButtonPosNew = { "width": 48, "height": 33, "top": 27, "left": -5, "topOnIcon": -1, "leftOnIcon": -1, "topMenuOld": 28, "leftMenuOld": -40, "border": "1px solid #808080", "borderState": { blur: "10px", focus: "5px" }, "innerImage": true, "innerImgOpacity": "60", "innerImgZoom": true }; //Input button specific values

W.show_button_prefer_soft = true; //When software keyboard activated, prefer it in place of native keyboard

W.fastButtonSoftNative = true; //When software keyboard activated and visible, fast input button calls native keyboard

W.autoKeyboardPopup = 0;   //When true, click in editable field calls software keyboard (by native keyboard only on second click). When = 2 keyboard will be always called when cursor already editable!
W.setCookiesViaIframe = "jwts"; //subfolder name for cookies that are stored for separate subfolder and are not available directly to other participants on domain, or false to disable

W.rdpCookieSet = ""; //rdp cookie to be passed for html5-to-rdp connection. Should usually begin with /~~ for passing balanced subserver.

W.showmainmenu = "all";      //possible all, no, mobile > when set to mobile, the menu is displayed only on mobile devices

W.newstylemenu = true;       //new style menu (works on Native Android browser partially only -> newstyleforceonbadbrowsers)

W.newstyleforceonbadbrowsers = false; //not recommended, when set to true, the Android 2.* mobile will reuse it too

W.newMenuOrder = { "keyboard": 0, "leftDouble": 1, "rightClick": 2, "move": 3, "keyb_pic": 4, "printer": 5, "fileTransfer": 6, "clipboard": 7, "fileTransfup": 8, "fileTransfall": 9, "full_screen": 10, "ctrlaltdel": 11, "zoom": 12 }; //Default new menu icon order

W.nowarnings = false; //when set to true, you will get no warnings at all, or set to false to inform about

W.followcursor = true; //when set to true, the round menu will follow cursor on mobile browsers

W.imgsizemobile = 9; //=1/9 of screen size (size of mouse menu)
W.imgsizepc = 17;    //=1/17 of screen size
W.imgswitchpos = 75; //initial position of mouse icon on 75% of left side, where supported

W.actionnewposition = 50; //50% position of action button on screen for new design

W.imgswitchposnewdesign = 90; //initial position of mouse notify icon(moving, clicking etc) by new design for compatibility purpose

W.ctraltdel = "no";      //possible all, no, mobile -> handles if the menu entry should initiate the action Ctrl+Alt+Del, or mobile_button, all_button -> extra button in top menu

W.winKeyCombo = "no";      //possible all, no, mobile -> handles if the menu entry should initiate the action Win+R, Win+A, Win+Q, Win+S, Win+X, Win+F, Win+E, Win+U

W.windowsKey = "yes";      //possible all, no, mobile -> Windows key enabled by default, set to "no" to disable it

W.imgswitchpospersistent = true; //when set to true, after moving the mouse icon the position will be saved persistently

W.imgtomenuratio = 1.3;   //the size of menu images in correlation to mouse image on mobile devices
W.imgtomenuratiopc = 1.1; //the size of menu images in correlation to mouse image on pc devices
W.imgtomenurationewdesign = 1.2;   //the size of round icon in correlation to "imgtomenuratio" for new design

W.onkeybnewdesignhide = true; //to avoid static toolbar bug in iOS Safari when entering keyboard mode on new design the toolbar gets auto hidden

W.imgmenuopacity = "95"; //round mouse icon opacity default 100%

W.moveIconWithScreen = false; //move round mouse and cursor with screen when panning.

W.imgmenuresize = true; //when set to true, the round menu-icon will adjust its size to device view

W.imgcurtomenutop = 2.8; //cursor round mouse image position from top
W.imgcurtomenuleft = 2.8; //cursor round mouse image position from left

W.menucenterdouble = false; //when set to true, short touch inside mouse menu produces double click

W.showprintbutton = "all"; //mobile, all, no
W.lefttopasmiddlepoint = true; //the left top corner of mouse pad should act as screen or as pad middle point
W.showmousescroll = "yes"; //yes or no, refers only to mobile version old style, by default off on pc (mouse scroll icon in menu)
W.showmouseoncebegin = "new"; //show mouse icon on session entering only once: "both", "old", "new", "none" (mobile browsers only)
W.addspacebymousepad = true; //the body view gets extra size added.

W.addSpaceDimensions = { "width": 102, "height": 102, "widthKeyb": 105, "heightKeyb": 145 }; //extra space for scrolling and extra bigger size when keyboard displayed
W.cmdline = ""; //initial parameter

W.clickoutofcursor = true; //or false, when set to true the touching out of cursor area will move mouse + produce mouse down mouse up action on end
                         //touching on cursor area will always produce mouse down mouse up action. Fast double touches are not involved

W.extrakeypadhandling = 2; //0 disable, 1 keypad without conversion, 2 keypad without conversion + auto numlock activation

W.handlecapslock = false; //if true, the capslock activated out of focus may be so recognized

W.prefer_caps_num_new = true; //new way of handling for pressed capslock/numlock states, should be not touched. 

W.prefer_old_keyb_sync = false; //should not be used, if no issues, usually used new way to sync capslock/numlock on server side instead keyboard events

W.logintoconsole = false; //win2003 etc.
W.sendidlemovements = false; //send idle movements to avoid disconnection

W.theming_level = 3;    //0 = disable all, 3 = default, 4 = menu animations(costs drastic increased traffic consumption, avoid it!)

W.default_color = 16; //15, 16, 24, 32
                      //to enforce 24 or 32 on mobile devices, set 32:32 or 24:24, first value for PC, second for mobile browsers

W.forceoldprintstyle = "all"; //no, all, pc, mobile, ios

W.forceprinttext = "<p><strong><font color=\"#68838B\"><center>&nbsp;Action&nbsp;complete&nbsp;-&nbsp;click&nbsp;to&nbsp;view&nbsp;PDF&nbsp;<\/center><\/p>";
W.downfiletext = "<p><strong><font color=\"#68838B\"><center>&nbsp;Action&nbsp;complete&nbsp;-&nbsp;click&nbsp;to&nbsp;download&nbsp;file&nbsp;<\/center><\/p>";

W.forceoldfilestyle = "no"; //no, all, pc, mobile, ios

W.forcefiletext = "<p><strong><font color=\"#68838B\">POPUP&nbsp;BLOCKED!&nbsp;CLICK&nbsp;PLEASE!<\/font><\/strong><\/p>";

W.flipprintprot = false; //flip protocol for printing, avoid some browser connection limits
W.flipfileprot = false; //flip protocol for file download, avoid some browser connection limits

W.playsound = true; //true or false to play sound
W.MacHandleRightAlt = true; //when Mac used, extra handle right alt for correct char displaying @#

W.shortTouchMoveSwapToScroll = 0; //on mobile devices on short-touch+move by 1 will swap actions from left-mouse-hold+move to mouse-scroll, by 2 will additionally swap double-touch+move action from mouse-scroll to left-mouse-hold+move action
W.fontsmoothing = true;     //true to enable font smoothing.
W.mainscreenlayercache = "no"; //in rare case could accelerate frequently getImageData requests
W.fastpathdisabled = "yes"; //by yes disable fastpath keyboard/mouse input.
W.glyphcachedisabled = "mobile"; //disable glyph cache to accelerate drawing
W.offscreendisabled = "yes"; //disable offscreen cache to accelerate drawing
W.offscreensimulated = "no"; //when offscreen enabled, use simulated offscreen instead browser canvas
W.polylinedisabled = "yes"; //always yes, if AutoCAD makes troubles, RDP bug on Win7/2008 even with mstsc.exe
W.multirectangledisabled = "no"; //by yes, disable multirectangle
W.waitingcachelistdisabled = "no"; //by yes, disable waiting cache list
W.fastindexdisabled = "no"; //by yes, disable fastindex order
W.fastglyphdisabled = "no"; //by yes, disable fastglyph order
W.fontcachev2disabled = "no"; //by yes, disable fontcache version 2
W.multifragmentdisabled = "no"; //by yes, disable fastpath multifragment order
W.brushcachedisabled = "no"; //by yes, disable brush cache order
W.mem3bltdisabled = "yes"; //by yes, disable mem3blt order, support not yet full
W.heartbeatdisabled = "no"; //by yes, disable heart beat pdu order

W.CompatibleCanvasSimulation = true; //by no canvas support and/or non installed Flash use Canvas-Div simulation, IE5-IE8, and others.
W.playSoundFlashFallback = true; //If no one Web audio api supported, use Flash fallback, IE10-11!
W.fillBufferMax = 3; //number of prebufferred audio packets before playing

W.mouseCursorZoomFactor = 100; //resize mouse cursor and hotpoint according to zoom factor in percent
W.displayDesktopZoomFactor = 100; //send zoom-scale factor of display if supported in percent between 100-500
W.displayDeviceZoomFactor = 100; //send zoom-scale factor of device if supported in percent 100, 140 or 180
W.rdpZoomFactor = 100; //rdp session area zoom factor
W.cssNoCanvasOptimisation = false; //by true CSS will forcibly stop image optimisations on canvas by (image-rendering: crisp-edges) css value when browser supported

W.informOnBadConnection = true;  //inform about bad connection after 10 seconds of no data, connection timeout must be set

W.reconnectonresize = true; //when true, the resizing will cause reconnect enforcing new session size

W.allowdynamicresize = "all"; //when set to all and supported by server (win8, win2012 and higher) the resizing will set new session size.
W.full_screen = 0; //when 0, usual mode, 1 = full screen with fixed task bar, 2 = full screen size, by no full screen support it gets disabled, PC only.

W.showfullscreenbutton = "yes";  //show fullscreen button in top menu if supported.
W.allowOnclickFullScreen = true; //by enabled full screen support any physical mouse click will switch to full screen mode, PC only.
W.macSafariFullDisable = true; //when true, full screen will be disabled for Mac Safari browser due Safari browser security bug.
W.macSafariPrintOnload = true; //when true, call print() on printed documents displayed in iframe for Mac Safari bug handling.
W.smartfit = true; //only for PCs in RDP mode, false or 0 disables it, when set to true or 1, the html5 client will try to fit the session into screen and it will be squeezed into available area and will be resized by changing the browsers dimensions

W.mobilescroll = 0; //scrolling direction on mobile devices, 0 = default, 1 = opposite direction
W.callparentclose = false; //if page runs under iframe, call parents close on page closing

W.preferunicode = true; //by unrecognized char code map or chinese, japanese, korean etc. prefer direct unicode input.

W.disableTableConversion = false; //by true no unicode to scancode conversion used, but passed directly as unicode.

W.preferscancode = false; //Windows Chrome(or Chromium based like Opera etc.) and Windows FireFox only, on other browsers direct scancode mode will be disabled

W.swapMacCMD = true; //On Mac browsers swap CMD button, by false left_CMD = Windows_key, right_CMD = left_Ctrl, by true left CMD = left_Ctrl and right_CMD = Windows_key

W.newmenuopacity = "60"; //by default 60% opacity

W.server_follow = false; //when true, the page will reload to real server if differs allowing so direct connection

W.webport = ""; //when set, port will be preferred to follow instead recognized from main server

W.gooutofiframe = true; //if true, the client will reload to top layer

W.showZoomIframeButtons = { "out": "no", "in": "no", "out_func": "WINDOW.parent.postMessage('html5-zoom-out', '*');", "in_func": "WINDOW.parent.postMessage('html5-zoom-in', '*');" };  //the buttons will be displayed only under iframe, parent iframe has to implement message event listener for "html5-zoom-in" and "html5-zoom-out" and process those by own implementation.

W.reroutePrinterLinks = { "enabled": "no", "func": "if(WINDOW.parent) { WINDOW.parent.postMessage(caller + ':' + type + ':' + link, '*'); return false; }" };  //return true; if wished to continue default processing else printer links will not cause any future handling except messaging to parent.

W.splashonclickremove = true; //if true, the splash screen can be fast removed by onclick event

W.viewportwidth = "1024"; //initial width of session on mobile devices, height gets computed by browser. REAL MOBILE DEVICES ONLY

W.viewportminwidth = "200"; //when computed width is lesser than this value, the width will be set to wished minimum with scrollable overflowing area. REAL MOBILE DEVICES ONLY
W.viewportminheight = "200"; //when computed height is lesser than this value, the height will be set to wished minimum with scrollable overflowing area. REAL MOBILE DEVICES ONLY

W.viewportfixscale = true; //true = auto, false = disabled, or 0.1 for fixed values.

W.viewportscaleadd = 0; //negative or positive value will change scale factor.

W.dynminwidth = "0"; //when computed width is lesser than this value, the width will be set to wished minimum. PC DEVICES WITH DYNAMIC CHANNEL ONLY
W.dynminheight = "0"; //when computed height is lesser than this value, the height will be set to wished minimum. PC DEVICES WITH DYNAMIC CHANNEL ONLY

W.connectiontimeout = "40"; //between 20-160 seconds in 10 seconds step, or 0 to disable timeout.
W.connectionmessage = true; //if true, the message countdown for any new connection will be displayed

W.disconnectmessage = true; //if true, the message on disconnect will be displayed

W.send_logoff = true; //if true, by event of browser closing (if notificated by browser), the session disconnects and logoff command gets sent to session

W.printenabled = true; //if false, the printing gets disabled

W.UniversalPrinterServerDomain = false; //in rar cases can be set to true or to fixed "string", when printer server is different than localhost or 127.0.0.1

W.UniversalPrinterOwnPortLink = false; //in rar cases can be set to wished number, if printer link refers to unexpected port

W.disableprintpolling = false; //if true, this will stop to poll for new prints, requires support of print events via webime-rdp channel

W.topButtonWidth = "100%"; //top menu icons size

W.fix_iOS_screenwidthbug = true; //on iOS9 for some older iPads Apple introduced screen overflowing bug with viewport, true activates workaround

W.ienewcursor = true; //if true, the IE data url cursors activated, PC IE10+ only

W.ienewcursorForAll = true; //if true, data url cursors for all browsers activated, fixes bug with hotpoint on Chrome

W.base64onbinary = false; //if true, the binary transfer will use base64 encoding

W.emulate_tab = "mobile"; //on iOS devices the switching button for editable fields will emulate Tab button, other systems do not have such extra buttons

W.write_via_temp_file = false; //if true, the file written to rdp drive will be written firstly to temporary file before moving to final file

W.target_filecopy_behavior = 0; //if 0 increase file number enumeration, if 1 delete/overwrite old file when possible, if 2 prompt for handling. Affects only final target file copy behavior since unaffected in //tsclient/WebFile

W.frame_numbers = "80:120"; //frame tiles number low and upper limit

W.showrightclick = false; //mobile only, if true, the right click button in new menu visible

W.showmousedrag = false; //mobile only, the mouse drag button in new menu visible

W.softLanguageBar = { "device": "all", "font_size_sel": "100%", "font_color_sel": "white", "font_color_unsel": "black", "height": "45%", "width": "70px", "left": "91%", "top": "25%" }; //show language bar in menu

W.softMobileKeyboard = "mobile"; //use software emulated keyboard

W.softMKStyle = { "opacity-percent": "95", "background-color": "white", "button-color": "#DCDCDC", "button-color-press": "gray", "displayOverMouseIcon": false }; //transparency of software keyboard, background-color, button-color, display over mouse icon

W.autoScrollSoftMK = { behavior: 3, addcheck: 32, addscroll: 0 }; //by 1 screen will scroll to focus cursor against soft-keyboard, by 2 revert to previous position before scrolling, by 3 revert always to zero position, addcheck +/- added px value for position check, addscroll +/- for added px scroll value.

W.bodyBGColor = {"body":"#3A6EAF","main":"#3A6EA5"}; //example #3A6EAF, background color of html body element affecting added space color.

W.newMenuHideOnSoftKeybShow = true; //When new menu used and soft keyboard called, unroll the menu after keyboard appeared

W.newMenuHideOnClick = true; //When new menu used unroll the menu after clicking on buttons.

W.newMenuCountDown = { "pc": 7, "mobile": 15 }; //When new menu used unroll the menu after xyz seconds.

W.sendTimeZone = true; //Send browsers time offset - requests activated time zone redirection with gpedit.msc on server

W.timeZoneOffsetShift = "0:0"; //offset shift if time mismatches for summer/winter, as example -60:-1 

W.userTimeZoneNamesNew = false; //{ "z-12": [ "", "" ], "z-11": [ "", "" ], "z-10": [ "", "" ], "z-9.30": [ "", "" ], "z-9": [ "", "" ], "z-8": [ "", "" ], "z-7": [ "", "" ], "z-6": [ "", "" ], "z-5": [ "", "" ], "z-4": [ "", "" ], "z-3.30": [ "", "" ], "z-3": [ "", "" ], "z-2": [ "", "" ], "z-1": [ "", "" ], "z_0": [ "", "" ], "z+1": [ "", "" ], "z+2": [ "", "" ], "z+3": [ "", "" ], "z+3.30": [ "", "" ], "z+4": [ "", "" ], "z+4.30": [ "", "" ], "z+5": [ "", "" ], "z+5.30": [ "", "" ], "z+5.45": [ "", "" ], "z+6": [ "", "" ], "z+6.30": [ "", "" ], "z+7": [ "", "" ], "z+8": [ "", "" ], "z+8.30": [ "", "" ], "z+8.45": [ "", "" ], "z+9": [ "", "" ], "z+9.30": [ "", "" ], "z+10": [ "", "" ], "z+10.30": [ "", "" ], "z+11": [ "", "" ], "z+12": [ "", "" ], "z+12.45": [ "", "" ], "z+13": [ "", "" ], "z+14": [ "", "" ] }; 

W.timeZonesRegistryNew = "Dateline%UTC-11%Aleutian@Hawaiian%Marquesas%Alaskan@UTC-09%Pacific@UTC-08%Mountain@US Mountain%Central@Easter Island@Central America%Eastern@SA Pacific%Atlantic@Pacific SA@Venezuela%Newfoundland%Greenland@E. South America@Montevideo%UTC-02%Azores@Cape Verde%GMT@Greenwich%Romance@W. Central Africa%GTB@Kaliningrad%Russian%Iran%Caucasus%Afghanistan%West Asia%India%Nepal%Central Asia%Myanmar%Altai%China%North Korea%Aus Central W.%Korea%AUS Central@Cen. Australia@AUS Central%West Pacific@AUS Eastern@West Pacific%Lord Howe%Central Pacific%Russia Time Zone 11@New Zealand@Russia Time Zone 11%Chatham Islands%Tonga@Samoa@Tonga%Line Islands";
                      //usable with Standard Time string or without **%Romance%** or **%Romance Standard Time%**, each string represents time zone order of 39 timezones, steps from -12 to 14 inclusive -9.5, -3.5, 3.5, 4.5, 5.5, 5.75, 6.5, 8.5, 8.75, 9.5, 10.5, 12.75

W.show_fix_keyb_button = "false"; //When true, the button for pinning the soft keyboard displayed

W.maxLoudness = 0.2; //Max loudness which will be auto adjusted

W.upnDeleteDomain = true; //Delete domain if user has UPN style login

W.printclickpopup = "no"; //no, all, pc, mobile, when enabled, popup with pdf opens always initiated by onclick event, this may help to force pdf file to open in view mode instead being downloaded, as example on Mobile Chrome

W.nativerdp = "all"; //no, all, pc, mobile, when enabled, the native rdp protocol will be forced (Websockets only, xhr is too fragil for native RDP)

W.nativerdp_compression_level = 4; //0=no compression, 9=max compression, higher values are not really better

W.pageUnloadMessage = ""; //Dialog to return when page unloads
           //2. HTML standard does not distinguish between page refresh and page close action, the dialog will popup on page refresh too

W.touch3_keyboard_show = true; //Mobile only, when true, and editable cursor displayed, as example in editable field, any 3 fingers touching will call keyboard if 3 touch supported by device. When instead = true was set = 2 then keyboard will called even when outside of editable field. 

W.splashscreencontent = "<div style=\'margin: 0; padding: 0; box-sizing: border-box;\'><div class=\'container\' style=\'margin: 0; padding: 0; position: absolute; height: 100%;\'><div class=\'blur\' style=\'margin: 0; padding: 0; width: 100vw; display: flex; flex-direction: column; height: 100%; align-items: center; padding-top: 253px !important; backdrop-filter:blur(40px);\'><img style=\'margin-bottom: 30px;\' class=\'logo\' height=\'45\'><h1 style=\'width: 70%;font-family: Inter, sans-serif !important; text-align: center; font-family: Inter; font-size: 25px; font-style: normal; font-weight: 700; line-height: normal; margin-bottom: 20px;\'>Sua segurança online é importante para nós.<br />Aguarde enquanto protegemos sua conexão...</h1><img style=\'transform: translateY(24px);\' src=\'html5/imgs/spinner.gif\'></div></div></div>"; //splash screen message

W.splashscreentime = 5000; //splash screen play time

W.afterlogonsplashtime = 0; //additional splash screen play time after user logon was received, increase default W.splashsreentime value if additional time unreachable

W.onbeforesplashscreen = function() { }; //this function will be called before splashscreen load

W.onaftersplashscreen = function() { }; //this function will be called after splashscreen load

W.popupBlockedCallBack = function(link) { return false; }; //customer callback when popup blocked

W.customerScripts = { }; //example
                         //Important notice: the third part JavaScripts and any issues/incompatibilities caused by its usage are not covered by support and any help on it will be discarded!
W.customerPrintHandling = function(link) { return false; }; //customer callback for own print handling, return true or positive value to disable it. //example function like by popup blocker handling

W.customPdfHtml5Viewer = function(isMobile, isIframe) { return ""; }; //custom pdf viewer link like > pdf.js/web/viewer.html?file=  calling by function may be adapted for specified browsers

W.postponeOnload = false; //set time in milliseconds after onload event the page should continue loading

W.storageAccessGranting = 1; //by true storageAccessGranting by cross-domain scenario will be enabled for all browsers, by false disabled, and by default = 1 only enabled for Safari on Mac and iOS devices

W.fileFramePosition = { "width": "40%", "height": "45%", "right": "10px", "bottom": "10px", "opacity": ".95", "opac": ".4" }; //File frame size and file frame position relative to right and bottom side, main opacity of file frame and opacity while dropping file
  
W.clipAreaStyle = { "width": "320px", "height": "200px", "left": "40%", "top": "40%", "color": "black", "background_color": "#f5f5f5C", "opacity": ".95", "font_family": "Arial", "font_size": "14px" }; //Clipboard window style
  
W.goToLinkOnClose = ""; //if you wish to forward to specified link on close, as example "../index.html" 

W.FetchFromGlobal = [ 1, "user", "pass", "domain", "cmdline", "server", "port", "lang" ]; 
                   //[0] == 1, fetch before window.name evaluation, [0] == 2, fetch after, [0] == 0 means disable fetching

W.user_scalable = true; //mobile browsers only, by false adds to meta tag user-scalable=no, and if supported by browser that will disable pinch zoom.

W.hyperv_server_resize = true; //allow hyperv session to send screen changes, by setting to false may workaround MS bug if any experienced by virtualized Linux etc.

W.waitBeforeMouseDown = 0; //minimum time delay before pressing left mouse button after left mouse-up in milliseconds, example value 50.
W.waitBeforeMouseUp = 0; //minimum time delay before releasing left mouse-down in milliseconds, example value 50.
W.waitBeforeCharUp = 0; //minimum time delay before releasing char key in milliseconds, example value 5.
W.MouseTimeoutDbl = 100; //minimum timeout to compute double clicks.

W.scrollOverBounds = { "moveScroll": 3, "thresholdMaxX": 15, "thresholdMaxY": 15, "thresholdMinX": 5, "thresholdMinY": 5, "clickJump": 0 };     //auto screen scrolling by mouse icon screen area over edges moving, may cause unexpected behavior on different systems or browsers, use at your own risk!

W.singlelogon = false;  //use browser basic auth for single login, you may use string value instead true to pass wished realm.

W.openinsideopener = false;  //highly not recommended to use, by true if page was loaded as popup the html5 client will close it self and it will try to open the link inside page it was originally called from, also inside portal page.

W.androiddeskmoderec = true;  //recognize Chrome mobile in desktop mode to avoid loading as desktop browser.

W.timeoutDownMove = 400;  //set to 0 if not wished, by default after 400 ms any movement will fire mouse down before, mobile only.

W.maximizeshell = false; //maximize shell on logon if supported.

W.fixedWidthHeight = function() { W.preferwidth = 0; W.preferheight = 0; }(); //edit if wished to use fixed width/height on PC only, fullscreen/reconnect-on-resize/dynamic-resize get automatically disabled.

W.removeUrlSearchParams = true; //if used *html5.html?&user=xyz, try to refresh page with removed url parameters.

W.remoteAppStart = [ "", "", "" ]; //remoteapp, shell_directory, cmd_line, not yet fully implemented, enable by */webserver/settings.bin > allow_remote_app=true

W.clickQueueTracking = true; //precisely tracking of clicks on mobile devices to improve handling.

W.webIMEReadAsync = true; //try to read data by remote webime channel asynchronously.

W.envval = ""; //wished environment variable will be set by Webime channel to HTML5_ENV=**** with canonical path security check, HTML5_ENV_UNCHECKED=****, and HTML5_BASE64_UTF8_ENV=**utf8_base64_encoded** as UTF-8 chars Base64 encoded

W.shiftFuncShortcut = { "F8": true, "F9": true, "F11": true, "F12": true }; //Shift button combinations with F8=pc_to_server, F9=server_to_pc, F11=clipboard, F12=file_manager (F10 reserved by Windows).

W.shortCutsMenu = [ { "val" : "Ctrl,W,-W,-Ctrl", "desc": "Ctrl W", "shalt": "W" }, { "val" : "Ctrl,Shift,W,-W,-Shift,-Ctrl", "desc": "Ctrl Shift W", "shalt": "V" }, { "val" : "Ctrl,F4,-F4,-Ctrl", "desc": "Ctrl F4", "shalt": "F5" }, { "val" : "Alt,Shift,F4,-F4,-Shift,-Alt", "desc": "Alt Shift F4", "shalt": "F6" }, { "val" : "Ctrl,T,-T,-Ctrl", "desc": "Ctrl T", "shalt": "T" }, { "val" : "Ctrl,N,-N,-Ctrl", "desc": "Ctrl N", "shalt": "N" }, { "val" : "Ctrl,Alt,Delete,-Delete,-Alt,-Ctrl", "desc": "Ctrl Alt Del", "shalt": "Delete" }, { "val" : "Ctrl,Tab,-Tab", "desc": "Ctrl-Half Tab", "shalt": "N", "ncl": true }, { "val" : "Alt,Tab,-Tab", "desc": "Alt-Half Tab", "shalt": "M", "ncl": true }, { "val" : "Left_Windows,R,-R,-Left_Windows", "desc": "Win R", "shalt": "R" }, { "val" : "Left_Windows,A,-A,-Left_Windows", "desc": "Win A", "shalt": "A" }, { "val" : "Left_Windows,Q,-Q,-Left_Windows", "desc": "Win Q", "shalt": "Q" }, { "val" : "Left_Windows,S,-S,-Left_Windows", "desc": "Win S", "shalt": "S" }, { "val" : "Left_Windows,X,-X,-Left_Windows", "desc": "Win X", "shalt": "X" }, { "val" : "Left_Windows,F,-F,-Left_Windows", "desc": "Win F", "shalt": "F" }, { "val" : "Left_Windows,E,-E,-Left_Windows", "desc": "Win E", "shalt": "E" }, { "val" : "Left_Windows,U,-U,-Left_Windows", "desc": "Win U", "shalt": "U" } ]; //shalt=shift+alt+*, ncl=no_closing, desc=description, val=buttons_press_and_release

W.setDocuTitle = function() { try { document.title = "HTML5" } catch(b) { } }();

W.port = "3389"; //correct for localhost, please no 80/443 by localhost!!!
