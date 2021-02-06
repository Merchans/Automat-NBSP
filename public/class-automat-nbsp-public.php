<?php

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       https://kongres-online.cz/
	 * @since      1.0.0
	 *
	 * @package    Automat_Nbsp
	 * @subpackage Automat_Nbsp/public
	 */

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    Automat_Nbsp
	 * @subpackage Automat_Nbsp/public
	 * @author     Richard Markovič <addmarkovic@gmail.com>
	 */
	class Automat_Nbsp_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of the plugin.
		 * @param string $version The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Automat_Nbsp_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Automat_Nbsp_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/automat-nbsp-public.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Automat_Nbsp_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Automat_Nbsp_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/automat-nbsp-public.js', array( 'jquery' ), $this->version, false );

		}

		/*
		 * Get the list of phrases
		 */
		public function chi_nbsp_automat( $content ) {

			if ( is_array( NBSP_SETTINGS['not_auto_nbsp'] ) && in_array( get_the_ID(), NBSP_SETTINGS['not_auto_nbsp'] ) ) {
				return $content;
			}

			$o = NBSP_SETTINGS;

			// Get phrases list
			$phrases = chi_nbsp_get_phrases();

			$pattern = array();

			// ADD PATTERNS - If phrases exists
			if ( $phrases ) {


				foreach ( $phrases as $phrase ) {

					//Possible beginnings of phrases
					$beginnings = chi_nbsp_get_phrases_beginnings();

					// Pattern: beginning + word/phrase + whitespace
					foreach ( $beginnings as $beginning ) {
						$pattern[] = '/' . $beginning . '+' . $phrase . '+\\s+/';
					}
				}
			}


			// ADD PATTERNS - Punctuation marks
			if ( isset( $o['before_punctuation'] ) && $o['before_punctuation'] == '1' ) {

				foreach ( chi_nbsp_get_punctuation_marks() as $mark ) {

					$pattern[] = dgwt_nbsp_get_pattern_for_punctuation_mark( $mark );
				}
			}


			// ADD PATTERNS - Punctuation numbers
			if ( isset( $o['number'] ) && $o['number'] == '1' ) {
				$content = preg_replace( '/([0-9]+)\s(\d)/', '$1&nbsp;$2', $content );
			}


			// Add &nbsp;
			if ( ! empty( $pattern ) ) {
				$new_content = preg_replace_callback( $pattern, 'chi_nbsp_format_matches', $content );
			}

			return $new_content;
		}

		/*
		 * Adds &nbsp; to titles
		 */
		public function chi_nbsp_automat_title( $title ) {

			if ( is_array( NBSP_SETTINGS['not_auto_nbsp'] ) && in_array( get_the_ID(), NBSP_SETTINGS['not_auto_nbsp'] ) ) {
				return $title;
			}


			$o = NBSP_SETTINGS;

			// Get phrases list
			$phrases = chi_nbsp_get_phrases();

			$new_title = $title;

			// If phrases exists
			if ( $phrases ) {

				$pattern = array();

				foreach ( $phrases as $phrase ) {

					// Pattern: whitespace + word/phrase + whitespace
					$pattern[] = '/\\s+' . $phrase . '+\\s+/';
				}

				// Add &nbsp;
				$new_title = preg_replace_callback( $pattern, 'chi_nbsp_format_matches', $title );
			}


			// ADD PATTERNS - Punctuation marks
			if ( isset( $o['before_punctuation'] ) && $o['before_punctuation'] == '1' ) {
				foreach ( chi_nbsp_get_punctuation_marks() as $mark ) {
					$pattern[] = dgwt_nbsp_get_pattern_for_punctuation_mark( $mark );
				}
			}

			// ADD PATTERNS - Punctuation numbers
			if ( isset( $o['number'] ) && $o['number'] == '1' ) {
				$title = preg_replace( '/([0-9]+)\s(\d)/', '$1&nbsp;$2', $title );

			}

			// Add &nbsp;
			if ( ! empty( $pattern ) ) {
				$new_title = preg_replace_callback( $pattern, 'chi_nbsp_format_matches', $title );
			}

			return $new_title;
		}

	}


	/*
	 * Get a regexp pattern built based on the punctuation mark
	 * @return regexp used to search into the text content
	 */
	function dgwt_nbsp_get_pattern_for_punctuation_mark( $mark ) {

		if ( $mark === '«' ) {
			// Special case, space should be after «, not before
			$pattern = '/\\' . $mark . '\\s+/';
		} else {
			$pattern = '/\\s+\\' . $mark . '/';
		}

		return $pattern;
	}

	function chi_nbsp_get_phrases() {


		// Check that conjunctions are defined
		if ( ! isset( NBSP_SETTINGS['words'] ) || empty( NBSP_SETTINGS['words'] ) ) {
			return false;
		}


		// Phrases
		$phrases_raw = NBSP_SETTINGS['words'];

		// Create array by new line
		$phrases_array = preg_split( "/\r\n|\n|\r/", $phrases_raw );


		if ( ! $phrases_array || empty( $phrases_array ) ) {
			return false;
		}


		$counter = 0;

		// Remove empty line and escape
		foreach ( $phrases_array as $phrase ) {

			// Strip whitespace from the beginning and end of a string
			$trimed_phrases = trim( $phrase );

			//Escape special chars
			$phrases_array[ $counter ] = preg_replace( "/(\.|\+|\?|\*|\^|\,|\:|\;|\"|\'|\/)/", "\\\\$1", $trimed_phrases );

			// Empty line
			if ( empty( $phrase ) ) {
				unset( $phrases_array[ $counter ] );
			}

			$counter ++;
		}


		// Reorder array keys
		$phrases = array_values( $phrases_array );


		// Case sensitive
		if ( isset( NBSP_SETTINGS['case_sensitive'] ) || NBSP_SETTINGS['case_sensitive'] == 1 ) {
			$phrases = chi_nbsp_all_phrases_variants( $phrases );
		}

		// Removes duplicate values
		$phrases_list = array_unique( $phrases );

		// Sort phrases by numbers of containing words.
		$reorder = usort( $phrases_list, "chi_nbsp_reorder_phrases" );


		if ( ! empty( $phrases_list ) && $reorder ) {
			return $phrases_list;
		} else {
			return false;
		}
	}
	/*
	 * Sort phrases by numbers of containing words.
	 * Phrases that contain more words need to be replaced first
	 */
	function chi_nbsp_reorder_phrases( $a, $b ) {

		$a_value = str_word_count( $a );

		$b_value = str_word_count( $b );

		if ( $a_value == $b_value ) {
			return 0;
		}

		return ( $a_value > $b_value ) ? - 1 : 1;
	}

	/*
	 * Possible beginnings of phrases
	 * Sometimes phrases can start with other characters than whitespace e.g.
	 * @return array of allowed characters
	 */
	function chi_nbsp_get_phrases_beginnings() {

		$beginnings = array(
			'\\s', // whitespace
			'>'
		);

		return $beginnings;
	}

	/*
	 * All punctuation marks
	 */
	function chi_nbsp_get_punctuation_marks() {

		$marks = array( '!', '?', ':', ';', '%', '«', '»' );

		return $marks;
	}


	/*
	 * Formats the phrase when you add spaces
	 * Callback function of preg_replace_callback.
	 * Replace all whitespaces for a &nbsp; in a phrase.
	 * For single word nothing change
	 * @param $matches
	 */
	function chi_nbsp_format_matches( $matches ) {

		$o = NBSP_SETTINGS;

		/*
		 * Possible beginnings of phrases.
		 * Sometimes phrases can start with other characters than whitespace e.g.
		 */
		$beginnings = chi_nbsp_get_phrases_beginnings();

		// Temporary strip whitespace from the beginning and end of a string
		$phrase_clear = trim( $matches[0] );

		$phrase_nbsp = preg_replace( '/\\s/', "&nbsp;", $phrase_clear );

		// Get first character.
		$first_char = mb_substr( $phrase_nbsp, 0, 1, get_bloginfo( 'charset' ) );

		$whitespace_first = true;
		foreach ( $beginnings as $beginning ) {

			if ( $first_char === $beginning ) {
				$phrase = $phrase_nbsp . '&nbsp;';

				$whitespace_first = false;
			}
		}

		// Restore whitespace
		if ( $whitespace_first ) {
			$phrase = ' ' . $phrase_nbsp . '&nbsp;';
		}


		if ( isset( $o['before_punctuation'] ) && $o['before_punctuation'] == '1' ) {
			$marks = chi_nbsp_get_punctuation_marks();

			if ( in_array( $phrase_clear, $marks ) ) {
				if ( $phrase_clear === '«' ) {
					// Special case, space should be after «, not before
					$phrase = ' ' . $phrase_clear . '&nbsp;';
				} else {
					$phrase = '&nbsp;' . $phrase_clear;
				}
			}

		}


		return $phrase;
	}

	/*
	 * Adds nbsp to the custom text
	 * @param string $content
	 * @param bool $echo - return or echo
	 */
	function add_nbsp( $content, $echo = true ) {
		if ( $echo ) {
			echo chi_nbsp_automat( $content );
		} else {
			return chi_nbsp_automat( $content );
		}
	}

	/*
	 * Convert phrases to lowercase and uppercase
	 */
	function chi_nbsp_all_phrases_variants( $phrases ) {

		$all_variants = array();

		foreach ( $phrases as $phrase ) {

			$all_variants[] = strtolower( $phrase );

			$all_variants[] = strtoupper( $phrase );

			if ( strlen( $phrase ) > 1 ) {
				$all_variants[] = ucfirst( $phrase );
			}
		}

		return $all_variants;
	}
