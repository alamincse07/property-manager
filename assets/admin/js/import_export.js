/**
 * Created by Raaz Ahmed on 6/14/14.
 */
$(document).ready(function () {
    /*For toggle Add new*/
    var flag = 0;
    var n = $(document).height();
    $("#add-btn").toggle(
        function () {
            $(".js_name").val('');
            $('.js_driver option[value=""]').prop('selected', true);
            $('.js_module option[value=""]').prop('selected', true);
            $(".export-save-btn").hide();
            $(".export-create-btn").show();
            $(".export-add").slideDown('fast');
            $(".export-view").slideUp('fast');
            $('html, body').animate({scrollTop: n}, 150);
        },
        function () {
            $(".export-add").slideUp('fast');
            $(".export-view").slideDown('fast');
        }
    );
    /*End For toggle Add new*/

    /*For toggle Edit Selection*/
    $(".edit_selection").live('click',
        function () {
            $(".export-add").slideDown('fast');
            $(".export-view").slideUp('fast');
            $('html, body').animate({scrollTop: n}, 150);
            var id = $(this).attr('id');
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: BASE_URL + "admin/edit_selection",
                data: {id: id},
                success: function (data) {
                    $(".export-create-btn").hide();
                    $(".export-save-btn").show();
                    $(".js_name").val(data.name);
                    $('.js_driver option[value="' + data.driver + '"]').prop('selected', true);
                    $('.js_module option[value="' + data.module + '"]').prop('selected', true);
                    /*Update xml data*/
                    $(".export-save-btn").live('click', function () {
                        var name = $(".js_name").val();
                        var driver = $(".js_driver").val();
                        var module = $(".js_module").val();
                        var status = $(".js_status").val();
                        $.ajax({
                            dataType: 'json',
                            type: "POST",
                            url: BASE_URL + "admin/edit_export",
                            data: {id: id, name: name, driver: driver, module: module, status: status},
                            success: function (data) {
                                if (data.success == true) {
                                    location.reload();
                                    /*commonCrudCode(data);*/
                                } else {
                                    $(".mini-layout-body").fadeIn(400);
                                    $(".js_ajax_msg").html(data.msg);
                                }
                            }
                        });
                    });
                    /*EndUpdate xml data*/
                }
            });
        });
    /*End For toggle Edit Selection*/

    /*For activate or Deactivate xml export*/
    $(".js_status").live('click', function () {
        var id = $(this).attr('id');
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: BASE_URL + "admin/update_status",
            data: {id: id},
            success: function (data) {
                if (data.success == true) {
                    commonCrudCode(data);
                }
            }
        });
    });
    /*End For activate or Deactivate xml export*/

    /*For activate or Deactivate Driver*/
    $(".status").live('click', function () {
        var id = $(this).attr('id');
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: BASE_URL + "admin/update_driver_status",
            data: {id: id},
            success: function (data) {
                if (data.success == true) {
                    commonCrudCode(data);
                }
            }
        });
    });
    /*End For activate or Deactivate Driver*/

    /*For Add New Xml Data*/
    $(".export-create-btn").live('click', function () {
        var name = $(".js_name").val();
        var driver = $(".js_driver").val();
        var module = $(".js_module").val();
        var status = $(".js_status").val();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: BASE_URL + "admin/add_export",
            data: {name: name, driver: driver, module: module, status: status},
            success: function (data) {
                if (data.success == true) {
                    commonCrudCode(data);
                } else {
                    $(".mini-layout-body").fadeIn(400);
                    $(".js_ajax_msg").html(data.msg);
                }
            }
        });
    });
    /*End For Add New Xml Data*/
    /*Start Delete */
    $(".delete_selection").live('click',function(){
        if (!confirm("Do you want to delete?")){
            return false;
        }
        var id = $(this).attr('id');
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: BASE_URL + "admin/delete_export",
            data: {id: id},
            success: function (data) {
                if (data.success == true) {
                    commonCrudCode(data);
                }
            }
        });
    });
    /*End Delete */
    function commonCrudCode(data){
        $(".mini-layout-body").fadeIn(400);
        $(".js_ajax_msg").html(data.msg);
        if(data.tab == 'driver'){
            $(".driver-view").load(BASE_URL+"admin/export .driver-view");
            $(".export-add").load(BASE_URL+"admin/export .export-add");
        }else
        $(".export-view").load(BASE_URL+"admin/export .export-view");
        $(".export-add").slideUp('fast');
        $(".export-view").slideDown('fast');
        setInterval(function(){$(".mini-layout-body").fadeOut(400)}, 5000);
    }
});