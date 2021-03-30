<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="delete__as">
  <div class="info__as">
    <div class="title__as">Заявка удалена!</div>
    <div class="text__as">Ваша заявка была успешно удалена.</div>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Все заявки</a>
  </div>
</div>