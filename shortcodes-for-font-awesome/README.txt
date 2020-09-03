=== SCFA (Shortcodes for Font Awesome) ===
Contributors: joho68, webbplatsen
Donate link: https://www.joho.se/scfa/
Tags: font awesome, fontawesome, webfont, font, icon, shortcode
Requires at least: 5.0.0
Tested up to: 5.5.1
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate inline HTML with Font Awesome icon libray by using shortcodes.

== Description ==

This WordPress shortcode plugin generates inline HTML for the Font Awesome icon libray.

You can use any icon code supported by Font Awesome.

A few notes about this plugin:

*   You may choose to host your Font Awesome files locally (default)
*   You may choose to use the Font Awesome CDN
*   You may choose to specify a custom URL for your Font Awesome files
*   You may choose to disable Font Awesome assets if you include them elsewhere
*   This plugin may create entries in your PHP error log (if active)
*   This plugin contains no tracking code and does not process or collect any information about the visitor

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the contents of the `shortcodes-for-font-awesome` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure accordingly

== Configuration ==

Decide if you want to use the included Font Awesome assets, they are the time of this writing at version 5.2.4. If you want to use another version, you can replace the included assets, use another URL for the CSS and webfont assets, or specify a Font Awesome CDN kit URL.

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

You can replace all of the Font Awesome files in the public/css directory with an updated version of Font Awesome. Please note that the webfonts from Font Awesome go into the webfonts/ folder.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release

== Credits ==

The SCFA WordPress Plugin is based on the WordPress Plugin Boilerplate, as a starting point. The SCFA WordPress Plugin was written by Joaquim Homrighausen.

The WordPress Plugin Boilerplate was started in 2011 by [Tom McFarlin](http://twitter.com/tommcfarlin/) and has since included a number of great contributions. In March of 2015 the project was handed over by Tom to Devin Vinson.

The current version of the Boilerplate was developed in conjunction with [Josh Eaton](https://twitter.com/jjeaton), [Ulrich Pogson](https://twitter.com/grapplerulrich), and [Brad Vincent](https://twitter.com/themergency).

You can get the WordPress Plugin Boilerplate here: http://wppb.io/
You may also like the WordPress Plugin Boilerplate generator: https://wppb.me/

Font Awesome: https://fontawesome.com

Development sponsored by WebbPlatsen i Sverige AB: https://www.webbplatsen.se
