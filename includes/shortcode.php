<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_shortcode( 'as_tenders_list', 'as_create_shortcode' );

// Создание шорткода для блоков с заявками
function as_create_shortcode( $atts ) {
  
  $block_title = $atts['block_title'];
  $tabs = $atts['tabs'];
  $tabs_title = $atts['tabs_title'];
  
  $tabs = explode( ',', $tabs );
  $tabs_title = explode( ',', $tabs_title );
  
  ob_start();
  require_once AS_PATH . 'public/partials/shortcode/shortcode.php';
  $shortcode = ob_get_clean();

  return $shortcode;
  
}