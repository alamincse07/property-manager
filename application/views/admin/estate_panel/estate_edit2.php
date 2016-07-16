<?php echo $header;
echo $sidebar ?>
    <div class="span10 content">
    <?php echo $hugemenu ?>
    <h1><?php echo lang('estate_edit'); ?></h1>
    <?php if ($message) { ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
        </div>
    <?php }
    if ($success) { ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
        </div>
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/admin/css/mdp.css">
    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/jquery.ui.datepicker.js"></script>

    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript">
        $().ready(function () {


            $('#start_date').datepicker({
                minDate: 0,
                defaultDate: "+1w",
                dateFormat: "yy-mm-dd"

            });
            $('#end_date').datepicker({
                minDate: 0,
                defaultDate: "+1w",
                dateFormat: "yy-mm-dd"

            });
            $('#start_date0').datepicker({
                minDate: 0,
                defaultDate: "+1w",
                dateFormat: "yy-mm-dd"

            });
            $('#end_date0').datepicker({
                minDate: 0,
                defaultDate: "+1w",
                dateFormat: "yy-mm-dd"

            });

            $('#custom-date-format').multiDatesPicker({
                minDate: 0,
               
                dateFormat: "yy-mm-dd",
                <?php
                if(isset($not_available_dates) && strlen($not_available_dates)>4){
                echo "addDates: ".$not_available_dates.",";
                }
                ?>
                altField: '#altField'

            });
            $('textarea.tinymce').tinymce({
                script_url: '<?php echo base_url() ?>assets/admin/js/tiny_mce/tiny_mce.js',
                theme: "advanced",
                plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
                theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist",
                theme_advanced_buttons2: "styleselect,formatselect,fontselect,fontsizeselect,|,print,charmap,emotions,iespell,media,advhr",
                theme_advanced_buttons3: "undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,outdent,indent,blockquote",
                theme_advanced_buttons4: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_statusbar_location: "bottom",
                theme_advanced_resizing: true,
                height: "400"
            });
        });
    </script>

    <?php echo form_open_multipart(current_url()); ?>
    <input type="hidden" name="country" value="<?php echo (isset($get_country)) ? $get_country : ''; ?>"/>
    <input type="hidden" name="province" value="<?php echo (isset($get_province)) ? $get_province : ''; ?>"/>
    <input type="hidden" name="city" value="<?php echo (isset($get_city)) ? $get_city : ''; ?>"/>
    <input type="hidden" name="address" value="<?php echo (isset($get_address)) ? $get_address : ''; ?>"/>
    <input type="hidden" name="postal_code" value="<?php echo (isset($get_postal_code)) ? $get_postal_code : ''; ?>"/>
    <input type="hidden" name="gps" value="<?php echo (isset($get_gps)) ? $get_gps : ''; ?>"/>

    <div class="widget">
    <div class="widget-head"><h3 class="heading"><?php echo lang('blog_edit_widget_title') . $title['value'] ?></h3>
    </div>
    <div class="widget-body">

    <div class="row-fluid">
    <div class="span8">
        <div class="control-group">
            <label class="control-label"><?php echo lang('estate_add_title', 'title'); ?></label>

            <div class="controls">
                <?php $title['class'] = 'span';
                echo form_input($title); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo lang('estate_add_content', 'content'); ?></label>

            <div class="controls">
                <?php $content['class'] = 'span tinymce';
                $content['style'] = "width:100%";
                echo form_textarea($content); ?>
            </div>
        </div>

        <!--this is for uploaded photos-->

        <!--
        <div class="control-group">
            <label class="control-label photo">
                <?php /*echo lang('estate_add_photo', 'photo'); */?>
                <?php /*echo form_upload('userfile', '', 'id="userfile"');
                echo (isset($error)) ? $error : ''; */?>
            </label>

            <div class="controls">
                <link href="<?php /*echo base_url(); */?>assets/admin/js/jquery/uploadify_31/uploadify.css" type="text/css"
                      media="screen" rel="stylesheet"/>
                <script src="<?php /*echo base_url(); */?>assets/admin/js/jquery/uploadify_31/jquery.uploadify-3.1.min.js"
                        type="text/javascript"></script>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var base_url = '<?php /*echo base_url(); */?>';
                        $('#userfile').uploadify({
                            'auto': true,
                            'swf': base_url + 'assets/admin/js/jquery/uploadify_31/uploadify.swf',
                            'uploader': base_url + 'index.php/uploadify/do_upload',
                            'cancelImg': base_url + 'uploadify-cancel.png',
                            'fileTypeExts': '*.jpg;*.bmp;*.png;*.tif',
                            'fileTypeDesc': 'Image Files (.jpg,.bmp,.png,.tif)',
                            'fileSizeLimit': '5MB',
                            'fileObjName': 'userfile',
                            'buttonText': '<?php /*echo lang('estate_add_photo_btn')*/?>',
                            'multi': true,
                            'removeCompleted': true,
                            'onUploadSuccess': function (file, data, response) {
                                obj = JSON.parse(data);
                                $('.thumb').append('<a class="deletelink" data-cc="confirm" target="_blank" href="' + base_url + 'index.php/uploadify/delete_file/' + obj.file_name + '"><img src="' + base_url + 'uploads/thumbs/' + obj.file_name + '" /></a>');
                                $('.photos').append('<input type="hidden" name="photo[]" value="' + obj.file_name + '" />');
                            }
                        });

                    });
                </script>
            </div>
        </div>

        <div class="control-group">
            <div class="thumb well well-small">
                <?php
