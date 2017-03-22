=== Affiliates Manager ===
Contributors: wp.insider, affmngr, ElementGreen
Donate link: https://wpaffiliatemanager.com/
Tags: affiliate, affiliates manager, affiliate marketing, affiliate plugin, affiliates, referral, affiliate program, ads, advertising, affiliate tool, digital downloads, e-commerce, tracking, track affiliates, leads, affiliate software, woocommerce, affiliate campaign, paypal  
Requires at least: 3.5
Tested up to: 4.7
Stable tag: 2.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Affiliates Manager plugin can help you manage an affiliate marketing program to drive more traffic and more sales to your site.

== Description ==

Running your WordPress site with an e-Commerce plugin or solution? WP Affiliate Manager can help you manage an affiliate marketing program to drive more traffic and more sales to your store.

Affiliate Marketing is the fastest growing advertising method and it is very cost effective.

This plugin facilitates the affiliates recruitment, registration, login, management process. 

It will also track the referrals your affiliates send to your site and give commissions appropriately.

Affiliates Manager integrates with some popular e-commerce solutions. It integrates with:

* WooCommerce
* Simple Shopping Cart
* WP eCommerce
* JigoShop
* Easy Digital Downloads
* iThemes Exchange
* WP eStore
* Sell Digital Downloads
* Paid Membership Pro
* S2Member

= Real-Time Reporting =

Your affiliate's traffic and sales are recorded and ready for display as soon as they happen.

= Unlimited Affiliates =

No matter if you have 1 or 1,000 affiliates, you can track them all.

= Flat Rate or Percentage Based Payouts =

You decide how you want to reward your affiliates. You can choose to pay your affiliates a flat rate per order, or choose to pay them a percentage of every order they initiate.

= Set Payout Rates Per Affiliate =

Each of your affiliates can be set to their own payout amount.

= Manual Adjustments and Payouts =

There may be a time when you need to credit an affiliate for something other than a sale. For example, a bonus for reaching a sales goal. Manual adjustments are treated as a line item for easy tracking.

= Pay Your Affiliates Using PayPal =

Ability to easily pay your affiliates their commission using PayPal.

= Unlimited Creatives and Ads for your Affiliates = 

You can add as many banners or text link ads as you wish. Plus, you can easily activate or deactivate creatives as needed.

= Customizable Affiliate Registration =

You can decide how much or how little data to collect from your affiliates when they sign up. You can mark each field as optional or required.

= Autoresponder Integration =

* Sign up affiliates to your Mailchimp list.
* Sign up affiliates to MailPoet newsletter list.

= Affiliate Ad Impression Tracking = 

Track how many times a particular affiliate ad is getting viewed.

= Customize Messages for Affiliates = 

You can customize the email messages that gets sent to your affiliate when they register for an account. The following messages are customizable:

* HTML message displayed to user at logon if affiliate STATUS = APPROVED 
* HTML message displayed to user at logon if affiliate STATUS = DECLINED
* HTML message displayed to user at logon if affiliate STATUS = PENDING
* HTML message displayed to user after successfully submitting the affiliate registration form
* Body of the e-mail sent to the affiliate immediately after submitting their application.
* Body of the e-mail sent to a newly registered affiliate immediately following their application being approved.
* Body of e-mail sent to the affiliate immediately following their application being declined.

= Translation Ready = 

This plugin can be translated to your language. The following language translations are already available in the plugin:

* English
* German
* French
* Hebrew
* Portuguese
* Spanish
* Bulgarian
* Finnish
* Dutch
* Italian
* Icelandic
* Polish

