jQuery(document).ready(function($) {
  
  $.validate({
    form: '#addadv-trade__as, #addadv-rent__as, #addadv-parts__as, #addadv-services__as, #edit-trade__as, #edit-rent__as',
    errorElementClass: 'error__as',
    validateOnBlur: false,
    showHelpOnFocus: false,
    addSuggestions: false,
    borderColorOnError: '',
    scrollToTopOnError: false,
    onError: function(form) {
      goToError(form);
    }
  });
  
  $.validate({
    form: '#send-message__as',
    errorElementClass: 'error__as',
    validateOnBlur: false,
    showHelpOnFocus: false,
    addSuggestions: false,
    borderColorOnError: '',
    scrollToTopOnError: false
  });
  
  $.validate({
    form: '#subscribe__as',
    errorElementClass: 'error__as',
    validateOnBlur: false,
    showHelpOnFocus: false,
    addSuggestions: false,
    borderColorOnError: '',
    scrollToTopOnError: false
  });
  
  $('.tender-type__as button').click(function(e) {
    
    e.preventDefault();
    
    if ($(this).hasClass('active__as')) return;
    
    var block = $(this).data('block');
  
    $('.tender-type__as button[data-block="'+ block +'"]').siblings().removeClass('active__as');
    $('.tender-type__as button[data-block="'+ block +'"]').addClass('active__as');
    
    $(block).siblings().hide();
    $(block).show();
    
    $(block).find('select').trigger('refresh');
    
  });
  
  restrictLengthFields();
  
  $('#trade-title-symbols__as, #trade-text-symbols__as, #rent-title-symbols__as, #rent-text-symbols__as, #parts-title-symbols__as, #parts-text-symbols__as, #services-title-symbols__as, #services-text-symbols__as').on('DOMSubtreeModified', function() {
    var symbols_count = $(this).text();
    if (symbols_count || +symbols_count === 0) {
      $(this).next().text(declOfNum(symbols_count, ['символ', 'символа', 'символов']));
    }
  });
  
  $('.cat__as, .main-cat__as, .subcat__as, .country__as, .city__as, .state__as, .period__as').styler({
    selectSearch: true,
    selectSearchLimit: 10
  });
  
  $('.preset-links__as .link__as').click(function() {
    
    var parent = $(this).parents('form');
    
    $(parent).find('select' + $(this).data('select')).val($(this).data('id')).trigger('refresh').trigger('change');
    
  });
  
  $('select.cat__as').change(function(e, from) {
    
    var parent = $(this).parents('form');
    
    var selectSubcat = $(parent).find('select.subcat__as');
    
    if (selectSubcat.prop('disabled')) {
      selectSubcat.prop('disabled', false);
    }
    if (!from) {
      $('option:selected', selectSubcat).prop('selected', false);
    }
    
    $(selectSubcat).find('option:not(.none__as)').prop('disabled', true);
    $(selectSubcat).find('option[data-parent="'+ $(this).val() +'"]').prop('disabled', false);
    
    var optgroupAll = $(selectSubcat).find('optgroup.all__as');
    var allOptions = $(optgroupAll).find('option');
    var allOptionsDisabled = true;
    allOptions.each(function() {
      if (!$(this).is(':disabled')) {
        allOptionsDisabled = false;
        return false;
      }
    });
    if (allOptionsDisabled) {
      $(optgroupAll).addClass('disabled__as');
    } else {
      $(optgroupAll).removeClass('disabled__as');
    }
    
    var optgroupPopular = $(selectSubcat).find('optgroup.popular__as');
    var popularOptions = $(optgroupPopular).find('option');
    var popularOptionsDisabled = true;
    popularOptions.each(function() {
      if (!$(this).is(':disabled')) {
        popularOptionsDisabled = false;
        return false;
      }
    });
    if (popularOptionsDisabled) {
      $(optgroupPopular).addClass('disabled__as');
    } else {
      $(optgroupPopular).removeClass('disabled__as');
    }
    
    $(this).trigger('refresh');
    $(selectSubcat).trigger('refresh');
    
    if (!from) {
      if (!$(parent).find('.item-2__as').length) return;
      $('html, body').animate({ scrollTop: $(parent).find('.item-2__as').offset().top }, 500);
    }
    
  });
  
  $('select.main-cat__as').change(function(e) {
    
    var parent = $(this).parents('form');
    
    if (!$(parent).find('.item-3__as').length) return;
    $('html, body').animate({ scrollTop: $(parent).find('.item-3__as').offset().top }, 500);
    
  });
  
  $('select.subcat__as').change(function(e) {
    
    var parent = $(this).parents('form');
    
    var dataParent = $('option:selected', this).data('parent');
    var selectCat = $(parent).find('select.cat__as');
    
    selectCat.val(dataParent);
    selectCat.trigger('refresh').trigger('change', ['subcat']);
    
    if (!$(parent).find('.item-3__as').length) return;
    $('html, body').animate({ scrollTop: $(parent).find('.item-3__as').offset().top }, 500);
    
  });
  
  $('select.country__as').change(function(e) {
    
    var parent = $(this).parents('form');
    
    var selectCity = $(parent).find('select.city__as');
    
    if (selectCity.prop('disabled')) {
      selectCity.prop('disabled', false);
    }
    
    $('option:selected', selectCity).prop('selected', false);
    
    $(selectCity).find('option:not(.none__as)').prop('disabled', true);
    $(selectCity).find('option[data-parent="'+ $(this).val() +'"]').prop('disabled', false);
    
    var optgroupAll = $(selectCity).find('optgroup.all__as');
    var allOptions = $(optgroupAll).find('option');
    var allOptionsDisabled = true;
    allOptions.each(function() {
      if (!$(this).is(':disabled')) {
        allOptionsDisabled = false;
        return false;
      }
    });
    if (allOptionsDisabled) {
      $(optgroupAll).addClass('disabled__as');
    } else {
      $(optgroupAll).removeClass('disabled__as');
    }
    
    var optgroupPopular = $(selectCity).find('optgroup.popular__as');
    var popularOptions = $(optgroupPopular).find('option');
    var popularOptionsDisabled = true;
    popularOptions.each(function() {
      if (!$(this).is(':disabled')) {
        popularOptionsDisabled = false;
        return false;
      }
    });
    if (popularOptionsDisabled) {
      $(optgroupPopular).addClass('disabled__as');
    } else {
      $(optgroupPopular).removeClass('disabled__as');
    }
    
    $(this).trigger('refresh');
    $(selectCity).trigger('refresh');
    
  });
  
  $('select.city__as').change(function(e) {
    
    var parent = $(this).parents('form');
    
    $('html, body').animate({ scrollTop: $(parent).find('.item-4__as').offset().top }, 500);
    
  });
  
  $('.state__as button').click(function(e) {
    
    e.preventDefault();
    
    var parent = $(this).parents('form');
    
    $(this).siblings().removeClass('active__as');
    $(this).addClass('active__as');
    
    var state = $(this).data('val');
    $(parent).find('input[name="as_state"]').val(state);
    
    $('html, body').animate({ scrollTop: $(parent).find('.item-5__as').offset().top }, 500);
    
  });
  
  $('.tender__as input[type="text"], .tender__as textarea, .send-message-popup__as input[type="text"], .send-message-popup__as textarea, .single-bottom__as .subscribe__as input[type="text"], .single-bottom__as .subscribe__as button[type="submit"]').focus(function() {
    $(this).removeClass('error__as');
  });
  
  $('.tender__as button.del__as').click(function(e) {
    
    e.preventDefault();
    
    var formId = $(this).closest('form').attr('id');
    $('#delete-popup__as button.yes__as').attr('data-form', formId);
    
    $.fancybox.open({
      src: '#delete-popup__as',
      type: 'inline',
      lang: 'ru',
      i18n: {
        'ru': {
          CLOSE: 'Закрыть'
        }
      }
    });
    
  });
  
  $('.delete-popup__as button.yes__as').click(function(e) {
    
    e.preventDefault();
    
    var form = '#' + $(this).data('form');
    
    $(form).find('input[name="action"]').val('delete');
    $(form).trigger('submit');
    
  });
  
  $('.single-top__as button.show-contact__as').click(function(e) {
    
    e.preventDefault();
    
    $(this).next().show();
    $(this).remove();
    
  });
  
  $('.single-top__as .helpers__as .print__as').click(function() {
    window.print();
  });
  
  $('.single-top__as .helpers__as .favorites__as').click(function() {
    
    var self = $(this);
    var actionInner = '';
    
    if (self.hasClass('active__as')) {
      self.attr('title', 'Добавить в закладки').removeClass('active__as').next().hide();
      actionInner = 'remove';
    } else {
      self.attr('title', 'Удалить с закладок').addClass('active__as').next().show();
      actionInner = 'add';
    }
    
    var data = {
      action: 'add_to_favorites',
      as_post_id: self.data('post-id'),
      as_action_inner: actionInner
    };
    
    $.post(asajax.url, data, function(resp) {
      self.next().find('.count__as').text(resp);
    });
    
  });
  
  $('.single-top__as .helpers__as .message__as').click(function() {
    
    $.fancybox.open({
      src: '#send-message-popup__as',
      type: 'inline',
      lang: 'ru',
      i18n: {
        'ru': {
          CLOSE: 'Закрыть'
        }
      }
    });
    
  });
  
  $('form#send-message__as').submit(function(e) {
    
    e.preventDefault();
    
    var self = $(this);
    
    self.find('.resp__as').hide();
    
    var data = {
      action: 'send_message',
      as_post_id: $(this).find('input[name="as_post_id"]').val(),
      as_message: $(this).find('textarea[name="as_message"]').val(),
      as_email: $(this).find('input[name="as_email"]').val()
    };
    
    $.post(asajax.url, data, function(resp) {
      
      self[0].reset();
      
      if (resp == 0) self.find('.resp__as').text('Ошибка при отправке сообщения.').show();
      else self.find('.resp__as').text('Спасибо! Ваше сообщение было успешно отправлено.').show();
      
    });
    
  });
  
  $('.favorites__as .star__as').click(function() {
    
    var data = {
      action: 'remove_from_favorites',
      as_post_id: $(this).data('post-id')
    };
    
    $.post(asajax.url, data, function() {
      window.location.reload(true);
    });
    
  });
  
  $('.favorites__as .clear-favorites__as').click(function(e) {
    
    e.preventDefault();
    
    var data = { action: 'clear_favorites' };
    
    $.post(asajax.url, data, function() {
      window.location.reload(true);
    });
    
  });
  
  $('form#subscribe__as').submit(function(e) {
    
    e.preventDefault();
    
    var self = $(this);
    
    var email = self.find('input[name="as_email"]').val();
    
    var data = {
      action: 'add_email_to_subscribe',
      as_email: email
    };
    
    $.post(asajax.url, data, function(resp) {
      
      self[0].reset();
      
      if (resp == 0) return;
      
      self.find('.info__as').text('На адрес '+ email +' отправлено сообщение с просьбой подтвердить подписку на рассылку.');
      
    });
    
  });
  
  $('.tenders__as button.load-more__as').click(function(e) {
    
    e.preventDefault();
    
    self = $(this);
    
    self.hide();
    self.next().show();
    
    var form = $('.tenders__as form');
    
    var postType = self.data('post-type');
    var offset = self.prev().find('.item__as').length;
    var cat = form.find('select[name="as_cat"]').val();
    var subcat = form.find('select[name="as_subcat"]').val();
    var state = form.find('select[name="as_state"]').val();
    var country = form.find('select[name="as_country"]').val();
    var period = form.find('select[name="as_period"]').val();
    var to = form.find('input[name="as_to"]').val();
    
    if (subcat === undefined) subcat = 0;
    if (state === undefined) state = 'any';
    
    var data = {
      action: 'load_more',
      as_post_type: postType,
      as_offset: offset,
      as_cat: cat,
      as_subcat: subcat,
      as_state: state,
      as_country: country,
      as_period: period,
      as_to: to
    };
    
    $.post(asajax.url, data, function(resp) {
      
      self.next().hide();
      
      if (resp == 0) return;
      
      self.prev().append(resp);
      
      if (self.prev().find('.item__as').length - offset >= 15) self.show();
      
    });
    
  });
  
  $('.tenders__as form').submit(function(e) {
    
    e.preventDefault();
    
    self = $(this);
    
    self.find('button[type="submit"]').attr('disabled', true);
    self.parent().next().empty().next().hide().next().show();
    $('.tenders__as .no-posts__as').hide();
    
    var postType = self.find('input[name="as_post_type"]').val();
    var cat = self.find('select[name="as_cat"]').val();
    var subcat = self.find('select[name="as_subcat"]').val();
    var state = self.find('select[name="as_state"]').val();
    var country = self.find('select[name="as_country"]').val();
    var period = self.find('select[name="as_period"]').val();
    var to = self.find('input[name="as_to"]').val();
    
    if (subcat === undefined) subcat = 0;
    if (state === undefined) state = 'any';
    
    var data = {
      action: 'filter_tenders',
      as_post_type: postType,
      as_cat: cat,
      as_subcat: subcat,
      as_state: state,
      as_country: country,
      as_period: period,
      as_to: to
    };
    
    $.post(asajax.url, data, function(resp) {
      
      $('.spinner__as').hide();
      $('html, body').animate({ scrollTop: $('.tenders__as .posts__as').offset().top }, 500);
      self.find('button[type="submit"]').attr('disabled', false);
      
      if (resp == 0) {
        $('.tenders__as .no-posts__as').show();
        return;
      }
      
      self.parent().next().append(resp);
      
      if (self.parent().next().find('.item__as').length >= 15) self.parent().next().next().show();
      
    });
    
    data['action'] = 'total_tenders';
    
    $.post(asajax.url, data, function(resp) {
      $('.tenders__as .total-tenders__as .count__as').text(resp);
    });
    
  });
  
  $('.tabs__as .header__as').click(function() {
    
    var self = $(this);
    
    if (self.hasClass('active__as')) return;
    
    self.siblings().removeClass('active__as');
    self.addClass('active__as');
    
    var tabId = self.data('id');
    $('#' + tabId).siblings().removeClass('active__as');
    $('#' + tabId).addClass('active__as');
    
  });
  
  function goToError(form) {
    $('html, body').animate({ scrollTop: $(form).find('.error__as').first().closest('.item__as').offset().top }, 500);
  }
  
  function declOfNum(number, titles) {  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];  
  }
  
  function restrictLengthFields() {
    
    if ($('.addadv-trade__as #trade-title-symbols__as').length)
    $('.addadv-trade__as input[name="as_title"]').restrictLength($('#trade-title-symbols__as'));
    if ($('.addadv-rent__as #rent-title-symbols__as').length)
      $('.addadv-rent__as input[name="as_title"]').restrictLength($('#rent-title-symbols__as'));
    if ($('.addadv-parts__as #parts-title-symbols__as').length)
      $('.addadv-parts__as input[name="as_title"]').restrictLength($('#parts-title-symbols__as'));
    if ($('.addadv-services__as #services-title-symbols__as').length)
      $('.addadv-services__as input[name="as_title"]').restrictLength($('#services-title-symbols__as'));
    
    if ($('.edit-trade__as #trade-title-symbols__as').length)
      $('.edit-trade__as input[name="as_title"]').restrictLength($('#trade-title-symbols__as'));
    if ($('.edit-rent__as #rent-title-symbols__as').length)
      $('.edit-rent__as input[name="as_title"]').restrictLength($('#rent-title-symbols__as'));
    if ($('.edit-parts__as #parts-title-symbols__as').length)
      $('.edit-parts__as input[name="as_title"]').restrictLength($('#parts-title-symbols__as'));
    if ($('.edit-services__as #services-title-symbols__as').length)
      $('.edit-services__as input[name="as_title"]').restrictLength($('#services-title-symbols__as'));

    if ($('.addadv-trade__as #trade-text-symbols__as').length)
      $('.addadv-trade__as textarea[name="as_text"]').restrictLength($('#trade-text-symbols__as'));
    if ($('.addadv-rent__as #rent-text-symbols__as').length) 
      $('.addadv-rent__as textarea[name="as_text"]').restrictLength($('#rent-text-symbols__as'));
    if ($('.addadv-parts__as #parts-text-symbols__as').length) 
      $('.addadv-parts__as textarea[name="as_text"]').restrictLength($('#parts-text-symbols__as'));
    if ($('.addadv-services__as #services-text-symbols__as').length) 
      $('.addadv-services__as textarea[name="as_text"]').restrictLength($('#services-text-symbols__as'));
    
    if ($('.edit-trade__as #trade-text-symbols__as').length) 
      $('.edit-trade__as textarea[name="as_text"]').restrictLength($('#trade-text-symbols__as'));
    if ($('.edit-rent__as #rent-text-symbols__as').length) 
      $('.edit-rent__as textarea[name="as_text"]').restrictLength($('#rent-text-symbols__as'));
    if ($('.edit-parts__as #parts-text-symbols__as').length) 
      $('.edit-parts__as textarea[name="as_text"]').restrictLength($('#parts-text-symbols__as'));
    if ($('.edit-services__as #services-text-symbols__as').length) 
      $('.edit-services__as textarea[name="as_text"]').restrictLength($('#services-text-symbols__as'));
    
  }
  
});