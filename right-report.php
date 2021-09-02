<?php
/**
 * @link              https://rightreport.com
 * @since             1.0.4
 * @package           Right_Report
 *
 * @wordpress-plugin
 * Plugin Name:       Right Report
 * Plugin URI:        https://rightreport.com
 * Description:       Connect to RightReport.com, so that you can share your content and have your content shared for greater reach and engagement.
 * Version:           1.0.5
 * Author:            Right Report
 * Author URI:        https://rightreport.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       right-report
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RR_VERSION', '1.0.5' );
define( 'RR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'RR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Add image sizes.
 */
add_image_size( 'rr-large', 540, 290, true );
add_image_size( 'rr-small', 220, 160, true );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-right-report-activator.php
 */
function activate_right_report() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-right-report-activator.php';
	Right_Report_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-right-report-deactivator.php
 */
function deactivate_right_report() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-right-report-deactivator.php';
	Right_Report_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_right_report' );
register_deactivation_hook( __FILE__, 'deactivate_right_report' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-right-report.php';

/**
 * Updates.
 */
require plugin_dir_path( __FILE__ ) . 'updates/plugin-update-checker.php';
$updates = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/tylerjohnsondesign/right-report',
	__FILE__,
	'right-report'
);
$updates->getVcsApi()->enableReleaseAssets();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_right_report() {

	$plugin = new Right_Report();
	$plugin->run();

}
run_right_report();
