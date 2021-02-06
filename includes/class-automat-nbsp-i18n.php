<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://kongres-online.cz/
 * @since      1.0.0
 *
 * @package    Automat_Nbsp
 * @subpackage Automat_Nbsp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Automat_Nbsp
 * @subpackage Automat_Nbsp/includes
 * @author     Richard Markovič <addmarkovic@gmail.com>
 */
class Automat_Nbsp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'automat-nbsp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
