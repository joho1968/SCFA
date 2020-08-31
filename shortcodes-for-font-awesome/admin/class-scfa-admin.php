<?php
/**
 * SCFA Admin
 *
 * @package    SCFA
 * @subpackage scfa/admin
 * @author     Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * class-scfa-admin.php
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
class scfa_Admin {

	private $plugin_name;
	private $version;

	protected $scfa_asset_type; /* 1=local, 2=url, 3=cdn, 4=none */
	protected $scfa_asset_url;
	protected $scfa_default_style; /* 1=fas, 2=far, 3=fal, 4=fad */


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	    $this->scfa_asset_type = get_option('scfa-asset-type', 1 );
		$this->scfa_asset_url = get_option( 'scfa-asset-url', '' );
		$this->scfa_default_type = get_option( 'scfa-default-style', 1 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		switch( $this->scfa_asset_type ) {
			case 1:
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __DIR__ ) . 'public/css/all.min.css', array(), $this->version, 'all' );
				break;
			case 2:
				wp_enqueue_style( $this->plugin_name, $this->scfa_asset_url, array(), $this->version, 'all' );
				break;

		}// switch
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		switch( $this->scfa_asset_type ) {
			case 3:
				wp_enqueue_script( $this->plugin_name, $this->scfa_asset_url, array(), $this->version, 'all' );
				break;
		}// switch
	}

	/**
	 * Do editor related things if we're editing/creating page/post
	 */
	public function register_editor_hooks() {
		//Just to make sure user is actually allowed to do this
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		//Figure out where we are, if it's possible
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( is_object( $current_screen ) ) {
				if ( isset( $current_screen->action ) ) {
					$wp_action = $current_screen->action;
				} else {
					$wp_action = '';
				}
				if ( isset( $current_screen->base ) ) {
					$wp_base = $current_screen->base;
				} else {
					$wp_base = '';
				}
				if ( isset( $current_screen->id ) ) {
					$wp_id = $current_screen->id;
				} else {
					$wp_id = '';
				}
				if ( isset( $current_screen->parent_base ) ) {
					$wp_parent_base = $current_screen->parent_base;
				} else {
					$wp_parent_base = '';
				}
				if ( isset( $current_screen->is_block_editor ) ) {
					$wp_block_editor = $current_screen->is_block_editor;
				} else {
					$wp_block_editor = '';
				}
				//Make sure we're in the right place. Maybe we should check
				//the $current_screen->post_type as well ...
				if ( $wp_base == 'post' && $wp_parent_base == 'edit' ) {
					add_action( 'media_buttons', array( $this, 'make_classic_editor_button' ), 1001 );
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/scfa-admin.js', array(), $this->version, 'all' );
				}
			}
		}
	}

	/*
	 * Setup buttons for editor
	 */
	public function make_classic_editor_button() {
		echo '<button id="scfa_shortcode" class="button" type="button">'.
		     '<span class="fas fa-code"></span> '.
		     esc_html__( 'Insert SCFA', 'scfa' ).
			 '</button>';
	}

	/**
	 * Output content for settings form
	 */
	public function setup_options_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="wrap">';
		echo '<h1>SCFA</h1>';
		echo '<h3>';
		echo '[ <span class="small fas fa-font"></span>';
		echo '&nbsp;';
		echo '<span class="fas fa-code"></span>';
		echo ' ]</h3>';
		if ( $this->scfa_asset_type == 2 ) {
			echo '<p>';
			echo esc_html__( 'Please note that to serve the specified asset URL as CSS, Font Awesome expects to be able to include web fonts relative to the URL you specify.', 'scfa' );
			echo ' ' . esc_html__( 'If you specify https://mysite.com/assets/css/all.min.css, Font Awesome will attempt to include web fonts from https://mysite.com/assets/webfonts/...', 'scfa' );
			echo '</p>';
		} elseif ( $this->scfa_asset_type == 4  ) {
			echo '<p>';
			echo esc_html__( 'Please make sure you include the Font Awesome assets in some other way.', 'scfa' );
			echo '</p>';
		}
		echo '<form method="post" action="options.php">';
		settings_fields( 'scfa-settings' );
		do_settings_sections( 'scfa-settings' );
		submit_button();
		echo '</form>';

		echo '<div style="background: rgba(220,220,220,0.95); color: black; border: 2px solid #888; margin-top: 20px; padding: 5px;">';
		echo '<h2>' . esc_html__( 'Quick reference', 'scfa' ) . '</h2>';
		echo '<p>' . esc_html__( 'This is intended as a quick reference for the shortcode. For a complete list of Font Awesome classes, please see', 'scfa').
		     ' <a href="https://fontawesome.com" target="_blank">fontawesome.com</a>. '.
			 '<i>' . esc_html__( 'Some icon styles may only be available with a Pro license', 'scfa').'</i>'.
			 '. '.
			 esc_html__( 'You can (obviously) specify everything class related in the class="" parameter if that is more convenient for you', 'scfa').
			 '.</p>';
		echo '<h3>' . esc_html__( 'General format', 'scfa' ) . '</h3>';
		//Basic usage
		echo '<p>'.
		     esc_html__( 'Generate <span> element with icon and default (icon) style:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="address-book"][/scfa]' ).
		     '</pre></p>';
		//Basic usage with style
		echo '<p>'.
		     esc_html__( 'Generate <span> element with the specified style and icon:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="far address-book"][/scfa]' ).
		     '</pre></p>';
		//Show size usage
		echo '<p>'.
		     esc_html__( 'Generate <span> element with the specified style and icon and size:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="far address-book" size="5x"][/scfa]' ).
		     '</pre></p>';
		//Show basic with additional css
		echo '<p>'.
		     esc_html__( 'Generate <span> element with the specified style and icon and custom CSS:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="fas address-book" css="font-size:20px;color:blue;"][/scfa]' ).
		     '</pre></p>';
		//Show basic with additional class
		echo '<p>'.
		     esc_html__( 'Generate <span> element with the specified style and icon and custom class:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="far address-book" class="fa-pull-right myotherclass"][/scfa]' ).
		     '</pre></p>';
		//Show basic with fixed width
		echo '<p>'.
		     esc_html__( 'Generate <span> element with the specified style and icon and fixed width:', 'scfa').
		     '<br/><pre>  ' . esc_html( '[scfa icon="fas address-book" fixed="1"][/scfa]' ).
		     '</pre></p>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Activate ourselves as a menu option
	 */
	public function setup_scfa_menu() {
		if ( ! current_user_can( 'manage_options' ) )  {
			return;
		}
		add_options_page( __( 'SCFA settings', 'scfa' ),
						 'SCFA',
					     'manage_options',
					     'scfa',
					     [ $this, 'setup_options_page' ]
						);
	}

	/**
	 * Setup the required fields (and sections)
	 */
	public function register_scfa_settings() {
		// Add section
		add_settings_section( 'scfa_section_1', 'Shortcode Font Awesome settings', false, 'scfa-settings' );
		// Add fields asset URL
		add_settings_field( 'scfa-asset-url',
						    '<label for="scfa-asset-url">' . esc_html__( 'Asset URL', 'scfa' ) . '</label>',
							[ $this, 'paint_setting_field' ],
							'scfa-settings',
							'scfa_section_1',
							array(
								'name'        => 'scfa-asset-url',
								'class'       => 'row',
								'label'       => '',
								'type'        => 'text',
								'placeholder' => __( 'Enter a valid URL for the CSS or JS file to be used', 'scfa' ),
								'helper'      => '',
								'desc'        => __( 'The URL of a Font Awesome CSS or JS file to be included when your site loads. '.
													 'Leave empty to use included Font Awesome CSS resources. '.
													 'If this is configured correctly, you will see some Font Awesome icons '.
													 'below the SCFA header on this page.', 'scfa' ),
								'default'     => '',
								'size'        => 60,
								'maxlength'   => 255,
							)
						   );
		// Add section
		add_settings_section( 'scfa_section_2', __( 'Other settings', 'scfa' ), false, 'scfa-settings' );
		add_settings_field( 'scfa-asset-type',
						    '<label for="scfa-asset-type">' . __( 'Asset type', 'scfa' ) . '</label>',
							[ $this, 'paint_setting_field' ],
							'scfa-settings',
							'scfa_section_2',
							array(
								'name'        => 'scfa-asset-type',
								'class'       => 'row',
								'type'        => 'radio',
								'options'     => array (
													1 => __( 'Serve local Font Awesome CSS', 'scfa' ),
													2 => __( 'Serve asset URL as CSS', 'scfa' ),
													3 => __( 'Serve asset URL as Font Awesome CDN kit', 'scfa' ),
													4 => __( 'None of the above, Font Awesome is activated elsewhere', 'scfa' ),
												 ),
								'desc'        => __( 'How the asset URL (above) should be used', 'scfa' ),
								'helper'      => '',
								'default'     => 1,
								'value'       => $this->scfa_asset_type,
							)
						   );
		add_settings_field( 'scfa-default-style',
						    '<label for="scfa-asset-type">' . __( 'Default style', 'scfa' ) . '</label>',
							[ $this, 'paint_setting_field' ],
							'scfa-settings',
							'scfa_section_2',
							array(
								'name'        => 'scfa-default-style',
								'class'       => 'row',
								'type'        => 'radio',
								'options'     => array (
													1 => 'Solid (fas)',
													2 => 'Regular (far)',
													3 => 'Light (fal)',
													4 => 'Duotone (fad)',
												 ),
								'desc'        => __( 'Default icon style if not specified', 'scfa' ),
								'helper'      => '',
								'default'     => 1,
								'value'       => $this->scfa_default_style,
							)
						   );
		add_settings_field( 'scfa-remove-settings',
						    '<label for="scfa-remove-settings">' . __( 'Remove settings', 'scfa' ) . '</label>',
							[ $this, 'paint_setting_field' ],
							'scfa-settings',
							'scfa_section_2',
							array(
								'name'        => 'scfa-remove-settings',
								'class'       => 'row',
								'type'        => 'checkbox',
								'desc'        => __( 'Remove all SCFA plugin settings and data when plugin is uninstalled.', 'scfa' ),
								'default'     => '0',
							)
						   );
		register_setting( 'scfa-settings', 'scfa-asset-url');
		register_setting( 'scfa-settings', 'scfa-asset-type');
		register_setting( 'scfa-settings', 'scfa-default-style');
		register_setting( 'scfa-settings', 'scfa-remove-settings');
	}

	/**
	 * Actual output of input fields HTML, etc.
	 */
	public function paint_setting_field( $args ) {
		if ( empty( $args['name'] ) ) {
			return;
		}
	    $value = get_option( $args['name'], '!' );
		$html = '';
		if ( ! empty( $args ) ) {
			if ( ! empty( $args['type'] ) && $args['type']=='checkbox' ) {
				if ( $value == '!' ) {
					// not set at all
					if ( ! empty( $args['default'] ) ) {
						$value = 1;
					} else {
						$value = 0;
					}
				}
				$html .= ' type="checkbox" value="1"'.checked( 1, ! empty( $value ), false );
				foreach( $args as $k => $v ) {
					switch( $k ) {
						case 'name':
							$html .= ' name="' . esc_attr( $v ) . '"' .
									 ' id="'   . esc_attr( $v ) . '"';
							break;
						case 'class':
							$html .= ' class="' . esc_attr( $v ) . '"';
							break;
					}//switch
				}//foreach
				echo '<input'.$html.' />';
			} elseif ( ! empty( $args['type'] ) && $args['type']=='radio' ) {
				echo '<p>';
				if (empty( $args ['value'] ) ) {
					if ( empty( $args ['default'] ) ) {
						$value = -1;
					} else {
						$value = $args ['default'];
					}
				} else {
					$value = $args ['value'];
				}
				foreach ( $args['options'] as $k => $v) {
					echo '<input type="radio" name="' . esc_attr( $args ['name'] ) . '"' .
					     'id="' . esc_attr( $args ['name'] ). '" value="' .esc_attr( $k ). '" '.
						 'class="' . esc_attr( $args ['class'] ). '"'.checked( $k, $value, false ).
						 '>' . esc_html( $v ) . '<br/>';
				}
				echo '</p>';
			} else {
				foreach ( $args as $k => $v ) {
					switch ( $k ) {
						case 'name':
							$html .= ' name="' . esc_attr( $v ) . '"' .
									 ' id="'   . esc_attr( $v ) . '"';
							break;
						case 'placeholder':
							$html .= ' placeholder="' . esc_attr__( $v ) . '"';
							break;
						case 'class':
							$html .= ' class="' . esc_attr( $v ) . '"';
							break;
						case 'size':
							$html .= ' size="' . esc_attr( $v ) . '"';
							break;
						case 'maxlength':
							$html .= ' maxlength="' . esc_attr( $v ) . '"';
							break;
						case 'type':
							$html .= ' type="' . esc_attr( $v ) . '"';
							break;
						case 'default':
							if ( empty( $value ) ) {
								$value = $v;
							}
							break;
					}//switch
				}//foreach
				echo '<input value="' . esc_attr( $value ) . '" ' . $html . ' />';
			}// !checkbox
		}
		// Handle help text
		if ( ! empty( $args['helper'] )) {
			echo '<span class="helper">' . esc_html__( $args['helper'] ) . '</span>';
		}
		// Handle supplemental description
		if ( ! empty( $args['desc'] )) {
			echo '<p class="description">' . esc_html__( $args['desc'] ) . '</p>';
		}
	}

}