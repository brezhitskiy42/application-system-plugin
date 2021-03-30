<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="tenders__as">
  <div class="rent__as">
    <div class="top-block__as">
      <div class="title__as">Поиск по заявкам</div>
      <div class="links__as">
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/favorites' ) ); ?>" class="favorites-link__as"><span class="star__as"></span>Избранные заявки (<span class="count__as"><?php echo as_favorites_count(); ?></span>)</a>
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/addadv' ) ); ?>" class="addadv-link__as">Разместить заявку</a>
      </div>
    </div>
    <div class="search-block__as">
      <form id="rent">
        <input type="hidden" name="as_post_type" value="rent">
        <div class="block__as">
          <div class="tender-type__as">
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Покупка</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/rent' ) ); ?>" class="active__as">Аренда</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/parts' ) ); ?>">Запчасти</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/services' ) ); ?>">Услуги</a>
          </div>
          <?php $all_posts = get_posts( array( 'numberposts' => -1, 'post_type' => 'rent' ) ); ?>
          <div class="total-tenders__as">Всего заявок: <span class="count__as"><?php echo count( $all_posts ); ?></span></div>
        </div>
        <?php
          $rent_categories = get_terms( array( 'taxonomy' => 'rent_categories', 'hide_empty' => false, 'orderby' => 'name' ) );

          $rent_categories_parents = array();
          $rent_categories_childs = array();
          foreach ( $rent_categories as $rent_category ) {
            if ( 0 == $rent_category->parent ) {
              $rent_categories_parents[] = $rent_category;
            } else {
              $rent_categories_childs[] = $rent_category;
            }
          }

          $rent_categories_parents_popular = array();
          $rent_categories_parents_all = array();
          foreach ( $rent_categories_parents as $rent_category ) {
            if ( get_field( 'popular_category', $rent_category ) ) {
              $rent_categories_parents_popular[] = $rent_category;
            } else {
              $rent_categories_parents_all[] = $rent_category;
            }
          }

          $rent_categories_childs_popular = array();
          $rent_categories_childs_all = array();
          foreach ( $rent_categories_childs as $rent_category ) {
            if ( get_field( 'popular_category', $rent_category ) ) {
              $rent_categories_childs_popular[] = $rent_category;
            } else {
              $rent_categories_childs_all[] = $rent_category;
            }
          }
        
          $regions = get_terms( array( 'taxonomy' => 'regions', 'hide_empty' => false, 'orderby' => 'name' ) );
        
          $regions_parents = array();
          foreach ( $regions as $region ) {
            if ( 0 == $region->parent ) {
              $regions_parents[] = $region;
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
        ?>
        <div class="block__as one-third__as">
          <select name="as_cat" class="cat__as" data-placeholder="Тип оборудования/техники">
            <option></option>
            <optgroup label="Популярные">
              <?php foreach ( $rent_categories_parents_popular as $rent_category ): ?>
                <option value="<?php echo $rent_category->term_id; ?>"><?php echo $rent_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все">
              <?php foreach ( $rent_categories_parents_all as $rent_category ): ?>
                <option value="<?php echo $rent_category->term_id; ?>"><?php echo $rent_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
          <select class="subcat__as" name="as_subcat" data-placeholder="Категория" disabled>
            <option class="none__as"></option>
            <optgroup label="Популярные" class="popular__as">
              <?php foreach ( $rent_categories_childs_popular as $rent_category ): ?>
                <option value="<?php echo $rent_category->term_id; ?>" data-parent="<?php echo $rent_category->parent; ?>"><?php echo $rent_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все" class="all__as">
              <?php foreach ( $rent_categories_childs_all as $rent_category ): ?>
                <option value="<?php echo $rent_category->term_id; ?>" data-parent="<?php echo $rent_category->parent; ?>"><?php echo $rent_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
          <select class="state__as" name="as_state" data-placeholder="Состояние">
            <option></option>
            <option value="any">Любая</option>
            <option value="new">Новая</option>
            <option value="fc">Б/у</option>
          </select>
          <select class="country__as" name="as_country" data-placeholder="Страна">
            <option></option>
            <optgroup label="Популярные">
              <?php foreach ( $regions_parents_popular as $region ): ?>
                <option value="<?php echo $region->term_id; ?>"><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все">
              <?php foreach ( $regions_parents_all as $region ): ?>
                <option value="<?php echo $region->term_id; ?>"><?php echo $region->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
          <select class="period__as" name="as_period" data-placeholder="Период">
            <option></option>
            <option value="3">3 дня</option>
            <option value="7">7 дней</option>
            <option value="14">14 дней</option>
          </select>
          <input type="text" name="as_to" placeholder="Цена до, &#8381;">
        </div>
        <button type="submit">Подобрать заявки</button>
      </form>
    </div>
    <div class="posts__as">
      <?php
        $posts = get_posts( array( 'numberposts' => 15, 'post_type' => 'rent' ) );
      
        $total_posts = count($posts);
        
        if ( $posts ): foreach ( $posts as $post ):
      
          $date = get_the_date( 'd.m.y', $post->ID );
          $today_date = date( 'd.m.y' );
          $now = null;
          if ( $date == $today_date ) {
            $now = true;
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
      <div class="item__as<?php if ( $now ) echo ' top__as'; ?>">
        <div class="date__as<?php if ( $now ) echo ' today__as'; ?>"><?php if ( $now ) echo 'Сегодня'; else echo $date; ?></div>
        <div class="content__as">
          <a href="<?php echo get_permalink( $post->ID ); ?>" class="title__as"><?php echo $post->post_title; ?></a>
          <div class="text__as"><?php echo wp_trim_words( $post->post_content, 15, '...' ); ?></div>
        </div>
        <div class="city-price__as">
          <div class="city__as"><?php echo $city; ?></div>
          <div class="price__as">Договорная</div>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>
    <button type="button" data-post-type="rent" class="load-more__as<?php if ( $total_posts < 15 ) echo ' hide__as'; ?>">Загрузить еще</button>
    <div class="spinner__as">
      <div class="double-bounce1__as"></div>
      <div class="double-bounce2__as"></div>
    </div>
    <div class="no-posts__as<?php if ( ! $posts ) echo ' show__as'; ?>">По вашему запросу заявок не найдено</div>
  </div>
</div>