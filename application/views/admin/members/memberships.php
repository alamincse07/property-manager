<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('memberships'); ?> <?php echo lang('dashboard'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php endif; ?>

    <table class="table table-striped table-bordered table-hover table-primary">

        <thead>
            <tr>
                <th><?php echo lang('membership'); ?></th>
                <th><?php echo lang('amount'); ?></th>
                <th><?php echo lang('valid'); ?> <?php echo lang('days'); ?></th>
                <th><?php echo lang('description'); ?></th>
                <th class="action"><?php echo lang('options'); ?></th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($memberships as $m_package): ?>

                <tr>
                    <td><?php echo $m_package['membership'] ?></td>
                    <td><span class="badge badge-pink"><?php echo lang('currency_dollar_sign'); ?> <?php echo number_format($m_package['amount'], 2) ?></span>
                    </td>
                    <td><span class="badge badge-purple">
                            <?php echo $m_package['valid_days'] ?></span></td>
                    <td><?php echo $m_package['description'] ? $m_package['description'] : '---'; ?></td>

                    <td class="action">
                        <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('membership_tooltip_edit') ?>" href="<?php echo site_url() . "admin/membershipsEdit/" . $m_package['m_id']; ?>"><i class="icon-edit"></i></a>
                        <a class="tooltp" data-toggle="tooltip" title="<?php echo lang('membership_tooltip_delete') ?>" href="<?php echo site_url() . 'admin/membershipsDelete/' . $m_package['m_id']; ?>" role="button" data-bb="confirm"><i class="icon-trash"></i></a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <?php echo $pagination; ?>

    <div class="btn-toolbar pull-right">
        <a href='<?php echo site_url('admin/membershipsAdd'); ?>'>
            <button class="btn btn-medium btn-danger"><i class='icon-user'></i> <?php echo lang('add') ?> <?php echo lang('membership') ?></button>
        </a>
        <a href='<?php echo site_url('admin/membersLoginActivities'); ?>'>
            <button class="btn btn-medium btn-success" style="margin-left: 10px !important;"><i class='icon-unlock'></i> <?php echo lang('login') ?> <?php echo lang('activities') ?></button>
        </a>
    </div>

</div><!--/content-->

<?php echo $footer; ?>
