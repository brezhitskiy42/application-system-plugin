<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_action( 'wp_ajax_send_message', 'as_send_message' );
add_action( 'wp_ajax_nopriv_send_message', 'as_send_message' );
add_action( 'wp_ajax_add_to_favorites', 'as_add_tender_to_favorites' );
add_action( 'wp_ajax_nopriv_add_to_favorites', 'as_add_tender_to_favorites' );
add_action( 'wp_ajax_remove_from_favorites', 'as_remove_from_favorites' );
add_action( 'wp_ajax_nopriv_remove_from_favorites', 'as_remove_from_favorites' );
add_action( 'wp_ajax_clear_favorites', 'as_clear_favorites' );
add_action( 'wp_ajax_nopriv_clear_favorites', 'as_clear_favorites' );
add_action( 'wp_ajax_add_email_to_subscribe', 'as_add_email_to_subscribe' );
add_action( 'wp_ajax_nopriv_add_email_to_subscribe', 'as_add_email_to_subscribe' );
add_action( 'wp_ajax_export_tenders', 'as_export_tenders' );
add_action( 'wp_ajax_load_more', 'as_load_more' );
add_action( 'wp_ajax_nopriv_load_more', 'as_load_more' );
add_action( 'wp_ajax_filter_tenders', 'as_filter_tenders' );
add_action( 'wp_ajax_nopriv_filter_tenders', 'as_filter_tenders' );
add_action( 'wp_ajax_total_tenders', 'as_total_tenders' );
add_action( 'wp_ajax_nopriv_total_tenders', 'as_total_tenders' );

add_action( 'admin_init', 'as_send_export_file' );

// Отправка сообщения со страницы заявки
function as_send_message() {
  
  if ( ! isset( $_POST['as_post_id'] ) || ! isset( $_POST['as_message'] ) || ! isset( $_POST['as_email'] ) ) {
    return;
  }
  
  $to = get_field( 'tender_user_email', $_POST['as_post_id'] );
  if ( ! $to ) {
    return;
  }
  
  $title = get_the_title( $_POST['as_post_id'] );
  $message = trim( esc_html( $_POST['as_message'] ) );
  $email = trim( sanitize_text_field( $_POST['as_email'] ) );
  
  
  $site_name = get_bloginfo( 'name' );
  $site_url = get_home_url();
  $message = "С сайта {$site_url} по вашей заявке «{$title}» поступило сообщение:\n{$message}";
  
  $from = "{$site_name} <{$email}>";
  $headers = array(
    "content-type: text/plain",
    "from: {$from}"
  );
  wp_mail( $to, 'Сообщение по заявке', $message, $headers );
  
  echo 'ok';
  
  wp_die();
  
}

