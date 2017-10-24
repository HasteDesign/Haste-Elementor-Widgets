<?php
/**
 * Plugin Name: Elementor Custom Elements
 * Description: Custom element added to Elementor
 * Plugin URI: http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
 * Version: 0.0.1
 * Author: dtbaker
 * Author URI: http://dtbaker.net
 * Text Domain: haste-posts-widgets
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'HASTE_ELEMENTOR_WIDGETS_URL', plugins_url( '/', __FILE__ ) );
define( 'HASTE_ELEMENTOR_WIDGETS_ASSETS_URL', HASTE_ELEMENTOR_WIDGETS_URL . 'assets/' );

// This file is pretty much a boilerplate WordPress plugin.
// It does very little except including wp-widget.php
class HasteElementorWidgets {
	private static $instance = null;
	public static function get_instance() {
		if ( ! self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	public function init(){

		add_action( 'elementor/init', array( $this, 'widgets_registered' ) );

		function haste_elementor_widgets_scripts() {
			wp_enqueue_style( 'haste-elementor-widgets-style', HASTE_ELEMENTOR_WIDGETS_ASSETS_URL . 'css/haste-elementor-widgets.css' );
		}

		add_action( 'wp_enqueue_scripts', 'haste_elementor_widgets_scripts' );

	}

	public function widgets_registered() {
		// We check if the Elementor plugin has been installed / activated.
		if(defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')){
			// We look for any theme overrides for this custom Elementor element.
			// If no theme overrides are found we use the default one in this plugin.
			$widget_file = 'haste-post-widget.php';
			$template_file = locate_template($widget_file);
			if ( !$template_file || !is_readable( $template_file ) ) {
				$template_file = plugin_dir_path( __FILE__ ) . 'haste-post-widget.php';
			}
			if ( $template_file && is_readable( $template_file ) ) {
				require_once $template_file;
			}
		}

		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			if ( class_exists( 'Elementor\Plugin' ) ) {

				if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
					$elementor = Elementor\Plugin::instance();

					if ( isset( $elementor->widgets_manager ) ) {

						if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
							error_log( print_r( $widget_file, true ) );

							if ( $template_file && is_readable( $template_file ) ) {
								error_log( print_r( __FILE__ . ' - Line: '. __LINE__, true ) );
								require_once $template_file;
								Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Haste_Post_Widget() );
							}
						}
					}
				}
			}
		}
	}
}

HasteElementorWidgets::get_instance()->init();
