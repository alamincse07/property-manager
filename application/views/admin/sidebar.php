<?php
    //\application\helpers\Generic::_setTrace($this->session->userdata);
?>
    <div class="span2 sidebar" style="width:176px;">
    <div class="accordion" id="accordion2">
    <?php if ($this->session->userdata('level') >= 3) { ?>
        <?php if( $this->session->userdata('page') == 1 || $this->session->userdata('page') == 7 || $this->session->userdata('page') == 11 || $this->session->userdata('page') == 13 ){ ?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse"
                       data-parent="#accordion2" href="#collapse1"><i class="icon-home"></i> <?php echo lang('sidebar_estate_head')?></a>
                </div>
                <div id="collapse1" class="accordion-body collapse">
                    <ul class="nav nav-list">
                        <li><a href="<?php echo site_url(); ?>admin/estateAll"><i class="icon-home"></i> <?php echo lang('sidebar_estate_all')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateShowcase"><i class="icon-bookmark hidden"></i> <?php echo lang('sidebar_estate_showcase')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateConfirm"><i class="icon-trash"></i> <?php echo lang('sidebar_estate_draft')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateAdd"><i class="icon-plus-sign-alt"></i> <?php echo lang('sidebar_estate_newadd')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateCat"><i class="icon-folder-open-alt"></i> <?php echo lang('sidebar_estate_cats')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateType"><i class="icon-folder-open-alt"></i> <?php echo lang('sidebar_estate_type')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateProperty"><i class="icon-cog"></i> <?php echo lang('sidebar_estate_property')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateSelectbox"><i class="icon-cog"></i> <?php echo lang('sidebar_estate_selectbox')?></a></li>
                        <?php
                        if ($this->session->userdata('user_id') == 1){
                            ?><li><a href="<?php echo site_url(); ?>admin/estateAttribute"><i class="icon-cog"></i> <?php echo lang('sidebar_estate_attributes')?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } }

        elseif ($this->session->userdata('level') >= 1) { ?>
        <?php if($this->session->userdata('page') == 1|| $this->session->userdata('page') == 6 || $this->session->userdata('page') == 10 || $this->session->userdata('page') == 3){ ?>
            <!--<div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse"
                       data-parent="#accordion2" href="#collapse2"><i class="icon-pencil"></i> <?php /*echo lang('sidebar_blog_head')*/?></a>
                </div>
                <div id="collapse2" class="accordion-body collapse">
                    <ul class="nav nav-list">
                        <li><a href="<?php /*echo site_url(); */?>admin/blogAll"><i class="icon-pencil"></i> <?php /*echo lang('sidebar_blog_all')*/?></a></li>
                        <li><a href="<?php /*echo site_url(); */?>admin/blogAdd"><i class="icon-plus-sign-alt"></i> <?php /*echo lang('sidebar_blog_add')*/?></a></li>
                        <li><a href="<?php /*echo site_url(); */?>admin/blogConfirm"><i class="icon-trash"></i> <?php /*echo lang('sidebar_blog_confirm')*/?></a></li>
                    </ul>
                </div>
            </div>-->




            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse"
                       data-parent="#accordion2" href="#collapse1"><i class="icon-home"></i> <?php echo lang('sidebar_estate_head')?></a>
                </div>
                <div id="collapse1" class="accordion-body collapse">
                    <ul class="nav nav-list">
                        <li><a href="<?php echo site_url(); ?>admin/estateAll"><i class="icon-home"></i> <?php echo lang('sidebar_estate_all')?></a></li>
                       <!-- <li><a href="<?php /*echo site_url(); */?>admin/estateShowcase"><i class="icon-bookmark hidden"></i> <?php /*echo lang('sidebar_estate_showcase')*/?></a></li>-->
                        <li><a href="<?php echo site_url(); ?>admin/estateConfirm"><i class="icon-trash"></i> <?php echo lang('sidebar_estate_draft')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/estateAdd"><i class="icon-plus-sign-alt"></i> <?php echo lang('sidebar_estate_newadd')?></a></li>
                        <li><a href="<?php echo site_url(); ?>admin/export"><i class="icon-folder-open-alt"></i> <?php echo trim('Export for listing')?></a></li>


                        <!-- <li><a href="<?php /*echo site_url(); */?>admin/estateType"><i class="icon-folder-open-alt"></i> <?php /*echo lang('sidebar_estate_type')*/?></a></li>
                         <li><a href="<?php /*echo site_url(); */?>admin/estateProperty"><i class="icon-cog"></i> <?php /*echo lang('sidebar_estate_property')*/?></a></li>
                         <li><a href="<?php /*echo site_url(); */?>admin/estateSelectbox"><i class="icon-cog"></i> <?php /*echo lang('sidebar_estate_selectbox')*/?></a></li>
                         <?php
 /*                        if ($this->session->userdata('user_id') == 1){
                             */?><li><a href="<?php /*echo site_url(); */?>admin/estateAttribute"><i class="icon-cog"></i> <?php /*echo lang('sidebar_estate_attributes')*/?></a></li>
                         --><?php /*} */?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
























    <!--//to do poff for now-->


    </div>
    <!--/.well -->
    </div>
<?php
if($this->session->userdata('page') != 1){
    ?>
    <script>
        jQuery(document).ready( function () {
            $(".collapse").collapse('hide');/*({
                show: false
            });*/
        });
    </script>

<?php }else if(strstr(uri_string(), '/estate') != '/estateAll' || strstr(uri_string(), '/estate') != '/estateAdd'){
    ?>
    <script>
        $(document).ready( function () {
            $(".collapse").collapse('hide');/*({
                show: false
            });*/
        });
    </script>
<?php
}?>