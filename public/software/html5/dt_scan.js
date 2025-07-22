(function() {
  W.DT_DecoderData_separator = " "; //just use some separator to distinguish strings or set own.
  W.DT_handlescreensizebug = true; //set to false, if want to disable, the y-pixel size will be extracted from height if outerHeight equals to screen height
  W.PendingRuntimeInfoRequest = false;

  W.special_onload = function() { 
   try { 
     if(W.io && W.io.util.ios && W.DT_handlescreensizebug && window.outerHeight == screen.height) { 
      W.margrighty = Math.round(window.innerHeight - (window.innerHeight / (480 / 425)));
      if(top != self) { W.margrighty += 4; }
     }
     if(window.DT_SetSupportedOrientationRequest) {
       try { DT_SetWebViewScrollingRequest(false); } catch(dg) { }
     }
   } catch(dn) { } 
  };
})();

function DT_DecoderDataResponse(decoderData, rawDecoderData) {
   try { W.sendScannerText(decoderData + W.DT_DecoderData_separator); } catch(er) { }
};
   
function DT_CaptuvoDecoderSerialNumberResponse(serialNumber) {
   try { W.sendScannerText(serialNumber + W.DT_DecoderData_separator); } catch(er) { }     
};

function DT_AlertBoxResponse(idNumber, buttonTitle) { };

function DT_DeviceConnectionEventResponse(boolConnectedStatus,deviceName) { };

function DT_BrowserStatusResponse(browserReadyStatus)  {
     if(browserReadyStatus) {
       W.PendingRuntimeInfoRequest = true;
       DT_RuntimeInformationRequest();
       DT_DecoderHardwarePowerRequest(true);
       DT_MSRHardwarePowerRequest(true);
       DT_SetStatusBarDisplayRequest(true);
       DT_SetAutoLockRequest(true);
       DT_SetSupportedOrientationRequest(true, true, true);
     } else {
       return false;
     }
};

function DT_RuntimeInformationResponse(appVersion,displayName,executableName,bundleID,iOSVersion,iOSPlatform,iOSDeviceIdentifier,platformVersion,platformBuildDate,platformSVNRevision,license,mfiData)  {
  if(W.PendingRuntimeInfoRequest) { W.PendingRuntimeInfoRequest = false; return false; } 
};

function DT_MSRDataResponse(msrData,rawMSRData) { };
function DT_LocationResponse(latitude, longitude, precision) { };
function DT_DecoderHardwarePowerReadyResponse() { };
function DT_MSRHardwarePowerReadyResponse() { };
function DT_BatteryStatusResponse(batteryStatus, deviceName) { };
function DT_ChargeStatusResponse(chargeStatus, deviceName) { };
function DT_ApplicationDidEnterBackground() { };
function DT_ApplicationWillEnterForeground() { };
function DT_ApplicationWillResignActive() { try { DT_DecoderHardwarePowerRequest(false); } catch(dg) { } };
function DT_ApplicationDidBecomeActive() { try { DT_BrowserStatusRequest(); } catch(dg) { } };