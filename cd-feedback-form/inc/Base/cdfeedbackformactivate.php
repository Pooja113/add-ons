<?php
/**
 * Trigger the file on plugin activation
 *
 * @package  CdFeedbackForm
 */

class CdFeedbackFormActivate
{
    public static function activate(){
        global $wpdb;
        
		$table_name = $wpdb->prefix.'cd_feedback_form';

		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				form_data longtext NOT NULL,
				form_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
		flush_rewrite_rules();
    }
} 