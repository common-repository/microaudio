/**
 * microAudio.bootstrap.js
 */

// First add the additional vars we need to ma
ma.sidebar = {[$enable_widget]};
ma.files = {
			'jquery':'jquery-1.3.2',
			'ui':'jquery-ui-1.7.2.min',
			'sidebar':'microAudio.widget',
			'ma':'microAudio',
			'sm':'soundmanager2',
			'jplayer':'jquery.jplayer',
			'bootstrap':'microAudio.bootstrap'
			};
ma.loaded = false;
ma.jsloaded = "none";

//Load a file in the index
ma.load = function(id,c) {
	ma.loadJs(ma.url + "js/" + ma.files[id] + "-" + ma.filekey + ".js",c);
}

// Second Finish Bootstrapping
ma.bootstrap = function() {
	ma.log("Starting microAudio load sequence . . .");
	if (ma.sidebar) {
		ma.log("Loading sidebar . . .");
		ma.JqueryInit(function() {
			ma.loadJs(ma.url + "js/" + ma.files.sidebar + "-" + ma.filekey + ".js",function(){ma.WidgetInit();});
		});
	}
	
	// Prepare links (simple)
	ma.log("Preparing links . . .");
	var links = document.getElementsByTagName("a");
	for (var i = 0; i < links.length; i++) {
		if(links[i].href.indexOf('mp3') != -1) {
			links[i].className = "ma-link";
			links[i].onclick = ma.ClickInit;
		}
	}
	ma.log("microAudio setup complete . . .");
}

// Third, initialize jQuery (id needed)
ma.JqueryInit = function(callback) {
	if(ma.jsloaded == "none") {
		ma.jsloaded = "loading";
		ma.log("Loading files with key: " + ma.filekey);
		ma.loadJs(ma.url + "js/" + ma.files.jquery + "-" + ma.filekey + ".js",function(){
			ma.log("Loaded jQuery:" + ma.files.jquery + ". . . ");
			ma.loadJs(ma.url + "js/" + ma.files.ui + "-" + ma.filekey + ".js",function(){
				ma.log("Loaded jQuery UI:" + ma.files.ui + ". . . ");
				ma.loadJs(ma.url + "js/" + ma.files.sm + "-" + ma.filekey + ".js",function(){
					ma.log("Loaded Player:" + ma.files.sm + ". . . ");
					ma.jsloaded = "done";
					soundManager.onready(function(oStatus){
						if (oStatus.success) {
							ma.log('Yay, SM2 loaded OK!');	
						} else {
							ma.log('Oh snap, SM2 could not start.');
						}
						if(callback != null) callback();
					});
					soundManager.flashLoadTimeout = 750;
					soundManager.debugMode = ma.debug;
					soundManager.consoleOnly = true;
					soundManager.url = ma.url;
					soundManager.reboot();
				});
			});
		});
	} else if(ma.jsloaded == "loading") {
		setTimeout(ma.JqueryInit(callback),100);
	} else {
		if(callback != null) callback();
	}
}

//Fourth, Start loading code when we've been clicked
ma.ClickInit = function(e) {
    e = e||window.event;
    var target = e.target||e.srcElement;
    ma.log(target);
	if(!ma.loaded) {
		ma.JqueryInit(function() {
			ma.loadJs(ma.url + "js/" + ma.files.ma + "-" + ma.filekey + ".js",function(){
				ma.PageInit();
				ma.Player(target);
			});
		});
		ma.loaded = true;
	} else {
		ma.Player(target);
	}
	return false;
}