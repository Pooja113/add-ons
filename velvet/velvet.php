<?php
/**
 * Plugin Name:       Velvet Plugin
 * Plugin URI:        https://www.linkedin.com/in/pooja-paul/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pooja Paul
 * Author URI:        https://www.linkedin.com/in/pooja-paul/
 * Text Domain:       velvet-plugin
 * Domain Path:       /languages
 */



/**
 * Register the "product" custom post type
 */
function velvet_setup_post_type() {
  register_post_type( 'product', ['public' => true, 'label' => 'Products' ] ); 
} 
add_action( 'init', 'velvet_setup_post_type' );


/**
* Activate the plugin.
*/
function velvet_activate() { 
  velvet_setup_post_type(); 
  flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'velvet_activate' );



/**
 * Deactivation hook.
 */
function velvet_deactivate() {
  unregister_post_type( 'product' );
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'velvet_deactivate' );

