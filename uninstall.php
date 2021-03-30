<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

as_remove_pages();
as_remove_cron_tasks();

// Удаление всех страниц добавленных при активации
function as_remove_pages() {
  
  $tender_page = get_page_by_path( 'tender' );
  $trade_page = get_page_by_path( 'tender/trade' );
  $rent_page = get_page_by_path( 'tender/rent' );
  $parts_page = get_page_by_path( 'tender/parts' );
  $services_page = get_page_by_path( 'tender/services' );
  $addadv_page = get_page_by_path( 'tender/addadv' );
  $save_page = get_page_by_path( 'tender/save' );
  $edit_page = get_page_by_path( 'tender/edit' );
  $delete_page = get_page_by_path( 'tender/delete' );
  $favorites_page = get_page_by_path( 'tender/favorites' );
  $confirmation_page = get_page_by_path( 'tender/confirmation' );
  $unsubscribe_page = get_page_by_path( 'tender/unsubscribe' );
  
  $all_pages = array( $tender_page, $trade_page, $rent_page, $parts_page, $services_page, $addadv_page, $save_page, $edit_page, $delete_page, $favorites_page, $confirmation_page, $unsubscribe_page );
  
  foreach ( $all_pages as $page ) {
    if ( $page ) {
      wp_delete_post( $page->ID, true );
    }
  }
  
}

// Удаление cron задач
function as_remove_cron_tasks() {
  
  wp_clear_scheduled_hook( 'as_send_recent_tenders_event' );
  wp_clear_scheduled_hook( 'as_send_cancel_notific_event' );
  
}