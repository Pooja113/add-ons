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




add_action( 'admin_menu', 'velvet_options_page' );
function velvet_options_page() {
    add_menu_page(
        'Velvet',
        'Velvet Options',
        'manage_options',
        'velvetop',
        'velvet_options_page_html',
        'dashicons-dashboard',
        20
    );
    add_submenu_page(
      'velvetop',
      'Settings Options',
      'Settings Options',
      'manage_options',
      'velvetsetting',
      'velvet_suboptions_page_html'
  );
}


function velvet_options_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  require_once plugin_dir_path(__FILE__) . 'admin/view.php';

}

function velvet_suboptions_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  echo 'test';

}

function wporg_settings_init() {
  register_setting('velvetop', 'wporg_setting_name');

  add_settings_section(
      'wporg_settings_section',
      'WPOrg Settings Section', 'wporg_settings_section_callback',
      'velvetop'
  );

  add_settings_field(
      'wporg_settings_field',
      'WPOrg Setting', 'wporg_settings_field_callback',
      'velvetop',
      'wporg_settings_section'
  );
}

add_action('admin_init', 'wporg_settings_init');

function wporg_settings_section_callback() {
  echo '<p>WPOrg Section Introduction.</p>';
}

function wporg_settings_field_callback() {
  $setting = get_option('wporg_setting_name');
  ?>
  <input type="text" name="wporg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
  <?php
}