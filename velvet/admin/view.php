<?php 
 if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

if ( isset( $_GET['settings-updated'] ) ) {
  add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
}

settings_errors( 'wporg_messages' );
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
          <?php
      settings_fields( 'velvetop' );
      do_settings_sections( 'velvetop' );
      submit_button( __( 'Save Settings', 'textdomain' ) );
      ?>
    </form>
  </div>



  