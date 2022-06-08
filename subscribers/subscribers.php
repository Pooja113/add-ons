<?php 
/**
 * Plugin Name:       Subscribers
 * Plugin URI:        https://github.com/Pooja113/
 * Description:       Display the subscribers
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pooja Paul
 * Author URI:        https://github.com/Pooja113/
 */

 if(!defined('ABSPATH')){
  exit;
 }

//Load Scripts
 require_once(plugin_dir_path(__FILE__).'/inc/subscribers-scripts.php');
 //Load Class
 require_once(plugin_dir_path(__FILE__).'/inc/subscribers-class.php');
//Register
function register_subscribers_widget(){
  register_widget('Subs_Widget');
}

add_action('widgets_init','register_subscribers_widget');



