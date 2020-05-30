# Quick Crawler Masker Demo

Super rough demo of a masking script to a Shopify (etc) store.

## Installation

* Set up a free Wix or Wordpress website and fill it with some cheap scraped / spun content and images from Unsplash.
* Create a Bitly (or similar) link to your business page and add this to the `overlay.js` file.
* Minify and obfuscate your JS and CSS files with http://minifycode.com/ and https://obfuscator.io/ (Change a few colors and sizes in the css to mask better).
* You will need to redo this step for each website to avoid footprints and to change your Bitly link.
* Install the script and the css on your webpage.
* Make sure to rename the `overlay.css` and `overlay.js` scripts to something opaque with a tool like: https://onlinehashtools.com/generate-random-md5-hash (for instance `overlay.css` might become `a8cd4a0bc52accb8542fe2f82752792c.css`)
```html
<link rel="stylesheet" href="overlay.min.css">
<script type="text/javascript" src="overlay.obfuscated.js"></script>
```

## Usage

The css will create an overlay with a loading spinner that covers the spun content on the underlying page.

When a user hits the page the js will run a bot check (recommend making this more robust depending on your use case) and either:

* a) Redirect a human user to your Bit.ly link, or;

* b) Unmask the overlay and show the bot the underlying website.


To disable the redirect and test what a bot would see, swap out the complete `overlay.js` for `overlay-check-bot.js`.

## Thoughts

For practical use, I would also add in a geo blocker to avoid sending unwanted geos to the business page, as well as improve the robustness of the bot check. You'll probably have this cloak page behind Cloudflare so you can use https://www.cloudflare.com/cdn-cgi/trace to get the users IP and region.

I would also probably want to deploy a Google Tag Manager to tag anyone that triggers a redirect so that I can map their path to a sale or end user action for campaign optimisation purposes. Naturally, you would want to obfuscate the GTM id as well to avoid footprints.

## Improvments

If you are improving to pass a human approval, you'll want to also add in a check for your social media platform of choice's corporate IP range ie https://ipinfo.io/AS35995 and even block the geos where you can intuit that their human approval staff are probably located, ie Silicon Valley, Phillipines, etc.

Clearly if it needs to pass a human check, you'll want to also put real text on the dummy page vs illegible spun content, so scraping is probably the way to go for this. 


## License
[MIT](https://choosealicense.com/licenses/mit/)
