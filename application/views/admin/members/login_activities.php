<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><i class="icon-unlock"></i> <?php echo lang('user'); ?> <?php echo lang('login'); ?> <?php echo lang('activities'); ?></h1>

    <table class="table table-striped table-bordered table-hover table-primary">
        <thead>

            <tr>
                <th><?php echo $this->lang->line('user'); ?></th>
                <th><?php echo $this->lang->line('ip_address'); ?></th>
                <th><?php echo $this->lang->line('login'); ?> <?php echo $this->lang->line('date'); ?></th>
                <th><?php echo $this->lang->line('platform'); ?></th>
                <th><?php echo $this->lang->line('browser'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($login_activities)): ?>
                <?php foreach ($login_activities as $key => $login): ?>
                    <tr>
                        <td><span class="badge badge-light"><a href='<?php echo site_url(); ?>admin/membersProfile/<?php echo $login['id'] ?>'> <?php echo $login['username']; ?></a></span></td>
                        <td><?php echo $login['ip_address']; ?></a></td>
                        <td><?php echo $login['login_time']; ?></td>
                        <td><span class="label label-success"><?php echo $login['u_platform']; ?></span></td>
                        <td><?php echo $login['browser']; ?></td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

            <?php endif; ?>

        </tbody>
    </table>

</div><!--/content-->

<?php echo $footer; ?>
