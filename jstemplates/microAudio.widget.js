ma.WidgetInit = function() {
	ma.current = null;
	jQuery("#microAudio-widget-container").html('').append("<div id='microAudio-widget-control'></div>").append("<ul id='microAudio-widget-list'></ul>");
	jQuery("a[href$='mp3']").each(function() {
			ma.log("Adding widget item: " + jQuery(this).html());
			jQuery("#microAudio-widget-list").append("<li class='microAudio-widget-list-item'><a href='" + jQuery(this).attr('href') + "' class='microAudio-widget-link'>" + jQuery(this).html() + "</a></li>");
	});
	jQuery(".microAudio-widget-link").click(ma.handleWidgetClick);
}
ma.handleWidgetClick = function(element) {
	if(ma.current !== null) {
		ma.current.stop();
		jQuery(ma.currentLink).removeClass("microAudio-widget-playing").unbind('click').click(ma.handleWidgetClick);
	}
	ma.log("Clicked on " + jQuery(this).html());
	ma.currentLink = this;
	ma.current = soundManager.createSound({
											id:jQuery(this).html(),
											url:jQuery(this).attr('href'),
											autoPlay:true
											});
	jQuery(this).addClass("microAudio-widget-playing").unbind('click').click(function(){ // Pause Handler
		ma.current.togglePause();
		return false;
	});
	ma.current.whileloading = function(arg) {
		output = "Loaded " + ma.current.bytesLoaded + " of " + ma.sidebar.current.bytesTotal;
		ma.log(output);
		jQuery("#microAudio-widget-control").html(output);
	};
	jQuery("#microAudio-widget-control").html("Loading . . .");
	return false;
}