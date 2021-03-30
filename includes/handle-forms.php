<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_action( 'admin_post_addadv', 'as_handle_addadv_form' );
add_action( 'admin_post_nopriv_addadv', 'as_handle_addadv_form' );
add_action( 'admin_post_edit', 'as_handle_edit_form' );
add_action( 'admin_post_nopriv_edit', 'as_handle_edit_form' );
add_action( 'admin_post_delete', 'as_handle_delete_form' );
add_action( 'admin_post_nopriv_delete', 'as_handle_delete_form' );

// Обработка добавления заявки
function as_handle_addadv_form() {
  
  if ( ! isset( $_POST['as_post_type'] ) || ! isset( $_POST['as_cat'] ) || ! isset( $_POST['as_country'] ) || ! isset( $_POST['as_city'] ) || ! isset( $_POST['as_state'] ) || ! isset( $_POST['as_title'] ) || ! isset( $_POST['as_text'] ) || ! isset( $_POST['as_name'] ) || ! isset( $_POST['as_email'] ) || ! isset( $_POST['as_phone'] ) ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
  
  $post_type = trim( sanitize_text_field( $_POST['as_post_type'] ) );
  $cat = trim( sanitize_text_field( $_POST['as_cat'] ) );
  if ( 'trade' == $post_type || 'rent' == $post_type ) {
    $subcat = trim( sanitize_text_field( $_POST['as_subcat'] ) );  
  } else {
    $subcat = null;
  }
  $country = trim( sanitize_text_field( $_POST['as_country'] ) );
  $city = trim( sanitize_text_field( $_POST['as_city'] ) );
  $state = trim( sanitize_text_field( $_POST['as_state'] ) );
  $from = trim( sanitize_text_field( $_POST['as_from'] ) );
  $to = trim( sanitize_text_field( $_POST['as_to'] ) );
  $title = trim( sanitize_text_field( $_POST['as_title'] ) );
  $text = trim( esc_html( $_POST['as_text'] ) );
  $name = trim( sanitize_text_field( $_POST['as_name'] ) );
  $email = trim( sanitize_text_field( $_POST['as_email'] ) );
  $phone = trim( sanitize_text_field( $_POST['as_phone'] ) );
  
  if ( ! $post_type || ! $cat || ! $country || ! $city || ! $state || ! $title || ! $text || ! $name || ! $email || ! $phone ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
  
  $key = as_random_str( 32 );
  
  $post_data = array(
    'post_title' => $title,
    'post_content' => $text,
    'post_status' => 'pending',
    'post_type' => $post_type,
    'tax_input' => array(
      $post_type . '_categories' => array( $cat, $subcat ),
      'regions' => array( $country, $city )
    ),
    'meta_input' => array( 'key' => $key )
  );
  
  $post_id = wp_insert_post( $post_data );
  $regions_terms = get_the_terms( $post_id, 'regions' );
  
  if ( ! $post_id ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
  
  if ( 'any' == $state ) {
    $state = array( 'new', 'fc' );
  } elseif ( 'new' == $state ) {
    $state = array( 'new' );
  } elseif ( 'fc' == $state ) {
    $state = array( 'fc' );
  }
  
  update_field( 'tender_state_field', $state, $post_id );
  update_field( 'tender_amount_from_field', $from, $post_id );
  update_field( 'tender_amount_to_field', $to, $post_id );
  update_field( 'tender_user_name_field', $name, $post_id );
  update_field( 'tender_user_email_field', $email, $post_id );
  update_field( 'tender_user_phone_field', $phone, $post_id );
  
  $email_moderation = as_get_email_template( 'email_moderation' );
  
  $edit_link = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/edit' ) ) );
  $type_text = '';
  if ( 'trade' == $post_type ) {
    $type_text = 'покупку оборудования/техники';
  } elseif ( 'rent' == $post_type ) {
    $type_text = 'аренду оборудования/техники';
  } elseif ( 'parts' == $post_type ) {
    $type_text = 'запчасти';
  } elseif ( 'services' == $post_type ) {
    $type_text = 'услуги';
  }
  
  $email_moderation = str_replace( array( '%%type_text%%', '%%edit_link%%' ), array( $type_text, $edit_link ), $email_moderation );
  
  $site_name = get_bloginfo( 'name' );
  $site_email = get_bloginfo( 'admin_email' );
  $from = "{$site_name} <{$site_email}>";
  $headers = array(
    "content-type: text/html",
    "from: {$from}"
  );  
  wp_mail( $email, 'Заявка принята на модерацию', $email_moderation, $headers );
  
  $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/save' ) ) );
  wp_redirect( $redirect_url, 301 );
  exit;
    
}

// Обработка редактирования заявки
function as_handle_edit_form() {
  
  if ( ! isset( $_POST['as_post_type'] ) || ! isset( $_POST['as_cat'] ) || ! isset( $_POST['as_country'] ) || ! isset( $_POST['as_city'] ) || ! isset( $_POST['as_state'] ) || ! isset( $_POST['as_title'] ) || ! isset( $_POST['as_text'] ) || ! isset( $_POST['as_name'] ) || ! isset( $_POST['as_email'] ) || ! isset( $_POST['as_phone'] ) ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
  
  $post_type = trim( sanitize_text_field( $_POST['as_post_type'] ) );
  $key = trim( sanitize_text_field( $_POST['as_key'] ) );
  $cat = trim( sanitize_text_field( $_POST['as_cat'] ) );
  if ( 'trade' == $post_type || 'rent' == $post_type ) {
    $subcat = trim( sanitize_text_field( $_POST['as_subcat'] ) );  
  } else {
    $subcat = null;
  }
  $country = trim( sanitize_text_field( $_POST['as_country'] ) );
  $city = trim( sanitize_text_field( $_POST['as_city'] ) );
  $state = trim( sanitize_text_field( $_POST['as_state'] ) );
  $from = trim( sanitize_text_field( $_POST['as_from'] ) );
  $to = trim( sanitize_text_field( $_POST['as_to'] ) );
  $title = trim( sanitize_text_field( $_POST['as_title'] ) );
  $text = trim( esc_html( $_POST['as_text'] ) );
  $name = trim( sanitize_text_field( $_POST['as_name'] ) );
  $email = trim( sanitize_text_field( $_POST['as_email'] ) );
  $phone = trim( sanitize_text_field( $_POST['as_phone'] ) );
  
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
  
  if ( ! $post_by_key || ! $post_type || ! $cat || ! $country || ! $city || ! $state || ! $title || ! $text || ! $name || ! $email || ! $phone ) {
    $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/edit' ) ) );
    wp_redirect( $redirect_url, 301 );
    exit;
  }
  
  $post_id = $post_by_key->ID;
  
  $post_data = array(
    'ID' => $post_id,
    'post_title' => $title,
    'post_content' => $text,
    'post_status' => 'pending',
    'post_type' => $post_type,
    'tax_input' => array(
      $post_type . '_categories' => array( $cat, $subcat ),
      'regions' => array( $country, $city )
    )
  );
  
  $post_id = wp_update_post( $post_data );
  
  if ( ! $post_id ) {
    $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/edit' ) ) );
    wp_redirect( $redirect_url, 301 );
    exit;
  }
  
  if ( 'any' == $state ) {
    $state = array( 'new', 'fc' );
  } elseif ( 'new' == $state ) {
    $state = array( 'new' );
  } elseif ( 'fc' == $state ) {
    $state = array( 'fc' );
  }
  
  update_field( 'tender_state', $state, $post_id );
  update_field( 'tender_amount_from', $from, $post_id );
  update_field( 'tender_amount_to', $to, $post_id );
  update_field( 'tender_user_name', $name, $post_id );
  update_field( 'tender_user_email', $email, $post_id );
  update_field( 'tender_user_phone', $phone, $post_id );
  
  $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/save' ) ) );
  wp_redirect( $redirect_url, 301 );
  exit;
  
}

// Обработка удаления заявки
function as_handle_delete_form() {
  
  if ( ! isset( $_POST['as_key'] ) ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
  
  $key = trim( sanitize_text_field( $_POST['as_key'] ) );
  
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
  
  if ( ! $post_by_key ) {
    $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/edit' ) ) );
    wp_redirect( $redirect_url, 301 );
    exit;
  }
  
  $post_id = $post_by_key->ID;
  
  $delete_result = wp_delete_post( $post_id );
  
  if ( ! $delete_result ) {
    $redirect_url = add_query_arg( array( 'key' => $key ), get_permalink( get_page_by_path( 'tender/edit' ) ) );
    wp_redirect( $redirect_url, 301 );
    exit;
  }
  
  wp_redirect( get_permalink( get_page_by_path( 'tender/delete' ) ), 301 );
  exit;
  
}