<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <title><?php echo $ptitle ?></title>
    <meta name="description" content="<?php echo $desc ?>"/>
    <meta name="keywords" content="<?php echo $keyword ?>"/>
    <meta name="author" content="<?php echo $author ?>">

    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.ico"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap-responsive.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/font-awesome.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/uniform.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/prettyPhoto.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/media/css/TableTools.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/media/css/datatables.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/custom.css"/>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script>
-->
    <?php echo '<script>const $baseUrl = "' . base_url() . '"</script>' ?>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery/jquery-1.8.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/bootbox.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/uniform.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/import_export.js"></script>


    <!--<script src="<?php /*echo base_url() */?>assets/admin/js/gmap3.min.js"></script>-->
    <script src="<?php echo base_url(); ?>assets/admin/js/rental.js"></script>

    <?php if (!preg_match('@reports/payments@', $_SERVER['REQUEST_URI'])): ?>
        <script src="<?php echo base_url(); ?>assets/admin/js/custom.js"></script>
    <?php endif; ?>

    <script type="text/javascript" charset="utf-8"
            src="<?php echo base_url(); ?>assets/admin/media/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8"
            src="<?php echo base_url(); ?>assets/admin/media/js/ZeroClipboard.js"></script>
    <script type="text/javascript" charset="utf-8"
            src="<?php echo base_url(); ?>assets/admin/media/js/TableTools.js"></script>
    <script>
        function baseUrl() {
            var href = window.location.href.split('/');
            return href[0] + '//' + href[2] + '/';
        }

        var SITE_URL = baseUrl();
        var BASE_URL = SITE_URL + '';
    </script>
    <style>
        .user_info {
            border: 0px solid red;
            float: left;
            width: 16%;
        }

        .menus {
            border: 0px solid blue;
            float: left;
            /*height: 102px;*/
            width: 83%;
        }

        .all_item {

            width: 100%;
        }

        .f_left {
            float: left;
        }

        .user_name {
            border: 0px solid #b0daff;
            color: #fffeef;
            height: 30px;
            line-height: 33px;
            margin: 10px;

        }

        .search-query {
            margin-top: 5px;
            width: 150px;
        }

        .icon-user-img {
            font-size: 40px;
            color: #FFFEEF;

            border: 0px solid greenyellow;
        }

        .prf_img {
            margin: 5px;
        }

        .user_name_link li a {
            color: white;
        }

        .user_display {
            width: 100%
        }
    </style>
</head>

<body>

<div style="position: static;" class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container">
            <a href="<?php echo site_url(); ?>admin" class="brand"> LeftTravel Property Management
                <?php //echo $main_title ?></a>

            <div class="nav-collapse collapse navbar-inverse-collapse">

                <ul class="nav pull-left hidden" >
                    <?php if ($this->session->userdata('level') >= 2) { ?>
                        <li><a href="<?php echo site_url(); ?>"><i
                                    class="icon-home"></i><?php echo lang('header_menu_home') ?></a></li>
                    <?php }
                    if ($this->session->userdata('level') >= 3) { ?>
                        <li><a href="<?php echo site_url(); ?>admin/blogAll"><i
                                    class="icon-pencil"></i> <?php echo lang('header_menu_blog') ?></a></li>
                    <?php }
                    if ($this->session->userdata('level') >= 3) { ?>
                        <li><a href="<?php echo site_url(); ?>"><i
                                    class="icon-bar-chart"></i> <?php echo lang('header_menu_statistics') ?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/clearCache"><i
                                    class="icon-trash"></i><?php echo lang('header_menu_clear_cache') ?></a></li>
                    <?php
                    }
                    if ($this->session->userdata('level') >= 3) {
                        ?>
                        <li><a href="<?php echo site_url(); ?>admin/pageAll"><i
                                    class="icon-file"></i><?php echo lang('header_menu_pages') ?></a></li>
                    <?php
                    }
                    if ($this->session->userdata('level') >= 4) {
                        ?>
                        <li><a href="<?php echo site_url(); ?>admin/viewSlider"><i
                                    class="icon-play"></i><?php echo lang('header_menu_slider') ?></a></li>
                    <?php
                    }
                    if ($this->session->userdata('level') >= 3) {
                        ?>
                        <li><a href="<?php echo site_url(); ?>admin/estateShowcase"><i
                                    class="icon-bookmark"></i><?php echo lang('header_menu_showcase') ?></a></li>
                    <?php
                    }
                    if ($this->session->userdata('level') >= 5) {
                        ?>
                        <li><a href="<?php echo site_url(); ?>admin/setGeneral"><i
                                    class="icon-wrench"></i><?php echo lang('header_menu_settings') ?></a></li>
                    <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i><b
                                class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url(); ?>admin/userProfile"><i
                                        class="icon-user"></i> <?php echo lang('header_menu_profile') ?></a></li>
                            <li><a href="<?php echo site_url() ?>logout"><i
                                        class="icon-off"></i><?php echo lang('header_menu_logout') ?></a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i><b
                            class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url(); ?>admin/userProfile"><i
                                    class="icon-user"></i> <?php echo lang('header_menu_profile') ?></a></li>
                        <li><a href="<?php echo site_url() ?>logout"><i
                                    class="icon-off"></i><?php echo lang('header_menu_logout') ?></a></li>
                    </ul>
                </li>
                </ul>

            </div>
            <!-- /.nav-collapse -->
        </div>
    </div>
    <!-- /navbar-inner -->
</div>
<div class="all_item">
    <div class="user_info hidden">
        <div class="user_display">
            <div class="f_left prf_img">
                <i class="icon-user icon-user-img"></i>
            </div>
            <div class="f_left user_name">
                <ul class="nav pull-left user_name_link">
                    <li>

                        <a href="#"><?php echo lang('welcome') ?>

                            <strong><?php echo $username ?></strong></a>
                    </li>
                    <li><a href="<?php echo site_url(); ?>admin/userProfile">
                            <?php echo lang('sidebar_edit_profile') ?></a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="search1">
            <form method="get" action="http://www.outsourcebase.com//index.php/admin/viewSearch" class="form-search">
                <input type="text" class="span search-query" name="ss" id="ss" style="width:168px"></form>
        </div>

    </div>
    <div class="menus ">


        <div class="button-div hidden">
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/">Dashboard</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/estateAll">Vacation Rentals</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/estateLandlord">Property Management</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/booking/addReservation">CRM</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/email_marketing/">E-mail Marketing</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/messages/inbox">Messages</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin_payments">Payments</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/agents">Manage Agents</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/hrmStaffList">Human Resources</a>

            <a class="header-btn" href="<?php echo base_url(); ?>index.php/reports/daily_sales">Reports</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/pageAll">Social Media</a>
            <a class="header-btn" href="javascript:void(0)">Web Publishing</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/export">Import/Export</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/userAll">Accounting</a>
            <a class="header-btn" href="javascript:void(0)">SEO</a>
            <a class="header-btn" href="javascript:void(0)">Support Tickets</a>
            <a class="header-btn" href="javascript:void(0)">Customers</a>
            <a class="header-btn" href="javascript:void(0)">Websites</a>
            <a class="header-btn" href="javascript:void(0)">Finance</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/blogAll">Site Settings</a>
            <a class="header-btn" href="<?php echo base_url(); ?>index.php/admin/userAll">Users</a>
        </div>


    </div>

</div>

<div class="contain">
    <div class="row-fluid">
