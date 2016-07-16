/**
 * Created by alamin on 6/22/14.
 */
$(document).ready(function () {
    var i = 1;
    var j = 1;

    $('#image_add').click(function () {



        var html =' <tr class="single_image_row"> ' +
            '<td><a href="javascript:void(0);" title="Delete this photo" onclick="remove_image(this);">' +
            'REMOVE</a>   </td>             <td>Photo url</td>' +
            '            <td>                 ' +
            '<input type="text" class="image_input" size="50" value="" name="images[]"/> ' +
            '</td> </tr>';

        $('.image-urls-table').append(html);
        j++;


    });
    $('#image_delete-btn').live('click', function () {
        if($('.single_image_row').length > 1){
            $('.image-urls-table .single_image_row').last().remove();
        }

    });

    $('#rate-save-btn').click(function () {


        var html='<tr class="single_rate_row"> <td> <a onclick="remove_me(this);" title="Delete this row" href="javascript:void(0);">X</a> </td> <td> <input type="text" class="start_date_pic" onkeypress="" id="start_date'+i+'" value="" name="start_date[]"> </td> <td> <input type="text" class="end_date_pic" onkeypress="" id="end_date'+i+'" value="" name="end_date[]"> </td> <td> <input type="text" class="span" onkeypress="" value="" name="rate_title[]"> </td> <td> <input type="text" onkeypress="return isNumber(event)"  value="" name="min_los[]"> </td> <td> <input type="text"  value="" name="nightly[]"> </td> <td> <input type="text"  value="" name="weekly[]"> </td> </tr>';
        $('.price-rate-table').append(html);

        $( "#start_date" + i ).datepicker({ minDate: 0,
            defaultDate: "+1w",
            dateFormat: "yy-mm-dd"

        });
        $( "#end_date" + i ).datepicker({ minDate: 0,
            defaultDate: "+1w",
            dateFormat: "yy-mm-dd"

        });
        i++;


    });
    $('#rate-delete-btn').live('click', function () {
        if($('.single_rate_row').length > 1){
            $('.price-rate-table .single_rate_row').last().remove();
        }

    });
});

function remove_me(obj){
    if($('.single_rate_row').length > 1){

        $(obj).closest('.single_rate_row').remove();
    }
}

function remove_image(obj){
    if($('.single_image_row').length > 1){

      /*  if($('#image_edit_delete').length > 1){

            var c= confirm('Are you sure to delete?');
            if(c===true){
                $(obj).closest('.single_image_row').remove();
            }

        }else{
            console.log('addd images');
            $(obj).closest('.single_image_row').remove();
        }*/

        $(obj).closest('.single_image_row').remove();


    }else{
        console.log('no images');
    }
}