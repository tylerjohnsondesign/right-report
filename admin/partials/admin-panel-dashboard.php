<?php
// Get user data.
$user = get_option( 'rr_user' ); ?>
<div class="rr-left rr-bg-dred rr-color-white" style="background:url(<?php echo RR_URL . 'assets/rr-bg.jpg'; ?>) no-repeat;">
    <div class="rr-info">
        <div class="rr-branding rr-branding-light">
            <?php include RR_PATH . 'assets/rr-logo.svg'; ?>
        </div>
        <div class="rr-content rr-text-center">
            <p class="rr-lead">
                Thanks for joining the #1 Conservative Aggregator Network!
            </p>
        </div>
    </div>
</div>
<div class="rr-right">
    <div class="rr-form">
        <div class="rr-form-lead">
            <h2>Dashboard</h2>
            <p>Welcome to your dashboard for <a href="https://rightreport.com" target="_blank">RightReport.com</a>.</p>
        </div>
        <div class="rr-form-body">
            <div class="rr-form-section rr-form-section-full">
                <span class="rr-form-label">Account Status:</span><span class="rr-form-value"><span class="rr-form-bubble rr-form-bubble-good"></span></span>
                <p>Your account is currently in good standing.</p>
                <p><strong>Username:</strong> <?php echo $user['username']; ?> | <strong>Password:</strong> <span class="hide-password-obfus">****************</span><span class="hide-password" style="display:none"><?php echo $user['password']; ?></span><span class="show-pass">View</span></p>
            </div>
            <div class="rr-form-section rr-form-section-full rr-form-section-login">
                <span class="rr-form-label">Access Account:</span><span class="rr-form-value"><span id="rr-account-login" data-user="<?php echo $data['user_id']; ?>">Login</span></span>
            </div>
        </div>
    </div>
</div>