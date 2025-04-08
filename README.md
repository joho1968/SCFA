[![Software License](https://img.shields.io/badge/License-GPL%20v2-green.svg?style=flat-square)](LICENSE) [![PHP 7.4\+](https://img.shields.io/badge/PHP-7.4-blue?style=flat-square)](https://php.net) [![PHP 8.1\+](https://img.shields.io/badge/PHP-8.1-blue?style=flat-square)](https://php.net) [![WordPress 5](https://img.shields.io/badge/WordPress-6.7-orange?style=flat-square)](https://wordpress.org)

# SCFA

Shortcodes for Font Awesome (SCFA) is a WordPress plugin to generate inline HTML with Font Awesome 6 icon libray.

## Description

SCFA generates inline HTML for Font Awesome icons. The WordPress slug is `shortcodes-for-font-awesome`.

The plugin is also available on [wordpress.org](https://wordpress.org/plugins/shortcodes-for-font-awesome/)

A few notes about this plugin:

* You may choose to host your Font Awesome files 6 locally (default)
* You may choose to use the Font Awesome CDN
* You may choose to specify a custom URL for your Font Awesome files
* You may choose to disable Font Awesome assets if you include them elsewhere
* This plugin may create entries in your PHP error log (if active)
* This plugin contains no tracking code and does not process or collect any information about the visitor
* Tested with WordPress 5.5-6.7
* Tested with PHP 7.2, 7.4 and PHP 8.1

## Installation

This section describes how to install the plugin and get it working.

1. Upload the contents of the `shortcodes-for-font-awesome` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure accordingly

## Configuration

Decide if you want to use the included Font Awesome assets, they are the time of this writing at version 5.2.4. If you want to use another version, you can replace the included assets, use another URL for the CSS and webfont assets, or specify a Font Awesome CDN kit URL.

## Usage

There is one shortcode available with this plugin:

`[scfa][/scfa]`

# General format #

This is intended as a quick reference for the shortcode. For a complete list of Font Awesome classes, please see fontawesome.com. Some icon styles may only be available with a Pro license. You can (obviously) specify everything class related in the class="" parameter if that is more convenient for you.

Generate <span> element with icon and default (icon) style:

`[scfa icon="address-book"][/scfa]`

Generate <span> element with the specified style and icon:

`[scfa icon="far address-book"][/scfa]`

Generate <span> element with the specified style and icon and size:

`[scfa icon="far address-book" size="5x"][/scfa]`

There are more examples in the SCFA plugin settings screen.

## Frequently Asked Questions

### How to I make local customizations

You can replace all of the Font Awesome files in the `css/fontawesome/` sub-directory with an updated version of Font Awesome. Please note that the webfonts from Font Awesome go into the `css/webfonts/` sub-directory.

## Changelog

### 1.4.1
* Upgraded to FontAwesome 6.7.2
* Tested plugin with WordPress 6.7.x

### 1.4.0
* Upgraded to FontAwesome 6.4
* Tested plugin with WordPress 6.4.1

### 1.3.0
* Upgraded to FontAwesome 6.0
* Tested plugin with WordPress 5.9
* Minor i18n (translation) correction
* Minor cosmetic correction
* Added support for "Thin" icons (fat, fa-thin)
* Added support for alternate (new) V6 type names (fa-solid, fa-regular, fa-light, fa-duotone, fa-thin, fa-brands

### 1.2.1
* Upgraded to FontAwesome 5.15.3
* Tested plugin with WordPress 5.8
* Minor i18n (translation) correction
* Minor cosmetic correction

### 1.2.0
* Upgraded to FontAwesome 5.15.2
* Tested plugin with WordPress 5.7
* Removed BoilerPlate

### 1.1.0
* Upgraded to FontAwesome 5.15.1
* Tested with PHP 7.4
* Tested plugin with WordPress 5.6

### 1.0.0
* Initial release

## Upgrade Notice

### 1.4.1
* Simply update the plugin via wordpress.org or download and install as per the installation instructions above.
* The .eot, .svg, and .woff files in the /css/webfonts/ directory of the plugin are not used by Font Awesome 6 and can be removed.

### 1.3.0
* Simply update the plugin via wordpress.org or download and install as per the installation instructions above.
* The .eot, .svg, and .woff files in the /css/webfonts/ directory of the plugin are not used by Font Awesome 6 and can be removed.

### 1.2.1
* Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

### 1.2.0
* Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

### 1.1.0
* Simply update the plugin via wordpress.org or download and install as per the installation instructions above.

### 1.0.0
* Initial release

## License

Please see [LICENSE](LICENSE) for a full copy of GPLv2

Copyright (C) 2020-2025 [Joaquim Homrighausen](https://github.com/joho1968).

This file is part of Shortcodes for Font Awesome (SCFA). Shortcodes for Font Awesome is free software.

You may redistribute it and/or modify it under the terms of the GNU General Public License version 2, as published by the Free Software Foundation.

Shortcodes for Font Awesome is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with the SCFA package. If not, write to:

```
The Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor
Boston, MA  02110-1301, USA.
```

## Credits

The Shortcodes for Font Awesome (SCFA) WordPress Plugin was written by Joaquim Homrighausen while converting :coffee: into code.

SCFA is sponsored by [WebbPlatsen i Sverige AB](https://webbplatsen.se) in :sweden:

Commercial support and customizations for this plugin is available from WebbPlatsen i Sverige AB in :sweden:

This plugin can also be downloaded from [code.webbplatsen.net](https://code.webbplatsen.net/wordpress/wordpress-shortcodes-for-font-awesome/) and [WordPress.org](https://wordpress.org/plugins/shortcodes-for-font-awesome/)

### External references

These links are not here for any sort of endorsement or marketing, they're purely for informational purposes.

* me; :monkey: https://joho.se and https://github.com/joho1968
* WebbPlatsen; https://webbplatsen.se and https://code.webbplatsen.net
* [Font Awesome](https://fontawesome.com) for their awesome icons!

Stay safe!
