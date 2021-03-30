<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<?php
  $post = as_get_post_by_key();

  $post_id = $post->ID;
  $post_type = $post->post_type;
  
  $state = get_field( 'tender_state', $post_id );
  $from = get_field( 'tender_amount_from', $post_id );
  $to = get_field( 'tender_amount_to', $post_id );
  $title = $post->post_title;
  $text = $post->post_content;
  $name = get_field( 'tender_user_name', $post_id );
  $email = get_field( 'tender_user_email', $post_id );
  $phone = get_field( 'tender_user_phone', $post_id );

  if ( 2 == count( $state ) ) {
    $state = 'any';
  } else {
    $state = $state[0];
  }

  $parts_categories_terms = get_the_terms( $post_id, 'parts_categories' );
  if ( ! $parts_categories_terms ) {
    $parts_categories_terms = array();
  }

  $cat = null;
  foreach ( $parts_categories_terms as $term ) {
    if ( 0 == $term->parent ) {
      $cat = $term->term_id;
      break;
    }
  }

  $regions_terms = get_the_terms( $post_id, 'regions' );
  $country = null;
  $city = null;
  foreach ( $regions_terms as $term ) {
    if ( 0 == $term->parent ) {
      $country = $term->term_id;
      break;
    }
  }
  foreach ( $regions_terms as $term ) {
    if ( 0 != $term->parent ) {
      $city = $term->term_id;
      break;
    }
  }
  $city_parent = $country;
?>

