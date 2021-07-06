<?php
/**
 * Customer.
 * 
 * @link       https://rightreport.com
 * @since      1.0.0
 *
 * @package    Right_Report
 * @subpackage Right_Report/customer
 */
class Right_Report_Customer {

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

    // Set.
    public $user_id;
    public $api;

    /**
     * Construct.
     */
    public function __construct( $id ) {

        // Set.
        $this->user_id  = $id;
        $this->api      = 'https://rightreport.com/wp-json/rr/v1/';

        // AJAX.
        add_action( 'wp_ajax_rr_create_customer', [ $this, 'rr_create_customer' ] );
        add_action( 'wp_ajax_rr_check_customer', [ $this, 'rr_check_customer' ] );
        add_action( 'wp_ajax_rr_login_customer', [ $this, 'rr_login_customer' ] );

    }

    /**
     * Create user.
     */
    public function rr_create_customer() {

        // Get data.
        if( !empty( $_POST ) ) {

            // Create args.
            $args = [
                'body'      => json_encode( $_POST['form'] ),
                'headers'   => [
                    'Content-Type'   => 'application/json',
                ],
            ];

            // POST.
            $response = wp_remote_post( $this->api . 'user', $args );

            // Decode response.
            $response = json_decode( wp_remote_retrieve_body( $response ), true );

            // Save.
            if( !empty( $response['user_id'] ) && !empty( $response['username'] ) && !empty( $response['password'] ) ) {

                // Save to the site.
                $data = [
                    'user_id'   => $response['user_id'],
                    'username'  => $response['username'],
                    'password'  => $response['password']
                ];

                // Save.
                update_option( 'rr_user', $data );

                // Set auth.
                $auth = base64_encode( $response['username'] . ':' . $response['password'] ); 

                // Set args.
                $args = [
                    'timeout'   => 60,
                    'headers'   => [
                        'Authorization' => 'Basic ' . $auth,
                    ],
                ];

                // Use wp_remote_get.
                $response = wp_remote_get( $this->api . 'login/' . $response['user_id'], $args );

                // Decode response.
                $response = json_decode( wp_remote_retrieve_body( $response ), true );

                // Check status.
                if( $response['key'] ) {

                    // Login.
                    echo 'https://rightreport.com/cart?add-to-cart=34583&id=' . $user['user_id'] . '&key=' . $response['key'];

                }

            }

            // Die.
            wp_die();

        }

    }

    /**
     * Check customer.
     */
    public function rr_check_customer() {

        // Check for data.
        if( !empty( get_option( 'rr_user' ) ) ) {

            // Get data.
            $user = get_option( 'rr_user' );

            // Set auth.
            $auth = base64_encode( $user['username'] . ':' . $user['password'] ); 

            // Set args.
            $args = [
                'timeout'   => 60,
                'headers'   => [
                    'Authorization' => 'Basic ' . $auth,
                ],
            ];

            // Use wp_remote_get.
            $response = wp_remote_get( $this->api . 'user/' . $user['user_id'], $args );

            // Decode response.
            $response = json_decode( wp_remote_retrieve_body( $response ), true );

            // Check status.
            if( $response['subscription'] ) {

                // Load dashboard.
                echo $this->load_dashboard();

            } else {

                // Set redirect. 
                $redirect = '<script>window.location.href = "https://rightreport.com/cart?add-to-cart=34583";</script>';

                // Send.
                echo $redirect;

            }

            // Die.
            wp_die();

        }

    }

    /**
     * Login customer.
     */
    public function rr_login_customer() {

        // Check for data.
        if( !empty( get_option( 'rr_user' ) ) ) {

            // Get data.
            $user = get_option( 'rr_user' );

            // Set auth.
            $auth = base64_encode( $user['username'] . ':' . $user['password'] ); 

            // Set args.
            $args = [
                'timeout'   => 60,
                'headers'   => [
                    'Authorization' => 'Basic ' . $auth,
                ],
            ];

            // Use wp_remote_get.
            $response = wp_remote_get( $this->api . 'login/' . $user['user_id'], $args );

            // Decode response.
            $response = json_decode( wp_remote_retrieve_body( $response ), true );

            // Check status.
            if( $response['key'] ) {

                // Login.
                echo 'https://rightreport.com/my-account/?id=' . $user['user_id'] . '&key=' . $response['key'];

            } else {

                // Alert.
                alert('Sorry! We had trouble logging you into your account automatically. Please try logging in manually.');

            }

            // Die.
            wp_die();

        }

    }

    /**
     * Load dashboard.
     */
    public function load_dashboard() {

        // Set output.
        $output = '';

        // Start output buffering.
        ob_start();

        // Get.
        include RR_PATH . 'admin/partials/admin-panel-dashboard.php';

        // Get contents.
        $output = ob_get_contents();

        // End.
        ob_end_clean();

        // Return.
        return $output;

    }

}