<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="unsubscribe__as">
  <div class="info__as">
    <div class="title__as">Подписка отменена!</div>
    <div class="text__as">Вы успешно отменили подписку на новые заявки.</div>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Все заявки</a>
  </div>
</div>