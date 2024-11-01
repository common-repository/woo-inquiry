jQuery(document).ready(function($) {



    $('#woo_wping_price_answer').mask("000,000,000,000,000,000,000,000,000,000,000,000,000,000", {
        reverse: true
    });
    $(".woo_wpinq_preload").css({
        "display": "none"
    });

    var audio = document.getElementById("woo_wpinq_notification");
    window.WooReportCheck = setInterval(function() {
        var ms = new Date().getTime();
        var lastnot = 0;
        $.get(woo_reports.jsonfile + "?dummy=" + ms, function(data) {
            var property = 'New';
            if (typeof data === "object" && data !== null) {
                if (data.hasOwnProperty(property)) {

                    if (lastnot != data.New.id) {
                        lastnot = data.New.id;
                        jQuery.ajax({
                            type: "POST",
                            url: woo_reports.wpajax,
                            data: {
                                'action': 'woo_wpinq_reports_table',
                                'dbid': data.New.id
                            },
                            success: function(textStatus) {

                                audio.muted = false;
                                const playPromise = audio.play();
                                if (playPromise !== null) {
                                    playPromise.catch(() => {
                                        audio.append('<b style="color:red">' + woo_reports.notifnotic + '</b>');
                                        $('#woo_wpinq_notification').show();

                                    })
                                }

                                $('#woo_wpinq_current_inquiries tbody').html(textStatus);

                            },
                            error: function(MLHttpRequest, textStatus, errorThrown) {
                                alert(errorThrown);
                            }
                        });
                    }

                }

            }
        }, 'json');




        $.get(woo_reports.deletefile + "?dummy=" + ms, function(data) {
            var property = 'Miss';
            if (typeof data === "object" && data !== null) {
                if (data.hasOwnProperty(property)) {

                    $.each(data.Miss, function(k, v) {
                        var elem = "#Row" + v;
                        $(elem).remove();
                    });

                }

            }
        }, 'json');


    }, 1000);




    $(document).on('click', '.woo_wpinq_actions button', function() {
        var action = $(this).attr('data-action');
        var dbid = $(this).attr('data-id');
        $(".woo_wping_price_answer_confirm").attr('data-id', dbid);
        $(".woo_wping_inquiry_sms_confirm").attr('data-id', dbid);
        $(".woo_wping_inquiry_sms_confirm").attr('data-act', action);
        var elem = $('#Row' + dbid);
        if (action === 'resp') {
            $('#mdModal .modal-content').removeAttr('class').addClass('modal-content modal-col-black');
            $('#mdModal').modal('show');
        } else if (action === 'SMSrespup') {
            $('#mdModal2').modal('show');
        } else if (action === 'SMSrespoff') {
            $('#mdModal2').modal('show');
        } else {
            $(".woo_wpinq_preload").css({
                "display": "block"
            });
            jQuery.ajax({
                type: "POST",
                url: woo_reports.wpajax,
                data: {
                    'action': 'woo_wpinq_out_of_stock',
                    'dbid': dbid
                },
                success: function(textStatus) {

                    $(elem).remove();
                    $(".woo_wpinq_preload").css({
                        "display": "none"
                    });
                },
                error: function(MLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

        }
    });




    $(document).on('click', '.woo_wping_price_answer_confirm', function() {
        $('#woo_wpinq_price_waiting').show();
        var kind = $(this).attr('data-kind');
        var dbid = $(this).attr('data-id');
        var price = $('#woo_wping_price_answer').val();
        if (price.length == 0) {
            alert(woo_reports.pricewarn);
            return;
        }
        var elem = $('#Row' + dbid);
        var r = confirm(woo_reports.confwarn);
        if (r == true) {
            jQuery.ajax({
                type: "POST",
                url: woo_reports.wpajax,
                data: {
                    'action': 'woo_wpinq_price_answer',
                    'dbid': dbid,
                    'kind': kind,
                    'price': price.replace(/,/g, '')
                },
                success: function(textStatus) {
                    $('#woo_wpinq_price_waiting').hide();
                    $(elem).remove();
                    $('#mdModal').modal('hide');
                },
                error: function(MLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

        }


    });



    $(".woo_wping_deleter").on("click", function() {
        var kind = $(this).attr("data-kind");

        var warn = '';

        if (kind == 'users') {
            warn = woo_reports.userwarn;
        } else if (kind == 'history') {
            warn = woo_reports.hiswarn;
        } else if (kind == 'timeoff') {
            warn = woo_reports.toffwarn;
        } else {
            warn = woo_reports.tupwarn;
        }

        var r = confirm(warn);
        if (r == true) {
            $(".woo_wpinq_preload").css({
                "display": "block"
            });
            jQuery.ajax({
                type: "POST",
                url: woo_reports.wpajax,
                data: {
                    'action': 'woo_wpinq_delete_db',
                    'kind': kind
                },
                success: function(textStatus) {
                    location.reload();
                }
            });
        }

    });


});




jQuery(function($) {

    $('#woo_wpinq_current_inquiries').DataTable({
        responsive: true
    });

    $("#tabs").tabs({
        beforeActivate: function(event, ui) {
            $(".woo_wpinq_preload").css({
                "display": "block"
            });
            var elem = ui.newTab.attr("aria-controls");
            var id = elem.replace('tabs-', '');

            if (id == 1) {

                $(".woo_wpinq_preload").css({
                    "display": "none"
                });

            } else {
                var tablename;
                var tablecontent;
                var method;

                if (id == 2) {
                    tablename = '#woo_wpinq_timeup_table_js';
                    tablecontent = '#woo_wpinq_timeup_table';
                    method = 'woo_wpinq_reports_table_timeup';
                }

                if (id == 3) {
                    tablename = '#woo_wpinq_timeoff_table_js';
                    tablecontent = '#woo_wpinq_timeoff_table';
                    method = 'woo_wpinq_reports_table_timeoff';
                }

                if (id == 4) {
                    tablename = '#woo_wpinq_history_table_js';
                    tablecontent = '#woo_wpinq_history_table';
                    method = 'woo_wpinq_reports_table_history';
                }

                if (id == 5) {
                    tablename = '#woo_wpinq_users_table_js';
                    tablecontent = '#woo_wpinq_users_table';
                    method = 'woo_wpinq_users_list';
                }

                jQuery.ajax({
                    type: "POST",
                    url: woo_reports.wpajax,
                    data: {
                        'action': method
                    },
                    success: function(textStatus) {

                        $(tablecontent).html(textStatus);


                        if (id == 3 || id == 2) {

                            if ($.fn.DataTable.isDataTable(tablename)) {
                                $(tablename).DataTable().destroy();
                            }

                            $(tablename).DataTable({
                                responsive: true
                            });

                        } else {

                            if ($.fn.DataTable.isDataTable(tablename)) {
                                $(tablename).DataTable().destroy();
                            }

                            $(tablename).DataTable({
                                dom: 'Bfrtip',
                                responsive: true,
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ]
                            });

                        }

                        $(".woo_wpinq_preload").css({
                            "display": "none"
                        });
                    }
                });

            }

        }
    });

});