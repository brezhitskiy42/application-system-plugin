<?php

/**
 * Plugin Name: Система заявок
 * Description: Плагин позволяет создавать систему заявок на сайте.
 * Version: 1.0.0
 */

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

// Пути к директории
define( 'AS_PATH',  plugin_dir_path( __FILE__ ) );
define( 'AS_URL',  plugin_dir_url( __FILE__ ) );

// Подключение файлов с функционалом
require_once plugin_dir_path( __FILE__ ) . 'includes/helpers.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/activation.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handle-tenders.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handle-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handle-forms.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handle-ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handle-cron.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';

register_activation_hook( __FILE__, 'as_activate' );