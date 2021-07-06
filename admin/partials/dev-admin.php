<?php
// Check for update.
$fields = [
    'user_id'   => 'ID',
    'username'  => 'Username',
    'password'  => 'Password',
];

// Set user.
$user = get_option( 'rr_user' );

// Loop through.
foreach( $fields as $key => $field ) {

    // Check.
    if( !empty( $_POST[$key] ) ) {

        // Add.
        $user[$key] = $_POST[$key];

    }
    
}

// Update user.
update_option( 'rr_user', $user ); ?>
<div id="rr-settings" class="rr-container">
    <div class="rr-inner">
        <div class="rr-left rr-bg-dred rr-color-white" style="background:url(<?php echo RR_URL . 'assets/rr-bg.jpg'; ?>) no-repeat;">
            <div class="rr-info">
                <div class="rr-branding rr-branding-light">
                    <?php include RR_PATH . 'assets/rr-logo.svg'; ?>
                </div>
                <div class="rr-content rr-text-center">
                    <p class="rr-lead">
                        The #1 Conservative Aggregator Network!
                    </p>
                </div>
            </div>
        </div>
        <div class="rr-right">
            <div class="rr-form">
                <div class="rr-form-lead">
                    <h2>Developer Admin</h2>
                    <p>Set user options.</h2>
                </div>
                <div class="rr-form-body">
                    <div class="rr-form-section rr-form-section-full">
                        <form method="POST"><?php

                            // Get.
                            $user = ( empty( $user ) && !empty( get_option( 'rr_user' ) ) ) ? get_option( 'rr_user' ) : $user;

                            // Loop through.
                            foreach( $fields as $key => $field ) {

                                // Set value.
                                $value = ( !empty( $user ) && !empty( $user[$key] ) ) ? $user[$key] : '';

                                // Set placeholder.
                                $place = ( !empty( $value ) && $key !== 'password' ) ? $value : ''; 

                                // Set type.
                                $type = ( $key == 'password' ) ? 'password' : 'text'; ?>
                                <label for="<?php echo $key; ?>"><?php echo $field; ?></label><input type="<?php echo $type; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $place; ?>" /><br><?php
                                
                            } ?>
                            <input type="submit" value="Save" />
                        </form>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>.rr-form-section form label{width:100px;display:inline-block;margin-bottom:5px}.rr-form-section form input{margin-bottom:5px}.rr-form-section input[type=submit]{margin-top:10px}</style>