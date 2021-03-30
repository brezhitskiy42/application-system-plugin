<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div style="display: none;" class="send-message-popup__as" id="send-message-popup__as">
  <div class="title__as">Напишите сообщение</div>
  <form id="send-message__as">
    <input type="hidden" name="as_post_id" value="<?php global $post; echo $post->ID; ?>">
    <div class="block__as"><textarea name="as_message" placeholder="Сообщение" data-validation="required"></textarea></div>
    <div class="block__as"><input type="text" name="as_email" placeholder="Оставьте ваш email" data-validation="email"></div>
    <div class="all-required__as">Все поля обязательны для заполнения</div>
    <button type="submit">Отправить</button>
    <div class="resp__as">Все поля обязательны для заполнения</div>
  </form>
</div>