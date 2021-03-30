<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

// Активация плагина
function as_activate() {
  
  as_check_plugins_exists();
  as_create_tender_page();
  as_create_trade_page();
  as_create_rent_page();
  as_create_parts_page();
  as_create_services_page();
  as_create_addadv_page();
  as_create_save_page();
  as_create_edit_page();
  as_create_delete_page();
  as_create_favorites_page();
  as_create_confirmation_page();
  as_create_unsubscribe_page();
  
}

// Проверка на наличие нужных плагинов
function as_check_plugins_exists() {
  
  if ( ! in_array( 'advanced-custom-fields-pro/acf.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    wp_die( '<p>Нужный плагин <b>Advanced Custom Fields PRO</b> не активирован.</p>', 'Ошибка при активации плагина', [ 'response' => 200, 'back_link' => TRUE ] );
  }
  
  if ( ! in_array( 'cyr3lat/cyr-to-lat.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    wp_die( '<p>Нужный плагин <b>Cyr to Lat enhanced</b> не активирован.</p>', 'Ошибка при активации плагина', [ 'response' => 200, 'back_link' => TRUE ] );
  }
  
}

// Создание страницы "Заявки"
function as_create_tender_page() {
  
  $page_title = 'Заявки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'tender'
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Покупки"
function as_create_trade_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Покупки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'trade',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Аренда"
function as_create_rent_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Аренда';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'rent',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Запчасти"
function as_create_parts_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Запчасти';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'parts',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Услуги"
function as_create_services_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Услуги';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'services',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Разместить заявку"
function as_create_addadv_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Разместить заявку';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'addadv',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Сохранение заявки"
function as_create_save_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Сохранение заявки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'save',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Редактировать заявку"
function as_create_edit_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Редактировать заявку';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'edit',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Удаление заявки"
function as_create_delete_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Удаление заявки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'delete',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Избранные заявки"
function as_create_favorites_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Избранные заявки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'favorites',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Подтверждение подписки"
function as_create_confirmation_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Подтверждение подписки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'confirmation',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}

// Создание страницы "Отмена подписки"
function as_create_unsubscribe_page() {
  
  $tender_page_check = get_page_by_title( 'Заявки' );
  if ( ! isset( $tender_page_check->ID ) ) {
    return;
  }
  
  $page_title = 'Отмена подписки';
  $page_check = get_page_by_title( $page_title );
  $page = array(
    'post_type' => 'page',
    'post_title' => $page_title,
    'post_author' => 1,
    'post_status' => 'publish',
    'post_name' => 'unsubscribe',
    'post_parent' => $tender_page_check->ID
  );
  
  if ( ! isset( $page_check->ID ) && ! as_slug_exists( $page_title ) ) {
    wp_insert_post( $page );
  }
  
}