=== SCFA (Shortcodes for Font Awesome) ===
Contributors: joho68, webbplatsen
Donate link: https://code.webbplatsen.net/wordpress/wordpress-shortcodes-for-font-awesome/
Tags: font awesome, fontawesome, webfont, font, icon
Requires at least: 5.5.0
Tested up to: 6.4
Stable tag: 1.4.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate inline HTML with Font Awesome icon libray by using shortcodes.

== Description ==

This WordPress shortcode plugin generates inline HTML for the Font Awesome icon libray.

You can use any icon code supported by Font Awesome 6.

A few notes about this plugin:

*   You may choose to host your Font Awesome 6 files locally (default)
*   You may choose to use the Font Awesome CDN
*   You may choose to specify a custom URL for your Font Awesome files
*   You may choose to disable Font Awesome assets if you include them elsewhere
*   This plugin may create entries in your PHP error log (if active)
*   This plugin contains no tracking code and does not process or collect any information about the visitor
*   Tested with WordPress 5.5-6.4.1
*   Tested with PHP 7.2 and PHP 8

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the contents of the `shortcodes-for-font-awesome` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure accordingly

== Configuration ==

Decide if you want to use the included Font Awesome assets, they are the time of this writing at version 5.15.2. If you want to use another version, you can replace the included assets, use another URL for the CSS and webfont assets, or specify a Font Awesome CDN kit URL.

== Usage ==

There is one shortcode available with this plugin:

    [scfa][/scfa]

= General format =

This is intended as a quick reference for the shortcode. For a complete list of Font Awesome classes, please see fontawesome.com. Some icon styles may only be available with a Pro license. You can (obviously) specify everything class related in the class="" parameter if that is more convenient for you.

Generate <span> element with icon and default (icon) style:

    [scfa icon="address-book"][/scfa]

Generate <span> element with the specified style and icon:

    [scfa icon="far address-book"][/scfa]

Generate <span> element with the specified style and icon and size:

    [scfa icon="far address-book" size="5x"][/scfa]

There are more examples in the SCFA plugin settings screen.

== Frequently Asked Questions ==

= How to make local customizations =

You can replace all of the Font Awesome files in the css/ sub-directory with an updated version of Font Awesome. Please note that the webfonts from Font Awesome go into the css/webfonts/ sub-directory.

= Reference for the Font Awesome free icons =

https://fontawesome.com/search?q=filter&m=free

== Changelog ==

= 1.4.0 =
* Upgraded to FontAwesome 6.4
* Tested plugin with WordPress 6.4.1

= 1.3.0 =
* Upgraded to FontAwesome 6.0
* Tested plugin with WordPress 5.9
* Minor i18n (translation) correction
* Minor cosmetic correction
* Added support for "Thin" icons (fat, fa-thin)
* Added support for alternate (new) V6 type names (fa-solid, fa-regular, fa-light, fa-duotone, fa-thin, fa-brands

= 1.2.1 =
* Upgraded to FontAwesome 5.15.3
* Tested plugin with WordPress 5.8
* Minor i18n (translation) correction
* Minor cosmetic correction

= 1.2.0 =
* Upgraded to FontAwesome 5.15.2
* Tested plugin with WordPress 5.7
* Refactored code and removed all WordPress Plugin Boilerplate code
* Removed non-minified CSS assets

= 1.1.0 =
* Upgraded to FontAwesome 5.15.1
* Tested with PHP 7.4
* Tested plugin with WordPress 5.6

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.4.0 =
Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

The .eot, .svg, and .woff files in the /css/webfonts/ directory of the plugin are not used by Font Awesome 6 and can be removed.
= 1.3.0 =
Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

The .eot, .svg, and .woff files in the /css/webfonts/ directory of the plugin are not used by Font Awesome 6 and can be removed.

= 1.2.1 =
Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

= 1.2.0 =
Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

= 1.1.0 =
Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

= 1.0.0 =
Initial release

== Credits ==

The Shortcodes for Font Awesome Plugin was written by Joaquim Homrighausen while converting caffeine into code.

Shortcodes for Font Awesome is sponsored by [WebbPlatsen i Sverige AB](https://www.webbplatsen.se), Stockholm, Sweden.

This plugin can also be downloaded from [code.webbplatsen.net](https://code.webbplatsen.net/wordpress/wordpress-shortcodes-for-font-awesome/) and [GitHub](https://github.com/joho1968/SCFA)

[Font Awesome](https://fontawesome.com)

Stay safe!
