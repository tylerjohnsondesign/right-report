<?php
/**
 * API.
 * 
 * @link       https://rightreport.com
 * @since      1.0.0
 *
 * @package    Right_Report
 * @subpackage Right_Report/customer
 */
class Right_Report_API {

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
     * Set variables.
     */
    private $auth;
    public $api;
    public $user;

    /**
     * Construct.
     */
    public function __construct() {

        // Check for user.
        if( !empty( get_option( 'rr_user' ) ) ) {

            // Get user.
            $this->user = get_option( 'rr_user' );

            // Set auth.
            $this->auth = base64_encode( $this->user['username'] . ':' . $this->user['password'] );

            // Set API.
            $this->api = 'https://rightreport.com/wp-json/rr/v1/';

        }

        // On post save.
        add_action( 'save_post', [ $this, 'prepare_post' ], 10, 2 );

        // On trash.
        add_action( 'wp_trash_post', [ $this, 'trash_post' ], 1, 1 );

    }

    /**
     * Check for subscription.
     */
    public function check_subscription() {

        // Set.
        $has = false;

        // Check.
        if( !empty( $this->user ) ) {

            // Set args.
            $args = [
                'timeout'   => 60,
                'headers'   => [
                    'Authorization' => 'Basic ' . $this->auth,
                ],
            ];

            // Use wp_remote_get.
            $response = wp_remote_get( $this->api . 'user/' . $this->user['user_id'], $args );

            // Decode response.
            $response = json_decode( wp_remote_retrieve_body( $response ), true );

            // Check status.
            if( $response['subscription'] ) {

                // Set.
                $has = true;

            }

        }

        // Return.
        return $has;

    }

    /**
     * Send post.
     */
    public function prepare_post( $post_id, $post ) {

        // Check if this is a revision.
        if( wp_is_post_revision( $post_id ) ) return;

        // Check if is post.
        if( $post->post_type == 'post' ) {

            // Set publish.
            $valid_publish = [ 'publish', 'future' ];

            // Set update.
            $valid_update = [ 'draft', 'private', 'pending' ];

            // Set publish.
            $publish = ( isset( $_POST['rr_publish'] ) && $_POST['rr_publish'] == true ) ? true : false;

            // Set external.
            $external = ( !empty( get_post_meta( $post->ID, 'rr_external_id', true ) ) ) ? true : false;

            // Check if publish.
            if( isset( $_POST['rr_publish'] ) && $_POST['rr_publish'] && $this->check_subscription() ) { 

                // Check status.
                if( !$external && in_array( $post->post_status, $valid_publish ) ) {

                    // Publish.
                    $this->publish( $post->ID );

                } elseif( $external && in_array( $post->post_status, $valid_update ) ) {

                    // Update.
                    $this->update( $post->ID );

                }

            } elseif( !$publish && $external ) {

                // Delete.
                $this->delete( $post->ID );

            }

        }

    }

    /**
     * On trash.
     */
    public function trash_post( $post_id ) {

        // Delete.
        $this->delete( $post_id );

    }

    /**
     * Send request.
     */
    public function send_request( $data, $point, $method ) {

        // Set args.
        $args = [
            'body'      => json_encode( $data ),
            'method'    => $method,
            'headers'   => [
                'Content-Type'      => 'application/json',
                'Authorization'     => 'Basic ' . $this->auth,
            ],
        ];

        // Send.
        $response = wp_remote_post( $this->api . $point, $args );

        // Decode response.
        $response = json_decode( wp_remote_retrieve_body( $response ), true );

        // Return.
        return $response;

    }

