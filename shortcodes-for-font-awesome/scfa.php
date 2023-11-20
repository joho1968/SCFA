<?php
/**
 * SCFA Plugin bootstrap file
 *
 * @link              https://code.webbplatsen.net/wordpress/wordpress-shortcodes-for-font-awesome/
 * @since             1.0.0
 * @package           SCFA
 * @author            Joaquim Homrighausen <joho@webbplatsen.se>
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcodes for Font Awesome
 * Plugin URI:        https://code.webbplatsen.net/wordpress/wordpress-shortcodes-for-font-awesome/
 * Description:       Generate inline HTML for Font Awesome using shortcodes
 * Version:           1.4.0
 * Author:            Joaquim Homrighausen <joho@webbplatsen.se>
 * Author URI:        https://github.com/joho1968/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shortcodes-for-font-awesome
 * Domain Path:       /languages
 *
 * scfa.php (Shortcodes for Font Awesome)
 * Copyright (C) 2020-2023 Joaquim Homrighausen
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
namespace scfa;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Current plugin version.
define( 'SCFA_WORDPRESS_PLUGIN', true                          );
define( 'SCFA_VERSION',          '1.4.0'                       );
define( 'SCFA_REV',              1                             );
define( 'SCFA_PLUGINNAME_HUMAN', 'SCFA'                        );
define( 'SCFA_PLUGINNAME_SLUG',  'shortcodes-for-font-awesome' );
/*
define( 'SCFA_DEBUG',            true                          );
*/


class SCFA_Class {
	public static $instance = null;
	protected $plugin_name;
	protected $scfa_plugin_version;
    protected $scfa_have_mbstring;                // @since 1.2.0
	protected $scfa_asset_type;                   // 1=local, 2=url, 3=cdn, 4=none
	protected $scfa_default_style;                // 1=fas, 2=far, 3=fal, 4=fad
	protected $scfa_asset_url;                    // asset URL for asset_type == 2
    protected $scfa_settings_tab = '';

	final public static function getInstance( string $version = '', string $slug = '' )
	{
		null === self::$instance AND self::$instance = new self( $version, $slug );
		return( self::$instance );
	}
    /**
     * No clones please.
     *
     * @return void
     */
    final public function __clone() {
    }
    /**
     * We are not a serial
     *
     * @return void
     */
    final public function __wakeup() {
    }
	public function __construct( string $version = '', string $slug = '' ) {
        if ( empty( $version ) ) {
            if ( defined( 'SCFA_VERSION' ) ) {
                $this->scfa_plugin_version = SCFA_VERSION;
            } else {
                $this->scfa_plugin_version = '1.4.0';
            }
        } else {
            $this->scfa_plugin_version = $version;
        }
        if ( empty( $slug ) ) {
    		$this->plugin_name = SCFA_PLUGINNAME_SLUG;
        } else {
    		$this->plugin_name = $slug;
        }
        // Possibly trigger call debugging
        if ( defined('SCFA_DEBUG' ) ) {
            add_action( 'all', [$this, 'scfa_call_log'], 99999, 99);
        }

        // We only need to query this once really
        $this->scfa_have_mbstring = extension_loaded( 'mbstring' );
        // Fetch options
	    $this->scfa_asset_type = get_option( 'scfa-asset-type', 1 );
	    $this->scfa_default_style = get_option( 'scfa-default-style', 1 );
		$this->scfa_asset_url = get_option( 'scfa-asset-url', '' );
        // Validate selected configuration tab
        $this->scfa_settings_tab = ( ! empty( $_GET['tab'] ) ? $_GET['tab'] : '' );
        if ( ! in_array( $this->scfa_settings_tab, ['usage', 'about'] ) ) {
            $this->scfa_settings_tab = '';
        }
	}// CTOR

