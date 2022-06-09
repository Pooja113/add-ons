<?php
/**
 * Trigger this file on Plugin uninstallation
 *
 * @package  CdFeedbackForm
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

// Clear Database stored data

global $wpdb;
$table_name = $wpdb->prefix.'cd_feedback_form';
$wpdb->query( "DROP TABLE IF EXISTS '$table_name'" );
