<?php
/**
 * Internationalization functionality for SCFA
 *
 * @since      1.0.0
 * @package    SCFA
 * @subpackage scfa/includes
 * @author     Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * class-scfa-i18n.php (Shortcodes for Font Awesome)
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
class scfa_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			SCFA_PLUGINLANG_SLUG,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