    /**
     * Fetch filemtime() of file and return it.
     *
     * Fetch filemtime() of $filename and return it, upon error, plugin_version
     * is returned instead. This could possibly simply return plugin_version in
     * production.
     *
	 * @since  1.2.0
     * @param  string $filename The file for which we want filemtime()
     * @return string
     */
    protected function resource_mtime( $filename ) {
        $filetime = @ filemtime( $filename );
        if ( $filetime === false ) {
            if ( defined('SCFA_DEBUG' ) ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): ' . 'Unable to fetch filetime for "' . $filename . '"' );
            }
            $filetime = $this->scfa_plugin_version;
        }
        return ( $filetime );
    }

    /**
     * Setup language support.
     *
     * @since 1.0.0
     */
    public function scfa_locale() {
		if ( ! load_plugin_textdomain( $this->plugin_name,
                                       false,
                                       dirname( plugin_basename( __FILE__ ) ) . '/languages' ) ) {
            /**
             * We don't consider this to be a "real" error
             */
            if ( defined('SCFA_DEBUG' ) ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): ' . 'Unable to load language file "' . dirname( plugin_basename( __FILE__ ) ) . '/languages' . '"' );
            }
        }
    }

    /**
     * Setup CSS (admin)
     *
	 * @since 1.0.0
     */
    public function scfa_setup_css() {
		switch( $this->scfa_asset_type ) {
			case 1:
				wp_register_style( 'scfa-fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome/all.min.css', array(), $this->resource_mtime( dirname(__FILE__).'/css/fontawesome/all.min.css' ), 'all' );
				wp_enqueue_style( 'scfa-fontawesome' );
				break;
			case 2:
				wp_register_style( 'scfa-fontawesome', $this->asset_url, array(), $this->version, 'all' );
				wp_enqueue_style( 'scfa-fontawesome' );
				break;
		}// switch
        if ( function_exists( 'is_admin' ) && is_admin() ) {
            wp_register_style( 'scfa-admin', plugin_dir_url( __FILE__ ) . 'css/scfa-admin.css', array(), $this->resource_mtime( dirname(__FILE__).'/css/scfa-admin.css' ), 'all' );
            wp_enqueue_style( 'scfa-admin' );
        }
    }

    /**
     * Run plugin.
     *
     * Basically "enqueues" WordPress actions and lets WordPress do its thing.
     *
     * @since 1.0.0
     */
    public function run() {
        // Admin setup
        if ( function_exists( 'is_admin' ) && is_admin() ) {
            // Setup i18n. We use the 'init' action rather than 'plugins_loaded' as per
            // https://developer.wordpress.org/reference/functions/load_plugin_textdomain/#user-contributed-notes
    		add_action( 'init',                  [$this, 'scfa_locale'] );
            // Other "admin" things to do
    		add_action( 'admin_enqueue_scripts', [$this, 'scfa_setup_css'] );
            add_action( 'admin_menu',            [$this, 'scfa_admin_setup_menu'] );
            add_action( 'admin_init',            [$this, 'scfa_admin_settings'] );
    		// Maybe "current_screen" is a better hook than "edit_form_top" to call here
    		add_action( 'edit_form_top',         [$this, 'scfa_admin_register_editor_hooks'] );
        } else {
            // Public things to do
    		add_action( 'init',                  [$this, 'shortcode_init'] );
    		add_action( 'wp_enqueue_scripts',    [$this, 'scfa_setup_css'] );
        }
        // Other setup
        //add_action( 'wp_loaded',                 [$this, 'scfa_wp_loaded'] );
    }

    /**
     * Call debugger.
     *
     * @since 1.2.0
     */
    function scfa_call_log() {
        static $call_list = array();
        // Log everything except these
        $exclude_calls = array( 'gettext', 'gettext_default', 'gettext_with_context', 'gettext_with_context_default',
                                'attribute_escape', 'esc_html', 'sanitize_title', 'debug_bar_enqueue_scripts',
                                'debug_bar_panels', 'alloptions' );
        $action = current_filter();
        if ( ! in_array( $action, $exclude_calls ) ) {
            $call_list[] = $action;
        }
        // Trigger log on last action
        if ( $action == 'shutdown' ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . ')' . "\n" );
            error_log( implode( "\n", $call_list ) );
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): --- end of list ---' );
        }
    }

	/**
	 * Activate ourselves as a menu option (admin).
     *
     * @since 1.0.0
	 */
	public function scfa_admin_setup_menu() {
        if ( ! is_admin() || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
            return;
        }
		add_options_page( esc_html__( 'SCFA settings', 'shortcodes-for-font-awesome' ),
						 'SCFA' . ' [ <span class="small fa-solid fa-font"></span>&nbsp;<span class="fa-solid fa-code"></span> ]',
					     'manage_options',
					     $this->plugin_name,
					     [ $this, 'scfa_admin_setup_options_page' ]
						);
        // Add 'Settings' link in plugin list, @since 1.2.0
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [$this, 'scfa_admin_settings_link']);
	}

    /**
     * Add link to SCFA settings in plugin list.
     *
     * @since 1.2.0
     */
    public function scfa_admin_settings_link( array $links ) {
        $our_link = '<a href ="' . esc_url( admin_url() ) . 'options-general.php?page=' . $this->plugin_name. '">' . esc_html__( 'Settings' ) . '</a> ';
        array_unshift( $links, $our_link );
        return ( $links );
    }

	/**
	 * Output content for settings form (admin).
     *
     * @since 1.0.0
	 */
	public function scfa_admin_setup_options_page() {
        if ( ! is_admin() || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
            return;
        }
        // Get ourselves a proper URL
        // $action = admin_url( 'options-general.php' ) . '?page=scfa-settings';
        $action = admin_url( 'admin.php' ) . '?page=' . $this->plugin_name;
        //
        $html = '';
        $tab_header = '<div class="wrap">';
            $tab_header .= '<h1>Shortcodes for Font Awesome 6 (SCFA)</h1>';
            $tab_header .= '<h3>';
            $tab_header .= '[ <span class="small fa-solid fa-font"></span>';
            $tab_header .= '&nbsp;';
            $tab_header .= '<span class="fa-solid fa-code"></span>';
            $tab_header .= ' ]</h3>';
            $tab_header .= '<nav class="nav-tab-wrapper">';
            $tab_header .= '<a href="' . esc_url_raw( $action ) . '" class="nav-tab' . ( empty( $this->scfa_settings_tab ) ? ' nav-tab-active':'' ) . '">'.
                     esc_html__( 'Configuration', 'shortcodes-for-font-awesome' ) .
                     '</a>';
            $tab_header .= '<a href="' . esc_url_raw( $action ) . '&tab=usage" class="nav-tab' . ( $this->scfa_settings_tab === 'usage' ? ' nav-tab-active':'' ) . '">'.
                     esc_html__( 'Quickguide', 'shortcodes-for-font-awesome' ) .
                     '</a>';
            $tab_header .= '<a href="' . esc_url_raw( $action ). '&tab=about" class="nav-tab' . ( $this->scfa_settings_tab === 'about' ? ' nav-tab-active':'' ) . '">'.
                     esc_html__( 'About', 'shortcodes-for-font-awesome' ) .
                     '</a>';
            $tab_header .= '</nav>';
            ob_start();
            if ( $this->scfa_settings_tab == 'about' ) {
                $this->scfa_about_page();
                $html .= ob_get_contents();
                ob_end_clean();
            } elseif ( $this->scfa_settings_tab == 'usage' ) {
                $html .= '<div class="tab-content">';
                $html .= '<div class="scfa-config-header">';
                $html .= '<p>' . esc_html__( 'This is intended as a quick reference for the shortcode. For a complete list of Font Awesome classes, please see', 'shortcodes-for-font-awesome' ).
                         ' <a href="https://fontawesome.com" target="_blank" class="scfa-ext-link">fontawesome.com</a> &amp; <a href="https://fontawesome.com/cheatsheet" class="scfa-ext-link" target="_blank">fontawesome.com/cheatsheet</a>.</p>' .
                         '<p>' . esc_html__( 'Some icon styles may only be available with a Pro license', 'shortcodes-for-font-awesome' ) . '</i>' .
                         '. ' .
                         esc_html__( 'You can (obviously) specify everything class related in the class="" parameter if that is more convenient for you', 'shortcodes-for-font-awesome' ) .
                         '.</p>';
                $html .= '<h2>' . esc_html__( 'General format', 'shortcodes-for-font-awesome' ) . '</h2>';
                //Basic usage
                $html .= '<h4>' . esc_html__( 'Generate <span> element with icon and default (icon) style:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="address-book"][/scfa]' ) . '</pre></p>';
                //Basic usage with style
                $html .= '<h4>' . esc_html__( 'Generate <span> element with the specified style and icon:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="far address-book"][/scfa]' ). '</pre></p>';
                //Show size usage
                $html .= '<h4>' . esc_html__( 'Generate <span> element with the specified style and icon and size:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="far address-book" size="5x"][/scfa]' ) . '</pre></p>';
                //Show basic with additional css
                $html .= '<h4>' . esc_html__( 'Generate <span> element with the specified style and icon and custom CSS:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="fas address-book" css="font-size:20px;color:blue;"][/scfa]' ) . '</pre></p>';
                //Show basic with additional class
                $html .= '<h4>' . esc_html__( 'Generate <span> element with the specified style and icon and custom class:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="far address-book" class="fa-pull-right myotherclass"][/scfa]' ) . '</pre></p>';
                //Show basic with fixed width
                $html .= '<h4>' . esc_html__( 'Generate <span> element with the specified style and icon and fixed width:', 'shortcodes-for-font-awesome' ) . '</h4>' .
                         '<p><pre>  ' . esc_html( '[scfa icon="fas address-book" fixed="1"][/scfa]' ) . '</pre></p>';
                $html .= '</div>';
                $html .= '</div>'; // tab-content
            } else {
                if ( $this->scfa_asset_type == 2 ) {
                    $tab_header .= '<div class="notice notice-info"><p>';
                    $tab_header .= esc_html__( 'Please note that to serve the specified asset URL as CSS, Font Awesome expects to be able to include web fonts relative to the URL you specify.', 'shortcodes-for-font-awesome' ).
                                   '</p>';
                    $tab_header .= '<p>' . esc_html__( 'If you specify https://mysite.com/assets/css/all.min.css, Font Awesome will attempt to include web fonts from https://mysite.com/assets/webfonts/...', 'shortcodes-for-font-awesome' ).
                                   '</p>';
                    $tab_header .= '</div>';
                } elseif ( $this->scfa_asset_type == 4  ) {
                    $tab_header .= '<div class="notice notice-info"><p>';
                    $tab_header .= esc_html__( 'Please make sure you include the Font Awesome assets in some other way.', 'shortcodes-for-font-awesome' );
                    $tab_header .= '</p></div>';
                }
                $html .= '<form method="post" action="options.php">';
                $html .= '<div class="tab-content">';
                $html .= '<div class="scfa-config-header">';
        		settings_fields( 'scfasettings' );
        		do_settings_sections( 'scfasettings' );
                submit_button();
                $html .= ob_get_contents();
                ob_end_clean();
                $html .= '</div>';
                $html .= '</div>'; // tab-content
                $html .= '</form>';
            }
        $html .= '</div>'; // wrap
        //
		echo $tab_header . $html;
	}

	/**
	 * Show about information (admin)
     *
     * @since 1.2.0
	 */
    public function scfa_about_page() {
        echo '<div class="tab-content">';
        echo '<div class="scfa-config-header">'.
             '<p>'  . esc_html__( 'Thank you for installing', 'shortcodes-for-font-awesome' ) .' Shortcodes for Font Awesome (SCFA). '.
                      esc_html__( 'This plugin provides a simple way to include', 'shortcodes-for-font-awesome' ).
                      '&nbsp;<a href="https://fontawesome.com" class="scfa-ext-link" target="_blank">Font Awesome</a> '.
                      esc_html__( 'on your WordPress site', 'shortcodes-for-font-awesome' ) . '</p>'.
             '</div>';
        echo '<div class="scfa-config-section">'.
             '<p>'  . '<img class="scfa-wps-logo" alt="" src="' . plugin_dir_url( __FILE__ ) . 'img/webbplatsen_logo.png" />' .
                      esc_html__( 'Commercial support and customizations for this plugin is available from', 'shortcodes-for-font-awesome' ) .
                      ' <a class="scfa-ext-link" href="https://webbplatsen.se" target="_blank">WebbPlatsen i Sverige AB</a> '.
                      esc_html__('in Stockholm, Sweden. We speak Swedish and English', 'shortcodes-for-font-awesome' ) . ' :-)' .
                      '<br/><br/>' .
                      esc_html__( 'The plugin was written by Joaquim Homrighausen and sponsored by WebbPlatsen i Sverige AB.', 'shortcodes-for-font-awesome' ) .
             '</p>' .
             '<p>'  . esc_html__( 'If you find this plugin useful, the author is happy to receive a donation, good review, or just a kind word.', 'shortcodes-for-font-awesome' ) . ' ' .
                      esc_html__( 'If there is something you feel to be missing from this plugin, or if you have found a problem with the code or a feature, please do not hesitate to reach out to', 'shortcodes-for-font-awesome' ) .
                                  ' <a class="scfa-ext-link" href="mailto:support@webbplatsen.se">support@webbplatsen.se</a>' .
             '</p>';
             '</div>';
        echo '</div>';
    }

	/**
	 * Setup the required fields and sections (admin)
     *
     * @since 1.0.0
	 */
	public function scfa_admin_settings() {
        if ( ! is_admin() || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
            return;
        }
        /*error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): BEFORE ' . print_r( $GLOBALS['new_whitelist_options'], true ) );*/
		// Add section
		add_settings_section( 'scfasettings', '', false, 'scfasettings' );
        // Register settings

		register_setting( 'scfasettings', 'scfa-asset-url');
		register_setting( 'scfasettings', 'scfa-asset-type');
		register_setting( 'scfasettings', 'scfa-default-style');
		register_setting( 'scfasettings', 'scfa-remove-settings');
		// Add fields asset URL
		add_settings_field( 'scfa-asset-url',
						    '<label for="scfa-asset-url">' . esc_html__( 'Asset URL', 'shortcodes-for-font-awesome' ) . '</label>',
							[ $this, 'scfa_admin_paint_setting_field' ],
							'scfasettings',
							'scfasettings',
							array(
								'name'        => 'scfa-asset-url',
								'class'       => 'row',
								'label'       => '',
								'type'        => 'text',
								'placeholder' => __( 'Enter a valid URL for the CSS or JS file to be used', 'shortcodes-for-font-awesome' ),
								'helper'      => '',
								'desc'        => __( 'The URL of a Font Awesome CSS or JS file to be included when your site loads. '.
													 'Leave empty to use included Font Awesome CSS resources. '.
													 'If this is configured correctly, you will see some Font Awesome icons '.
													 'below the SCFA header on this page.', 'shortcodes-for-font-awesome' ),
								'default'     => '',
								'size'        => 60,
								'maxlength'   => 255,
							)
						   );
		// Add section
		//add_settings_section( 'scfa-section-2', __( 'Other settings', 'shortcodes-for-font-awesome' ), false, 'shortcodes-for-font-awesome' );
		add_settings_field( 'scfa-asset-type',
						    '<label for="scfa-asset-type">' . __( 'Asset type', 'shortcodes-for-font-awesome' ) . '</label>',
							[ $this, 'scfa_admin_paint_setting_field' ],
							'scfasettings',
							'scfasettings',
							array(
								'name'        => 'scfa-asset-type',
								'class'       => 'row',
								'type'        => 'radio',
								'options'     => array (
													1 => __( 'Serve local Font Awesome CSS', 'shortcodes-for-font-awesome' ) . ' ("FontAwesome Free")',
													2 => __( 'Serve asset URL as CSS', 'shortcodes-for-font-awesome' ),
													3 => __( 'Serve asset URL as Font Awesome CDN kit', 'shortcodes-for-font-awesome' ),
													4 => __( 'None of the above, Font Awesome is activated elsewhere', 'shortcodes-for-font-awesome' ),
												 ),
								'desc'        => __( 'How the asset URL (above) should be used', 'shortcodes-for-font-awesome' ),
								'helper'      => '',
								'default'     => 1,
								'value'       => $this->scfa_asset_type,
							)
						   );
		add_settings_field( 'scfa-default-style',
						    '<label for="scfa-default-style">' . __( 'Default style', 'shortcodes-for-font-awesome' ) . '</label>',
							[ $this, 'scfa_admin_paint_setting_field' ],
							'scfasettings',
							'scfasettings',
							array(
								'name'        => 'scfa-default-style',
								'class'       => 'row',
								'type'        => 'radio',
								'options'     => array (
													1 => 'Solid (fa-solid)',
													2 => 'Regular (fa-regular)',
													3 => 'Light (fa-light)',
													4 => 'Duotone (fa-duotone)',
													5 => 'Thin (fa-thin)',
												 ),
								'desc'        => __( 'Default icon style if not specified', 'shortcodes-for-font-awesome' ),
								'helper'      => '',
								'default'     => 1,
								'value'       => $this->scfa_default_style,
							)
						   );
		add_settings_field( 'scfa-remove-settings',
						    '<label for="scfa-remove-settings">' . __( 'Remove settings', 'shortcodes-for-font-awesome' ) . '</label>',
							[ $this, 'scfa_admin_paint_setting_field' ],
							'scfasettings',
							'scfasettings',
							array(
								'name'        => 'scfa-remove-settings',
								'class'       => 'row',
								'type'        => 'checkbox',
								'desc'        => __( 'Remove all SCFA plugin settings and data when plugin is uninstalled.', 'shortcodes-for-font-awesome' ),
								'default'     => '0',
							)
						   );


        //if ( defined('SCFA_DEBUG' ) ) {
        /*
        error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): AFTER ' . print_r( $GLOBALS['new_whitelist_options'], true ) );
        global $wp_registered_settings;
        error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): REG ' . print_r( $wp_registered_settings, true ) );
        */
    }

	/**
	 * Actual output of input fields HTML, etc.
	 */
	public function scfa_admin_paint_setting_field( $args ) {
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
					     'id="' . esc_attr( $args ['name'] ). '" value="' . esc_attr( $k ) . '" '.
						 'class="' . esc_attr( $args ['class'] ) . '"' . checked( $k, $value, false ).
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

	/**
	 * Do editor related things if we're editing/creating page/post (admin).
	 *
	 * @since 1.0.0
	 */
	public function scfa_admin_register_editor_hooks() {
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
					add_action( 'media_buttons', [$this, 'scfa_admin_make_classic_editor_button'], 1001 );
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/scfa-admin.js', array(), $this->resource_mtime( dirname(__FILE__).'/js/scfa-admin.js' ), 'all' );
				}
			}
		}
	}

	/*
	 * Setup buttons for editor (admin).
	 *
	 * @since 1.0.0
	 */
	public function scfa_admin_make_classic_editor_button() {
		echo '<button id="scfa_shortcode" class="button" type="button">'.
		     '<span class="fa-solid fa-code"></span> '.
		     esc_html__( 'Insert SCFA', 'shortcodes-for-font-awesome' ).
			 '</button>';
	}

	/**
	 * Create actual output for shortcode(s).
	 *
	 * @access protected
	 * @since 1.0.0
     * @param mixed $args Typically, an array
     * @return string
	 */
	protected function scfa_shortcode_handle( $args ) {
		$html = '';
		if ( is_array( $args ) ) {
			$fa_styles = array( 'fas', 'far', 'fal', 'fad', 'fat', 'fab', 'fa-solid', 'fa-regular', 'fa-light', 'fa-duotone', 'fa-thin', 'fa-brands');
			$s_class = $s_size = $s_addclass = $s_addcss = $s_fixed = '';
			foreach( $args as $k => $v ) {
				$v = trim ($v);
				switch( $k ) {
					case 'icon':
                        if ( $this->scfa_have_mbstring ) {
                            $s_len = mb_strlen( $v );
                        } else {
                            $s_len = strlen( $v );
                        }
						if ( $s_len > 0 ) {
							$x_icon = explode( ' ', $v, 100 );
							if ( is_array( $x_icon ) ) {
								$is_first = true;
								foreach ( $x_icon as $icon ) {
									if ( in_array ( $icon, $fa_styles ) ) {
										//One of our known styles, simply add it
										$s_classs .= ( $is_first ? '' : ' ' ) . $icon;
									} else {
                                        if ( $this->scfa_have_mbstring ) {
                                            if ( mb_strpos( $icon, 'fa-' ) !== 0 ) {
                                                //Add 'fa-' prefix
                                                $icon = 'fa-' . $icon;
                                            }
                                        } else {
                                            if ( strpos( $icon, 'fa-' ) !== 0 ) {
                                                //Add 'fa-' prefix
                                                $icon = 'fa-' . $icon;
                                            }
                                        }
										$s_class .= ( $is_first ? '' : ' ' ) . $icon;
									}
									$is_first = false;
								}//foreach
							}//is_array
						}
						break;
					case 'size':
                        if ( $this->scfa_have_mbstring ) {
                            if ( mb_strlen( $v ) > 0 ) {
                                if ( mb_strpos( $v, 'fa-' ) !== 0 ) {
                                    $v = 'fa-' . $v;
                                }
                                $s_size = ' ' . $v;
                            }
                        } else {
                            if ( strlen( $v ) > 0 ) {
                                if ( strpos( $v, 'fa-' ) !== 0 ) {
                                    $v = 'fa-' . $v;
                                }
                                $s_size = ' ' . $v;
                            }
                        }
						break;
					case 'class':
						$s_addclass = ' ' . $v;
						break;
					case 'css':
						$s_addcss = $v;
						break;
					case 'fixed':
                        if ( $this->scfa_have_mbstring ) {
                            if ( mb_strlen( $v ) == 0 || $v != '0' ) {
                                $s_fixed = ' fa-fw';
                            }
                        } else {
                            if ( strlen( $v ) == 0 || $v != '0' ) {
                                $s_fixed = ' fa-fw';
                            }
                        }
						break;
				}//switch
			}//foreach

			//Possibly apply default style
            if ( $this->scfa_have_mbstring ) {
    			$x_class = explode( ' ', mb_strtolower( $s_class ), 100 );
            } else {
    			$x_class = explode( ' ', strtolower( $s_class ), 100 );
            }
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
					switch( $this->scfa_default_style ) {
						default://fas
							$s_class = 'fa-solid ' . $s_class;
							break;
						case 2:
							$s_class = 'fa-regular ' . $s_class;
							break;
						case 3:
							$s_class = 'fa-light ' . $s_class;
							break;
						case 4:
							$s_class = 'fa-duotone ' . $s_class;
							break;
						case 5:
							$s_class = 'fa-thin ' . $s_class;
							break;
					}//switch
				}//apply default style
			}//is_array ()

			//Generate final output
			$html .= '<span class="' . trim( esc_attr( $s_class ) .
   			                                 esc_attr( $s_size ) .
								 	         esc_attr( $s_fixed ) .
									         esc_attr( $s_addclass ) ) . '"';
			if ( ! empty( $s_addcss ) ) {
				$html .= ' style="' . esc_attr( $s_addcss ) . '"';
			}
			$html .= '></span>';
		}
		return ($html);
	}

	/**
	 * Shortcode handler for [scfa][/scfa]
	 *
	 * @since 1.0.0
	 */
	public function scfa_shortcode( $args ) {
		return( $this->scfa_shortcode_handle( $args, false ) );
	}

	/**
	 * Register the shortcodes for the public-facing side of the site.
	 *
	 * This function is added as an init action in run().
	 *
	 * @since 1.0.0
	 */
	public function shortcode_init() {
		add_shortcode( 'scfa', [ $this, 'scfa_shortcode' ] );
		add_filter( 'widget_text', 'do_shortcode' );
	}

    /**
     * Activation of plugin.
     *
     * We don't really need to do anything at activation of the plugin
     *
     * @since 1.0.0
     */
    /*
    public function scfa_activate_plugin() {
    }
    */
    /**
     * Deactivation of plugin.
     *
     * We don't really need to do anything at activation of the plugin
     *
     * @since 1.0.0
     */
    /*
    public function scfa_deactivate_plugin() {
    }
    */
}// SCFA


/**
 * Run plugin
 *
 * @since 1.0.0
 */
function run_scfa() {
	$plugin = SCFA_Class::getInstance( SCFA_VERSION, SCFA_PLUGINNAME_SLUG );
	$plugin->run();
}

run_scfa();
