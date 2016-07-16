/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Ramazan APAYDIN
 *         apaydinweb@gmail.com
 *
 * Website: http://ramazanapaydin.com
 *
 * Created:  04.15.2013
 */

/* ---------------- Bootbox ----------------- */
$(function () {
    var demos = {};

    $(document).on("click", "a[data-bb]", function (e) {
        e.preventDefault();
        var type = $(this).data("bb");

        if (typeof demos[type] === 'function') {
            demos[type](this);
        }
    });

    demos.confirm = function (e) {
        bootbox.confirm("Are you sure to delete?", "No", "Yes", function (result) {
            var href = e.href;
            if (result) {
                window.location = href;
            } else {
            }
        });
    };
});


$(document).ready(function () {
    /* --------------- Tooltip Active ------------ */
    $('.tooltp').tooltip();
    $(".tip").tooltip();

    /* ------- Country Selection Ajax Load ------- */
    $('.country').bind('change click', function () {
        flag = $('.country').val();
        $('.province').empty();
        $.getJSON($baseUrl + 'index.php/admin/estateProvince/' + encodeURI(flag), function (JSON) {
            $.each(JSON, function (flag, province) {
                $('.province').append("<option value='" + flag + "'>" + province + "</option>");
            });
        });
    });

    /* ----- Jquery Uniform Form Element ------ */
    $("input[type='checkbox'],input[type='radio']").uniform();

    /* ----- Estate Add İmage Deleten Ajax ------ */
    var deleted = {};
    $('body').on('click', '.deletelink', function (e) {
        e.preventDefault();
        var type = $(this).data("cc");
        if (typeof deleted[type] === 'function') {
            deleted[type](this);
        }
    });

    deleted.confirm = function (e) {
        bootbox.confirm("Are you sure to delete?", "No", "Yes", function (result) {
            if (result) {
                var $item = $(e);
                var $link = $(e).attr('href');
                var index = $link.lastIndexOf("/") + 1;
                var $filename = $link.substr(index);
                $.ajax({
                    url: $link,
                    dataType: "json",
                    success: function (data) {
                        if (data.status === "success") {
                            $item.fadeOut(500, function () {
                                $item.remove();
                                $('input[value$="' + $filename + '"]').remove();
                            })
                        } else {
                            alert('Error');
                        }
                    }
                });
            } else {
            }
        });
    };

    deleted.slider = function (e) {
        bootbox.confirm("Are you sure to delete?", "No", "Yes", function (result) {
            if (result) {
                var $item = $(e);
                var $link = $(e).attr('href');
                var index = $link.lastIndexOf("/") + 1;
                var $filename = $link.substr(index);
                $.ajax({
                    url: $link,
                    dataType: "json",
                    success: function (data) {
                        if (data.status === "success") {
                            $item.fadeOut(500, function () {
                                $item.remove();
                                $('input[value$="' + $filename + '"]').remove();
                                $('div[class$="' + $filename + '"]').remove();
                            })
                        } else {
                            alert('Error');
                        }
                    }
                });
            } else {
            }
        });
    };

    deleted.hidden = function (e) {
        var $item = $(e);
        var $link = $(e).attr('href');
        var index = $link.lastIndexOf("/") + 1;
        var $filename = $link.substr(index);
        $.ajax({
            url: $link,
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    $item.fadeOut(500, function () {
                        $item.remove();
                        $('input[value$="' + $filename + '"]').remove();
                    })
                } else {
                    alert('Error');
                }
            }
        });
    };

});

/* Modal fix */
//$('.modal').appendTo($('body'));


/* ----------- Other -------------- */
function isNumber(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function getURL() {
    protocol = window.location.protocol;
    host = window.location.host;
    return protocol + '//' + host + '/';
}

/* Widget close */
$(document).ready(function () {
    $('.wclose').click(function (e) {
        e.preventDefault();
        var $wbox = $(this).parent().parent().parent();
        $wbox.hide(100);
    });
});
/* Widget minimize */
$(document).ready(function () {
    $('.wminimize').click(function (e) {
        e.preventDefault();
        var $wcontent = $(this).parent().parent().next('.widget-content');
        if ($wcontent.is(':visible')) {
            $(this).children('i').removeClass('icon-chevron-up');
            $(this).children('i').addClass('icon-chevron-down');
        }
        else {
            $(this).children('i').removeClass('icon-chevron-down');
            $(this).children('i').addClass('icon-chevron-up');
        }
        $wcontent.toggle(500);
    });
});
