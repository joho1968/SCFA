<?php
/**
 * Public-facing functionality of SCFA
 *
 * @package    SCFA
 * @subpackage SCFA/public
 * @author     Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * class-scfa-public.php (Shortcodes for Font Awesome)
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

class scfa_Public {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;
	private $version;

	/**
	 * Finding SCFA
	 */
	protected $plugin_path;

	/**
	 * Stylesheet to use
	 */
	protected $asset_url;
	protected $asset_type;
	protected $default_style;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->plugin_path = plugin_dir_path( __FILE__ );
	    $this->asset_type = get_option( 'scfa-asset-type', 1 );
	    $this->default_style = get_option( 'scfa-default-style', 1 );
		$this->asset_url = get_option( 'scfa-asset-url', '' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
		switch( $this->asset_type ) {
			case 1:
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/all.min.css', array(), $this->version, 'all' );
				break;
			case 2:
				wp_enqueue_style( $this->plugin_name, $this->asset_url, array(), $this->version, 'all' );
				break;

		}// switch
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
		switch( $this->asset_type ) {
			case 3:
				wp_enqueue_script( $this->plugin_name, $this->asset_url, array(), $this->version, 'all' );
				break;
		}// switch
	}

	/**
	 * Register the shortcodes for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_init() {
		add_shortcode( 'scfa', [ $this, 'scfa_shortcode' ] );
		add_filter( 'widget_text', 'do_shortcode' );
	}

	/**
	 * Create actual output for shortcode(s)
	 *
	 * @access   protected
	 * @since    1.0.0
	 */
	protected function shortcode_handle( $args ) {
		$html = '';
		if ( is_array( $args ) ) {
			$fa_styles = array( 'fas', 'far', 'fal', 'fad', 'fab' );
			$s_class = $s_size = $s_addclass = $s_addcss = $s_fixed = '';
			foreach( $args as $k => $v ) {
				$v = trim ($v);
				switch( $k ) {
					case 'icon':
						if (strlen( $v ) > 0 ) {
							$x_icon = explode( ' ', $v, 100 );
							if ( is_array( $x_icon ) ) {
								$is_first = true;
								foreach ( $x_icon as $icon ) {
									if ( in_array ( $icon, $fa_styles ) ) {
										//One of our known styles, simply add it
										$s_classs .= ( $is_first ? '' : ' ' ) . $icon;
									} else {
										if ( strpos( $icon, 'fa-' ) !== 0 ) {
											//Add 'fa-' prefix
											$icon = 'fa-' . $icon;
										}
										$s_class .= ( $is_first ? '' : ' ' ) . $icon;
									}
									$is_first = false;
								}//foreach
							}//is_array
						}
						break;
					case 'size':
						if ( strlen( $v ) > 0 ) {
							if ( strpos( $v, 'fa-' ) !== 0 ) {
								$v = 'fa-' . $v;
							}
							$s_size = ' ' . $v;
						}
						break;
					case 'class':
						$s_addclass = ' ' . $v;
						break;
					case 'css':
						$s_addcss = $v;
						break;
					case 'fixed':
						if ( strlen( $v ) == 0 || $v != '0' ) {
							$s_fixed = ' fa-fw';
						}
						break;
				}//switch
			}//foreach

			//Possibly apply default style
			$x_class = explode( ' ', strtolower( $s_class ), 100 );
			if ( is_array( $x_class ) ) {
				$has_style = false;
				foreach( $x_class as $v ) {
					if ( in_array( $v, $fa_styles ) ) {
						$has_style = true;
						break;
					}
				}//foreach
				if ( ! $has_style ) {
					//No (known) style found in class= tag, apply default
					switch( $this->default_style ) {
						default://fas
							$s_class = 'fas ' . $s_class;
							break;
						case 2:
							$s_class = 'far ' . $s_class;
							break;
						case 3:
							$s_class = 'fal ' . $s_class;
							break;
						case 3:
							$s_class = 'fad ' . $s_class;
							break;
					}//switch
				}//apply default style
			}//is_array ()

			//Generate final output
			$html .= '<span class="' . esc_attr( $s_class ) .
			                           esc_attr( $s_size ) .
								 	   esc_attr( $s_fixed ) .
									   esc_attr( $s_addclass ) . '"';
			if ( ! empty( $s_addcss ) ) {
				$html .= ' style="' . esc_attr( $s_addcss ) . '"';
			}
			$html .= '></span>';
		}
		return ($html);
	}


	/**
	 * Shortcode handler for [scfa][/scfa]
	 */
	public function scfa_shortcode( $args ) {
		return( $this->shortcode_handle( $args, false ) );
	}

}
// scfa_Public
