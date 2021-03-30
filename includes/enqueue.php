<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_action( 'wp_enqueue_scripts', 'as_enqueue_styles_scripts' );

// Подключение стилей и скриптов
function as_enqueue_styles_scripts() {
  
  wp_register_style( 'as-formstyler', AS_URL . 'public/css/jquery.formstyler.css' );
  wp_register_style( 'as-formstyler-theme', AS_URL . 'public/css/jquery.formstyler.theme.css' );
  wp_register_style( 'as-fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.css' );
  wp_register_style( 'as-main', AS_URL . 'public/css/main.css' );
  wp_register_style( 'as-media', AS_URL . 'public/css/media.css' );
  wp_register_style( 'as-clear', AS_URL . 'public/css/clear.css' );
  
  wp_register_script( 'as-formstyler', AS_URL . 'public/js/jquery.formstyler.js', array( 'jquery' ), false, true );
  wp_register_script( 'as-form-validator', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.77/jquery.form-validator.js', array( 'jquery' ), false, true );
  wp_register_script( 'as-fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js', array( 'jquery' ), false, true );
  wp_register_script( 'as-main', AS_URL . 'public/js/main.js', array( 'jquery' ), false, true );
  wp_register_script( 'as-clear', AS_URL . 'public/js/clear.js', array( 'jquery' ), false, true );
  
  wp_enqueue_style( 'as-formstyler' );
  wp_enqueue_style( 'as-formstyler-theme' );
  wp_enqueue_style( 'as-fancybox' );
  wp_enqueue_style( 'as-main' );
  wp_enqueue_style( 'as-media' );
  if ( is_singular( array( 'trade', 'rent', 'parts', 'services' ) ) ) {
    wp_enqueue_style( 'as-clear' );
  }
  
  wp_enqueue_script( 'as-formstyler' );
  wp_enqueue_script( 'as-form-validator' );
  wp_enqueue_script( 'as-fancybox' );
  wp_enqueue_script( 'as-main' );
  wp_localize_script( 'as-main', 'asajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
  if ( is_singular( array( 'trade', 'rent', 'parts', 'services' ) ) ) {
    wp_enqueue_script( 'as-clear' );
    wp_localize_script( 'as-clear', 'urls', array( 'tender' => get_permalink( get_page_by_path( 'tender' ) ) ) );
  }
  
}