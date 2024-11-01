=== Events Manager Pro - Payment Gateway Selector ===
Plugin Name: 		Events Manager Pro - Payment Gateway Selector
Contributors:		DuisterDenHaag
Tags: 				events manager, gateway, payment, mollie
Donate link: 		https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/
Requires at least: 	5.5
Tested up to: 		6.0
Requires PHP: 		7.3
Tested up to PHP:   8.0.14
Stable tag: 		2.0.4
License: 			GPLv2 or later
License URI: 		http://www.gnu.org/licenses/gpl-2.0.txt


Easily set or unset your activated payment gateway(s) per individual single event in Event Manager Pro with a simple checkbox.


== Description ==
> Requires [Events Manager](https://wordpress.org/plugins/events-manager/) (free plugin) & [Events Manager Pro](https://eventsmanagerpro.com/) (paid plugin) to be installed & activated.

This plugin adds a section to the Tickets Options meta box in the Event Edit Page. It lists all activated gateways in alphabetical order.
Uncheck any gateway you want to disable for <em>this specific</em> event. Save the post. That's it!

= 100% Free Add-on =
This add-on is free of charge to use and works with any available Events Manager Pro gateway (Mollie, Offline, PayPal, Stripe, Heartland, Bank Transfer, WorldPay, etc.)


== Feedback ==
I am open to your suggestions and feedback!
[Please also check out my other plugins, tutorials and useful snippets for Events Manager.](https://www.stonehengecreations.nl/)


== Frequently Asked Questions ==
= Are you part of the Events Manager team? =
No, I am not! am not associated with [Events Manager](https://wordpress.org/plugins/events-manager/) or its developer, [NetWebLogic](http://netweblogic.com/), in any way.

= Do I really need Events Manager Pro? =
Yes. Payment Gateways are an Events Manager Pro feature. This add-on cannot be used without it.

= Does this plugin include Payment Gateways? =
No. This plugin handles the already installed Events Manager Pro Payment Gateways.

= Does it support recurring events? =
Yes, it does.

= Is this WP MultiSite compatible? =
Yes, it is.

= I love this plugin! Let me buy you a coffee. =
How kind! Please, consider a [donation](https://paymentlink.mollie.com/payment/x7dNYfFAWy6rN6G42PFkv/).


== Screenshots ==
1. The Meta Box with installed and activated gateways.


== Installation ==
1. Upload the entire `stonehenge-em-gateway-selector` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Uncheck the gateway you want to disable in your Edit Event page.
4. Save the post.


== Changelog ==
= 2.0.4 =
- Fixed the checkboxes not being shown in some cases.
- Fixed fatal error related to the is_plugin_active() function.
- Confirmed compatibility with WordPress 6.0 Bèta

= 2.0.3 =
Fixed a typo on line 119.

= 2.0.1 =
Fixed a typo on line 83.

= 2.0 =
- Complete code rewrite for better coding standards.
- New Plugin Banner and Plugin Icon
- Confirmed compatibility with WordPress 5.9
- Confirmed compatibility with PHP 8.0.14
- Confirmed compatibility with Events Manager 5.12.1
- Confirmed compatibility with Events Manager Pro 2.7

= 1.3.8 =
- Confirmed compatibility with WordPress 5.5.

= 1.3.7 =
- Confirmed compatibility with WordPress 5.4.
- Confirmed compatibility with PHP 7.4.2.

= 1.3.6 =
- Updated WP Repository graphics.
- Updated ReadMe.txt
- Some minor code changes.

= 1.3.5 =
- Bug fix for events with only free tickets.

= 1.3.4 =
- Added additional validation to the Event Submission form. Preventing errors by EM if all gateways were deselected.
- Updated the .pot file for translations.

= 1.3.3 =
- Removed Admin Meta Box, because it conflicted with the Ticket Options. Front- and Back-end are now identical: you can (de)select gateways below the ticket section.

= 1.3.2 =
- Fixed ability to deselect all gateways for an event.
- Better Plugin Dependency Check.

= 1.3.1 =
- Added missing actions.

= 1.3.0 =
- Added support for Front-End Event Submission Forms.
- Created translatable strings.
- Code updated.
- .pot file added for easy translation.

= 1.2.1 =
- Checked WordPress 5.0 compatibility.

= 1.1 =
- Bugfix: wrong function name was called, resulting in plugin not showing any active gateways.

= 1.0 =
- Initial release (12-08-2018)
