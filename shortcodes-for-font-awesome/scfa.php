<?php
/**
 * SCFA Plugin bootstrap file
 *
 * @link              https://github.com/joho1968/SCFA
 * @since             1.0.0
 * @package           SCFA
 * @author            Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcodes for Font Awesome
 * Plugin URI:        https://github.com/joho1968/SCFA
 * Description:       Generate inline HTML for Font Awesome using shortcodes
 * Version:           1.1.0
 * Author:            Joaquim Homrighausen <joho@webbplatsen.se>
 * Author URI:        https://github.com/joho1968/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shortcodes-for-font-awesome
 * Domain Path:       /languages
 *
 * scfa.php (Shortcodes for Font Awesome)
 * Copyright (C) 2020 Joaquim Homrighausen
 *
 * This file is part of SCFA. SCFA is free software.
 *
 * You may redistribute it and/or modify it under the terms of the
 * GNU General Public License version 2, as published by the Free Software
 * Foundation.
 *
 * SCFA is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the SCFA package. If not, write to:
 *  The Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor
 *  Boston, MA  02110-1301, USA.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'SCFA_VERSION', '1.1.0' );
define( 'SCFA_PLUGINNAME_HUMAN', 'SCFA' );
define( 'SCFA_PLUGINNAME_SLUG',  'shortcodes-for-font-awesome' );
define( 'SCFA_PLUGINLANG_SLUG',  'shortcodes-for-font-awesome' );


/**
 * The code that runs during plugin activation.
 */
function activate_scfa() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scfa-activator.php';
	scfa_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_scfa() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scfa-deactivator.php';
	scfa_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_scfa' );
register_deactivation_hook( __FILE__, 'deactivate_scfa' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-scfa.php';

/**
 * Begins execution of the plugin.
 */
function run_scfa() {

	$plugin = new scfa();
	$plugin->run();

}

run_scfa();
