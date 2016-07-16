<?php
echo $header;
echo $sidebar
?>		
<div class="span10 content">
    <?php echo $hugemenu ?>

    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('message') ?>
        </div>
    <?php } ?>
    <div class="statics">
        <div class="span12 well">
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo $estateCount ?></span>
                    <span class="stat-text"><?php echo lang('statics_estate')?></span>
                </div>
            </div>
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo $showcaseCount; ?></span>
                    <span class="stat-text"><?php echo lang('statics_showcase')?></span>
                </div>
            </div>
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo $blogCount ?></span>
                    <span class="stat-text"><?php echo lang('statics_blog')?></span>
                </div>
            </div>
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo $userCount ?></span>
                    <span class="stat-text"><?php echo lang('statics_user')?></span>
                </div>
            </div>
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo $imageCount; ?></span>
                    <span class="stat-text"><?php echo lang('statics_image')?></span>
                </div>
            </div>
            <div class="span2">
                <div class="stat-box border-blue">
                    <span class="count"><?php echo round(memory_get_usage(TRUE) / 1024 / 1024, 3); ?><sub> MB</sub></span>
                    <span class="stat-text"><?php echo lang('statics_memory')?></span>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span4 widget">
                <div class="widget-head"><h3 class="heading"><?php echo lang('statics_last_estate')?></h3></div>
                <div class="widget-body">
                    <table class="table table-hover">
                        <?php foreach ($estates as $item) { ?>
                            <tr>
                                <th><?php echo $item->title ?></th>
                                <th><?php echo $item->catName ?></th>
                            </tr>
                        <?php } ?>
                    </table> 
                </div>
            </div>
            <div class="span4 widget">
                <div class="widget-head"><h3 class="heading"><?php echo lang('statics_last_blog')?></h3></div>
                <div class="widget-body">
                    <table class="table table-hover">
                        <?php foreach ($blogs as $item) { ?>
                            <tr>
                                <th><?php echo $item->title ?></th>
                                <th><?php echo $item->addedDate ?></th>
                            </tr>
                        <?php } ?>
                    </table> 
                </div>
            </div>
            <div class="span4 widget">
                <div class="widget-head"><h3 class="heading"><?php echo lang('statics_last_user')?></h3></div>
                <div class="widget-body">
                    <table class="table table-hover">
                        <?php foreach ($users as $item) { ?>
                            <tr>
                                <th><?php echo $item->username ?></th>
                                <th><?php echo $item->email ?></th>
                            </tr>
                        <?php } ?>
                    </table> 
                </div>
            </div>
        </div>
    </div>

</div><!--/content-->

<?php echo $footer ?>