<?php

function sub_add_scripts(){
  wp_enqueue_style('sub_style',plugins_url().'/subscribers/css/styles.css');
  wp_enqueue_script('sub_script',plugins_url().'/subscribers/js/main.js');
  wp_register_script('google_script','https://apis.google.com/js/platform.js');
  wp_enqueue_script('google_script');
}

add_action('wp_enqueue_scripts','sub_add_scripts');