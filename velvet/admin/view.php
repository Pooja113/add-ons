<?php 
 if ( ! current_user_can( 'manage_options' ) ) {
  return;
}
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="<?php menu_page_url( 'velvetop' ) ?>" method="post">
          <?php
      settings_fields( 'velvet_options' );
      do_settings_sections( 'velvetop' );
      submit_button( __( 'Save Settings', 'textdomain' ) );
      ?>
    </form>
  </div>