<?php

// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

add_action( 'init', 'as_register_options_pages' );

add_action( 'acf/init', 'as_add_settings_custom_fields' );
add_action( 'acf/init', 'as_add_email_templates_custom_fields' );
add_action( 'acf/init', 'as_add_subscribers_custom_fields' );

add_action( 'acf/input/admin_head', 'as_hide_subscriber_key_field' );
add_action( 'acf/input/admin_head', 'as_add_export_func' );

add_action( 'wp_head', 'as_add_meta_tags' );

// Регистрация страниц с настройками
function as_register_options_pages() {
  
  acf_add_options_page( array(
    'page_title' => 'Система заявок',
    'menu_title' => 'Система заявок',
    'menu_slug' => 'as-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ) );
  
  acf_add_options_sub_page( array(
    'page_title' => 'Шаблоны писем',
    'menu_title' => 'Шаблоны писем',
    'menu_slug' => 'as-email-settings',
    'parent_slug' => 'as-settings'
  ) );
  
  acf_add_options_sub_page( array(
    'page_title' => 'Подписчики',
    'menu_title' => 'Подписчики',
    'menu_slug' => 'as-subscribers',
    'parent_slug' => 'as-settings'
  ) );
  
}

// Добавление произвольных полей к настройкам
function as_add_settings_custom_fields() {
  if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
      'key' => 'settings_group',
      'title' => 'Настройки',
      'fields' => array(
        array(
          'key' => 'export_tab_field',
          'label' => 'Экспорт заявок',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'export_from_field',
          'label' => 'От',
          'name' => 'export_from',
          'type' => 'date_picker',
          'display_format' => 'd.m.Y',
		  'return_format' => 'd.m.Y',
		  'first_day' => 1
        ),
        array(
          'key' => 'export_to_field',
          'label' => 'До',
          'name' => 'export_to',
          'type' => 'date_picker',
          'display_format' => 'd.m.Y',
		  'return_format' => 'd.m.Y',
		  'first_day' => 1
        ),
        array(
          'key' => 'seo_tab_field',
          'label' => 'SEO для заявок',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'seo_description_field',
          'label' => 'Описание',
          'name' => 'seo_description',
          'type' => 'textarea'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'as-settings'
          )
        )
      )
    ) );
  }
}

// Добавление произвольных полей к шаблонам писем
function as_add_email_templates_custom_fields() {
  if ( function_exists( 'acf_add_local_field_group' ) ) {
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-moderation.php';
    $email_moderation = ob_get_clean();
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-confirm.php';
    $email_confirm = ob_get_clean();
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-cancel.php';
    $email_cancel = ob_get_clean();
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-delete.php';
    $email_delete = ob_get_clean();
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-subscribe-confirmation.php';
    $email_subscribe_confirmation = ob_get_clean();
    
    ob_start();
    require_once AS_PATH . 'public/partials/email/email-newsletter.php';
    $email_newsletter = ob_get_clean();
    
    acf_add_local_field_group( array(
      'key' => 'email_template_group',
      'title' => 'Шаблоны писем',
      'fields' => array(
        array(
          'key' => 'email_moderation_tab_field',
          'label' => 'Заявка принята на модерацию',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_moderation_field',
          'label' => 'Текст',
          'name' => 'email_moderation',
          'type' => 'wysiwyg',
          'default_value' => $email_moderation
        ),
        array(
          'key' => 'email_confirm_tab_field',
          'label' => 'Заявка опубликована',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_confirm_field',
          'label' => 'Текст',
          'name' => 'email_confirm',
          'type' => 'wysiwyg',
          'default_value' => $email_confirm
        ),
        array(
          'key' => 'email_cancel_tab_field',
          'label' => 'Заявка отклонена',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_cancel_field',
          'label' => 'Текст',
          'name' => 'email_cancel',
          'type' => 'wysiwyg',
          'default_value' => $email_cancel
        ),
        array(
          'key' => 'email_delete_tab_field',
          'label' => 'Заявка удалена',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_delete_field',
          'label' => 'Текст',
          'name' => 'email_delete',
          'type' => 'wysiwyg',
          'default_value' => $email_delete
        ),
        array(
          'key' => 'email_subscribe_confirmation_tab_field',
          'label' => 'Подтверждение подписки',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_subscribe_confirmation_field',
          'label' => 'Текст',
          'name' => 'email_subscribe_confirmation',
          'type' => 'wysiwyg',
          'default_value' => $email_subscribe_confirmation
        ),
        array(
          'key' => 'email_newsletter_tab_field',
          'label' => 'Рассылка',
          'type' => 'tab',
          'placement' => 'left'
        ),
        array(
          'key' => 'email_newsletter_field',
          'label' => 'Текст',
          'name' => 'email_newsletter',
          'type' => 'wysiwyg',
          'default_value' => $email_newsletter
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'as-email-settings'
          )
        )
      )
    ) );
    
  }
}

