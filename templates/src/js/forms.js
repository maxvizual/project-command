'use strict';

(function ($, api) {
    $(document).ready(function () {

        // Contact Form Validation
        var validateEmail = function (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            return re.test(email);
        }

        var validate = function ($form) {
            var is_validate = true;
            var response = 'no-captcha';

            $('.invalid-verification').removeClass('active');

            $form.find('input, textarea, .agree-invalid').removeClass('invalid');
            $form.find('input[type="text"], textarea[name="message"]').each(function (i, elem) {
                if (!$(elem).val()) {
                    $(elem).addClass('invalid');
                    is_validate = false;
                }
            });
            if (!validateEmail($form.find('input[name="email"]').val())) {
                $form.find('input[name="email"]').addClass('invalid');
                is_validate = false;
                if($form.find('input[name="email"]').val().indexOf('@') == -1) {
                    $form.find('.invalid-email-at').addClass('active');
                }
            }
            if ($form.find('input[name="agree"]').length && !$form.find('input[name="agree"]').prop('checked')) {
                $form.find('input[name="agree"],.agree-invalid').addClass('invalid');
                $form.find('input[name="agree"]').parent().addClass('invalid');
                is_validate = false;
            }
            if ($form.find('input[name="agree2"]').length && !$form.find('input[name="agree2"]').prop('checked')) {
                $form.find('input[name="agree2"],.agree-invalid').addClass('invalid');
                $form.find('input[name="agree2"]').parent().addClass('invalid');
                is_validate = false;
            }
            if ($form.find('input[name="agree3"]').length && !$form.find('input[name="agree3"]').prop('checked')) {
                $form.find('input[name="agree3"],.agree-invalid').addClass('invalid');
                $form.find('input[name="agree3"]').parent().addClass('invalid');
                is_validate = false;
            }
            if (response.length == 0) {
                is_validate = false;
            }
            return is_validate;
        }

        $('body').on('change', '.js--agree-all', function (e) {
            var $form = $(this).closest('form');
            var $agree_checkboxs = $form.find('[type="checkbox"]');
            $agree_checkboxs.prop('checked', $(this).prop('checked'));
        });

       
        api.onSubmit = function(token) {            
            api.activeForm.submit();
        };
        
        $('body').on('submit', 'form', function (e) {
            e.preventDefault();
            var $form = $(this);
            if (validate($form)) {
                $form.removeClass('submited').find('.submit-response').removeClass('success').removeClass('failed').html('');
                $.ajax({
                    url: __formVars.ajax_url + '?action=send_form&lang=' + __formVars.app_lang,
                    method: 'post',
                    data: $(this).serialize()
                }).done(function (data) {
                    $('html,body').stop().animate({
                        scrollTop: $(e.currentTarget).offset().top - $('.navbar').outerHeight()
                    }, 800);
                    $form.addClass('submited').find('.thanks').addClass('active');
                    $form.find('.thanks .thanks-close').one('click', function (e) {
                        e.preventDefault();
                        $form.find('input[type="text"], textarea').each(function (i, elem) {
                            $(elem).val('');
                        });
                        $form.closest('.collapse').collapse("hide");
                        $form.find('.thanks').removeClass('active');
                    });
                    $form.find('[type="checkbox"]').prop('checked', false);
                    $form.find(textarea).val('Interesuje mnie mieszkanie {{formItem.LOKAL_NAZWA}} w budynku {{formItem.BUDYNEK_NAZWA}}');
                    if ($form.closest('.form-content.offer')) {
                        setTimeout(function () {
                            $form.find('input[type="text"], textarea').each(function (i, elem) {
                                $(elem).val('');
                            });
                            $form.closest('.collapse').collapse("hide");
                            $form.removeClass('submited').find('.thanks').removeClass('active');
                        }, 1000 * 10);
                    }
                }).fail(function (jqXHR, textStatus) {
                    console.log("Request failed: ", jqXHR, textStatus);
                    var response = JSON.parse(jqXHR.responseText);
                    $form.find('.submit-response').addClass('failed').html(response.error);
                });
            } else {
                $('.invalid-verification').addClass('active');
            }
            grecaptcha.reset(api.widgetId);
        });

        if ($('[data-toggle="tooltip"]').length > 0) $('[data-toggle="tooltip"]').tooltip();

        $('body').on('shown.bs.collapse', '.collapse', function () {
            $(this).find('[data-toggle="tooltip"]').tooltip();
        })

    });

    /**
     * Contact Form 7 Checkbox
     */
    $(document).ready(function () {
        $('#agree0').change(function () {
            if ($(this).prop('checked')) {
                $('input[type="checkbox"]').prop('checked', true);
            } else {
                $('input[type="checkbox"]').prop('checked', false);
            }
        })
        $('#agree0').trigger('change');
    })

})(jQuery, window);