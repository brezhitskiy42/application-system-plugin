<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_action( 'init', 'as_register_tender_types' );
add_action( 'init', 'as_register_tender_taxes' );

add_action( 'acf/init', 'as_add_category_custom_fields' );
add_action( 'acf/init', 'as_add_region_custom_field' );
add_action( 'acf/init', 'as_add_tender_custom_fields' );

add_action( 'acf/input/admin_head', 'as_hide_state_field' );

add_action( 'template_redirect', 'as_tender_page_redirect' );
add_action( 'template_redirect', 'as_save_page_redirect' );
add_action( 'template_redirect', 'as_edit_page_redirect' );
add_action( 'template_redirect', 'as_confirmation_page_redirect' );
add_action( 'template_redirect', 'as_unsubscribe_page_redirect' );

add_filter( 'the_content', 'as_add_content_to_main_page' );
add_filter( 'the_content', 'as_add_content_to_trade_page' );
add_filter( 'the_content', 'as_add_content_to_rent_page' );
add_filter( 'the_content', 'as_add_content_to_parts_page' );
add_filter( 'the_content', 'as_add_content_to_services_page' );
add_filter( 'the_content', 'as_add_content_to_addadv_page' );
add_filter( 'the_content', 'as_add_content_to_save_page' );
add_filter( 'the_content', 'as_add_content_to_edit_page' );
add_filter( 'the_content', 'as_add_content_to_delete_page' );
add_filter( 'the_content', 'as_add_content_to_single_tender' );
add_filter( 'the_content', 'as_add_content_to_favorites_page' );
add_filter( 'the_content', 'as_add_content_to_confirmation_page' );
add_filter( 'the_content', 'as_add_content_to_unsubscribe_page' );

add_action( 'pending_to_publish', 'as_send_tender_confirm' );
add_action( 'pending_to_trash', 'as_send_tender_cancel' );

