<div class="rr-left rr-bg-dred rr-color-white" style="background:url(<?php echo RR_URL . 'assets/rr-bg.jpg'; ?>) no-repeat;">
    <div class="rr-info">
        <div class="rr-branding rr-branding-light">
            <?php include RR_PATH . 'assets/rr-logo.svg'; ?>
        </div>
        <div class="rr-content rr-text-center">
            <p class="rr-lead">
                Join the #1 Conservative Aggregator Network and start increasing your traffic today!
            </p>
            <p>By joining, you agree to pay $50 a month be a member of the Right Report network. You agree that our widget/plugin will appear in the top right column of your website. You can cancel at anytime. Must be a website built on WordPress in order to join.</p>
        </div>
    </div>
</div>
<div class="rr-right">
    <div class="rr-form">
        <div class="rr-form-lead">
            <h2>Let's get started...</h2>
            <p>First, we need to get some information about you.</p>
        </div>
        <form id="rr-register-form" name="rr-register" method="POST">
            <div class="rr-field rr-field-full">
                <label for="website">Website</label>
                <input type="text" name="website" value="<?php echo site_url(); ?>" readonly/>
            </div>
            <div class="rr-field rr-field-full">
                <label for="email">Email Address</label>
                <input type="text" name="email"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="address_1">Address</label>
                <input type="text" name="address_1"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="city">City</label>
                <input type="text" name="city"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="state">State</label>
                <select name="state">
                    <option value="">Select a state...</option><?php

                    // Get the helper.
                    $helper = new Right_Report_Helper;

                    // Loop through states.
                    foreach( $helper->get_states() as $abb => $state ) { ?>

                        <option value="<?php echo $abb; ?>"><?php echo $state; ?></option><?php


                    } ?>

                </select>
            </div>
            <div class="rr-field rr-field-half">
                <label for="country">Country</label>
                <input type="text" value="United States" name="country" readonly/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="postcode">Postcode</label>
                <input type="number" name="postcode"/>
            </div>
            <div class="rr-field rr-field-half">
                <label for="phone">Phone</label>
                <input type="tel" name="phone"/>
            </div>
            <input class="rr-submit" type="submit" value="Submit" />
        </form>
    </div>
</div>