(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get: '',
            placeholder: '',
            otherOptions: {minimumInputLength: 2},
            entitytype : '',
            theme: '',
            required: true
        };

        return this.each(function () {
            if (options) {
                $.extend(true, settings, options);
            }
            var $this = $(this);
            var $fakeInput = $this.clone();
            var $fakeInput2 = $('<select></select>');
            $.each($fakeInput.prop('attributes'),function(){
                $fakeInput2.attr(this.name,this.value);
            });
            $fakeInput = $fakeInput2;
            $fakeInput.addClass('form-control');
            var val = '';
            var select2options = {
                ajax: {
                    url: settings.url_list,
                    dataType: 'json',
                    type: 'POST',
                    delay: 250,
                    placeholder: settings.placeholder,
                    theme: settings.theme,
                    data: function (params) {
                        return {
                            q: params,
                            class: settings.entitytype,
                            required: settings.required
                        };
                    },
                    processResults: function (data) {
                        var results = [];
                        $.each(data, function (index, item) {
                            results.push({
                                id: item.id,
                                text: item.text
                            });
                        });
                        return {
                            results: results
                        };
                    },
                    cache: true,
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                initSelection: function (element, callback) {
                    var data = {id: element.val(), text: val};
                    callback(data);
                }
            };
            $this.removeAttr('required');
            $fakeInput.removeAttr('required');
            if (settings.otherOptions) {
                $.extend(true, select2options, options.otherOptions);
            }
            $fakeInput.attr('id', 'fake_' + $fakeInput.attr('id'));
            $fakeInput.attr('name', 'fake_' + $fakeInput.attr('name'));
            $this.hide().after($fakeInput);
            $fakeInput.select2(select2options);
            if ($this.attr('value')) {
                $.ajax({
                    url: (settings.url_get.substring(-1) === '/' ? settings.url_get : settings.url_get + '/') + $this.attr('value'),
                    type: 'POST',
                    data: {
                        class: settings.entitytype
                    },
                }).done(function(name) {
                    $fakeInput.html('<option value="' + name.id + '">' + name.text + '</option>');
                    val = name;
                    $fakeInput.select2('val', name);
                    $fakeInput.val(name.id);
                    $fakeInput.change();
                });
            }
            $fakeInput.on('change', function (e) {

                $this.val($fakeInput.val()).change();
            });
        });
    };
})(jQuery);
