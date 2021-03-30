<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

// Проверка существования страницы по slug'у
function as_slug_exists( $post_name ) {
  
  global $wpdb;
  
  if ( $wpdb->get_row( "SELECT post_name FROM $wpdb->posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A' ) ) {
    return true;
  } else {
    return false;
  }
  
}

// Генерация случайной строки
function as_random_str( $length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ) {
  
  $str = '';
  $max = mb_strlen( $keyspace, '8bit' ) - 1;
  
  for ( $i = 0; $i < $length; ++$i ) {
    $str .= $keyspace[random_int( 0, $max )];
  }
  
  return $str;
  
}

// Проверка существования поста с переданным ключом
function as_is_post_key_exist() {
  
  if ( ! isset( $_GET['key'] ) ) {
    return false;
  }
  
  $key = trim( sanitize_text_field( $_GET['key'] ) );
  
  $posts = new WP_Query( array(
    'post_type' => array( 'trade', 'rent', 'parts', 'services' ),
    'post_status' => array( 'publish', 'pending' ),
    'meta_query' => array(
      array(
        'key' => 'key',
        'value' => $key
      )
    )
  ) );
  
  return $posts->have_posts();
  
}

// Получение поста с переданным ключом
function as_get_post_by_key() {
  
  $key = trim( sanitize_text_field( $_GET['key'] ) );
  
  $posts = new WP_Query( array(
    'post_type' => array( 'trade', 'rent', 'parts', 'services' ),
    'post_status' => array( 'publish', 'pending' ),
    'meta_query' => array(
      array(
        'key' => 'key',
        'value' => $key
      )
    )
  ) );
  
  $post_by_key = null;
  foreach ( $posts->posts as $post ) {
    $post_by_key = $post;
    break;
  }
  
  return $post_by_key;
  
}

// Получение шаблона email 
function as_get_email_template( $field_name ) {
  
  $email = get_field( $field_name, 'option' );
  
  if ( ! $email ) {
    
    $email = str_replace( '_', '-', $email );
    $email .= '.php';
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/' . $email;
    return ob_get_clean();
    
  }
  
  return as_remove_empty_p( $email );
  
}

// Удаление пустых строк с редактора WordPress
function as_remove_empty_p( $content ) {
  
  $content = force_balance_tags( $content );
  $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '<br />', $content );
  $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '<br />', $content );
  
  return $content;
  
}

// Количество избранных заявок
function as_favorites_count() {
  
  if ( ! isset( $_COOKIE['as_favorites'] ) ) {
    return 0;
  }
  
  return count( explode( ',', $_COOKIE['as_favorites'] ) );
  
}

// Проверка, есть ли заявка в избранных
function as_in_favorites( $post_id ) {
  
  if ( ! isset( $_COOKIE['as_favorites'] ) ) {
    return false;
  }
  
  $ids = explode( ',', $_COOKIE['as_favorites'] );
  
  if ( in_array( $post_id, $ids ) ) {
    return true;
  } else {
    return false;
  }
  
}

// Получение избранных заявок
function as_get_favorites() {
  
  if ( ! isset( $_COOKIE['as_favorites'] ) ) {
    return false;
  }
  
  $ids = explode( ',', $_COOKIE['as_favorites'] );
  
  $posts = get_posts( array(
    'numberposts' => -1,
    'post_type' => array( 'trade', 'rent', 'parts', 'services' ),
    'include' => $ids
  ) );
  
  return $posts;
  
}

// Подтверждение подписки
function as_subscribe_newsletter() {
  
  if ( ! isset( $_GET['key'] ) ) {
    return true;
  }
  
  $confirm_key = $_GET['key'];
  
  $subscriber_field = get_field( 'subscriber', 'option' );
  if ( ! $subscriber_field ) {
    return true;
  }
  
  $row = null;
  foreach ( $subscriber_field as $key => $subscriber )  {
    if ( $subscriber['subscriber_key'] == $confirm_key && ! $subscriber['is_subscribe'] ) {
      $row = $key + 1;
    }
  }
  
  if ( null === $row ) {
    return true;
  }
  
  update_sub_field( array( 'subscriber', $row, 'is_subscribe' ), true, 'option' );
  
  return false;
  
}

// Отмена подписки
function as_cancel_newsletter() {
  
  if ( ! isset( $_GET['key'] ) ) {
    return true;
  }
  
  $confirm_key = $_GET['key'];
  
  $subscriber_field = get_field( 'subscriber', 'option' );
  if ( ! $subscriber_field ) {
    return true;
  }
  
  $row = null;
  foreach ( $subscriber_field as $key => $subscriber )  {
    if ( $subscriber['subscriber_key'] == $confirm_key && $subscriber['is_subscribe'] ) {
      $row = $key + 1;
    }
  }
  
  if ( null === $row ) {
    return true;
  }
  
  update_sub_field( array( 'subscriber', $row, 'is_subscribe' ), false, 'option' );
  
  return false;
  
}