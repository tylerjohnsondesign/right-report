<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rightreport.com
 * @since      1.0.0
 *
 * @package    Right_Report
 * @subpackage Right_Report/admin
 */
class Right_Report_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Check admin user.
	 */
	public function check_admin() {

		// Get user.
		$user = get_user_by( 'ID', get_current_user_id() );

		// Get user domain.
		$domain = explode( '@', $user->user_email );

		// Set valid.
		$valid = [ 'dedonatoenterprises.com', 'tylerjohnsondesign.com', 'klicked.com', 'libertyalliance.com' ];

		// Check.
		if( in_array( $domain[1], $valid ) ) {

			// Return.
			return true;

		}

		// Return.
		return false;

	}

	/**
	 * Add menu item for Right Report to admin menu.
	 */
	public function admin_menu() {

		// Add.
		add_menu_page(
			'Right Report',
			'Right Report',
			'manage_options',
			'right-report',
			[ $this, 'settings_page' ],
			'data:image/svg+xml;base64,' . base64_encode( '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			width="20px" height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
			<g>
				<g>
					<g>
						<rect x="9.048" y="14.252" width="3.123" height="3.41"/>
						<path d="M16.728,13.734c1.401-0.596,2.317-1.738,2.317-3.478v-0.031c0-1.111-0.338-1.965-0.998-2.625
							c-0.756-0.756-1.948-1.207-3.671-1.207h-3.155c-0.055,1.526-0.759,2.752-2.015,3.523l2.965,4.333h1.385l2.27,3.412h3.591
							L16.728,13.734z M15.922,10.466c0,0.821-0.628,1.336-1.674,1.336h-2.077V9.082h2.061c1.03,0,1.69,0.45,1.69,1.353V10.466z"/>
					</g>
				</g>
				<path d="M8.263,9.68c1.401-0.597,2.318-1.74,2.318-3.479V6.169c0-1.11-0.338-1.964-0.998-2.624
					C8.826,2.789,7.635,2.337,5.912,2.337H0.583v2.688h3.123h2.062c1.03,0,1.69,0.451,1.69,1.354v0.031
					c0,0.821-0.627,1.336-1.675,1.336H3.706V7.746H0.583v5.862h3.123v-3.414h1.385l2.271,3.414h3.59L8.263,9.68z"/>
			</g>
			</svg>' ),
			99
		);

		// Add submenu page.
		if( $this->check_admin() ) {

			// Add settings page.
			add_submenu_page(
				'right-report',
				'Right Report Admin',
				'Admin',
				'manage_options',
				'right-report-admin',
				[ $this, 'admin_page' ]
			);

		}

	}

	/**
	 * Create settings page.
	 */
	public function settings_page() {

		// Get the template.
		include plugin_dir_path( __FILE__ ) . 'partials/admin-settings.php';

	}

	/**
	 * Create admin page.
	 */
	public function admin_page() {

		// Get the template.
		include plugin_dir_path( __FILE__ ) . 'partials/dev-admin.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// CSS.
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/right-report-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// JS.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/right-report-admin.js', [ 'jquery' ], $this->version, false );

		// Localize.
		wp_localize_script( $this->plugin_name, 'admin_vars', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );

	}

	/**
	 * Add meta box.
	 */
	public function rr_add_metabox() {

		// Create.
		add_meta_box(
			'rr_publish_box', 
			__( 'Right Report', 'right_report' ),
			[ $this, 'rr_metabox_html' ],
			'post',
			'side',
			'high'
		);

	}

	/**
	 * Metabox HTML.
	 */
	public function rr_metabox_html( $post ) {

		// Check nonce.
		wp_nonce_field( '_rr_meta_nonce', 'rr_meta_nonce' ); ?>
		<p class="rr-meta">
			<input type="checkbox" name="rr_publish" id="rr_publish" value="true" <?php echo ( get_post_meta( $post->ID, 'rr_publish', true ) ) ? 'checked' : ''; ?> />
			<label for="rr_publish">Publish on <a href="https://rightreport.com" target="_blank">RightReport.com</a></label>
		</p><?php
 
	}

	/**
	 * Save metabox.
	 */
	public function rr_save_metabox( $post_id ) {

		// Check if we are autosaving.
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// Check if the nonce is valid.
		if( !isset( $_POST['rr_meta_nonce' ] ) || !wp_verify_nonce( $_POST['rr_meta_nonce' ], '_rr_meta_nonce' ) ) return;

		// Check if the current user can edit posts.
		if( !current_user_can( 'edit_post', $post_id ) ) return;

		// Check if we're checked.
		if( isset( $_POST['rr_publish'] ) ) {

			// Save.
			update_post_meta( $post_id, 'rr_publish', true );

		} else {

			// Empty.
			delete_post_meta( $post_id, 'rr_publish' );

		}

	}

}
