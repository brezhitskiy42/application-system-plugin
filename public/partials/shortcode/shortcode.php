<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="tabs__as">
  <div class="title__as"><?php echo $block_title; ?></div>
  <div class="headers__as">
    <?php $i = 1; foreach ( $tabs_title as $title ): ?>
    <div class="header__as<?php if ( 1 == $i ) echo ' active__as'; ?>" data-id="tab<?php echo $i; ?>__as"><span><?php echo $title; ?></span></div>
    <?php $i++; endforeach; ?>
  </div>
  <div class="tabs-content__as">
    <?php $j = 1; foreach ( $tabs as $tab ): ?>
    <div id="tab<?php echo $j; ?>__as" class="tab__as<?php if ( 1 == $j ) echo ' active__as'; ?>">
      <?php
        $posts = get_posts( array( 'numberposts' => 6, 'post_type' => $tab ) );
        if ( $posts ):
      ?>
      <div class="items__as">
        <?php foreach ( $posts as $post ): ?>
        <div class="item__as">
          <a href="<?php echo get_permalink( $post->ID ); ?>" class="title__as"><?php echo $post->post_title; ?></a>
          <div class="content__as"><?php echo wp_trim_words( $post->post_content, 15, '...' ); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="links__as">
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/' . $tab ) ); ?>" class="all-tenders__as">Смотреть все заявки</a>
        <div class="divider__as"></div>
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/addadv' ) ); ?>" class="add-tender__as">Разместить заявку</a>
      </div>
    </div>
    <?php $j++; endforeach; ?>
  </div>
</div>