<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>
<?php
  global $post;

  $post_id = $post->ID;
  $post_type = $post->post_type;
  $title = $post->post_title;
  $date = get_the_date( 'd.m.Y', $post_id );

  $state = get_field( 'tender_state', $post_id );
  $from = get_field( 'tender_amount_from', $post_id );
  $to = get_field( 'tender_amount_to', $post_id );
  $name = get_field( 'tender_user_name', $post_id );
  $email = get_field( 'tender_user_email', $post_id );
  $phone = get_field( 'tender_user_phone', $post_id );

  if ( 2 == count( $state ) ) {
    $state = 'новая и Б/у';
  } elseif ( 'new' == $state[0] ) {
    $state = 'новая';
  } elseif ( 'fc' == $state[0] ) {
    $state = 'Б/у';
  }

  $amount = '';
  if ( ! $from && ! $to ) {
    $amount = 'договорная';
  } elseif ( ! $from && $to ) {
    $amount = "до {$to}";
  } elseif ( $from && ! $to ) {
    $amount = "от {$from}";
  } else {
    $amount = "от {$from} до {$to}";
  }

  $regions_terms = get_the_terms( $post_id, 'regions' );
  $country = null;
  $city = null;
  foreach ( $regions_terms as $term ) {
    if ( 0 == $term->parent ) {
      $country = $term->name;
      break;
    }
  }
  foreach ( $regions_terms as $term ) {
    if ( 0 != $term->parent ) {
      $city = $term->name;
      break;
    }
  }

  $categories_terms = get_the_terms( $post_id, $post_type . '_categories' );

  $cat = null;
  $subcat = null;
  foreach ( $categories_terms as $term ) {
    if ( 0 == $term->parent ) {
      $cat = $term->name;
      break;
    }
  }
  foreach ( $categories_terms as $term ) {
    if ( 0 != $term->parent ) {
      $subcat = $term->name;
      break;
    }
  }

  $cat_name = '';
  if ( 'trade' == $post_type || 'rent' == $post_type ) {
    $cat_name = 'Оборудование/Техника';
  } elseif ( 'parts' == $post_type ) {
    $cat_name = 'Запчасти';
  } elseif ( 'services' ) {
    $cat_name = 'Услуги';
  }
?>

<div class="single-top__as">
  <div class="header__as">
    <div class="title__as"><?php echo $title; ?></div>
    <div class="helpers__as">
      <span class="btn__as message__as">Написать сообщение</span>
      <span title="Версия для печати" class="btn__as print__as"></span>
      <span title="<?php if ( as_in_favorites( $post_id ) ) echo 'Удалить с закладок'; else echo 'Добавить в закладки'; ?>" class="btn__as favorites__as<?php if ( as_in_favorites( $post_id ) ) echo ' active__as'; ?>" data-post-id="<?php echo $post_id; ?>"></span>
      <a href="<?php echo get_permalink( get_page_by_path( 'tender/favorites' ) ); ?>" class="btn__as favorites-link__as<?php if ( as_in_favorites( $post_id ) ) echo ' active__as'; ?>"><span class="star__as"></span>Избранные заявки (<span class="count__as"><?php echo as_favorites_count(); ?></span>)</a>
      <span class="date__as"><?php echo $date; ?></span>
    </div>
  </div>
  <div class="main-info__as">
    <div class="item__as"><?php echo $cat_name; ?>: <span><?php echo $cat; ?></span></div>
    <?php if ( $subcat ): ?><div class="item__as">Категория: <span><?php echo $subcat; ?></span></div><?php endif; ?>
    <div class="item__as">Состояние: <span><?php echo $state; ?></span></div>
    <div class="item__as">Регион: <span><?php echo $country; ?>, <?php echo $city; ?></span></div>
    <div class="item__as">Сумма сделки: <span><?php echo $amount; ?></span></div>
  </div>
  <button type="button" class="show-contact__as">Показать контактные данные</button>
  <div class="contact-info__as">
    <div class="item__as">Имя: <span><?php echo $name; ?></span></div>
    <div class="item__as">Телефон: <span><?php echo $phone; ?></span></div>
    <div class="item__as">Email: <span><?php echo $email; ?></span></div>
  </div>
</div>