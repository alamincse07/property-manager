<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('members'); ?> <?php echo lang('dashboard'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php endif; ?>

    <table class="table table-striped table-bordered table-hover table-primary">
        <thead>

            <tr>
                <th>Username</th>
                <th><?php echo $this->lang->line('email') ?></th>
                <th><?php echo $this->lang->line('phone') ?></th>
                <th><?php echo $this->lang->line('membership') ?></th>
                <th>User Type</th>
                <th><?php echo $this->lang->line('options') ?></th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($members)): ?>
                <?php foreach ($members as $key => $user): ?>

                    <tr>
                        <td><a href='<?php echo site_url() ?>/members/view/profile/<?php echo $user['user_id'] ?>'><?php echo $user['username'] ?></a></td>
                        <td><?php echo $user['email'] ?></td>
                        <td><?php echo $user['phone'] ? $user['phone'] : '---'; ?></td>
                        <td><?php
                            $this->db->where('m_id', $user['membership_id']);
                            $query = $this->db->get('memberships');
                            if ($query->num_rows() > 0) {
                                $row = $query->row_array();
                                if ($user['role_id'] == '1') {
                                    echo "<span class=\"badge badge-pink\">Admin</span>";
                                } else {
                                    echo "<span class=\"badge badge-purple\">" . $row['membership'] . "</span>";
                                }
                            }
                            ?></td>
                        <td><span class="badge badge-info"><?php
                                $this->db->where('r_id', $user['role_id']);
                                $query = $this->db->get('roles');
                                if ($query->num_rows() > 0) {
                                    $row = $query->row_array();
                                    echo $row['role'];
                                }
                                ?></span></td>
                        <td align="right">
                            <div class='btn-group'>
                                <a href='<?php echo site_url() ?>/members/view/profile/<?php echo $user['user_id'] ?>'>
                                    <button class='btn btn-mini btn-success'><i class='icon-eye-open'></i><?php echo $this->lang->line('view') ?></button>
                                </a>
                                <?php if ($user['role_id'] != '1') { ?>
                                    <a href='<?php echo site_url() ?>/members/view/edit_profile/<?php echo $user['user_id'] * 1200 ?>'>
                                        <button class='btn btn-mini btn-info'><i class='icon-edit'></i><?php echo $this->lang->line('edit') ?></button>
                                    </a>
                                <?php } ?>



                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

            <?php endif; ?>

        </tbody>
    </table>


    <?php echo $pagination; ?>

    <div class="btn-toolbar pull-right">
        <a href='<?php echo site_url('admin/userAdd'); ?>'>
            <button class="btn btn-medium btn-danger"><i class='icon-user'></i> <?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('member'); ?></button>
        </a>
        <a href='<?php echo site_url('admin/membersLoginActivities'); ?>'>
            <button class="btn btn-medium btn-success" style="margin-left: 10px !important;"><i class='icon-unlock'></i> <?php echo $this->lang->line('login') ?> <?php echo $this->lang->line('activities') ?></button>
        </a>
    </div>

</div><!--/content-->

<?php echo $footer; ?>
