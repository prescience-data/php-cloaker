/**
 * Demo example of redirecting if crawler.
 * Naturally you would want to implement more robust bot checking depending on your use case.
 * You'll want to run this script through https://obfuscator.io to get a unique version each time to avoid any patterns.
 * Note there are PHP sprintf('%s') tokens in the script as it is meant to be used with the 'header.php' component to generate these variables.
 * If you wish to use this as a standalone script, replace the 'crawlerUserAgentPattern' token with a regex user agent string, and the redirectUrl token with your bitly url.
 *
 */

var redirectUrl = '%s';
var crawlerUserAgentPattern = '%s';

function checkUserAgent() {

	var test = new RegExp(crawlerUserAgentPattern, 'i');
	var userAgent = navigator.userAgent;
	return !test.test(userAgent);
}

function checkFeatures() {

	var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
	var isFirefox = typeof InstallTrigger !== 'undefined';
	var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
		return p.toString() === '[object SafariRemoteNotification]';
	})(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
	var isIE = /*@cc_on!@*/false || !!document.documentMode;
	var isEdge = !isIE && !!window.StyleMedia;
	var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
	var isEdgeChromium = isChrome && (navigator.userAgent.indexOf('Edg') != -1);
	var isBlink = (isChrome || isOpera) && !!window.CSS;

	var browsers = [isOpera, isFirefox, isSafari, isIE, isEdge, isChrome, isEdgeChromium, isBlink];
	var passes = false;
	browsers.forEach(function (browser) {
		if (browser) {
			passes = true;
		}
	});

	return passes;
}

if (checkUserAgent() && checkFeatures()) {
	window.location.replace(redirectUrl);
}
else {
	document.addEventListener('DOMContentLoaded', function () {
		var overlay = document.getElementById('overlay');
		overlay.style.display = 'none';
		overlay.parentNode.removeChild(overlay);
	});
}
