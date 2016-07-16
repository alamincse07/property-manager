<div class="hugeMenu hidden" style="display: none">
    <div class="span2">
        <a href="<?php echo site_url(); ?>admin/estateAdd" class="btn btn-info span <?php echo ($this->session->userdata('level') >= 3) ? '' : 'disabled' ?>"> <i class="icon-home"></i> <span><strong><?php echo lang('huge_menu_new_estate')?></strong></span>
        </a>
    </div>
    <div class="span2 ">
        <a href="<?php echo site_url(); ?>admin/blogAdd" class="btn btn-info span"> <i class="icon-pencil"></i> <span><strong><?php echo lang('huge_menu_new_blog')?></strong></span>
        </a>
    </div>
    <div class="span2 ">
        <a href="<?php echo site_url(); ?>admin/userAdd" class="btn btn-info span <?php echo ($this->session->userdata('level') >= 4) ? '' : 'disabled' ?>"> <i class="icon-user"></i> <span><strong><?php echo lang('huge_menu_new_user')?></strong></span>
        </a>
    </div>
    <div class="span2 ">
        <a href="<?php echo site_url(); ?>admin/estateShowcase" class="btn btn-info span <?php echo ($this->session->userdata('level') >= 3) ? '' : 'disabled' ?>"> <i class="icon-bookmark"></i> <span><strong><?php echo lang('huge_menu_new_showcase')?></strong></span>
        </a>
    </div>
    <div class="span2 ">
        <a href="<?php echo site_url(); ?>admin/viewSlider" class="btn btn-info span <?php echo ($this->session->userdata('level') >= 4) ? '' : 'disabled' ?>"> <i class="icon-play"></i> <span><strong><?php echo lang('huge_menu_slider')?></strong></span>
        </a>
    </div>
    <div class="span2 ">
        <a href="<?php echo site_url(); ?>admin/viewStatics" class="btn btn-info span"> <i class="icon-bar-chart"></i> <span><strong><?php echo lang('huge_menu_statics')?></strong></span>
        </a>
    </div>
</div>