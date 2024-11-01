(function() {
	if (window.__linkedinCWSHandler) return;
	// http://www.linkedin.com/cws/share?url=http%3A%2F%2Fwww.enriquedans.com%2F2010%2F11%2Fla-neutralidad-de-la-red-llega-al-senado.html&original_referer=http://www.enriquedans.com
	var intentRegex = /linkedin\.com(\:\d{2,4})?\/cws\/(\w+)/,
		windowOptions = "scrollbars=yes,resizable=yes,toolbar=no,location=yes",
		width = 550,
		height = 220,
		winHeight = screen.height,
		winWidth = screen.width;
		
	function handleCWS(e) {
		e = e || window.event;
		var target = e.target || e.srcElement, m, left, top;
		
		while (target && target.nodeName.toLowerCase() !== "a") {
			target = target.parentNode;
		}
		
		if (target && target.nodeName.toLowerCase() === "a" && target.href) {
			m = target.href.match(intentRegex);
			
			if (m) {
				left = Math.round((winWidth / 2) - (width / 2));
				top = 0;
				
				if (winHeight > height) {
					top = Math.round((winHeight / 2) - (height / 2));
				}
				
				window.open(target.href, "intent", windowOptions + ",width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);
				
				e.returnValue = false;
				e.preventDefault && e.preventDefault();
			}
		}
	}
	
	if (document.addEventListener) {
		document.addEventListener("click", handleCWS, false);
	} else if (document.attachEvent) {
		document.attachEvent("onclick", handleCWS);
	}
	
	window.__linkedinCWSHandler = true;
}());