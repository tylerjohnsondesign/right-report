<?php
// Set classes.
$class = ( empty( get_option( 'rr_user' ) ) ) ? 'rr-inner' : 'rr-inner rr-customer rr-loading'; ?>
<div id="rr-settings" class="rr-container">
    <div class="<?php echo $class; ?>">
        <?php

        // Check options.
        if( empty( get_option( 'rr_user' ) ) ) {

            // Get panels.
            include RR_PATH . 'admin/partials/admin-panel-register.php';

        } else {

            // Output loading.
            include RR_PATH . 'assets/rr-logo.svg'; 
            include RR_PATH . 'assets/rr-loading.svg';

        } ?>
        
    </div>
</div>