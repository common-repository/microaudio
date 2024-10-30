// JavaScript for microAudio
// Christopher O'Connell, 2010
// http://compu.terlicio.us/

// First, implment minimum vars
var ma = {
	url:"{[$location]}",
	filekey:"{[$key]}",
	files:{'bootstrap':'microAudio.bootstrap',},
	debug:{[$debug]},
};

// Second, set logging based on debugging
ma.log = function(string) {
	if(ma.debug) console.log(string);
};

// Third, get the onload event, and bootstrap
onload = function() {
	ma.log("Loading microAudio bootstrapper . . .");
	ma.loadJs(ma.url + "js/" + ma.files.bootstrap + "-" + ma.filekey + ".js",function(){ma.bootstrap();});
};

// Loads a js file
ma.loadJs = function(url,c) {
	ma.log("Requesting file " + url + " with callback " + c);
	var s=document.createElement("script");
	s.src=url;
	s.type="text/javascript";
	var h = document.getElementsByTagName("head")[0];
	if(h) {
		if(c !== null) {
			var lc = function() {
				if(this.readyState == 'complete' || this.readyState == 'loaded') c();
			}
				s.onreadystatechange = lc;
				s.onload = c;
		}
		h.appendChild(s);
	}
};