//channels to be registered for java applet inside browser mode channel;channel2;channel3 and so on.
//channels = "CHANNEL";
//
channels = ""; 

//create function with channel name 
//as example: 
//function on_call_CHANNEL(msg) { alert(msg); }
//or if channels = "CHANNEL;RDPCHAN";
//function on_call_RDPCHAN(msg) { alert(msg); } 
//
//to send text to channel use: send_to_channel("RDPCHAN", "text_to_send");
//or to format it as UTF-16LE use
//send_to_channel("RDPCHAN%UTF-16LE", "text_to_send");
//

function call_on_connect() {
 //alert("connected");
}

function call_on_disconnect() {
 //alert("disconnected");
}