<div class="edit-parts__as"<?php if ( 'parts' != $post_type ) echo ' style="display: none;"'; ?>>
  <form id="edit-parts__as" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="as_post_type" value="parts">
    <input type="hidden" name="as_key" value="<?php echo $_GET['key']; ?>">
    <div class="item__as">
      <div class="content__as">
        <div class="title__as">Выберите тип заявки:</div>
        <div class="btns__as tender-type__as">
          <button type="button" data-block=".edit-trade__as">Покупка</button>
          <button type="button" data-block=".edit-rent__as">Аренда</button>
          <button type="button" class="active__as" data-block=".edit-parts__as">Запчасти</button>
          <button type="button" data-block=".edit-services__as">Услуги</button>
        </div>
      </div>
      <div class="sidebar__as">
        <div class="text__as">Если вы хотите купить технику, то выбирайте раздел «Покупка».</div>
        <div class="text__as">Если вы хотите арендовать технику, выбирайте «Аренда».</div>
        <div class="text__as">Если вас интересуют комплектующие для спецтехники, выбирайте раздел «Запчасти».</div>
        <div class="text__as">Если вы хотите заказать услугу, выбирайте раздел «Услуги».</div>
      </div>
    </div>
    <?php
      $parts_categories = get_terms( array( 'taxonomy' => 'parts_categories', 'hide_empty' => false, 'orderby' => 'name' ) );

      $parts_categories_parents = array();
      foreach ( $parts_categories as $parts_category ) {
        if ( 0 == $parts_category->parent ) {
          $parts_categories_parents[] = $parts_category;
        }
      }

      $parts_categories_parents_popular = array();
      $parts_categories_parents_all = array();
      $parts_categories_parents_presets = array();
      foreach ( $parts_categories_parents as $parts_category ) {
        if ( get_field( 'popular_category', $parts_category ) ) {
          $parts_categories_parents_popular[] = $parts_category;
        } else {
          $parts_categories_parents_all[] = $parts_category;
        }

        if ( get_field( 'preset_link', $parts_category ) ) {
          $parts_categories_parents_presets[] = $parts_category;
        }
      }
      $parts_categories_parents_presets = array_slice( $parts_categories_parents_presets, 0, 9 );
    ?>
    <div class="item__as item-1__as">
      <div class="content__as">
        <div class="title__as">Какой тип запчастей вам нужен?<sup>*</sup></div>
        <select class="main-cat__as" name="as_cat" data-placeholder="Тип запчастей" data-validation="required">
          <option></option>
          <optgroup label="Популярные">
            <?php foreach ( $parts_categories_parents_popular as $parts_category ): ?>
              <option value="<?php echo $parts_category->term_id; ?>"<?php if ( $cat == $parts_category->term_id ) echo ' selected'; ?>><?php echo $parts_category->name; ?></option>
            <?php endforeach; ?>
          </optgroup>
          <optgroup label="Все">
            <?php foreach ( $parts_categories_parents_all as $parts_category ): ?>
              <option value="<?php echo $parts_category->term_id; ?>"<?php if ( $cat == $parts_category->term_id ) echo ' selected'; ?>><?php echo $parts_category->name; ?></option>
            <?php endforeach; ?>
          </optgroup>
        </select>
        <div class="preset-links__as">
          <?php foreach ( $parts_categories_parents_presets as $parts_category ): ?>
            <span class="link__as" data-id="<?php echo $parts_category->term_id; ?>" data-select=".cat__as"><?php echo $parts_category->name; ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="sidebar__as">
        <div class="text__as">Выберите один, главный тип техники, который вам необходим. Если вам нужно несколько типов техники, то вы можете их перечислить в описании заявки.</div>
      </div>
    </div>
    <?php
      $regions = get_terms( array( 'taxonomy' => 'regions', 'hide_empty' => false, 'orderby' => 'name' ) );

      $regions_parents = array();
      $regions_childs = array();
      foreach ( $regions as $region ) {
        if ( 0 == $region->parent ) {
          $regions_parents[] = $region;
        } else {
          $regions_childs[] = $region;
        }
      }

      $regions_parents_popular = array();
      $regions_parents_all = array();
      foreach ( $regions_parents as $region ) {
        if ( get_field( 'popular_region', $region ) ) {
          $regions_parents_popular[] = $region;
        } else {
          $regions_parents_all[] = $region;
        }
      }

      $regions_childs_popular = array();
      $regions_childs_all = array();
      foreach ( $regions_childs as $region ) {
        if ( get_field( 'popular_region', $region ) ) {
          $regions_childs_popular[] = $region;
        } else {
          $regions_childs_all[] = $region;
        }
      }
    ?>
    <div class="item__as item-3__as">
      <div class="content__as">
        <div class="title__as">В каком регионе вы ищите запчасти?<sup>*</sup></div>
        <div class="select-block__as half__as">
          <select class="country__as" name="as_country" data-placeholder="Страна" data-validation="required">
            <option></option>
            <optgroup label="Популярные">
              <?php foreach ( $regions_parents_popular as $region ): ?>
                <option value="<?php echo $region->term_id; ?>"<?php if ( $country == $region->term_id ) echo ' selected'; ?>><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все">
              <?php foreach ( $regions_parents_all as $region ): ?>
                <option value="<?php echo $region->term_id; ?>"<?php if ( $country == $region->term_id ) echo ' selected'; ?>><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
          <select class="city__as" name="as_city" data-placeholder="Город" data-validation="required">
            <option class="none__as"></option>
            <optgroup label="Популярные" class="popular__as">
              <?php foreach ( $regions_childs_popular as $region ): ?>
                <option value="<?php echo $region->term_id; ?>" data-parent="<?php echo $region->parent; ?>"<?php if ( $city == $region->term_id ) echo ' selected'; ?><?php if ( $city_parent != $region->parent ) echo ' disabled'; ?>><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все" class="all__as">
              <?php foreach ( $regions_childs_all as $region ): ?>
                <option value="<?php echo $region->term_id; ?>" data-parent="<?php echo $region->parent; ?>"<?php if ( $city == $region->term_id ) echo ' selected'; ?><?php if ( $city_parent != $region->parent ) echo ' disabled'; ?>><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
        </div>
      </div>
      <div class="sidebar__as"></div>
    </div>
    <div class="item__as item-4__as">
      <div class="content__as">
        <div class="title__as">Какое состояние запчасти вас интересует?</div>
        <div class="btns__as state__as">
          <button type="button" data-val="any"<?php if ( 'any' == $state ) echo ' class="active__as"'; ?>>Любая</button>
          <button type="button" data-val="new"<?php if ( 'new' == $state ) echo ' class="active__as"'; ?>>Новая</button>
          <button type="button" data-val="fc"<?php if ( 'fc' == $state ) echo ' class="active__as"'; ?>>Б/у</button>
        </div>
        <input type="hidden" name="as_state" value="<?php echo $state; ?>">
      </div>
      <div class="sidebar__as"></div>
    </div>
    <div class="item__as item-5__as">
      <div class="content__as">
        <div class="title__as">Сумма сделки: <span class="add-info__as">(заполняется по желанию)</span></div>
        <div class="price-block__as">
          <span class="from">от</span>
          <input type="text" placeholder="1 000 000" name="as_from" data-validation="number" data-validation-optional="true" value="<?php echo $from; ?>" autocomplete="fuck">
          <span class="to">до</span>
          <input type="text" placeholder="12 000 000" name="as_to" data-validation="number" data-validation-optional="true" value="<?php echo $to; ?>" autocomplete="fuck">
          <span class="rub">рублей</span>
        </div>
      </div>
      <div class="sidebar__as">
        <div class="text__as">Укажите ограничение в бюджете, если оно имеется. Если поле оставить пустым, то в заявке будет указано «Цена договорная».</div>
      </div>
    </div>
    <div class="item__as item-6__as pb10__as">
      <div class="content__as">
        <div class="title__as mb0__as">Заголовок:<sup>*</sup></div>
        <div class="remain__as">Осталось <span id="parts-title-symbols__as">100</span> <span class="symbols__as">символов</span></div>
        <input type="text" placeholder="Введите заголовок заявки" name="as_title" data-validation="length" data-validation-length="1-100" value="<?php echo $title; ?>" autocomplete="fuck">
      </div>
      <div class="sidebar__as">
        <div class="text__as bold__as">Заголовок:</div>
        <div class="text__as">Напишите краткий и емкий заголовок.</div>
        <div class="text__as">Например, «Куплю экскаватор-погрузчик в Москве».</div>
      </div>
    </div>
    <div class="item__as item-7__as">
      <div class="content__as">
        <div class="title__as mb0__as">Описание:<sup>*</sup></div>
        <div class="remain__as">Осталось <span id="parts-text-symbols__as">1000</span> <span class="symbols__as">символов</span></div>
        <textarea placeholder="Введите описание заявки" name="as_text" data-validation="length" data-validation-length="1-1000"><?php echo $text; ?></textarea>
      </div>
      <div class="sidebar__as">
        <div class="text__as bold__as">Описание:</div>
        <div class="text__as">Как можно более подробно опишите свою заявку. Укажите предпочитаемый способ связи. Можете перечислить дополнительные типы оборудования или техники.</div>
      </div>
    </div>
    <div class="item__as item-8__as">
      <div class="content__as">
        <div class="title__as">Как продавцам с вами связаться?</div>
        <div class="input-block__as"><input type="text" placeholder="Ваше имя" name="as_name" data-validation="required" value="<?php echo $name; ?>" autocomplete="fuck"></div>
        <div class="input-block__as"><input type="text" placeholder="Email для связи" name="as_email" data-validation="email" value="<?php echo $email; ?>" autocomplete="fuck"></div>
        <div class="input-block__as"><input type="text" placeholder="Контактный телефон" name="as_phone" data-validation="custom" data-validation-regexp="\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})" value="<?php echo $phone; ?>" autocomplete="fuck"></div>
        <button type="submit">Сохранить изменения</button>
        <button type="button" class="del__as">Удалить заявку</button>
      </div>
      <div class="sidebar__as">
        <div class="text__as">Обязательно укажите email и регулярно проверяйте его.</div>
        <div class="text__as">Обязательно укажите телефон для связи с вами.</div>
      </div>
    </div>
  </form>
</div>