// Добавление заявок в избранные
function as_add_tender_to_favorites() {
  
  if ( ! isset( $_POST['as_post_id'] ) || ! isset( $_POST['as_action_inner'] ) ) {
    return;
  }
  
  if ( ! isset( $_COOKIE['as_favorites'] ) ) {
    
    setcookie( 'as_favorites', $_POST['as_post_id'], time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    
    echo 1;
    wp_die();
    
  }
  
  $ids = explode( ',', $_COOKIE['as_favorites'] );
  
  if ( 'remove' == $_POST['as_action_inner'] ) {
    if ( false !== ( $key = array_search( $_POST['as_post_id'], $ids ) ) ) {
      unset( $ids[$key] );
    }
  } else {
    $ids[] = $_POST['as_post_id'];
  }
  
  setcookie( 'as_favorites', implode( ',', $ids ), time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
  
  echo count( $ids );
  wp_die();
  
}

// Удаление заявок с избранных
function as_remove_from_favorites() {
  
  if ( ! isset( $_POST['as_post_id'] ) || ! isset( $_COOKIE['as_favorites'] ) ) {
    return;
  }
  
  $ids = explode( ',', $_COOKIE['as_favorites'] );
  if ( false !== ( $key = array_search( $_POST['as_post_id'], $ids ) ) ) {
    unset( $ids[$key] );
  }
  
  setcookie( 'as_favorites', implode( ',', $ids ), time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
  
  wp_die();
  
}

// Удаление всех заявок с избранных
function as_clear_favorites() {
  
  if ( ! isset( $_COOKIE['as_favorites'] ) ) {
    return;
  }
  
  setcookie( 'as_favorites', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
  
  wp_die();
  
}

// Добавление email'a к рассылке
function as_add_email_to_subscribe() {
  
  if ( ! isset( $_POST['as_email'] ) ) {
    return;
  }
  
  $email = trim( sanitize_text_field( $_POST['as_email'] ) );
  
  $subscribe_key = as_random_str( 32 );
  add_row( 'subscriber', array( 'subscriber_email' => $email, 'is_subscribe' => false, 'subscriber_key' => $subscribe_key ), 'option' );
  
  $subscriber_field = get_field( 'subscriber', 'option' );
  
  $subscriber_email = array();
  $is_subscribe = array();
  $subscriber_key = array();
  foreach ( $subscriber_field as $key => $subscriber ) {
    $subscriber_email[$key] = $subscriber['subscriber_email'];
    $is_subscribe[$key] = $subscriber['is_subscribe'];
    $subscriber_key[$key] = $subscriber['subscriber_key'];
  }
  
  $subscriber_email = array_unique( $subscriber_email );
  
  $subscriber_field = array();
  foreach ( $subscriber_email as $key => $subscriber ) {
    $subscriber_field[$key]['subscriber_email'] = $subscriber;
    $subscriber_field[$key]['is_subscribe'] = $is_subscribe[$key];
    $subscriber_field[$key]['subscriber_key'] = $subscriber_key[$key];
  }
  
  update_field( 'subscriber', $subscriber_field, 'option' );
  
  $subscribe_data = array();
  foreach ( $subscriber_field as $subscriber ) {
    if ( $subscriber['subscriber_email'] == $email ) {
      $subscribe_data['key'] = $subscriber['subscriber_key'];
      $subscribe_data['is_subscribe'] = $subscriber['is_subscribe'];
      break;
    }
  }
  
  if ( $subscribe_data['is_subscribe'] ) {
    return;
  }
  
  $email_subscribe_confirmation = as_get_email_template( 'email_subscribe_confirmation' );
  $subscribe_link = add_query_arg( array( 'key' => $subscribe_data['key'] ), get_permalink( get_page_by_path( 'tender/confirmation' ) ) );
  
  $email_subscribe_confirmation = str_replace( '%%subscribe_link%%', $subscribe_link, $email_subscribe_confirmation );
  
  $site_name = get_bloginfo( 'name' );
  $site_email = get_bloginfo( 'admin_email' );
  $from = "{$site_name} <{$site_email}>";
  $headers = array(
    "content-type: text/html",
    "from: {$from}"
  );  
  wp_mail( $email, 'Подтверждение подписки на заявки', $email_subscribe_confirmation, $headers );
  
  echo 'ok';
  
  wp_die();
  
}

// Экспорт заявок
function as_export_tenders() {
  
  $from = $_POST['as_from'];
  $to = $_POST['as_to'];
  
  $posts = get_posts( array(
    'numberposts' => -1,
    'post_type' => array( 'trade', 'rent', 'parts', 'services' ),
    'date_query' => array(
      array(
        'after' => $from,
        'before' => $to,
        'inclusive' => true
      )
    )
  ) );
  
  if ( ! $posts ) {
    
    echo 0;
    
    wp_die();
  
  }
  
  $data = array();
  $data[] = array( 'ИМЯ', 'ТЕЛЕФОН', 'ПОЧТА' );
  foreach ( $posts as $post ) {
    $data[] = array(
      get_field( 'tender_user_name', $post->ID ),
      get_field( 'tender_user_phone', $post->ID ),
      get_field( 'tender_user_email', $post->ID )
    );
  }
  
  $fp = fopen( AS_PATH . 'export/export.csv', 'w' );
  
  foreach ( $data as $row ) {
    fputcsv( $fp, $row );
  }
      
  fclose( $fp );
  
  echo 1;
  
  wp_die();
  
}

// Отправка файла с заявками
function as_send_export_file() {
  
  global $pagenow;
  
  if ( 'admin.php' != $pagenow || ! isset( $_GET['page'] ) || 'as-settings' != $_GET['page'] ) {
    return;
  }
  
  if ( ! isset( $_GET['export'] ) ) {
    return;
  }
  
  $file = AS_PATH . 'export/export.csv';
      
  if( ! file_exists( $file ) ) {
    return;
  }

  $finfo = finfo_open( FILEINFO_MIME_TYPE );
  header( 'Content-Type: ' . finfo_file( $finfo, $file ) );
  finfo_close( $finfo );

  header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
  header( 'Expires: 0' );
  header( 'Cache-Control: must-revalidate' );
  header( 'Pragma: public' );
  header( 'Content-Length: ' . filesize( $file ) );

  if ( ob_get_length() > 0 ) { 
    ob_clean();
  }
  flush();
  readfile( $file );
  wp_die();
  
}

// Подгрузка заявок
function as_load_more() {
  
  if ( ! isset( $_POST['as_post_type'] ) || ! isset( $_POST['as_offset'] ) || ! isset( $_POST['as_cat'] ) || ! isset( $_POST['as_subcat'] ) || ! isset( $_POST['as_state'] ) || ! isset( $_POST['as_country'] ) || ! isset( $_POST['as_period'] ) || ! isset( $_POST['as_to'] ) ) {
    echo 0;
    wp_die();
  }
  
  $post_type = trim( sanitize_text_field( $_POST['as_post_type'] ) );
  $offset = trim( sanitize_text_field( $_POST['as_offset'] ) );
  $cat = trim( sanitize_text_field( $_POST['as_cat'] ) );
  $subcat = trim( sanitize_text_field( $_POST['as_subcat'] ) );
  $state = trim( sanitize_text_field( $_POST['as_state'] ) );
  $country = trim( sanitize_text_field( $_POST['as_country'] ) );
  $period = trim( sanitize_text_field( $_POST['as_period'] ) );
  $to = trim( sanitize_text_field( $_POST['as_to'] ) );
  
  if ( 'any' == $state ) {
    $state = array( 'new', 'fc' );
  }
  
  $args = array(
    'numberposts' => 15,
    'post_type' => $post_type,
    'offset' => $offset,
    'tax_query' => array( 'relation' => 'OR' ),
    'meta_query' => array( 'relation' => 'OR' )
  );
  
  $categories = array();
  if ( $cat ) $categories[] = $cat;
  if ( $subcat ) $categories[] = $subcat;
  
  if ( $categories ) $args['tax_query'][] = array( 'taxonomy' => $post_type . '_categories', 'terms' => $categories, 'operator' => 'AND', 'include_children' => false );
  if ( $country ) $args['tax_query'][] = array( 'taxonomy' => 'regions', 'terms' => array( $country ) );
  if ( ( $cat || $subcat ) && $country ) $args['tax_query']['relation'] = 'AND';
  if ( $state ) $args['meta_query'][] = array( 'key' => 'tender_state', 'value' => $state, 'compare' => 'LIKE' );
  if ( $to ) $args['meta_query'][] = array( 'key' => 'tender_amount_to', 'value' => $to, 'compare' => '>=' );
  if ( $state && $to ) $args['meta_query']['relation'] = 'AND';
  if ( $period ) $args['date_query'] = array( 'after' => $period . ' days ago' );
  
  $posts = get_posts( $args );
  
  if ( ! $posts ) {
    echo 0;
    wp_die();
  }
  
  foreach ( $posts as $post ):
  
    $date = get_the_date( 'd.m.y', $post->ID );
    $today_date = date( 'd.m.y' );
    $now = null;
    if ( $date == $today_date ) {
      $now = true;
    }

    $regions_terms = get_the_terms( $post->ID, 'regions' );
    $city = null;
    foreach ( $regions_terms as $term ) {
      if ( 0 != $term->parent ) {
        $city = $term->name;
        break;
      }
    }
?>

<div class="item__as<?php if ( $now ) echo ' top__as'; ?>">
  <div class="date__as<?php if ( $now ) echo ' today__as'; ?>"><?php if ( $now ) echo 'Сегодня'; else echo $date; ?></div>
  <div class="content__as">
    <a href="<?php echo get_permalink( $post->ID ); ?>" class="title__as"><?php echo $post->post_title; ?></a>
    <div class="text__as"><?php echo wp_trim_words( $post->post_content, 15, '...' ); ?></div>
  </div>
  <div class="city-price__as">
    <div class="city__as"><?php echo $city; ?></div>
    <div class="price__as">Договорная</div>
  </div>
</div>
 
<?php
  
  endforeach;
  
  wp_die();
  
}

// Фильтрация заявок
function as_filter_tenders() {
  
  if ( ! isset( $_POST['as_post_type'] ) || ! isset( $_POST['as_cat'] ) || ! isset( $_POST['as_subcat'] ) || ! isset( $_POST['as_state'] ) || ! isset( $_POST['as_country'] ) || ! isset( $_POST['as_period'] ) || ! isset( $_POST['as_to'] ) ) {
    echo 0;
    wp_die();
  }
  
  $post_type = trim( sanitize_text_field( $_POST['as_post_type'] ) );
  $cat = trim( sanitize_text_field( $_POST['as_cat'] ) );
  $subcat = trim( sanitize_text_field( $_POST['as_subcat'] ) );
  $state = trim( sanitize_text_field( $_POST['as_state'] ) );
  $country = trim( sanitize_text_field( $_POST['as_country'] ) );
  $period = trim( sanitize_text_field( $_POST['as_period'] ) );
  $to = trim( sanitize_text_field( $_POST['as_to'] ) );
  
  if ( 'any' == $state ) {
    $state = array( 'new', 'fc' );
  }
  
  $args = array(
    'numberposts' => 15,
    'post_type' => $post_type,
    'tax_query' => array( 'relation' => 'OR' ),
    'meta_query' => array( 'relation' => 'OR' )
  );
  
  $categories = array();
  if ( $cat ) $categories[] = $cat;
  if ( $subcat ) $categories[] = $subcat;
  
  if ( $categories ) $args['tax_query'][] = array( 'taxonomy' => $post_type . '_categories', 'terms' => $categories, 'operator' => 'AND', 'include_children' => false );
  if ( $country ) $args['tax_query'][] = array( 'taxonomy' => 'regions', 'terms' => array( $country ) );
  if ( ( $cat || $subcat ) && $country ) $args['tax_query']['relation'] = 'AND';
  if ( $state ) $args['meta_query'][] = array( 'key' => 'tender_state', 'value' => $state, 'compare' => 'LIKE' );
  if ( $to ) $args['meta_query'][] = array( 'key' => 'tender_amount_to', 'value' => $to, 'compare' => '>=' );
  if ( $state && $to ) $args['meta_query']['relation'] = 'AND';
  if ( $period ) $args['date_query'] = array( 'after' => $period . ' days ago' );
  
  $posts = get_posts( $args );
  
  if ( ! $posts ) {
    echo 0;
    wp_die();
  }
  
  foreach ( $posts as $post ):
  
    $date = get_the_date( 'd.m.y', $post->ID );
    $today_date = date( 'd.m.y' );
    $now = null;
    if ( $date == $today_date ) {
      $now = true;
    }

    $regions_terms = get_the_terms( $post->ID, 'regions' );
    $city = null;
    foreach ( $regions_terms as $term ) {
      if ( 0 != $term->parent ) {
        $city = $term->name;
        break;
      }
    }
?>
  
<div class="item__as<?php if ( $now ) echo ' top__as'; ?>">
  <div class="date__as<?php if ( $now ) echo ' today__as'; ?>"><?php if ( $now ) echo 'Сегодня'; else echo $date; ?></div>
  <div class="content__as">
    <a href="<?php echo get_permalink( $post->ID ); ?>" class="title__as"><?php echo $post->post_title; ?></a>
    <div class="text__as"><?php echo wp_trim_words( $post->post_content, 15, '...' ); ?></div>
  </div>
  <div class="city-price__as">
    <div class="city__as"><?php echo $city; ?></div>
    <div class="price__as">Договорная</div>
  </div>
</div>
  
<?php
  
  endforeach;
  
  wp_die();
  
}

// Общее к-во заявок
function as_total_tenders() {
  
  if ( ! isset( $_POST['as_post_type'] ) || ! isset( $_POST['as_cat'] ) || ! isset( $_POST['as_subcat'] ) || ! isset( $_POST['as_state'] ) || ! isset( $_POST['as_country'] ) || ! isset( $_POST['as_period'] ) || ! isset( $_POST['as_to'] ) ) {
    echo 0;
    wp_die();
  }
  
  $post_type = trim( sanitize_text_field( $_POST['as_post_type'] ) );
  $cat = trim( sanitize_text_field( $_POST['as_cat'] ) );
  $subcat = trim( sanitize_text_field( $_POST['as_subcat'] ) );
  $state = trim( sanitize_text_field( $_POST['as_state'] ) );
  $country = trim( sanitize_text_field( $_POST['as_country'] ) );
  $period = trim( sanitize_text_field( $_POST['as_period'] ) );
  $to = trim( sanitize_text_field( $_POST['as_to'] ) );
  
  if ( 'any' == $state ) {
    $state = array( 'new', 'fc' );
  }
  
  $args = array(
    'numberposts' => -1,
    'post_type' => $post_type,
    'tax_query' => array( 'relation' => 'OR' ),
    'meta_query' => array( 'relation' => 'OR' )
  );
  
  $categories = array();
  if ( $cat ) $categories[] = $cat;
  if ( $subcat ) $categories[] = $subcat;
  
  if ( $categories ) $args['tax_query'][] = array( 'taxonomy' => $post_type . '_categories', 'terms' => $categories, 'operator' => 'AND', 'include_children' => false );
  if ( $country ) $args['tax_query'][] = array( 'taxonomy' => 'regions', 'terms' => array( $country ) );
  if ( ( $cat || $subcat ) && $country ) $args['tax_query']['relation'] = 'AND';
  if ( $state ) $args['meta_query'][] = array( 'key' => 'tender_state', 'value' => $state, 'compare' => 'LIKE' );
  if ( $to ) $args['meta_query'][] = array( 'key' => 'tender_amount_to', 'value' => $to, 'compare' => '>=' );
  if ( $state && $to ) $args['meta_query']['relation'] = 'AND';
  if ( $period ) $args['date_query'] = array( 'after' => $period . ' days ago' );
  
  echo count( get_posts( $args ) );
  
  wp_die();
  
}