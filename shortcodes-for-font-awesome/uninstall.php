<?php
/**
 * SCFA Plugin is uninstalled.
 *
 * @link              https://github.com/joho1968/SCFA
 * @since             1.0.0
 * @package           SCFA
 * @author            Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * uninstall.php
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

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// If action is not to uninstall, then exit
if ( empty( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'delete-plugin' ) {
	exit;
}
// If it's not us, then exit
if ( empty( $_REQUEST['slug'] ) || $_REQUEST['slug'] !== 'scfa' ) {
	exit;
}
// If we shouldn't do this, then exit
if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'delete_plugins' ) ) {
	exit;
}


// Figure out if an uninstall should remove plugin settings.

$remove_settings = get_option( 'scfa-remove-settings', '0' );

if ( $remove_settings == '1' ) {
	// scfa plugin options
	delete_option( 'scfa-asset-url' );
	delete_option( 'scfa-asset-type' );
	delete_option( 'scfa-default-style' );
	delete_option( 'scfa-remove-settings' );
}
