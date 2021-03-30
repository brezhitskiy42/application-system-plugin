<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="save__as">
  <div class="info__as">
    <div class="title__as">Спасибо за размещение заявки!</div>
    <div class="text__as">Заявка будет размещена после проверки модератором. Максимальное время проверки 48 часов.</div>
    <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Все заявки</a>
  </div>
  <div class="tender-link__as">
    <div class="text__as">Ссылка для редактирования/удаления заявки:</div>
    <div class="link__as"><a href="%edit_link%">%edit_link%</a></div>
  </div>
</div>