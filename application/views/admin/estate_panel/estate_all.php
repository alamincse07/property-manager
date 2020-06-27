<?php
echo $header;
echo $sidebar ?>

    <style>

        #map_wrapper {
            height: 300px;
        }

        #map_canvas {
            width: 100%;
            height: 100%;
        }

    </style>

    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/admin/css/new_font-awesome.css"/>

    <link href="<?php echo $this->config->base_url(); ?>assets/admin/css/jquery-ui.css" rel="stylesheet">

    <link href="<?php echo $this->config->base_url(); ?>assets/admin/css/chosen.css" rel="stylesheet">

    <link href="<?php echo $this->config->base_url(); ?>assets/admin/css/chosen-bootstrap.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/admin/js/chosen.jquery.js"></script>

    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/admin/js/jquery-ui.js"></script>

    <script src="<?php echo $this->config->base_url(); ?>assets/admin/media/js/jquery.dataTables.columnFilter.js"
            type="text/javascript"></script>

    <script src="<?php echo $this->config->base_url(); ?>/assets/admin/media/js/jquery.dataTables.columnFilter.js"
            type="text/javascript"></script>

    <style type="text/css">

        .text_filter {
            width: 100% !important;
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .select_filter {
            width: 100% !important;
            padding: 0 !important;
            height: auto !important;
            margin: 0 !important;
        }

        .col-md-6 {
            float: left;
            width: 47%;
        }

        #dataTables_filter {
            float: right;
        }

        .select_filter {
            width: 100% !important;
            padding: 0 !important;
            height: auto !important;
            margin: 0 !important;
        }

        .btn-group, .btn-group-vertical {
            display: inline-block;
            position: relative;
            vertical-align: middle;
        }

        * {
            border-radius: 0 0 0 0 !important;
            box-shadow: none !important;
            font-family: "Open Sans", Arial, Helvetica, sans-serif;
            font-weight: 400;
            text-shadow: none !important;
        }

        .btn-group > .btn:first-child {
            margin-left: 0;
        }

        .btn-group a.btn {
            margin: 0 !important;
        }

        .btn.btn-primary {
            background: none repeat scroll 0 0 #1171A3 !important;
        }

        .btn-xs {
            padding: 1px 5px;
        }

        .btn-sm, .btn-xs {
            border-radius: 3px 3px 3px 3px;
            font-size: 12px;
            line-height: 1.5;
            padding: 2px 5px;
        }

        .tooltip, .fa {
            font-size: 15px;
        }

        .fa {
            display: inline-block;
            font-family: FontAwesome;
            font-style: normal;
            font-weight: normal;
            line-height: 1;
        }

        .btn-group, .btn-group-vertical {
            display: inline-block;
            position: relative;
            vertical-align: middle;
        }

        .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {

            min-height: 1px;
            padding-left: 15px;
            padding-right: 15px;
            position: relative;
        }

    </style>
    <div class="span10 content">

            <script>
            jQuery(function($) {
                var script = document.createElement('script');
            script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyCs1onskLKDONbA7dtWznaiVZQYh2PxWTo&sensor=false&callback=initialize";
                document.body.appendChild(script);
            });
            function initialize() {
                var map;
                var bounds = new google.maps.LatLngBounds();
                var mapOptions = {
                    mapTypeId: 'roadmap',
                    center: {lat:  37.09024, lng: -95.712891},
                    zoom:4

                };
                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                map.setTilt(45);
                var markers = [
                    <?php $i = 0; $len = count($estates); ?>
                    <?php foreach($estates as $estate): ?>
                    <?php if( $estate->gps != ''): ?>
                    ['<?php echo addslashes(@$estate->title); ?>',
                        <?php echo $estate->gps; ?>]
                    <?php echo ($i == $len - 1)?"":",";?>
                    <?php endif; ?>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                ];
//console.log(marker);

                var infoWindow = new google.maps.InfoWindow(), marker, i;
                for( i = 0; i < markers.length; i++ ) {
                    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                    bounds.extend(position);
                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: markers[i][0]
                    });
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infoWindow.setContent(marker.title);
                            infoWindow.open(map, marker);		            }
                    })(marker, i));

                   map.fitBounds(bounds);
                }
                /*var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                    this.setZoom(4);
                    google.maps.event.removeListener(boundsListener);
                });*/
            }




        </script>
        <?php if ($this->session->flashdata('message')) { ?>

            <div class="alert alert-success">

                <?php echo $this->session->flashdata('message'); ?>

            </div>

        <?php } ?>
        <?php
        echo $hugemenu
        ?>

        <h1><?php echo lang('sidebar_estate_all'); ?></h1>



        <div class="widget worange">

            <div class="widget-head">

                <div class="pull-left"><h3 class="heading">View properties</h3></div>

                <div class="widget-icons pull-right">

                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a>

                </div>

                <div class="clearfix"></div>

            </div>

            <div class="widget-content">

                <div id="map_wrapper">

                    <div id="map_canvas" class="mapping"></div>

                </div>

            </div>

        </div>

        <div>

            <!--  <table class="table table-striped table-bordered table-hover table-primary"> -->
            <table class="table table-striped table-bordered table-hover table-primary">
                <thead>
                <tr>
                   <!-- <th><?php /*echo lang('estate_image'); */?></th>-->
                    <th><?php echo lang('estate_title'); ?></th>
                    <th><?php echo trim('Published'); ?></th>
                    <th>Property Type</th>

                    <th>Country</th>
                    <th>Province</th>
                    <th>City</th>

                    <th><?php echo lang('estate_date'); ?></th>
                    <th><?php echo lang('estate_cat'); ?></th>
                    <th class="action"><?php echo lang('estate_action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($estates as $estate): ?>
                    <tr>
                        <!--<td width="4%"><?php /*echo ($estate->photo != "") ? "<img src='" . base_url() . "/uploads/thumbs/$estate->photo' width='80' />" : lang('blog_tooltip_noimg') */?></td>-->
                        <td width="24%"><?php echo $estate->title; ?></td>
                        <td><?php echo $estate->publish; ?></td>
                        <td><?php echo $estate->estateName; ?></td>

                        <td><?php echo $estate->country; ?></td>
                        <td><?php echo $estate->province; ?></td>
                        <td><?php echo $estate->city; ?></td>

                        <td width="4%"><?php echo date('d F Y', strtotime($estate->addedDate)); ?></td>
                        <td><?php echo $estate->catName; ?></td>
                        <td class="action">
                            <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('edit') ?>"
                               href="<?php echo site_url() . "admin/estateEdit/" . $estate->id ?>"><i class="icon-edit"></i></a>
                            <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('activate') ?>"
                               href="<?php echo ($estate->publish) ? site_url() . "admin/estateDeactivate/" . $estate->id . '/1' : site_url() . "admin/estateActivate/" . $estate->id . '/0' ?>"><i
                                    class='<?php echo ($estate->publish) ? 'icon-check' : 'icon-check-empty' ?>'></i></a>
                            <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('delete') ?>"
                               href="<?php echo site_url() . 'admin/estateDelete/' . $estate->id ?>" role="button"
                               data-bb="confirm"><i class="icon-trash"></i></a>
                            <a class="tooltp" target="_blank" data-toggle="tooltip" title="<?php echo trim('Submit') ?>"
                               href="<?php echo site_url() . 'admin/PreviewProperty/' . $estate->id ?>" role="button"
                               data-bb="confirm"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p><a href="<?php echo site_url('admin/estateAdd'); ?>" class="btn btn-primary">Add Property</a></p>
        </div>
    </div><!--/content-->
    <script
        src="<?php echo $this->config->base_url(); ?>assets/admin/js/jquery-ui-1.10.2.custom.min.js"></script> <!-- jQuery UI -->
    <script src="<?php echo $this->config->base_url(); ?>assets/admin/js/sparklines.js"></script>
    <script
        src="<?php echo $this->config->base_url(); ?>assets/admin/js/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
    <script
        src="<?php echo $this->config->base_url(); ?>assets/admin/js/bootstrap-datetimepicker.min.js"></script> <!-- Date picker -->
    <script src="<?php echo $this->config->base_url(); ?>assets/admin/js/DT_bootstrap.js"></script> <!-- jQuery UI -->
    <script
        src="<?php echo $this->config->base_url(); ?>assets/admin/js/jquery.prettyPhoto.js"></script> <!-- prettyPhoto -->        <?php echo $footer ?>
    <script>
        $(document).ready(function () {
            /*$(".collapse").collapse()({
                show: false
            });*/
            $(".collapse").collapse('hide');
        });
    </script>
<?php die;?>