// Добавление произвольных полей к подписчикам
function as_add_subscribers_custom_fields() {
  acf_add_local_field_group( array(
    'key' => 'subscribers_group',
    'title' => 'Подписчики',
    'fields' => array(
      array(
        'key' => 'subscriber_field',
        'label' => 'Подписчик',
        'name' => 'subscriber',
        'type' => 'repeater',
        'layout' => 'block',
        'sub_fields' => array(
          array(
            'key' => 'subscriber_email_field',
            'label' => 'Email',
            'name' => 'subscriber_email',
            'type' => 'text'
          ),
          array(
            'key' => 'is_subscribe_field',
            'label' => 'Подписан?',
            'name' => 'is_subscribe',
            'type' => 'true_false'
          ),
          array(
            'key' => 'subscriber_key_field',
            'label' => 'Ключ',
            'name' => 'subscriber_key',
            'type' => 'text'
          )
        )
      )
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'as-subscribers'
        )
      )
    )
  ) );
}

// Скрытие поля с ключом подписчика
function as_hide_subscriber_key_field() {
?>
<style type="text/css">.acf-field.acf-field-subscriber-key-field { display: none; }</style>
<?php
}

// Добавление функционала экспорта
function as_add_export_func() {
  
  global $pagenow;
  
  if ( 'admin.php' == $pagenow && isset( $_GET['page'] ) && 'as-settings' == $_GET['page'] ):
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {

    var exportBtnBlock = '<div class="export-block__as"><button type="button" class="acf-button button button-primary export-btn__as">Экспортировать</button></div>';
    $('.acf-field.acf-field-export-to-field').after(exportBtnBlock);
    
    if ($('.acf-tab-group li.active a').data('key') != 'export_tab_field') $('.export-block__as').hide();
    
    var exportForm = '<form id="export-form" method="get"><input type="hidden" name="export" value="ok"><input type="text" name="page" value="as-settings"></form>';
    $('form').after(exportForm);
    
    $('button.export-btn__as').click(function(e) {
      
      e.preventDefault();
      
      self = $(this);
      
      var from = $('input#acf-export_from_field').val();
      var to = $('input#acf-export_to_field').val();
      
      if ( ! from || ! to ) return;
      
      from = from.slice(-2) + '.' + from.slice(-4, -2) + '.' + from.slice(0, 4);
      to = to.slice(-2) + '.' + to.slice(-4, -2) + '.' + to.slice(0, 4);
      
      var data = {
        action: 'export_tenders',
        as_from: from,
        as_to: to
      };
      
      $.post(ajaxurl, data, function(resp) {
        
        if (!+resp) return;
        
        $('input#acf-export_from_field').val('');
        $('input#acf-export_to_field').val('');
        $('input#acf-export_from_field').next().val('');
        $('input#acf-export_to_field').next().val('');
        
        $('#export-form').trigger('submit');
        
      });
      
    });
    
    $('a.acf-tab-button').click(function() {
    
      if ($(this).data('key') == 'export_tab_field') $('.export-block__as').show();
      else $('.export-block__as').hide();
      
    });

  });
</script>
<style type="text/css">
  .export-block__as {
    padding: 15px 12px;
    padding-top: 0;
  }
  #export-form {
    display: none;
  }
</style>
<?php
  endif;
}

// Добавление мета тегов
function as_add_meta_tags() {
  
  if ( ! is_singular( 'trade', 'rent', 'parts', 'services' ) ) {
    return;
  }
  
  $seo_description = get_field( 'seo_description', 'option' );
  
  if ( ! $seo_description ) {
    $seo_description = '';
  }
  
  echo '<meta name="description" content="'. $seo_description .'">';
  
}