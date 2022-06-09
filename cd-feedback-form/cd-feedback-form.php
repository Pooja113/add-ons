<?php 
/**
 * Codeable Feedback Form
 *
 * @package           CdFeedbackForm
 *
 * @wordpress-plugin
 * Plugin Name:       Codeable Feedback Form
 * Plugin URI:        https://www.linkedin.com/in/pooja-paul/
 * Description:       This plugin contains a form for users to share their feedbacks and all users list is available for the administrator.
 * Version:           1.0.0
 * Author:            Pooja Paul
 * Author URI:        https://www.linkedin.com/in/pooja-paul/
 * Text Domain:       cdfeedback-form
 */


// If this file is called directly, abort.
if (!defined( 'ABSPATH')) {
    die;
}
define( 'CdFeedback_FORM_VERSION', '1.0.0' );
/**
 * The core plugin class used to define internationalization,
 * admin-specific hooks, and public site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'inc/CdFeedbackForm.php';

// activation 
require_once plugin_dir_path( __FILE__ ) . 'inc/Base/cdfeedbackformactivate.php';
register_activation_hook( __FILE__, array( 'CdFeedbackFormActivate', 'activate' ) );

// deactivation
require_once plugin_dir_path( __FILE__ ) . 'inc/Base/cdfeedbackformdeactivate.php';
register_deactivation_hook( __FILE__, array( 'CdFeedbackFormDeactivate', 'deactivate' ) );


/**
 * 
 * Begin execution of plugin
 * 
 * @since    1.0.0
 * 
 */
if ( class_exists( 'CdFeedbackForm' ) ) {
	$feedbackForm = new CdFeedbackForm();
} 