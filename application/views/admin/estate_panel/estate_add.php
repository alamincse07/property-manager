<?php echo $header;echo $sidebar ?>		
	
		<style>
			#mapCanvas {width: 100%;height: 400px;float: left;}
	  		#infoPanel {float: left;margin-left: 10px;}
	  		#infoPanel div {margin-bottom: 5px;}
  		</style>
		
			
		<div class="span10 content">
			<?php echo $hugemenu ?>

            <h1><?php echo lang('estate_add_header'); ?></h1>
            <?php if ($message) { ?>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
                </div>
            <?php } if ($success) { ?>
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
                </div>
            <?php } ?>
            <div class="alert alert-info"><?php echo lang('estate_add_message'); ?></div>

            <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/tiny_mce/jquery.tinymce.js"></script>
            <script type="text/javascript">
                $().ready(function() {
                    $('textarea.tinymce').tinymce({
                        script_url: '<?php echo base_url() ?>/assets/admin/js/tiny_mce/tiny_mce.js',
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
                        height : "400"
                    });
                });
            </script>

            <?php echo form_open('admin/estateAdd2',array('method' => 'get')); ?>
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('estate_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                <div>
                
                <table width="100%" cellpadding="5" cellspacing="0" border="0">
                	<tr>
                		<td width="33%">Country</td>
                		<td width="34%">State/Province</td>
                		<td width="33%" colspan="2">City/Town</td>
                	</tr>
                	<tr>
                		<td><?php echo form_dropdown('country', $country, '', 'class="country span" id="country" onchange="set_country_marker();"'); ?></td>
                		<td><?php echo form_dropdown('province', $province, 'AL', 'class="province span" id="province" onchange="set_state_marker();"'); ?></td>
                		<td colspan="2"><?php $city['class'] = 'span'; echo form_input($city); ?></td>
                	</tr>
                	<tr>
                		<td>Postal Code</td>
                		<td>Street Address</td>
                		<td width="16%">Latitude</td>
                		<td width="17%">Longitude</td>
                	</tr>
                	<tr>
                		<td><?php $title['class'] = 'span'; echo form_input($postal_code); ?></td>
                		<td><?php $title['class'] = 'span'; echo form_input($address); ?></td>
                		<td><?php $title['class'] = 'span'; echo form_input($lat); ?></td>
                		<td><?php $title['class'] = 'span'; echo form_input($lon); ?></td>
                	</tr>
                </table>
                
                
                <div class="widget wblue">
                <div class="widget-head">
                  <h3 class="heading">Location</h3>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapCanvas">

                  </div>
                </div>


              </div> 
              
               <div class="clearfix"></div>
               

              <p align="left">
                <input type="submit" name="submit" value="Next" class="btn btn-primary" />&nbsp;&nbsp;
                <input type="submit" name="skip" value="Skip" class="btn btn-primary" />&nbsp;&nbsp;
                <input type="reset" name="reset" value="Cancel" class="btn btn-primary" />
               </p> 
            </div>

            </div>
            </div>
            </div>

        <?php echo form_close(); ?>	


        </div>
        <!--/content-->


    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&amp;sensor=false&amp;language=en"></script>
    <script src="<?php echo base_url() ?>assets/admin/js/gmap3.min.js"></script>


    <script type="text/javascript">
    var timerMap;
    var firstSet = false;
    var savedGpsData;

    function set_country_marker() {

        var country = $('#country').val();

        $("#mapCanvas").gmap3({
            getlatlng:{
                address:  country,
                callback: function(results){
                    if ( !results ){
                        console.log('Bad address!');
                        return;
                    }

                    if(firstSet){
                        $(this).gmap3({
                            clear: {
                                name:["marker"],
                                last: true
                            }
                        });
                    }

                    $(this).gmap3({
                        marker:{
                            latLng:results[0].geometry.location,
                            options: {
                                id:'searchMarker',
                                draggable: true
                            },
                            events: {
                                dragend: function(marker){
                                    /* $('#gps').val(marker.getPosition().lat()+', '+marker.getPosition().lng()); */
                                    $('#lat').val(marker.getPosition().lat());
                                    $('#lon').val(marker.getPosition().lng());
                                }
                            }
                        }
                    });


                    $(this).gmap3('get').setCenter( results[0].geometry.location );

                    /* $('#gps').val(results[0].geometry.location.lat()+', '+results[0].geometry.location.lng()); */
                    $('#lat').val(results[0].geometry.location.lat());
                    $('#lon').val(results[0].geometry.location.lng());


                    firstSet = true;

                }
            }
        });
    }

    function set_state_marker() {

        var country = $('#country').val();
        var province = $('#province').val();

        $("#mapCanvas").gmap3({
            getlatlng:{
                address:  province+", "+country,
                callback: function(results){
                    if ( !results ){
                        console.log('Bad address!');
                        return;
                    }

                    if(firstSet){
                        $(this).gmap3({
                            clear: {
                                name:["marker"],
                                last: true
                            }
                        });
                    }

                    $(this).gmap3({
                        marker:{
                            latLng:results[0].geometry.location,
                            options: {
                                id:'searchMarker',
                                draggable: true
                            },
                            events: {
                                dragend: function(marker){
                                    /* $('#gps').val(marker.getPosition().lat()+', '+marker.getPosition().lng()); */
                                    $('#lat').val(marker.getPosition().lat());
                                    $('#lon').val(marker.getPosition().lng());
                                }
                            }
                        }
                    });


                    $(this).gmap3('get').setCenter( results[0].geometry.location );

                    /* $('#gps').val(results[0].geometry.location.lat()+', '+results[0].geometry.location.lng()); */
                    $('#lat').val(results[0].geometry.location.lat());
                    $('#lon').val(results[0].geometry.location.lng());


                    firstSet = true;

                }
            }
        });
    }



    $(function () {

        if($('#lat').length && $('#lat').val() != '')
        {
            /*savedGpsData = $('#gps').val().split(", ");*/
            var savedGpsData=[];
            savedGpsData[0] = $('#lat').val();
            savedGpsData[1] = $('#lon').val();

            $("#mapCanvas").gmap3({
                map:{
                    options:{
                        center: [parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])],
                        zoom: 6
                    }
                },
                marker:{
                    values:[
                        {latLng:[parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])]},
                    ],
                    options:{
                        draggable: true
                    },
                    events:{
                        dragend: function(marker){
                            /* $('#gps').val(marker.getPosition().lat()+', '+marker.getPosition().lng()); */
                            $('#lat').val(marker.getPosition().lat());
                            $('#lon').val(marker.getPosition().lng());
                        }
                    }}});

            firstSet = true;
        }
        else
        {
            $("#mapCanvas").gmap3({
                map:{
                    options:{
                        center: [53.81388459999999, -124.929416899999978],
                        zoom: 4
                    }
                }
            });
        }

        $('#lat').keyup(function (e) {
            var lat = $('#lat').val();
            var lon = $('#lon').val();
            var latlng = new google.maps.LatLng(lat, lon);

            if(lat != '' && lon != '')
            {
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    $('#mapCanvas').gmap3({'marker':{'latLng': [lat, lon],'draggable':true},'map':{'options':{'center':[lat, lon], 'zoom': 10} } });
                },2000);
            }

        });

        $('#lon').keyup(function (e) {
            var lat = $('#lat').val();
            var lon = $('#lon').val();
            var latlng = new google.maps.LatLng(lat, lon);

            if(lat != '' && lon != '')
            {
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    $('#mapCanvas').gmap3({'marker':{'latLng': [lat, lon],'draggable':true},'map':{'options':{'center':[lat, lon], 'zoom': 10} } });
                },2000);
            }

        });

        $('#city').keyup(function (e) {

            var country = $('#country').val();
            var province = $('#province').val();

            clearTimeout(timerMap);
            timerMap = setTimeout(function () {

                $("#mapCanvas").gmap3({
                    getlatlng:{
                        address:  $('#city').val()+", "+province+", "+country,
                        callback: function(results){
                            if ( !results ){
                                console.log('Bad address!');
                                return;
                            }

                            if(firstSet){
                                $(this).gmap3({
                                    clear: {
                                        name:["marker"],
                                        last: true
                                    }
                                });
                            }

                            $(this).gmap3({
                                marker:{
                                    latLng:results[0].geometry.location,
                                    options: {
                                        id:'searchMarker',
                                        draggable: true
                                    },
                                    events: {
                                        dragend: function(marker){
                                            /* $('#gps').val(marker.getPosition().lat()+', '+marker.getPosition().lng()); */
                                            $('#lat').val(marker.getPosition().lat());
                                            $('#lon').val(marker.getPosition().lng());
                                        }
                                    }
                                }
                            });


                            $(this).gmap3('get').setCenter( results[0].geometry.location );

                            /* $('#gps').val(results[0].geometry.location.lat()+', '+results[0].geometry.location.lng()); */
                            $('#lat').val(results[0].geometry.location.lat());
                            $('#lon').val(results[0].geometry.location.lng());


                            firstSet = true;

                        }
                    },
                    map:{options:{zoom: 8}}
                });
            }, 2000);

        });

        $('#address').keyup(function (e) {

            var country = $('#country').val();
            var province = $('#province').val();
            var city = $('#city').val();

            clearTimeout(timerMap);
            timerMap = setTimeout(function () {

                $("#mapCanvas").gmap3({
                    getlatlng:{
                        address:  $('#address').val()+", "+city+", "+province+", "+country,
                        callback: function(results){
                            if ( !results ){
                                console.log('Bad address!');
                                return;
                            }

                            if(firstSet){
                                $(this).gmap3({
                                    clear: {
                                        name:["marker"],
                                        last: true
                                    }
                                });
                            }

                            $(this).gmap3({
                                marker:{
                                    latLng:results[0].geometry.location,
                                    options: {
                                        id:'searchMarker',
                                        draggable: true
                                    },
                                    events: {
                                        dragend: function(marker){
                                            /* $('#gps').val(marker.getPosition().lat()+', '+marker.getPosition().lng()); */
                                            $('#lat').val(marker.getPosition().lat());
                                            $('#lon').val(marker.getPosition().lng());
                                        }
                                    }
                                }
                            });


                            $(this).gmap3('get').setCenter( results[0].geometry.location );

                            /* $('#gps').val(results[0].geometry.location.lat()+', '+results[0].geometry.location.lng()); */
                            $('#lat').val(results[0].geometry.location.lat());
                            $('#lon').val(results[0].geometry.location.lng());


                            firstSet = true;

                        }
                    },
                    map:{options:{zoom: 10}}
                });
            }, 2000);

        });


    });
    </script>
<?php echo $footer ?>