    /**
     * Publish.
     */
    public function publish( $id ) {

        // Check for ID.
        if( !empty( $id ) ) {

            // Get post.
            $post = get_post( $id );

            // Set data.
            $data = [
                'post_date'     => $post->post_date,
                'post_date_gmt' => $post->post_date_gmt,
                'post_title'    => get_the_title( $post->ID ),
                'post_excerpt'  => ( empty( get_the_excerpt( $post->ID ) ) ) ? wp_trim_words( get_the_content( $post->ID ), 55, '...' ) : get_the_excerpt( $post->ID ),
                'post_status'   => $post->post_status,
                'post_type'     => 'post',
                'meta'          => [
                    'external_link'         => get_permalink( $post->ID ),
                    'source_optional'       => get_bloginfo( 'name' ),
                    'author_optional'       => get_the_author_meta( 'display_name', $post->post_author ),
                ],
            ];

            // Check.
            if( !empty( get_the_post_thumbnail_url( $post->ID, 'rr-large' ) ) ) {

                // Add.
                $data['meta']['external_image_large'] = get_the_post_thumbnail_url( $post->ID, 'rr-large' );

            }
            if( !empty( get_the_post_thumbnail_url( $post->ID, 'rr-small' ) ) ) {

                // Add.
                $data['meta']['external_image_small'] = get_the_post_thumbnail_url( $post->ID, 'rr-small' );

            }

            // Send.
            $response = $this->send_request( $data, 'post', 'POST' );

            // Check.
            if( !empty( $response['post_id'] ) ) {

                // Save.
                update_post_meta( $post->ID, 'rr_external_id', $response['post_id'] );

            }

        }

    }

     /**
     * Update.
     */
    public function update( $id ) {

        // Check for ID.
        if( !empty( $id ) ) {

            // Get post.
            $post = get_post( $id );

            // Get external post ID.
            $ext_id = get_post_meta( $post->ID, 'rr_external_id', true );

            // Set data.
            $data = [
                'post_date'     => $post->post_date,
                'post_date_gmt' => $post->post_date_gmt,
                'post_title'    => get_the_title( $post->ID ),
                'post_excerpt'  => ( empty( get_the_excerpt( $post->ID ) ) ) ? wp_trim_words( get_the_content( $post->ID ), 55, '...' ) : get_the_excerpt( $post->ID ),
                'post_status'   => $post->post_status,
                'post_type'     => 'post',
                'meta'          => [
                    'external_link'         => get_permalink( $post->ID ),
                    'source_optional'       => get_bloginfo( 'name' ),
                    'author_optional'       => get_the_author_meta( 'display_name', $post->post_author ),
                ],
            ];

            // Check.
            if( !empty( get_the_post_thumbnail_url( $post->ID, 'rr-large' ) ) ) {

                // Add.
                $data['meta']['external_image_large'] = get_the_post_thumbnail_url( $post->ID, 'rr-large' );

            }
            if( !empty( get_the_post_thumbnail_url( $post->ID, 'rr-small' ) ) ) {

                // Add.
                $data['meta']['external_image_small'] = get_the_post_thumbnail_url( $post->ID, 'rr-small' );

            }

            // Send.
            $response = $this->send_request( $data, 'post/' . $ext_id, 'PUT' );

            // Check.
            if( !empty( $response['post_id'] ) ) {

                // Save.
                update_post_meta( $post->ID, 'rr_external_id', $response['post_id'] );

            }

        }

    }

     /**
     * Delete.
     */
    public function delete( $id ) {

        // Check for ID.
        if( !empty( $id ) ) {

            // Get post.
            $post = get_post( $id );

            // Get external post ID.
            $ext_id = get_post_meta( $post->ID, 'rr_external_id', true );

            // Set data.
            $data = [];

            // Send.
            $response = $this->send_request( $data, 'post/' . $ext_id, 'DELETE' );

            // Check.
            if( $response ) {

                // Delete post meta.
                delete_post_meta( $post->ID, 'rr_external_id' );
                delete_post_meta( $post->ID, 'rr_publish' );

            }

        }

    }

    /**
     * Get recent.
     */
    public function recent() {

        // Set args.
        $args = [
            'timeout'   => 60,
        ];

        // Use wp_remote_get.
        $response = wp_remote_get( $this->api . 'recent', $args );

        // Decode response.
        $response = json_decode( wp_remote_retrieve_body( $response ), true );

        // Return.
        return $response;

    }

}
new Right_Report_API;