/*                if ($photos) {
                    foreach ($photos as $photo) {
                        $filename = base_url() . "uploads/" . $photo;
                        if (@getimagesize($filename)) echo "<a href='" . base_url() . "index.php/uploadify/delete_file/$photo' target='_blank' data-cc='confirm' class='deletelink'><img src='" . base_url() . "uploads/thumbs/$photo'></a>";
                    }
                }
                */?>
            </div>
        </div>
        <div class="control-group">
            <div class="photos">
                <?php
/*                if ($photos) {
                    foreach ($photos as $photo) {
                        $filename = base_url() . "uploads/" . $photo;
                        if (@getimagesize($filename)) echo "<input type='hidden' value='$photo' name='photo[]'>";
                    }
                }
                */?>
            </div>
        </div>
        <div class="control-group">
            <div class="thumb well well-small">
                <div class="row-fluid">
                    <div class="mini-layout-body" style="text-align:center; display: none;">
                        <div class="alert fade in">
                            <button class="close" data-dismiss="alert" type="button">×</button>
                            <div class="js_ajax_msg"></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
-->


        <!--image upload by putting url-->


        <div class="more-rat1e-control-group">

            <h3>Images</h3>
            <hr/>
            <div>
                <input class="btn btn-medium btn-primary" type="button" id="image_add" value="Add Image Urls"/>
                <input class="btn btn-medium btn-primary" type="hidden" id="confirm_image_delete" value="1"/>
                <!-- <input class="btn btn-medium btn-primary" type="button" id="rate-delete-btn" value="Delete Last"/>-->
            </div>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="price-rat1e image-urls-table">

            <?php
            if(isset($photos[0])){
                foreach($photos as $k=>$image){
                    echo'
                    <tr class="single_image_row">
                    <td>
                        <a href="javascript:void(0);" title="Delete this photo" onclick="remove_image(this);">REMOVE</a>

                    </td>
                    <td>Photo url</td>
                    <td>
                        <input type="text" class="image_input" size="50" value="'.$image.'" name="images[]"/>
                    </td>
                     <td>
                       <img src="'.$image.'" height="80" width="100" class="single_img" />
                       <p></p>
                    </td>

                </tr>';
                }
            }else{
                ?>

                 <tr class="single_image_row">
                    <td>
                        <a href="javascript:void(0);" title="Delete this photo" onclick="remove_image(this);">REMOVE</a>

                    </td>             <td>Photo url</td>
                    <td>
                        <input type="text" class="image_input" size="50" value="" name="images[]"/>
                    </td>

                </tr>

                <?php } ?>
            </table>
        </div>





        <div class="control-group">
            <div class="more-rate-control-group">

                <h3>Default/Regular Rate</h3>
                <hr/>
                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="default-rate-table">
                    <tr>

                        <td>Min LOS</td>
                        <td>Nightly Price(USD)</td>
                        <td>Weekly Price(USD)</td>
                    </tr>
                        <tr class="single_rate_row1">

                        <td>
                            <?php
                            $min_los['name'] = 'default_min_los';
                            echo form_input($default_min_los);
                            ?>
                        </td>

                        <td>
                            <?php
                            $default_nightly['name'] = 'default_nightly';
                            echo form_input($default_nightly);
                            ?>
                        </td>
                        <td>
                            <?php
                            $default_weekly['name'] = 'default_weekly';
                            echo form_input($default_weekly);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="more-rate-control-group">

                <h3>Optional Rates</h3>
                <hr/>
                <div>
                    <input class="btn btn-medium btn-primary1" type="button" id="rate-save-btn" value="Add More"/>
                    <!-- <input class="btn btn-medium btn-primary" type="button" id="rate-delete-btn" value="Delete Last"/>-->
                </div>
                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="price-rate price-rate-table">
                    <tr>
                        <td> &nbsp; &nbsp;</td>
                        <td>Start Date</td>
                        <td>End Date</td>
                        <td>Rate Title</td>
                        <td>Min LOS</td>
                        <td>Nightly Price(USD)</td>
                        <td>Weekly Price(USD)</td>
                    </tr>
                    <?php
                    if(isset($optional_rates[0])){
                        foreach($optional_rates as $k=>$val){
                            echo'<tr class="single_rate_row"> <td> <a onclick="remove_me(this);" title="Delete this row" href="javascript:void(0);">X</a> </td> <td> <input type="text" class="start_date_pic" onkeypress="" id="start_date'.$k.'" value="'.$val->start_date.'" name="start_date[]"> </td> <td> <input type="text" class="end_date_pic" onkeypress="" id="end_date'.$k.'" value="'.$val->end_date.'" name="end_date[]"> </td> <td> <input type="text" class="span" onkeypress="" value="'.$val->title.'" name="rate_title[]"> </td> <td> <input type="text" onkeypress="return isNumber(event)"  value="'.$val->min_los.'"  name="min_los[]"> </td> <td> <input type="text"  value="'.$val->nightly_price.'" name="nightly[]"> </td> <td> <input type="text"  value="'.$val->weekly_price.'" name="weekly[]"> </td> </tr>';
                        }
                    }else{
                    ?>

                        <tr class="single_rate_row">
                            <td>
                                <a href="javascript:void(0);" title="Delete this row" onclick="remove_me(this);">X</a>
                            </td>

                            <td>
                                <?php
                                $start_date['id'] = 'start_date';
                                $start_date['name'] = 'start_date[]';
                                echo form_input($start_date);
                                ?>
                            </td>
                            <td>
                                <?php
                                $end_date['name'] = 'end_date[]';
                                $end_date['id'] = 'end_date';
                                echo form_input($end_date);
                                ?>
                            </td>
                            <td >
                                <?php
                                $rate_title['name'] = 'rate_title[]';
                                $rate_title['class'] = 'span';
                                $rate_title['onkeypress'] = '';
                                echo form_input($rate_title);
                                ?>
                            </td>
                            <td>
                                <?php
                                $min_los['name'] = 'min_los[]';
                                echo form_input($min_los);
                                ?>
                            </td>

                            <td>
                                <?php
                                $nightly['name'] = 'nightly[]';
                                echo form_input($nightly);
                                ?>
                            </td>
                            <td>
                                <?php
                                $weekly['name'] = 'weekly[]';
                                echo form_input($weekly);
                                ?>
                            </td>
                        </tr>

                    <?php

                    }

                    ?>


                </table>
            </div>
        </div>
        <!--
                <div class="control-group well well-small checboxAdd">
                    <?php $ref = 1;
        $ref2 = 2;
        $ref3 = 3 ?>
                    
                        <?php foreach ($checkboxName as $key => $item) {
            if ($item->typeID == $ref) {
                echo "<h3>" . lang('estate_add_textbox1') . "</h3><hr/>";
                $ref = NULL;
            }
            if ($item->typeID == $ref2) {
                echo "<h3>" . lang('estate_add_textbox2') . "</h3><hr/>";
                $ref2 = NULL;
            }
            if ($item->typeID == $ref3) {
                echo "<h3>" . lang('estate_add_textbox3') . "</h3><hr/>";
                $ref3 = NULL;
            }
            echo "<label class='checkbox'>";
            echo form_checkbox($checkbox[$key], $item->pfid, $item->value);
            echo "$item->name</label>";
        } ?>
                </div>
                -->

        <div class="control-group well well-small checboxAdd">
            <h3>Amenities</h3><hr/>
            <?php foreach ($attrib as $id => $value) {

                //echo "<h3>" . $value . "</h3><hr/>";
                foreach ($checkboxName as $key => $item) {
                    if ($item->typeID == $id) {
                        echo "<label class='checkbox'>";
                        echo form_checkbox($checkbox[$key], $item->pfid, $item->value);
                        echo $item->name . "</label>";
                    }
                }
            } ?>
        </div>

        <a href="/index.php/admin/estateEdit/<?php echo $id_estate; ?>"><input type="button" class="btn btn-primary"
                                                                               value="Back"/></a>&nbsp;&nbsp;
        <input type="submit" name="save" value="Update Property" class="btn btn-primary"/>&nbsp;&nbsp;
        <input type="reset" name="reset" value="Cancel" class="btn btn-primary"/>

    </div>

    <div class="span4">

        <div>

            <div class="control-group">
                <label class="control-label">Telephone</label>

                <div class="controls">
                    <?php $telephone['class'] = 'span';
                    echo form_input($telephone); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Booking/Owner site url for this property</label>

                <div class="controls">
                    <?php $gsm['class'] = 'span';$gsm['required'] = 'true';
                    echo form_input($gsm); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Email</label>

                <div class="controls">
                    <?php $email['class'] = 'span';
                    echo form_input($email); ?>
                </div>
            </div>

            <hr/>

            <div class="control-group">
                <label class="control-label"><?php echo lang('estate_add_cat', 'category'); ?></label>

                <div class="controls">
                    <?php echo form_dropdown('category', $category, $categoryAC, 'class="span" id="category"'); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo lang('estate_add_type', 'estatetype'); ?></label>

                <div class="controls">
                    <?php echo form_dropdown('estatetype', $estatetype, $estatetypeAC, 'class="span" id="estatetype"'); ?>
                </div>
            </div>

            <div class="control-group">

                <div id="custom-date-format">
                    <h4>Select not available dates</h4>


                </div>
                <?php
                $not_available_date_list['name']='not_available_date_list';
                $not_available_date_list['id']='altField';
                $not_available_date_list['style']='width: 315px';
                $not_available_date_list['type']='hidden';
                $not_available_date_list['cols']='6';
                echo form_input($not_available_date_list); ?>

            </div>


            <!--<div class="control-group">
                <label class="control-label"><?php /*echo lang('estate_add_price', 'price'); */?></label>

                <div class="controls">
                    <?php /*$price['class'] = 'span price';
                    echo form_input($price); */?>
                </div>
            </div>-->



            <div>
                <div class="control-group">
                    <label class="control-label"><?php echo lang('estate_add_room', 'room'); ?></label>

                    <div class="controls">
                        <?php echo form_dropdown('room', $room, $roomAC, 'class="span"'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Number of Sleep</label>

                    <div class="controls">
                        <?php $sleep['class'] = 'span';
                        echo form_input($sleep); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo lang('estate_add_bathroom', 'bathroom'); ?></label>

                    <div class="controls">
                        <?php echo form_dropdown('bathroom', $bathroom, $bathroomAC, 'class="span"'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo lang('estate_add_heating', 'heating'); ?></label>

                    <div class="controls">
                        <?php echo form_dropdown('heating', $heating, $heatingAC, 'class="span"'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo lang('estate_add_squaremeter', 'squaremeter'); ?></label>

                    <div class="controls">
                        <?php echo form_dropdown('squaremeter', $squaremeter, $squaremeterAC, 'class="span"'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Square Foot</label>

                    <div class="controls">
                        <?php echo form_dropdown('squarefoot', $squaremeter, $squarefootAC, 'class="span"'); ?>
                    </div>
                </div>
                <!--<div class="control-group">
                    <label class="control-label"><?php /*echo lang('estate_add_buildstatus', 'buildstatus'); */?></label>

                    <div class="controls">
                        <?php /*echo form_dropdown('buildstatus', $buildstatus, $buildstatusAC, 'class="span"'); */?>
                    </div>
                </div>-->
                <div class="control-group">
                    <label class="control-label"><?php echo lang('estate_add_floor', 'floor'); ?></label>

                    <div class="controls">
                        <?php echo form_dropdown('floor', $floor, $floorAC, 'class="span"'); ?>
                    </div>
                </div>

                <hr/>

                <div class="well well-small">
                    <div class="control-group">
                        <h3 class="control-label"><?php echo trim('Status'); ?></h3>

                        <div class="controls emlakoptions">
                            <label><?php echo form_radio($publish, '1', $publishAC);
                                echo lang('estate_add_publish') ?></label>
                            <label><?php echo form_radio($publish, '0', $publishAC2);
                                echo lang('estate_add_draft') ?></label>
                           <!-- <label><?php /*echo form_checkbox($showcase, 1, $showcaseAC);
                                echo lang('estate_add_showcase') */?></label>-->
                        </div>
                    </div>
                    <div class="control-group">
                        <h3 class="control-label">Feed listing to</h3>

                        <div class="controls emlakoptions">
                            <label><?php echo form_checkbox('fk', 1, $fk);
                                echo 'Flip Key' ?></label>
                            <!--<label><?php /*echo form_checkbox('vrbo', 1, $vrbo);
                                echo 'VRBO'*/?></label>
                            <label><?php /*echo form_checkbox('hw', 1, $hw);
                                echo 'Home Away'*/?></label>
                            <label><?php /*echo form_checkbox('vast', 1, $vast);
                                echo 'Vast' */?></label>
                            <label><?php /*echo form_checkbox('otalo', 1, $otalo);
                                echo 'Otalo'*/?></label>
                            <label><?php /*echo form_checkbox('airbnb', 1, $airbnb);
                                echo 'Airbnb'*/?></label>

                            <label><?php /*echo form_checkbox('ht', 1, $ht);
                                echo 'Housetrip' */?></label>
                            <label><?php /*echo form_checkbox('bk', 1, $bk);
                                echo 'Booking.com'*/?></label>
                            <label><?php /*echo form_checkbox('rm', 1, $rm);
                                echo 'Roomorama'*/?></label>

-->
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
    </div>
    </div>
    </div>

    <?php echo form_close(); ?>


    </div>
    <!--<!--/content-->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
-->
<?php echo $footer ?>