<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="main__as">
  <div class="top-block__as">
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>" class="title__as">Заявки</a>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Покупка</a>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/rent' ) ); ?>">Аренда</a>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/parts' ) ); ?>">Запчасти</a>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/services' ) ); ?>">Услуги</a>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/addadv' ) ); ?>" class="add-tender__as">Разместить заявку</a>
  </div>
  <?php
    $posts = get_posts( array( 'numberposts' => 9, 'post_type' => array( 'trade', 'rent', 'parts', 'services' ) ) );
    if ( $posts ):
  ?>
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
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>" class="all-tenders__as">Все заявки</a>
  <?php endif; ?>
</div>