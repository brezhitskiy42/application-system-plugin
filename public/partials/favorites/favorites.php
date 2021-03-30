<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>
<?php
  $posts = as_get_favorites();
  if ( ! $posts ) {
    $posts = array();
  }
?>

<div class="favorites__as">
  <?php if ( $posts ): ?><button class="clear-favorites__as">Очистить избранное</button><?php endif; ?>
  <?php if ( ! $posts ): ?><div class="no-favorites__as">Нет избранных заявок</div><?php endif; ?>
  <?php
    foreach ( $posts as $post ):
      
      $from = get_field( 'tender_amount_from', $post->ID );
      $to = get_field( 'tender_amount_to', $post->ID );
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

      $regions_terms = get_the_terms( $post->ID, 'regions' );
      $city = null;
      foreach ( $regions_terms as $term ) {
        if ( 0 != $term->parent ) {
          $city = $term->name;
          break;
        }
      }
  ?>
    <div class="item__as">
      <div class="star__as" title="Удалить с закладок" data-post-id="<?php echo $post->ID; ?>"></div>
      <div class="content__as">
        <a href="<?php echo get_permalink( $post->ID ); ?>" class="title__as"><?php echo $post->post_title; ?></a>
        <div class="text__as"><?php echo wp_trim_words( $post->post_content, 15, '...' ); ?></div>
      </div>
      <div class="region-price__as">
        <div class="item__as"><?php echo $city; ?></div>
        <div class="item__as"><?php echo $amount; ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>