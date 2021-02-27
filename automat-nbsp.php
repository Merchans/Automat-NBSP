<?php

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * admin area. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 * @link              https://kongres-online.cz/
	 * @since             1.0.0
	 * @package           Automat_Nbsp
	 *
	 * @wordpress-plugin
	 * Plugin Name:       AutomatNBSP
	 * Plugin URI:        https://kongres-online.cz/
	 * Description:       Automatically adds a non-breaking space (&nbsp) in the title, excerpt, content and widgets.
	 * Version:           1.0.0
	 * Author:            Richard Markovič
	 * Author URI:        https://kongres-online.cz/
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       automat-nbsp
	 * Domain Path:       /languages
	 */

// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	/**
	 * Currently plugin version.
	 * Start at version 1.0.0 and use SemVer - https://semver.org
	 * Rename this for your plugin and update it as you release new versions.
	 */
	define( 'AUTOMAT_NBSP_VERSION', '1.0.0' );
	define( 'TEXTDOMAIN', 'automat-nbsp' );
	define( 'NBSP_SETTINGS', array(
		'before_punctuation' => get_option( 'nbsp_dictionary_punctuation_marks' ),
		'content'            => get_option( 'nbsp_dictionary_content' ),
		'title'              => get_option( 'nbsp_dictionary_title' ),
		'excerpt'            => get_option( 'nbsp_dictionary_excerpt' ),
		'comment'            => get_option( 'nbsp_dictionary_comment' ),
		'widget'             => get_option( 'nbsp_dictionary_widget' ),
		'number'             => get_option( 'nbsp_dictionary_numbers' ),
		'words'              => get_option( 'nbsp_dictionary_area' ),
		'case_sensitive'     => get_option( 'nbsp_dictionary_case_sensitive' ),
		'not_auto_nbsp'      => get_option( 'nbsp_dictionary_not_in_automat' ),
	) );

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-automat-nbsp-activator.php
	 */
	function activate_automat_nbsp() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-automat-nbsp-activator.php';
		Automat_Nbsp_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-automat-nbsp-deactivator.php
	 */
	function deactivate_automat_nbsp() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-automat-nbsp-deactivator.php';
		Automat_Nbsp_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_automat_nbsp' );
	register_deactivation_hook( __FILE__, 'deactivate_automat_nbsp' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-automat-nbsp.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_automat_nbsp() {

		$plugin = new Automat_Nbsp();
		$plugin->run();

	}

	run_automat_nbsp();


	/*
	 * Adds nbsp to the custom text
	 * @param string $content
	 * @param bool $echo - return or echo
	 */
	function add_nbsp( $content, $echo = true ) {
		if ( ! isset( $automat ) ) {
			$automat = new Automat_Nbsp_Public( $content, $echo );
		}

		if ( $echo ) {
			echo $automat->chi_nbsp_automat( $content );
		} else {
			return $automat->chi_nbsp_automat( $content );
		}
	}