// Регистрация кастомных типов
function as_register_tender_types() {
  
  $labels = array(
    'name' => 'Заявки на покупки',
    'singular_name' => 'Заявка на покупку',
    'menu_name' => 'Заявки на покупки',
    'all_items' => 'Все',
    'add_new' => 'Добавить новую',
    'add_new_item' => 'Добавить новую',
    'edit_item' => 'Редактировать',
    'new_item' => 'Новая',
    'view_item' => 'Посмотреть',
    'view_items' => 'Посмотреть',
    'search_items' => 'Искать',
    'not_found' => 'Не найдено',
    'not_found_in_trash' => 'Не найдено в корзине',
    'parent_item_colon' => 'Родитель',
    'featured_image' => 'Изображение',
    'set_featured_image' => 'Установить изображение',
    'remove_featured_image' => 'Удалить изображение',
    'use_featured_image' => 'Использовать изображение',
    'archives' => 'Архивы',
    'insert_into_item' => 'Вставить',
    'uploaded_to_this_item' => 'Загрузить',
    'filter_items_list' => 'Фильтровать список',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список',
    'attributes' => 'Атрибуты',
    'parent_item_colon' => 'Родитель'
  );

  $args = array(
    'label' => 'Заявки на покупки',
    'labels' => $labels,
    'description' => '',
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_rest' => false,
    'rest_base' => '',
    'has_archive' => false,
    'show_in_menu' => true,
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'trade', 'with_front' => false ),
    'query_var' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-post',
    'supports' => array( 'title', 'editor' )
  );

  register_post_type( 'trade', $args );
  
  $labels = array(
    'name' => 'Заявки на аренду',
    'singular_name' => 'Заявка на аренду',
    'menu_name' => 'Заявки на аренду',
    'all_items' => 'Все',
    'add_new' => 'Добавить новую',
    'add_new_item' => 'Добавить новую',
    'edit_item' => 'Редактировать',
    'new_item' => 'Новая',
    'view_item' => 'Посмотреть',
    'view_items' => 'Посмотреть',
    'search_items' => 'Искать',
    'not_found' => 'Не найдено',
    'not_found_in_trash' => 'Не найдено в корзине',
    'parent_item_colon' => 'Родитель',
    'featured_image' => 'Изображение',
    'set_featured_image' => 'Установить изображение',
    'remove_featured_image' => 'Удалить изображение',
    'use_featured_image' => 'Использовать изображение',
    'archives' => 'Архивы',
    'insert_into_item' => 'Вставить',
    'uploaded_to_this_item' => 'Загрузить',
    'filter_items_list' => 'Фильтровать список',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список',
    'attributes' => 'Атрибуты',
    'parent_item_colon' => 'Родитель'
  );

  $args = array(
    'label' => 'Заявки на аренду',
    'labels' => $labels,
    'description' => '',
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_rest' => false,
    'rest_base' => '',
    'has_archive' => false,
    'show_in_menu' => true,
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'rent', 'with_front' => true ),
    'query_var' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-post',
    'supports' => array( 'title', 'editor' )
  );

  register_post_type( 'rent', $args );
  
  $labels = array(
    'name' => 'Заявки на запчасти',
    'singular_name' => 'Заявка на запчасть',
    'menu_name' => 'Заявки на запчасти',
    'all_items' => 'Все',
    'add_new' => 'Добавить новую',
    'add_new_item' => 'Добавить новую',
    'edit_item' => 'Редактировать',
    'new_item' => 'Новая',
    'view_item' => 'Посмотреть',
    'view_items' => 'Посмотреть',
    'search_items' => 'Искать',
    'not_found' => 'Не найдено',
    'not_found_in_trash' => 'Не найдено в корзине',
    'parent_item_colon' => 'Родитель',
    'featured_image' => 'Изображение',
    'set_featured_image' => 'Установить изображение',
    'remove_featured_image' => 'Удалить изображение',
    'use_featured_image' => 'Использовать изображение',
    'archives' => 'Архивы',
    'insert_into_item' => 'Вставить',
    'uploaded_to_this_item' => 'Загрузить',
    'filter_items_list' => 'Фильтровать список',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список',
    'attributes' => 'Атрибуты',
    'parent_item_colon' => 'Родитель'
  );

  $args = array(
    'label' => 'Заявки на запчасти',
    'labels' => $labels,
    'description' => '',
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_rest' => false,
    'rest_base' => '',
    'has_archive' => false,
    'show_in_menu' => true,
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'parts', 'with_front' => true ),
    'query_var' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-post',
    'supports' => array( 'title', 'editor' )
  );

  register_post_type( 'parts', $args );
  
  $labels = array(
    'name' => 'Заявки на услуги',
    'singular_name' => 'Заявка на услугу',
    'menu_name' => 'Заявки на услуги',
    'all_items' => 'Все',
    'add_new' => 'Добавить новую',
    'add_new_item' => 'Добавить новую',
    'edit_item' => 'Редактировать',
    'new_item' => 'Новая',
    'view_item' => 'Посмотреть',
    'view_items' => 'Посмотреть',
    'search_items' => 'Искать',
    'not_found' => 'Не найдено',
    'not_found_in_trash' => 'Не найдено в корзине',
    'parent_item_colon' => 'Родитель',
    'featured_image' => 'Изображение',
    'set_featured_image' => 'Установить изображение',
    'remove_featured_image' => 'Удалить изображение',
    'use_featured_image' => 'Использовать изображение',
    'archives' => 'Архивы',
    'insert_into_item' => 'Вставить',
    'uploaded_to_this_item' => 'Загрузить',
    'filter_items_list' => 'Фильтровать список',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список',
    'attributes' => 'Атрибуты',
    'parent_item_colon' => 'Родитель'
  );

  $args = array(
    'label' => 'Заявки на услуги',
    'labels' => $labels,
    'description' => '',
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_rest' => false,
    'rest_base' => '',
    'has_archive' => false,
    'show_in_menu' => true,
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'services', 'with_front' => true ),
    'query_var' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-post',
    'supports' => array( 'title', 'editor' )
  );

  register_post_type( 'services', $args );
  
}

