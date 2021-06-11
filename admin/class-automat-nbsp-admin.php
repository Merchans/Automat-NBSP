<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       https://kongres-online.cz/
	 * @since      1.0.0
	 *
	 * @package    Automat_Nbsp
	 * @subpackage Automat_Nbsp/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Automat_Nbsp
	 * @subpackage Automat_Nbsp/admin
	 * @author     Richard Markovič <addmarkovic@gmail.com>
	 */
	class Automat_Nbsp_Admin {

		/**
		 * The options name to be used in this plugin
		 *
		 * @since    1.0.0
		 * @access    private
		 * @var    string $option_name Option name of this plugin
		 */
		private $option_name = 'nbsp_dictionary';
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
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
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
			if ( isset($_GET['page']) && $_GET['page'] == 'automat-nbsp' ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/automat-nbsp-admin.css', array(), $this->version, 'all', true );
				wp_enqueue_style( 'multi-select', plugin_dir_url( __FILE__ ) . 'css/multi-select.css', array(), $this->version, 'all', true );
			}

		}

		/**
		 * Register the JavaScript for the admin area.
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

			if ( isset($_GET['page']) && $_GET['page'] == 'automat-nbsp' ) {

				wp_enqueue_script( 'quicksearch', plugin_dir_url( __FILE__ ) . 'js/quicksearch.js', array(
						'jquery'
				), $this->version, true );

				wp_enqueue_script( 'multiple-select', plugin_dir_url( __FILE__ ) . 'js/multiple-select.js', array(
						'jquery',
						'quicksearch'
				), $this->version, true );

				wp_enqueue_script( 'multiple-select-otions', plugin_dir_url( __FILE__ ) . 'js/multiple-select-otions.js', array(
						'jquery',
						'quicksearch',
						'multiple-select'
				), $this->version, true );

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/automat-nbsp-admin.js', array(
						'jquery',
						'quicksearch',
						'multiple-select',
						'multiple-select-otions'
				), $this->version, true );
			}
		}

		/**
		 * Add an options page under the Settings submenu
		 *
		 * @since  1.0.0
		 */
		public function add_options_page() {

			$this->plugin_screen_hook_suffix = add_menu_page(
					__( 'Automatic NBSP options', 'automat-nbsp' ),
					__( 'NBSP Settings', 'automat-nbsp' ),
					'editor',
					'automat-nbsp',
					array( $this, 'display_options_page' ),
					'dashicons-book-alt'
			);

		}

		/**
		 * Render the options page for plugin
		 *
		 * @since  1.0.0
		 */
		public function display_options_page() {
			include_once 'partials/automat-nbsp-admin-display.php';
		}


		/**
		 * @return string
		 */
		public function register_setting() {
			// Add a General section
			add_settings_section(
					$this->option_name . '_general',
					__( 'Custom', 'automat-nbsp' ),
					array( $this, $this->option_name . '_general_cb' ),
					$this->plugin_name
			);

			add_settings_field(
					$this->option_name . '_area',
					__( 'Words or phrases: <br> Each word or phrase on&nbsp;a&nbsp;new line', 'automat-nbsp' ),
					array( $this, $this->option_name . '_area_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_area', )
			);
			add_settings_field(
					$this->option_name . '_not_in_automat[]',
					__( "Disable non-breaking spaces to posts", 'automat-nbsp' ),
					array( $this, $this->option_name . '_not_in_automat_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_not_in_automat[]' )
			);

			add_settings_field(
					$this->option_name . '_case_sensitive',
					__( "Case sensitive", 'automat-nbsp' ),
					array( $this, $this->option_name . '_case_sensitive_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_case_sensitive' )
			);

			add_settings_field(
					$this->option_name . '_punctuation_marks',
					__( "Punctuation marks", 'automat-nbsp' ),
					array( $this, $this->option_name . '_punctuation_marks_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_punctuation_marks' )
			);

			add_settings_field(
					$this->option_name . '_content',
					__( "Content", 'automat-nbsp' ),
					array( $this, $this->option_name . '_content_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_content' )
			);

			add_settings_field(
					$this->option_name . '_title',
					__( "Title", 'automat-nbsp' ),
					array( $this, $this->option_name . '_title_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_title' )
			);

			add_settings_field(
					$this->option_name . '_excerpt',
					__( "Excerpt", 'automat-nbsp' ),
					array( $this, $this->option_name . '_excerpt_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_excerpt' )
			);

			add_settings_field(
					$this->option_name . '_comment',
					__( "Comment", 'automat-nbsp' ),
					array( $this, $this->option_name . '_comment_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_comment' )
			);

			add_settings_field(
					$this->option_name . '_widget',
					__( "Widget", 'automat-nbsp' ),
					array( $this, $this->option_name . '_widget_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_widget' )
			);

			add_settings_field(
					$this->option_name . '_numbers',
					__( "Number", 'automat-nbsp' ),
					array( $this, $this->option_name . '_numbers_cb' ),
					$this->plugin_name,
					$this->option_name . '_general',
					array( 'label_for' => $this->option_name . '_numbers' )
			);


			register_setting( $this->plugin_name, $this->option_name . '_area', array(
					$this,
					$this->option_name . '_sanitize_area'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_not_in_automat', array(
					$this,
					$this->option_name . '_not_inautomat_sn'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_case_sensitive', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_punctuation_marks', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_content', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_title', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_excerpt', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_comment', array(
					$this,
					$this->option_name . '_checkbox'
			) );

			register_setting( $this->plugin_name, $this->option_name . '_widget', array(
					$this,
					$this->option_name . '_checkbox'
			) );
			register_setting( $this->plugin_name, $this->option_name . '_numbers', array(
					$this,
					$this->option_name . '_checkbox'
			) );

		}

		/**
		 * Render the text for the general section
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_general_cb() {
			echo '<h3>' . __( 'Use <code>&lt;?php add_nbsp($content); ?&gt;</code> to print the custom text with the automatic <code>&amp;nbsp;</code>. Use <code>&lt;?php add_nbsp($content, false); ?&gt;</code> to only return.', 'automat-nbsp' ) . '</h3>';
		}

		/**
		 * Render the radio input field for position option
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_area_cb() {
			$is_nbsp_dictionary_area = sanitize_textarea_field( get_option( $this->option_name . '_area' ) );
			?>
			<fieldset>
				<div class="paper">
					<div class="paper-content">
						<textarea autofocus id="<?php echo $this->option_name . '_area' ?>"
								  name="<?php echo $this->option_name . '_area' ?>"><?php echo empty( $is_nbsp_dictionary_area ) ? "" : $is_nbsp_dictionary_area ?></textarea>
					</div>
				</div>
				<p><label id="<?php echo $this->option_name . '_area' ?>"
						  for="<?php echo $this->option_name . '_area' ?>">
						<?php _e( 'Add <code>&amp;nbsp;</code> after each word or&nbsp;phrase from the list.', 'automat-nbsp' ); ?>
					</label></p>
			</fieldset>
			<?php
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_case_sensitive_cb() {
			$is_punctuation_marks_checked = get_option( $this->option_name . '_case_sensitive' );
			$name                         = $this->option_name . '_case_sensitive';
			$is_checked                   = $is_punctuation_marks_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '"value="' . $is_punctuation_marks_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '"class="punctuation_marks_box toogle-box">' .
					__( "If enabled, you need to type variants of word manually. <br> For example: 'and', 'AND', 'And' etc.", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_punctuation_marks_cb() {
			$is_case_sensitive_checked = get_option( $this->option_name . '_punctuation_marks' );
			$name                      = $this->option_name . '_punctuation_marks';
			$is_checked                = $is_case_sensitive_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" class="hidden" value="' . $is_case_sensitive_checked . '" ' . $is_checked . ' > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add <code>&amp;nbsp;</code> before punctuation marks as <br> <code>!</code><code>?</code><code>:</code><code>;</code><code>%</code><code>«</code><code>»</code>", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_content_cb() {
			$is_content_checked = get_option( $this->option_name . '_content' );
			$name               = $this->option_name . '_content';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to contents", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_title_cb() {
			$is_content_checked = get_option( $this->option_name . '_title' );
			$name               = $this->option_name . '_title';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to titles", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_excerpt_cb() {
			$is_content_checked = get_option( $this->option_name . '_excerpt' );
			$name               = $this->option_name . '_excerpt';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to excerpts", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_comment_cb() {
			$is_content_checked = get_option( $this->option_name . '_comment' );
			$name               = $this->option_name . '_comment';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to comments", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_widget_cb() {
			$is_content_checked = get_option( $this->option_name . '_widget' );
			$name               = $this->option_name . '_widget';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to widgets", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}


		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_numbers_cb() {
			$is_content_checked = get_option( $this->option_name . '_numbers' );
			$name               = $this->option_name . '_numbers';
			$is_checked         = $is_content_checked ? 'checked' : '';

			echo
					'<section class="case-sensitive-box">
				<div class="case-sensitive-box__wrapp">
					<input id="' . $name . '" type="checkbox" name="' . $name . '" value="' . $is_content_checked . '" ' . $is_checked . ' class="hidden" > ' .
					'<label for="' . $name . '" class="punctuation_marks_box toogle-box">' .
					__( "Add non-breaking spaces to numbers", 'automat-nbsp' ) .
					'</label>
				</div>
			</section>';
		}

		/**
		 * Render the treshold day input for this plugin
		 *
		 * @since  1.0.0
		 */
		public function nbsp_dictionary_not_in_automat_cb() {

			$args  = array( 'post_type' => array( 'post', 'chi_video' ), 'numberposts' => - 1, );
			$posts = get_posts( $args );

			$is_content_checked = ( is_array( get_option( $this->option_name . '_not_in_automat' ) ) ? get_option( $this->option_name . '_not_in_automat' ) : array() );
			$name               = $this->option_name . '_not_in_automat[]';


			?>
			<!--			<select id='custom-headers' multiple='multiple'>-->
			<!--				<option value='elem_1' selected>elem 1</option>-->
			<!--				<option value='elem_2'>elem 2</option>-->
			<!--				<option value='elem_3'>elem 3</option>-->
			<!--				<option value='elem_4' selected>elem 4</option>-->
			<!--				<option value='elem_100'>elem 100</option>-->
			<!--			</select>-->

			<select id="custom-headers" class="multiple-select demo" name="<?php echo $name ?>"
					multiple="multiple">
				<?php
					foreach ( $posts as $post ) {
						$ID       = $post->ID;
						$selected = ( in_array( $ID, $is_content_checked ) ) ? 'selected="selected"' : '';
						?>
						<option <?php echo $selected; ?>
								value="<?php echo $ID ?>"><?php echo $post->post_title; ?>
						</option>
						<?php
					}
				?>
			</select>
			<label id="<?php echo $name ?>"
				   for="<?php echo $name ?>">
				<?php _e( 'Choose articles that you do not want to use auto nbsp.', 'automat-nbsp' ); ?>
			</label>
			<?php
		}

		//checkbox sanitization function
		function nbsp_dictionary_checkbox( $input ) {

			//returns true if checkbox is checked
			return ( isset( $input ) ? true : false );
		}

		function nbsp_dictionary_not_inautomat_sn( $array ) {

			foreach ( $array as $key => &$value ) {
				if ( is_array( $value ) ) {
					$value = recursive_sanitize_text_field( $value );
				} else {
					$value = sanitize_text_field( $value );
				}
			}

			return $array;

		}
	}
