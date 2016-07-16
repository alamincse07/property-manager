<?php echo $header;
echo $sidebar ?>
    <div class="span10 content">            <?php echo $hugemenu ?>
        <h1><?php echo lang('sidebar_estate_draft'); ?></h1> <?php if ($this->session->flashdata('message')) { ?>
            <div
                class="alert alert-success">                <?php echo $this->session->flashdata('message') ?>            </div>            <?php } ?>
        <div>
            <table class="table table-striped table-bordered table-hover table-primary">
                <thead>
                <tr>
                    <!--<th><?php /*echo lang('estate_image'); */?></th>-->
                    <th><?php echo lang('estate_title'); ?></th>
                   <!-- <th><?php /*echo lang('estate_price'); */?></th>-->
                    <th><?php echo lang('estate_date'); ?></th>
                    <th><?php echo lang('estate_cat'); ?></th>
                    <th class="action"><?php echo lang('estate_action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($estates as $estate): ?>
                    <tr>
                        <td width="5%">
                            <?php echo ($estate->photo != "") ? "<img src='" . $estate->photo . "' width='80' />" : lang('blog_tooltip_noimg') ?></td>
                        <td><?php echo $estate->title; ?></td>
                        <!--<td><?php /*echo $estate->price; */?></td>-->
                        <td><?php echo date('d F Y', strtotime($estate->addedDate)); ?></td>
                        <td><?php echo $estate->catName; ?></td>
                        <td class="action"><a class="tooltp" data-toggle="tooltip" title="<?php echo lang('edit') ?>"
                                              href="<?php echo site_url() . "admin/estateEdit/" . $estate->id ?>"><i
                                    class="icon-edit"></i></a> <a class="tooltp" data-toggle="tooltip"
                                                                  title="<?php echo lang('activate') ?>"
                                                                  href="<?php echo ($estate->publish != 1) ? site_url() . "admin/estateActivate/" . $estate->id . '/0' : site_url() . "admin/estateDeactivate/" . $estate->id . '/1' ?>"><i
                                    class='<?php echo ($estate->publish != 1) ? 'icon-check-empty' : 'icon-check' ?>'></i></a>
                            <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('delete') ?>"
                               href="<?php echo site_url() . 'admin/estateDelete/' . $estate->id ?>" role="button"
                               data-bb="confirm"><i class="icon-trash"></i></a></td>
                    </tr>                        <?php endforeach; ?>                    </tbody>
            </table>
        </div> <?php echo $pagination ?>        </div><!--/content--><?php echo $footer ?>