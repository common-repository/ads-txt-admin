=== Ads.txt Admin ===
Contributors: dmartuszewskium, dmartuszewski
Tags: adstxt, advertising, tool, premium content, paywall, paid content, content monetization, monetization, sell content, sell access, subscriptions, earn money, make money, paypal, visa, payment options, master card, sell digital goods, pay-per-item, monetize, sell, billing, subscription, paid content, transaction, pay, pay-per-view, premium, money, payment, subscribe, payments
Requires at least: 4.0
Tested up to: 5.0.3
Stable tag: 1.3
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ads.txt Admin is a simple tool that allows you to manage your ads.txt file from you WordPress dashboard.

== Description ==

Ads.txt Admin is a simple tool that allows you to manage your ads.txt file from your WordPress dashboard.

[Publishers now need an ads.txt file](https://iabtechlab.com/ads-txt) to be on their site. Publishers need to authorise sellers and resellers of their site advertising. Large ad buyers like Google and Facebook will no longer buy from publishers not running an ads.txt file

Ads.txt Admin makes this process simple.

Ads.txt Admin is a simple tool that manages this process without having to manually edit the ads.txt file every time you add or delete an ad sales partner for your site.

What does Ads.txt Admin do?

* Creates ads.txt file for your site
* Manages all authorised sellers and re-sellers
* Allows you to enter new reseller/seller partners using our simple-to-use form
* Previews all your changes
* Allows you to edit, save or delete details of your ads.txt file in real-time
* Gives you option to indicate to buyers that you do not have any resellers/sellers
* Works with Wordpress Multisite

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/ads-txt-admin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Ads.txt Admin to configure the plugin
4. Makes sure you don't have an ads.txt file in the root directory. If you have, rename or remove it.
5. Makes sure you have Rewrites enabled
6. Adblock users: Adblock could block some plugin files.  It contains 'ads' in the name/url. Please make sure you have whitelisted the domain on which Ads.txt Admin is installed.

== Frequently Asked Questions ==

= Does the plugin validate content?  =

Yes.  The plugin validates the data you enter into the ads.txt file to make sure it 100% complies with IAB specification.

= Can my site URL contain the full URL path (e.g. https://example.com/site/) =

No. That will break IAB specification.  It requires ads.txt to be located at the root level, e.g. https://example.com/ads.txt.

= How can I enable rewrites? =

Please follow details listed here, https://codex.wordpress.org/Using_Permalinks.

= Does it work with Wordpress Multisite? =

Yes. Multiple files are created for all sites.

== Screenshots ==

1. This screen shows plugin with no active ads.txt file.
2. This screen shows how a user can manage/edit a site's ads.txt file with Ads.txt Admin.

== Changelog ==


= 1.3 =
* Rebranding to VideoYield.com

= 1.2 =
* Fixed selecting DIRECT / RESELLER in configuration form

= 1.1 =
* Multisite support

= 1.0 =
* Editing ads.txt file

== Upgrade Notice ==
* No updates yet