// Регистрация кастомных таксономий
function as_register_tender_taxes() {
  
  $labels = array(
    'name' => 'Категории',
    'singular_name' => 'Категория',
    'menu_name' => 'Категории',
    'all_items' => 'Все',
    'edit_item' => 'Редактировать',
    'view_item' => 'Смотреть',
    'update_item' => 'Обновить название',
    'add_new_item' => 'Добавить новую',
    'new_item_name' => 'Новое название',
    'parent_item' => 'Родитель',
    'parent_item_colon' => 'Родитель:',
    'search_items' => 'Искать',
    'popular_items' => 'Популярные',
    'separate_items_with_commas' => 'Элементы разделяются запятыми',
    'add_or_remove_items' => 'Добавить или удалить',
    'choose_from_most_used' => 'Выбрать из наиболее используемых',
    'not_found' => 'Не найдено',
    'no_terms' => 'Нет элементов',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список'
  );

  $args = array(
    'label' => 'Категории',
    'labels' => $labels,
    'public' => false,
    'hierarchical' => true,
    'label' => 'Категории',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'query_var' => array( 'slug' => 'trade_categories', 'with_front' => true, ),
    'query_var' => false,
    'show_in_rest' => false,
    'show_in_rest' => '',
    'show_in_rest' => true
  );
  
  register_taxonomy( 'trade_categories', array( 'trade' ), $args );
  
  $labels = array(
    'name' => 'Категории',
    'singular_name' => 'Категория',
    'menu_name' => 'Категории',
    'all_items' => 'Все',
    'edit_item' => 'Редактировать',
    'view_item' => 'Смотреть',
    'update_item' => 'Обновить название',
    'add_new_item' => 'Добавить новую',
    'new_item_name' => 'Новое название',
    'parent_item' => 'Родитель',
    'parent_item_colon' => 'Родитель:',
    'search_items' => 'Искать',
    'popular_items' => 'Популярные',
    'separate_items_with_commas' => 'Элементы разделяются запятыми',
    'add_or_remove_items' => 'Добавить или удалить',
    'choose_from_most_used' => 'Выбрать из наиболее используемых',
    'not_found' => 'Не найдено',
    'no_terms' => 'Нет элементов',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список'
  );

  $args = array(
    'label' => 'Категории',
    'labels' => $labels,
    'public' => false,
    'hierarchical' => true,
    'label' => 'Категории',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'query_var' => array( 'slug' => 'rent_categories', 'with_front' => true, ),
    'query_var' => false,
    'show_in_rest' => false,
    'show_in_rest' => '',
    'show_in_rest' => true
  );
  
  register_taxonomy( 'rent_categories', array( 'rent' ), $args );
  
  $labels = array(
    'name' => 'Категории',
    'singular_name' => 'Категория',
    'menu_name' => 'Категории',
    'all_items' => 'Все',
    'edit_item' => 'Редактировать',
    'view_item' => 'Смотреть',
    'update_item' => 'Обновить название',
    'add_new_item' => 'Добавить новую',
    'new_item_name' => 'Новое название',
    'parent_item' => 'Родитель',
    'parent_item_colon' => 'Родитель:',
    'search_items' => 'Искать',
    'popular_items' => 'Популярные',
    'separate_items_with_commas' => 'Элементы разделяются запятыми',
    'add_or_remove_items' => 'Добавить или удалить',
    'choose_from_most_used' => 'Выбрать из наиболее используемых',
    'not_found' => 'Не найдено',
    'no_terms' => 'Нет элементов',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список'
  );

  $args = array(
    'label' => 'Категории',
    'labels' => $labels,
    'public' => false,
    'hierarchical' => true,
    'label' => 'Категории',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'query_var' => array( 'slug' => 'parts_categories', 'with_front' => true, ),
    'query_var' => false,
    'show_in_rest' => false,
    'show_in_rest' => '',
    'show_in_rest' => true
  );
  
  register_taxonomy( 'parts_categories', array( 'parts' ), $args );
  
  $labels = array(
    'name' => 'Категории',
    'singular_name' => 'Категория',
    'menu_name' => 'Категории',
    'all_items' => 'Все',
    'edit_item' => 'Редактировать',
    'view_item' => 'Смотреть',
    'update_item' => 'Обновить название',
    'add_new_item' => 'Добавить новую',
    'new_item_name' => 'Новое название',
    'parent_item' => 'Родитель',
    'parent_item_colon' => 'Родитель:',
    'search_items' => 'Искать',
    'popular_items' => 'Популярные',
    'separate_items_with_commas' => 'Элементы разделяются запятыми',
    'add_or_remove_items' => 'Добавить или удалить',
    'choose_from_most_used' => 'Выбрать из наиболее используемых',
    'not_found' => 'Не найдено',
    'no_terms' => 'Нет элементов',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список'
  );

  $args = array(
    'label' => 'Категории',
    'labels' => $labels,
    'public' => false,
    'hierarchical' => true,
    'label' => 'Категории',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'query_var' => array( 'slug' => 'services_categories', 'with_front' => true, ),
    'query_var' => false,
    'show_in_rest' => false,
    'show_in_rest' => '',
    'show_in_rest' => true
  );
  
  register_taxonomy( 'services_categories', array( 'services' ), $args );
  
  $labels = array(
    'name' => 'Регионы',
    'singular_name' => 'Регион',
    'menu_name' => 'Регионы',
    'all_items' => 'Все',
    'edit_item' => 'Редактировать',
    'view_item' => 'Смотреть',
    'update_item' => 'Обновить название',
    'add_new_item' => 'Добавить новый',
    'new_item_name' => 'Новое название',
    'parent_item' => 'Родитель',
    'parent_item_colon' => 'Родитель:',
    'search_items' => 'Искать',
    'popular_items' => 'Популярные',
    'separate_items_with_commas' => 'Элементы разделяются запятыми',
    'add_or_remove_items' => 'Добавить или удалить',
    'choose_from_most_used' => 'Выбрать из наиболее используемых',
    'not_found' => 'Не найдено',
    'no_terms' => 'Нет элементов',
    'items_list_navigation' => 'Навигация по списку',
    'items_list' => 'Список'
  );

  $args = array(
    'label' => 'Регионы',
    'labels' => $labels,
    'public' => false,
    'hierarchical' => true,
    'label' => 'Регионы',
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'query_var' => true,
    'query_var' => array( 'slug' => 'regions', 'with_front' => true, ),
    'query_var' => false,
    'show_in_rest' => false,
    'show_in_rest' => '',
    'show_in_rest' => true
  );
  
  register_taxonomy( 'regions', array( 'trade', 'rent', 'parts', 'services' ), $args );
  
}

