'use strict';
var ajax_form_submit_result;
var footer_loading = $('.ajax_form_submit_loading');


var zubizi = {
    sum_amt: '',
    footer_loading: $('.ajax_form_submit_loading'),
    tech_error_msg: "Something wrong happened! Please try after some time. If problem still persist please contact customer support",
    reducer: function(a, b) {
        return +a + +b;
    },
    sum: function(selector) {
        var a = 0;
        $(selector).each(function() {
            a += +this.value;
        });
        return a;
    },
    getFormData: function($form) {
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};
        $.map(unindexed_array, function(n, i) {
            indexed_array[n['name']] = n['value'];
        });
        return indexed_array;
    },
    fill_edit_modal: function(assoc_array, modal) {
        for (var i = 0; i < assoc_array.length; i++) {
            modal.find('[name=' + assoc_array[i]['field'] + ']').val(assoc_array[i]['val']).trigger('change')
        }
    },
    getUrlParameter: function(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    },
    fill_select_option: function(selector, api_url) {
        $.ajax({
            url: api_url,
            success: function(data) {
                $(selector).select2({
                    data: data,
                    placeholder: 'Please Select',
                });
            }
        });
    },
    get_url_parameter: function(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    },
    footer_totals: function(api, cols) {
        var totals = {};
        for (var i = 0; i < cols.length; i++) {
            totals[cols[i].total_name] = api.column(cols[i].column, { filter: "applied" }).data().reduce(zubizi.reducer, 0);
            $(api.column(cols[i].column).footer()).text(Math.round(totals[cols[i].total_name]).toFixed(2));
            $(cols[i].selector).text(Math.round(totals[cols[i].total_name]).toFixed(2));
        }
        return totals;
    },
    addCommas: function(nStr) {
        if (typeof(nStr) == 'number') {
            nStr = parseInt(nStr);
            nStr = nStr.toFixed(2);
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            nStr = x1 + x2;
        }
        return nStr;
    },
    ajax_delete_row: function(api_url_w_get_req, the_DataTable) {
        footer_loading.fadeIn(700);
        (new PNotify({
            title: 'Confirmation Needed',
            text: 'Are you sure?',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            confirm: {
                confirm: true
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            },
            addclass: 'stack-modal',
            stack: {
                dir1: 'down',
                dir2: 'right',
                modal: true
            }
        })).get().on('pnotify.confirm', function() {
            $.ajax({
                url: api_url_w_get_req,
                success: function(result) {
                    footer_loading.fadeOut(700);
                    the_DataTable.ajax.reload(null, false);
                    if (result.status === 'success') {
                        new PNotify({
                            title: 'Success!',
                            text: result.message,
                            type: 'success'
                        });
                    } else {
                        new PNotify({
                            title: 'Oh No!',
                            text: result.message || 'This data can not be deleted',
                            type: 'error'
                        });
                    }
                }
            });
        }).on('pnotify.cancel', function() {
            footer_loading.fadeOut(700);
        });
        $('.ui-pnotify-action-button')[0].focus();
    },
    ajax_form_submit: function(selector, firstInput = '', callback_function = function(e) { return e }, modal = '') {
        /**
         * simple ajax submit without validation
         * simple jquery selector
         * result data will be a json object
         * and should have a message obj
         * var result = {"status": "success", "message": "Success message"}
         * var result = {"status": "error", "message": "Error message"}
         */
        $(selector).submit(function(e) {
            e.preventDefault();
            $('.ajax_form_submit_loading').fadeToggle(700);
            var submit_btn = $(this).find('.btn');
            var this_submit_form = $(this);
            submit_btn.prop('disabled', true);
            $.ajax({
                type: this_submit_form.attr('method'),
                url: this_submit_form.attr('action'),
                data: this_submit_form.serialize(),
                success: function(result) {
                    ajax_form_submit_result = result;
                    $('.ajax_form_submit_loading').fadeToggle(700);
                    submit_btn.prop('disabled', false);
                    if (result.status == 'success') {
                        new PNotify({
                            title: 'Success!',
                            text: result.message,
                            type: 'success'
                        });
                        console.log(result);
                        this_submit_form[0].reset();
                        this_submit_form.find('select').val(null).trigger('change');
                        this_submit_form.find(firstInput).focus();
                        this_submit_form.parents('.modal').modal('toggle');
                    } else if (result.status == 'error') {
                        new PNotify({
                            title: 'Oh No!',
                            text: result.message,
                            type: 'error'
                        });
                    }
                    callback_function(result);
                    return ajax_form_submit_result;
                },
                error: function(err, err2, err3) {
                    $('.ajax_form_submit_loading').fadeToggle(700);
                    // console.log(err, err2, err3);
                    submit_btn.prop('disabled', false);
                    new PNotify({
                        title: 'Error!',
                        text: 'Unexpected Error! Something went wrong',
                        type: 'error'
                    });
                },
                always: function(data) {
                    console.log(data);
                }
            });
        });
    },
    next_input: function(selector) {
        $('table tbody').on('keydown', selector, function(e) {
            if (e.which == 40) {
                e.preventDefault();
                $(this).closest('tr').next().find(selector).focus().select();
            }
            if (e.which == 38) {
                e.preventDefault();
                $(this).closest('tr').prev().find(selector).focus().select();
            }
        });
    }
};