<?php
/**
 * Widget.
 * 
 * @link       https://rightreport.com
 * @since      1.0.0
 *
 * @package    Right_Report
 * @subpackage Right_Report/widget
 */
class Right_Report_Widget extends WP_Widget {

    /**
     * Construct. 
     */
    public function __construct() {

        // Set options.
        $options = [
            'classname'     => 'Right_Report_Widget',
            'description'   => 'Display recent articles from Right Report.'
        ];

        // Construct.
        parent::__construct( 'Right_Report_Widget', 'Right Report', $options );

    }

    /**
     * Form.
     */
    public function form( $instance ) {

        // Set title.
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : ''; ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
        </p><?php

    }

    /**
     * Save data.
     */
    public function update( $new, $old ) {

        // Set old.
        $instance = $old;

        // Set.
        $instance['title'] = strip_tags( $new['title'] );

        // Return.
        return $instance;

    }

    /**
     * Display.
     */
    public function widget( $args, $instance ) {

        // Set title.
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Get recent.
        $recent = get_transient( 'rr_recent' );

        // Check transient.
        if( !$recent ) {

            // Get API.
            $api = new Right_Report_API;

            // Get recent.
            $recent = $api->recent();

            // Set transient.
            set_transient( 'rr_recent', $recent, 900 );

        }

        // Before widget.
        echo $args['before_widget'];
        
        // Output, if we have articles.
        if( !empty( $recent ) ) { ?>

            <div class="right-report-widget"><?php

                // Check for title.
                if( !empty( $title ) ) {

                    // Output.
                    echo $args['before_title'];
                    echo $title;
                    echo $args['after_title'];

                } ?>

                <div class="right-report-articles"><?php

                    // Loop through articles.
                    foreach( $recent as $r ) { ?>

                        <div class="right-report-article">
                            <div class="right-report-text">
                                <div class="right-report-title">
                                    <a href="<?php echo $r['link']; ?>" target="_blank"><?php echo $r['title']; ?></a>
                                </div>
                                <div class="right-report-meta"><?php

                                    // Check for source.
                                    if( !empty( $r['source'] ) && !empty( $r['site'] ) ) { ?>

                                        <span class="rr-source"><a href="<?php echo $r['source']; ?>" target="_blank"><?php echo $r['site']; ?></a></span><?php
                                
                                    } ?>

                                </div>
                            </div><?php

                            // Check for image.
                            if( !empty( $r['image'] ) ) { ?>
                            
                                <div class="right-report-image">
                                    <a href="<?php echo $r['link']; ?>" target="_blank">
                                        <img src="<?php echo $r['image']; ?>" alt="<?php echo $r['title']; ?>" />
                                    </a>
                                </div><?php

                            } ?>

                        </div><?php

                    } ?>

                </div>

            </div>
            <style>.right-report-articles{float:left}.right-report-article{display:flex;border-bottom:1px solid rgb(0 0 0 / 20%);margin-bottom:10px;padding-bottom:5px}.right-report-article>div{flex:1}.right-report-image{max-width:100px}.right-report-title{font-weight:700;line-height:1.2}.right-report-meta>span{font-size:12px;text-transform:uppercase}</style><?php

        }

        // After widget.
        echo $args['after_widget'];

    }

}

/**
 * Load widget.
 */
add_action( 'widgets_init', 'rr_register_widget' );
function rr_register_widget() {

    // Register.
    register_widget( 'Right_Report_Widget' );

}