// Добавление произвольных полей к категориям
function as_add_category_custom_fields() {
  if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
      'key' => 'categories_group',
      'title' => 'Категории',
      'fields' => array(
        array(
          'key' => 'popular_category_field',
          'label' => 'Популярная',
          'name' => 'popular_category',
          'type' => 'true_false'
        ),
        array(
          'key' => 'preset_link_field',
          'label' => 'Быстрая ссылка',
          'name' => 'preset_link',
          'type' => 'true_false'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'trade_categories'
          )
        ),
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'rent_categories'
          )
        ),
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'parts_categories'
          )
        ),
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'services_categories'
          )
        )
      )
    ) );
  }
}

// Добавление произвольного поля к регионам
function as_add_region_custom_field() {
  if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
      'key' => 'regions_group',
      'title' => 'Регионы',
      'fields' => array(
        array(
          'key' => 'popular_region_field',
          'label' => 'Популярный',
          'name' => 'popular_region',
          'type' => 'true_false'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'regions'
          )
        )
      )
    ) );
  }
}

// Добавление произвольных полей к заявкам
function as_add_tender_custom_fields() {
  if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
      'key' => 'tenders_group',
      'title' => 'Данные заявки',
      'fields' => array(
        array(
          'key' => 'tender_state_field',
          'label' => 'Состояние',
          'name' => 'tender_state',
          'type' => 'checkbox',
          'choices' => array(
            'new' => 'Новая',
            'fc' => 'Б/у'
          ),
          'layout' => 'horizontal'
		),
		array(
          'key' => 'tender_amount_from_field',
          'label' => 'Сумма сделки (от)',
          'name' => 'tender_amount_from',
          'type' => 'number'
		),
		array(
          'key' => 'tender_amount_to_field',
          'label' => 'Сумма сделки (до)',
          'name' => 'tender_amount_to',
          'type' => 'number'
		),
		array(
          'key' => 'tender_user_name_field',
          'label' => 'Имя автора',
          'name' => 'tender_user_name',
          'type' => 'text'
		),
		array(
          'key' => 'tender_user_email_field',
          'label' => 'Email автора',
          'name' => 'tender_user_email',
          'type' => 'text'
		),
		array(
          'key' => 'tender_user_phone_field',
          'label' => 'Телефон автора',
          'name' => 'tender_user_phone',
          'type' => 'text'
		)
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'trade'
          )
        ),
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'rent'
          )
        ),
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'parts'
          )
        ),
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'services'
          )
        )
      )
    ) );
  }
}