You can translate the plugin using [this documentation](https://wpaffiliatemanager.com/affiliate-manager-plugin-translation/).

= Support =

View the [Plugin Documentation](https://wpaffiliatemanager.com/documentation/) to get started.

If you have a question, you can ask it on our support forum.

Visit the [affiliate manager plugin](https://wpaffiliatemanager.com) site for more details.

= Developers =
* If you are a developer and you need some extra hooks or filters for this plugin then let us know.
* Github repository - https://github.com/wp-insider/affiliates-manager-plugin

== Installation ==

* Go to the Add New plugins screen in your WordPress admin area
* Click the upload tab
* Browse for the plugin file (wpaffiliatemanager.zip)
* Click Install Now and then activate the plugin

== Frequently Asked Questions ==
None

== Screenshots ==
View screenshots in the following page:
https://wpaffiliatemanager.com/screen-shots/

== Changelog ==

= 2.4.3 =
- Fixed a bug where the email address field would be grayed out when adding a new affiliate.

= 2.4.2 =
- Changed a label in the affiliate login page from "Username" to "Email Address".
- Added support for Russian language translation. The translation file was submitted by Artyom Pokatilov.
- Affiliate terms and conditions are now displayed in a page instead of popup.

= 2.4.1 =
- Added a function to retrieve the referrer ID via IP address.
- Created a new addon to integrate MemberMouse with affiliates manager plugin. See details below:
  https://wpaffiliatemanager.com/affiliates-manager-membermouse-plugin-integration/

= 2.4.0 =
- Fixed a bug where the login page could redirect to a 404 page when the affiliate homepage URL is customized

= 2.3.9 =
- Fixed some typos and updated all the language files.

= 2.3.8 =
- Affiliate home page and registration page URLs can now be configured in the settings.

= 2.3.7 =
- Added a new column to the commissions table to save the email address of the buyer. All future transactions will capture the email address of the buyer.

= 2.3.6 =
- Click Tracking menu now shows the IP Address of the user.
- Created a new addon to integrate AWeber with affiliates manager plugin. See details below:
  https://wpaffiliatemanager.com/signup-affiliates-aweber-list/

= 2.3.5 =
- Fixed a bug where the customised Landing Page URL did not appear in the affiliate creative code.
- Creative URLs in the menu are now properly escaped.
- Added support for Czech language translation. The translation file was submitted by Valli Katzl.

= 2.3.4 =
- Replaced deprecated function call wp_get_single_post() with get_post().
- Replaced deprecated function call get_userdatabylogin() with get_user_by().

= 2.3.3 =
- Click Tracking menu now supports the bulk delete option.

= 2.3.2 =
- Made some security related improvements to the affiliates menu.

= 2.3.1 =
- Updated the link to the PayPal Mass Pay documentation.
- Payment settings link for PayPal Mass Pay configurations now goes to the payment tab.

= 2.3.0 =
- Added "Manual Transfer" as an available payment method.
- Replaced deprecated function call get_currentuserinfo() with wp_get_current_user().
- Updated the default terms and conditions.

= 2.2.9 =
- Impression tracking will now be disabled by default.
- Changed the deprecated 'add_object_page' call to 'add_menu_page' for creating a top-level menu page.

= 2.2.8 =
- Added translation option for the commission rate summary.
- Made some modifications to the transaction table so it can record large commission amount.

= 2.2.7 =
- Fixed a bug in the French translation file that was causing errors in the manual affiliate approval screen.
- Affiliates Manager is now compatible with WordPress 4.5.

= 2.2.6 =
- Fixed an issue that was preventing affiliates manager from submitting mass payment due to invalid security header.

= 2.2.5 =
- Fixed an issue that was preventing affiliates manager from creating new affiliates during WooCommerce checkout.

= 2.2.4 =
- Fixed an issue that was preventing affiliates manager from saving cookies on some servers.
- Optimized the code to render click tracking, commissions and affiliates data with minimal server resources.

= 2.2.3 =
- Default Landing Page URL can now be customized in the advanced settings.

= 2.2.2 =
- Affiliates Manager no longer sets any locale information in the system on which PHP is running.
- Added some debug data in the function that submits PayPal Mass payments.

= 2.2.1 =
- Gravatar image on the login page no longer shows security warning when rendered on a https site.
- Fixed an issue where redeclaration of function formatType() was causing a fatal error on some servers.

= 2.2.0 =
- Added en_US language translation file.

= 2.1.9 =
- Add a new role "affiliate" during activation.
- Affiliates Manager is now compatible with WordPress Multisite.

= 2.1.8 =
- UTF-8 is now set as the default encoding in the htmlentities function.
- Affiliates Manager is now compatible with WordPress 4.4.

= 2.1.7 =
- Updated the translatable strings to make the plugin compatible with language packs.

= 2.1.6 =
- Updated the spanish language translation file.
- Added a new menu to show all affiliate commissions.
- Added a new option to manually award commissions to your affiliates.

= 2.1.5 =
- fixed a bug so jQuery ui loads correctly when the language is not set in English.
- Added a new option to prevent zero amount commission from being recorded.
- Updated the pot file in the plugin.

= 2.1.4 =
- Updated Spanish language translation in the plugin. The translation file was submitted by Manuel Ballesta Ruiz.
- Added a new filter and addon to award product specific commission for WooCommerce products.
  https://wpaffiliatemanager.com/product-specific-affiliate-commission-for-woocommerce-products/

= 2.1.3 =
- Updated the pot file in the plugin.

= 2.1.2 =
- Plugin now logs information when a registration email is sent to an affiliate
- Added a new hook to award commission from an external plugin/extension.

= 2.1.1 =
- Plugin can now reverse commission for a refunded WooCommerce order

= 2.1.0 =
- Fixed a bug where saving an option in the settings was adding slashes

= 2.0.9 =
- Fixed some warning notices in the plugin

= 2.0.8 =
- Changed the structure of affiliate links generated by the plugin
- Made some improvements to the statistics menu of affiliate dashboard
- Fixed an issue where affiliate statistics (visits and purchases) were not showing in the admin menu

= 2.0.7 =
- Fixed an issue with commission not getting recorded sometimes

= 2.0.6 =
- Changed the default affiliate link in the plugin
- Changed some labels in the settings ("AID" to "Affiliate ID" and "Tracking ID" to "Row ID")
- Clicks can now be recorded using the new tracking system

= 2.0.5 =
- Made some improvements to the Easy Digital Downloads & Simple Shopping Cart tracking options

= 2.0.4 =
- Made some improvements to the WooCommerce order tracking option
- Fixed an issue with the commission awarding date

= 2.0.3 =
- Made some improvements to the click tracking function
- Added "Remember Me" checkbox to the affiliate login form
- Added "Lost Password" link to the login page

= 2.0.2 =
- Made some improvements to the commission awarding function

= 2.0.1 =
- Updated German language translation in the plugin. The translation file was submitted by Anne Ebert.
- Updated Hebrew language translation in the plugin. The translation file was submitted by Ido Yitzhaki.

= 1.9.9 =
- Fixed the "undefined required" error in the general settings.

= 1.9.8 =
- Added a fix to make sure the Store Affiliates page renders correctly 

= 1.9.7 =
- Affiliate Homepage content can now be customized in the settings (Affiliates->Settings->Affiliate Registration).
- "Terms and Conditions" section is now available under "Affiliate Registration" tab. 

= 1.9.6 =
- Added Polish language translation to the plugin. The translation file was submitted by Krzysztof Gorecki.
- Added an addons menu to list all the extenstions and addons of this plugin.

= 1.9.5 =
- Added a new filter and addon to award commission via WooCommerce Coupon.
  https://wpaffiliatemanager.com/tracking-affiliate-commission-using-woocommerce-coupons-or-discount-codes/

= 1.9.4 =
- An admin notice has been hidden since affiliates manager no longer loads currency from WPLANG settings.

= 1.9.3 =
- Fixed a bug in the Easy Digital Downloads plugin integration.

= 1.9.2 =
- Fixed the registration form translation issue.

= 1.9.1 =
- Added some debug messages for Easy Digital Downloads plugin integration

= 1.9.0 =
- Fixed an issue where logged-in users would be able to create affiliate accounts with different email addresses.
- Added a new filter in the registration form to print content before the submit button.
- Added a new filter in the registration form to run additional validation code upon submission.

= 1.8.9 =
- Added a function to log array in the DebugLogger file.
- Auto WooCommerce Affiliate Account Creation Addon: Address details of the affiliate will now be populated when a new WooCommerce user is created.

= 1.8.8 =
- Added a function to create a new affiliate account.
- Created a new addon to automatically create affiliate accounts for your WooCommerce users. See details below:
  https://wpaffiliatemanager.com/automatically-create-affiliate-account-woocommerce-customers/

= 1.8.7 =
- Fixed a bug where a new affiliate account could not be manually approved from the affiliates menu.

= 1.8.6 =
- Deleting an affiliate record will also delete the WordPress user account associated with it.

= 1.8.5 =
- Updated some text in the affiliate terms and conditions settings.
- Made some improvements to the "My Affiliates" interface on the admin side
- An affiliate record can now be deleted from the admin

= 1.8.4 = 
- Fixed a bug where Tracking IDs were not showing in the click tracking menu.

= 1.8.3 =
- Created a new addon to integrate S2Member with affilates manager plugin. See details below:
  https://wpaffiliatemanager.com/affiliates-manager-s2member-integration/ 
- Added a new menu to show affiliate click throughs.

= 1.8.2 =
- Fixed some jQuery ui display issues in the admin side of the plugin for WordPress 4.1.

= 1.8.1 =
- Default affiliate links are now displayed in the affiliate's dashboard. This will be available even if you haven't configured any creative.

= 1.8.0 =
- Made some improvements so the available payment methods (PayPal or Cheque) are shown correctly to the affiliate.
- Made an enhancement so if you are logging in using the affiliate login form, only then you will be redirected to the affiliates home page upon successful login.

= 1.7.9 =
- Fixed a bug in the WooCommerce integration where the plugin was awarding commission for "Pending" state. Now commission is only awarded when an order is set to "processing" or "completed" state.
- Moved the 3rd party integration hooks into the plugin constructor.
- Created a new function to define all the constants for this plugin.

= 1.7.8 =
- The style and design of the affiliate area has been improved significantly.
- MySQL database character set and collation values are read from the system when creating the tables.
- WooCommerce Integration: shipping and tax are now excluded from the total order amount.
- Affiliate URL/link can now be invoked using email address or affiliate ID.

= 1.7.7 =
- Added a new feature to allow tracking of ad impressions. This feature was added by Element Green.
- fixed a bug where default creative link was not showing correctly.
- Made some small improvements to the creatives menu.

= 1.7.6 =
- Created a new addon to integrate paid membership pro with affilates manager plugin. See details below:
  https://wpaffiliatemanager.com/affiliates-manager-paid-memberships-pro-integration/
- Created a new addon to integrate sell digital downloads with affilates manager plugin. See details below:
  https://wpaffiliatemanager.com/affiliates-manager-sell-digital-downloads-integration/
- The plugin will now add a default creative during installation.
- Added a "Log out" link in the affiliate login page of the plugin.
- Added British English translation to the plugin. The translation file was submitted by John.

= 1.7.5 =
- Added a new option to the settings to specify "currency code".
- Created a new addon to integrate the MailPoet newsletter with affilates manager plugin. See details here:
  https://wpaffiliatemanager.com/sign-affiliates-to-mailpoet-list/

= 1.7.4 =
- Added Icelandic language translation to the plugin. The translation was submitted by Bjarni K. Thors.
- Affiliate login page is now automatically created for you when you install the plugin.

= 1.7.3 =
- Added a new action hook to accommodate the mailchimp signup of your affiliates.

= 1.7.2 =
- Body of e-mail sent to a newly registered affiliate (once their application is approved) can now be customized in the "Messaging" settings.

= 1.7.1 =
- Made enhancements to the "add affiliate" button in the admin area.
- Made some enhancements to the money formatting function.
- Converted the settings menu tab links to WordPress style tabs.

= 1.7.0 =
- Added a new option to set currency symbol in the settings.
- Changed the creative status to be active by default when they are created.
- Added a new action hook that is triggered after affiliates submit the registration form.
- Added integration with jigoshop e-commerce plugin.

= 1.6.9 =
- Fixed a minor bug with WooCommerce commission tracking
- Added debug logging option
- Simple shopping cart tracking option added.

= 1.6.8 =
- First commit to wp repository

== Upgrade Notice ==
None

== Arbitrary section ==
None
