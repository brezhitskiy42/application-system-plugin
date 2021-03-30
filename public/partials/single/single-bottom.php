<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>
<?php
  global $post;
  $post_type = get_post_type( $post->ID );
  $posts = get_posts( array( 'numberposts' => 9, 'post_type' => $post_type, 'exclude' => $post->ID ) );
?>

<div class="single-bottom__as">
  <div class="subscribe__as">
    <form id="subscribe__as">
      <div class="title__as">Подписаться на заявки</div>
      <div class="input-block__as">
        <input type="text" name="as_email" placeholder="Ваш email" data-validation="email">
        <button type="submit">Подписаться</button>
      </div>
      <div class="info__as">Подпишитесь и получайте уведомления о новых заявках трижды в неделю. Никакого спама.</div>
    </form>
  </div>
  <?php if ( $posts ): ?>
  <div class="also__as">
    <div class="header__as">Смотрите также:</div>
    <div class="items__as">
    <?php
      foreach ( $posts as $post ):
        $regions_terms = get_the_terms( $post->ID, 'regions' );
        $city = null;
        foreach ( $regions_terms as $term ) {
          if ( 0 != $term->parent ) {
            $city = $term->name;
            break;
          }
        }
    ?>
      <a href="<?php echo get_permalink( $post->ID ); ?>" class="item__as">
        <span class="title__as"><?php echo $post->post_title; ?></span>
        <span class="city__as"><?php echo $city; ?></span>
      </a>
    <?php endforeach; ?>
    </div>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/' . $post_type ) ); ?>" class="all-tenders__as">Все заявки</a>
  </div>
  <?php endif; ?>
</div>