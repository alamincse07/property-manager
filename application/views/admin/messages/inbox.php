<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('message_inbox_title'); ?></h1>

    <div class="btn-toolbar">
        <a href="<?php echo site_url(); ?>admin/messages/new_message"><button class="btn btn-medium btn-danger"><i class="icon-envelope"></i> <?php echo lang('message_new'); ?> <?php echo lang('message_message'); ?></button></a>
    </div>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?= $this->session->flashdata('message') ?></p>
        </div>
    <?php endif; ?>

    <div>

        <table class="table table-striped table-bordered table-hover table-primary">

            <thead>
                <tr>
                    <th><?php echo lang('message_from_name'); ?></th>
                    <th style="width: 20%;"><?php echo lang('message_subject'); ?></th>
                    <th style="width: 20%;"><?php echo lang('message_message'); ?></th>
                    <th><?php echo lang('message_date'); ?> <?php echo lang('message_sent'); ?></th>
                    <th><?php echo lang('message_status'); ?></th>
                    <th><?php echo lang('message_attachment'); ?></th>
                    <th><?php echo lang('message_options'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>

                        <tr>
                            <td>
                                <?php if ($this->ion_auth->is_admin()): ?>
                                    <a href='<?php echo site_url(); ?>admin/viewProfile/<?php echo $message['user_from']; ?>'><?php echo ($message['first_name'] and $message['last_name']) ? $message['first_name'] . ' ' . $message['last_name'] : $message['username']; ?></a>
                                <?php else: ?>
                                    <?php echo ($message['first_name'] and $message['last_name']) ? $message['first_name'] . ' ' . $message['last_name'] : $message['username']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href='<?php echo site_url() ?>admin/messages/message/<?php echo $message['msg_id']; ?>'>
                                    <?php
                                    $sub = $message['subject'];
                                    echo word_limiter($sub, 3);
                                    ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                $msg = $message['message'];
                                echo word_limiter($msg, 3);
                                ?>
                            </td>
                            <td><?php echo $message['date_received']; ?></td>
                            <td>
                                <?php if ($message['status'] == 'Read'): ?>
                                    <span class="label label-success arrowed"><?php echo $message['status']; ?></span>
                                <?php else: ?>
                                    <span class="label label-inverse arrowed"><?php echo $message['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php $query = $this->db->where('attach_id', $message['attachment'])->get('attachments'); ?>
                                <?php if ($query->num_rows() > 0): ?>
                                    <?php foreach ($query->result() as $row): ?>
                                        <?php $file_name = $row->file_name; ?>
                                        <span class="label label-inverse arrowed arrowed-right">
                                            <a style="color:#fff;" href='<?php echo site_url(); ?>admin/messagesDownload/<?php echo $row->attach_id; ?>'>
                                                <i class='icon-paper-clip'></i> <?php echo lang('message_download'); ?>
                                            </a>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php $file_name = NULL; ?>
                                <?php endif; ?>
                            </td>
                            <td align="right">
                                <div class='btn-group'>
                                    <a href='<?= site_url() ?>admin/messagesReply/<?php echo $message['user_from']; ?>'>
                                        <button class='btn btn-mini btn-danger'><i class='icon-retweet'></i><?php echo lang('message_reply'); ?></button>
                                    </a>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                        <tr>
                            <td colspan="7"><?php echo lang('message_none'); ?></td>
                        </tr>
                <?php endif; ?>
            </tbody>

        </table>

    </div>

    <?php echo $pagination; ?>

</div> <!--/content-->

<?php echo $footer; ?>