// Скрытие поля "Состояние"
function as_hide_state_field() {
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    
    var postType = $('form#post input#post_type');
    if (!postType.length) return;
    var val = postType.val();
    if (val != 'services') return;
    $('.acf-field.acf-field-tender-state-field').hide();
    
  });
</script>
<?php
}

// Редирект страницы "Заявки" на страницу "Покупки"
function as_tender_page_redirect() {
  if ( is_page( 'tender' ) ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/trade' ) ), 301 );
    exit;
  }
}

// Редирект страницы "Сохранение заявки" на страницу "Разместить заявку"
function as_save_page_redirect() {
  if ( is_page( 'tender/save' ) && ! as_is_post_key_exist() ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
}

// Редирект страницы "Редактировать заявку" на страницу "Разместить заявку"
function as_edit_page_redirect() {
  if ( is_page( 'tender/edit' ) && ! as_is_post_key_exist() ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/addadv' ) ), 301 );
    exit;
  }
}

// Редирект страницы "Подтверждение подписки" на страницу "Покупки"
function as_confirmation_page_redirect() {
  if ( is_page( 'tender/confirmation' ) && as_subscribe_newsletter() ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/trade' ) ), 301 );
    exit;
  }
}

// Редирект страницы "Отмена подписки" на страницу "Покупки"
function as_unsubscribe_page_redirect() {
  if ( is_page( 'tender/unsubscribe' ) && as_cancel_newsletter() ) {
    wp_redirect( get_permalink( get_page_by_path( 'tender/trade' ) ), 301 );
    exit;
  }
}

// Добавление контента к странице "Главная"
function as_add_content_to_main_page( $content ) {
  
  if ( ! is_front_page() ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/main/main.php';
  $main_content = ob_get_clean();
  
  return $content . $main_content;
  
}

// Добавление контента к странице "Покупки"
function as_add_content_to_trade_page( $content ) {
  
  if ( ! is_page( 'tender/trade' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/tenders/trade.php';
  $trade_content = ob_get_clean();
  
  return $content . $trade_content;
  
}

// Добавление контента к странице "Аренда"
function as_add_content_to_rent_page( $content ) {
  
  if ( ! is_page( 'tender/rent' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/tenders/rent.php';
  $rent_content = ob_get_clean();
  
  return $content . $rent_content;
  
}

// Добавление контента к странице "Запчасти"
function as_add_content_to_parts_page( $content ) {
  
  if ( ! is_page( 'tender/parts' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/tenders/parts.php';
  $parts_content = ob_get_clean();
  
  return $content . $parts_content;
  
}

// Добавление контента к странице "Услуги"
function as_add_content_to_services_page( $content ) {
  
  if ( ! is_page( 'tender/services' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/tenders/services.php';
  $services_content = ob_get_clean();
  
  return $content . $services_content;
  
}

// Добавление контента к странице "Разместить заявку"
function as_add_content_to_addadv_page( $content ) {
  
  if ( ! is_page( 'tender/addadv' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/addadv/addadv-trade.php';
  $trade_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/addadv/addadv-rent.php';
  $rent_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/addadv/addadv-parts.php';
  $parts_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/addadv/addadv-services.php';
  $services_content = ob_get_clean();
  
  $addadv_content = '<div class="tender__as">' . $trade_content . $rent_content . $parts_content . $services_content . '</div>';
  
  return $content . $addadv_content;
  
}

// Добавление контента к странице "Сохранение заявки"
function as_add_content_to_save_page( $content ) {
  
  if ( ! is_page( 'tender/save' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/save/save.php';
  $save_content = ob_get_clean();
  
  $edit_link = add_query_arg( array( 'key' => $_GET['key'] ), get_permalink( get_page_by_path( 'tender/edit' ) ) ); 
  
  $save_content = str_replace( '%edit_link%', $edit_link, $save_content );
  
  return $content . $save_content;
  
}

// Добавление контента к странице "Редактировать заявку"
function as_add_content_to_edit_page( $content ) {
  
  if ( ! is_page( 'tender/edit' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/edit/edit-trade.php';
  $trade_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/edit/edit-rent.php';
  $rent_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/edit/edit-parts.php';
  $parts_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/edit/edit-services.php';
  $services_content = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/edit/delete-popup.php';
  $delete_content = ob_get_clean();
  
  $edit_content = '<div class="tender__as">' . $trade_content . $rent_content . $parts_content . $services_content . '</div>' . $delete_content;
  
  return $content . $edit_content;
  
}

// Добавление контента к странице "Удаление заявки"
function as_add_content_to_delete_page( $content ) {
  
  if ( ! is_page( 'tender/delete' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/delete/delete.php';
  $delete_content = ob_get_clean();
  
  return $content . $delete_content;
  
}

// Добавление контента к странице заявки
function as_add_content_to_single_tender( $content ) {
  
  if ( ! is_singular( array( 'trade', 'rent', 'parts', 'services' ) ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/single/single-top.php';
  $single_top = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/single/single-bottom.php';
  $single_bottom = ob_get_clean();
  
  ob_start();
  require_once AS_PATH . 'public/partials/single/send-message-popup.php';
  $send_message_content = ob_get_clean();
  
  $content = '<div class="tender-content__as single-content__as">' . $content . '</div>';
  
  return $single_top . $content . $single_bottom . $send_message_content;
  
}

// Добавление контента к странице "Избранные заявки"
function as_add_content_to_favorites_page( $content ) {
  
  if ( ! is_page( 'tender/favorites' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/favorites/favorites.php';
  $favorites_content = ob_get_clean();
  
  return $content . $favorites_content;
  
}

// Добавление контента к странице "Подтверждение подписки"
function as_add_content_to_confirmation_page( $content ) {
  
  if ( ! is_page( 'tender/confirmation' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/confirmation/confirmation.php';
  $confirmation_content = ob_get_clean();
  
  return $content . $confirmation_content;
  
}

// Добавление контента к странице "Отмена подписки"
function as_add_content_to_unsubscribe_page( $content ) {
  
  if ( ! is_page( 'tender/unsubscribe' ) ) {
    return $content;
  }
  
  ob_start();
  require_once AS_PATH . 'public/partials/unsubscribe/unsubscribe.php';
  $unsubscribe_content = ob_get_clean();
  
  return $content . $unsubscribe_content;
  
}

// Отправка оповещения пользователю о публикации заявки
function as_send_tender_confirm( $post ) {
  
  $post_id = $post->ID;
  $post_type = $post->post_type;
  
  $tender_types = array( 'trade', 'rent', 'parts', 'services' );
  
  if ( ! in_array( $post_type, $tender_types ) ) {
    return;
  }
    
  $email_confirm = as_get_email_template( 'email_confirm' );

  $tender_link = get_permalink( $post_id );
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

  $email_confirm = str_replace( array( '%%type_text%%', '%%tender_link%%' ), array( $type_text, $tender_link ), $email_confirm );

  $site_name = get_bloginfo( 'name' );
  $site_email = get_bloginfo( 'admin_email' );
  $from = "{$site_name} <{$site_email}>";
  $headers = array(
    "content-type: text/html",
    "from: {$from}"
  );  
  wp_mail( $email, 'Заявка опубликована', $email_confirm, $headers );
  
}

// Отправка оповещения пользователю об отклонении заявки
function as_send_tender_cancel( $post ) {
  
  $post_id = $post->ID;
  $post_type = $post->post_type;
  
  $tender_types = array( 'trade', 'rent', 'parts', 'services' );
  
  if ( ! in_array( $post_type, $tender_types ) ) {
    return;
  }
    
  $email_cancel = as_get_email_template( 'email_cancel' );
  
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

  $email_cancel = str_replace( array( '%%type_text%%', '%%add_link%%' ), array( $type_text, $add_link ), $email_cancel );

  $site_name = get_bloginfo( 'name' );
  $site_email = get_bloginfo( 'admin_email' );
  $from = "{$site_name} <{$site_email}>";
  $headers = array(
    "content-type: text/html",
    "from: {$from}"
  );  
  wp_mail( $email, 'Заявка отклонена', $email_cancel, $headers );
  
}