# Quick Crawler Masker Demo

Super rough demo of a masking script to a Shopify (etc) store.

## Installation

* Set up a free Wordpress website on https://www.000webhost.com and fill it with some cheap scraped / spun content and images from Unsplash.
* Install the following plugin to safely edit your header https://wordpress.org/plugins/header-footer
* Copy the code from `header.php` into the "Header" section of the plugin. 
* Optional: Obfuscate the JS file with https://obfuscator.io and paste the new obfuscated code over the old JS at the bottom of the script.
* Add this html to the "Start of Body tag" section:
```html
<div id="loader"><div class="loading"></div></div>
```
* Sign up for a free API key at https://ipstack.com - this will enable server side IP range lookups, free crawler checking, and geo blocking. Copy this to `$MY_IPSTACK_KEY=`.
* Add your Bitly link to the `$MY_BITLY_LINK=` code.
* Edit any geos or IP ranges you'd like to block as well.
* Save the script and test your page! If you have installed correctly you may see a quick flash, then a redirect to your Bitly link. 


#### Testing
To test what a crawler / anyone you've blocked will see, just add your country or city to the block list.

```php
$BLOCKED_COUNTRY_CODES = ['PH', 'US']; // Add US if you are in the United States to see what a blocked user sees.
```

#### Important note for re-obsfucation...
If you decide to re-obsfucate the JS you'll need to either add your bitly link directly to the code, or *after* you have obsfucated, search for the string `'ADD_YOUR_BITLY_LINK'` and replace it with `'<?php echo isset($MY_BITLY_LINK) ? $MY_BITLY_LINK : ''; ?>'`

## Usage

The css will create an overlay with a loading spinner that covers the spun content on the underlying page.

When a user hits the page the js will run a bot check (recommend making this more robust depending on your use case) and either:

* a) Redirect a human user to your Bit.ly link, or;

* b) Unmask the overlay and show the bot the underlying website.


## Improvements

I would also probably want to deploy a Google Tag Manager to tag anyone that triggers a redirect so that I can map their path to a sale or end user action for campaign optimisation purposes. You may want to obfuscate the GTM id as well to avoid footprints. Just install a GTM plugin for this via Wordpress.

If you are improving to pass a human approval, you'll want to also add in a check for your social media platform of choice's corporate IP range ie Twitter: https://ipinfo.io/AS35995 and even block the geos where you can intuit that their human approval staff are probably located, ie Silicon Valley, Phillipines, etc.

Clearly if it needs to pass a human check, you'll want to also put real text on the dummy page vs illegible spun content, so scraping is real sites probably the way to go for this. You'll only need a few real looking pages for it to pass any human check.


## License
[MIT](https://choosealicense.com/licenses/mit/)
