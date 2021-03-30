<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}


add_filter( 'cron_schedules', 'as_cron_add_one_min', 999 );
add_action( 'wp', 'as_register_cron_tasks' );
add_action( 'as_send_recent_tenders_event', 'as_send_recent_tenders' );
add_action( 'as_send_cancel_notific_event', 'as_send_cancel_notific' );

// Добавление нового интервала
function as_cron_add_one_min( $schedules ) {
  
  $schedules['one_per_two_days'] = array(
    'interval' => DAY_IN_SECONDS * 2,
    'display' => 'Раз в 2 дня'
  );
  
  return $schedules;
  
}

// Регистрация задач cron
function as_register_cron_tasks() {
  
  if ( ! wp_next_scheduled( 'as_send_recent_tenders_event' ) ) {
    wp_schedule_event( time(), 'one_per_two_days', 'as_send_recent_tenders_event' );
  }
  
  if ( ! wp_next_scheduled( 'as_send_cancel_notific_event' ) ) {
    wp_schedule_event( time(), 'hourly', 'as_send_cancel_notific_event' );
  }
  
}

// Отправление писем с последними заявками
function as_send_recent_tenders() {
  
  $subscriber_field = get_field( 'subscriber', 'option' );
  
  if ( ! $subscriber_field ) {
    return;
  }
  
  $subscribers = array();
  foreach ( $subscriber_field as $key => $subscriber ) {
    if ( $subscriber['is_subscribe'] ) {
      $subscribers[$key]['email'] = $subscriber['subscriber_email'];
      $subscribers[$key]['key'] = $subscriber['subscriber_key'];
    }
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/email/email-newsletter-rows.php';
  $email_newsletter_rows = ob_get_clean();
  
  $email_newsletter = as_get_email_template( 'email_newsletter' );
  $email_newsletter = str_replace( '%%rows%%', $email_newsletter_rows, $email_newsletter );
  
  foreach ( $subscribers as $subscriber ) {
    
    $unsubscribe_link = add_query_arg( array( 'key' => $subscriber['key'] ), get_permalink( get_page_by_path( 'tender/unsubscribe' ) ) );
    $email_newsletter_copy = $email_newsletter;
    $email_newsletter_copy = str_replace( '%%unsubscribe_link%%', $unsubscribe_link, $email_newsletter_copy );
    
    $site_name = get_bloginfo( 'name' );
    $site_email = get_bloginfo( 'admin_email' );
    $from = "{$site_name} <{$site_email}>";
    $headers = array(
      "content-type: text/html",
      "from: {$from}"
    );
    wp_mail( $subscriber['email'], 'Свежие заявки за последнее время', $email_newsletter_copy, $headers );
    
  }
  
}

// Отправление писем о просроченных заявках
function as_send_cancel_notific() {
  
  $posts = get_posts( array(
    'numberposts' => -1,
    'post_type' => array( 'trade', 'rent', 'parts', 'services' ),
    'post_status' => 'any',
    'date_query' => array(
      array(
        'before' => date( 'Y-m-d', strtotime( '-30 days' ) )
      )
    )
  ) );
  
  if ( ! $posts ) {
    return;
  }
  
  $email_delete = as_get_email_template( 'email_delete' );
  
  foreach ( $posts as $post ) {
    
    $post_id = $post->ID;
    $post_type = $post->post_type;
    
    $email_delete_copy = $email_delete;
  
    $add_link = get_permalink( get_page_by_path( 'tender/addadv' ) );
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
    $email = get_field( 'tender_user_email', $post_id );

    $email_delete_copy = str_replace( array( '%%type_text%%', '%%add_link%%' ), array( $type_text, $add_link ), $email_delete_copy );

    $site_name = get_bloginfo( 'name' );
    $site_email = get_bloginfo( 'admin_email' );
    $from = "{$site_name} <{$site_email}>";
    $headers = array(
      "content-type: text/html",
      "from: {$from}"
    );  
    wp_mail( $email, 'Заявка удалена', $email_delete_copy, $headers );
    
    wp_delete_post( $post_id );
    
  }
  
}