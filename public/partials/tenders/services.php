<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<div class="tenders__as">
  <div class="services__as">
    <div class="top-block__as">
      <div class="title__as">Поиск по заявкам</div>
      <div class="links__as">
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/favorites' ) ); ?>" class="favorites-link__as"><span class="star__as"></span>Избранные заявки (<span class="count__as"><?php echo as_favorites_count(); ?></span>)</a>
        <a href="<?php echo get_permalink( get_page_by_path( 'tender/addadv' ) ); ?>" class="addadv-link__as">Разместить заявку</a>
      </div>
    </div>
    <div class="search-block__as">
      <form id="services">
        <input type="hidden" name="as_post_type" value="services">
        <div class="block__as">
          <div class="tender-type__as">
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/trade' ) ); ?>">Покупка</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/rent' ) ); ?>">Аренда</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/parts' ) ); ?>">Запчасти</a>
            <a href="<?php echo get_permalink( get_page_by_path( 'tender/services' ) ); ?>" class="active__as">Услуги</a>
          </div>
          <?php $all_posts = get_posts( array( 'numberposts' => -1, 'post_type' => 'services' ) ); ?>
          <div class="total-tenders__as">Всего заявок: <span class="count__as"><?php echo count( $all_posts ); ?></span></div>
        </div>
        <?php
          $services_categories = get_terms( array( 'taxonomy' => 'services_categories', 'hide_empty' => false, 'orderby' => 'name' ) );

          $services_categories_parents = array();
          foreach ( $services_categories as $services_category ) {
            if ( 0 == $services_category->parent ) {
              $services_categories_parents[] = $services_category;
            }
          }

          $services_categories_parents_popular = array();
          $services_categories_parents_all = array();
          foreach ( $services_categories_parents as $services_category ) {
            if ( get_field( 'popular_category', $services_category ) ) {
              $services_categories_parents_popular[] = $services_category;
            } else {
              $services_categories_parents_all[] = $services_category;
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
        <div class="block__as one-half__as">
          <select name="as_cat" class="main-cat__as" data-placeholder="Тип запчастей">
            <option></option>
            <optgroup label="Популярные">
              <?php foreach ( $services_categories_parents_popular as $services_category ): ?>
                <option value="<?php echo $services_category->term_id; ?>"><?php echo $services_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="Все">
              <?php foreach ( $services_categories_parents_all as $services_category ): ?>
                <option value="<?php echo $services_category->term_id; ?>"><?php echo $services_category->name; ?></option>
              <?php endforeach; ?>
            </optgroup>
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
        $posts = get_posts( array( 'numberposts' => 15, 'post_type' => 'services' ) );
      
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
    <button type="button" data-post-type="services" class="load-more__as<?php if ( $total_posts < 15 ) echo ' hide__as'; ?>">Загрузить еще</button>
    <div class="spinner__as">
      <div class="double-bounce1__as"></div>
      <div class="double-bounce2__as"></div>
    </div>
    <div class="no-posts__as<?php if ( ! $posts ) echo ' show__as'; ?>">По вашему запросу заявок не найдено</div>
  </div>
</div>