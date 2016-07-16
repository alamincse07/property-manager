<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('message_sent_title'); ?></h1>

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
        <table id="table_report" class="table table-striped table-bordered table-hover table-primary">
            <thead>

                <tr>
                    <th><?= lang('message_recipient') ?></th>
                    <th style="width: 20%;"><?= lang('message_subject') ?></th>
                    <th style="width: 30%;"><?= lang('message_message') ?></th>
                    <th><?= lang('message_date') ?> <?= lang('message_sent') ?></th>
                    <th><?= lang('message_attachment') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>

                        <tr>
                            <td>
                                <?php if ($this->ion_auth->is_admin()): ?>
                                    <a href='<?= site_url() ?>admin/viewProfile/<?= $message['user_to'] ?>'><?= ($message['first_name'] and $message['last_name']) ? $message['first_name'] . ' ' . $message['last_name'] : $message['username'] ?></a>
                                <?php else: ?>
                                    <?= ($message['first_name'] and $message['last_name']) ? $message['first_name'] . ' ' . $message['last_name'] : $message['username'] ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href='<?= site_url() ?>admin/messages/view_sent/<?= $message['msg_id'] ?>'>
                                    <?php
                                    $sub = $message['subject'];
                                    echo word_limiter($sub, 3);
                                    ?>
                                </a>
                            </td>
                            <td><?php
                                $msg = $message['message'];
                                echo word_limiter($msg, 3);
                                ?></td>
                            <td><?= $message['date_received'] ?></td>
                            <td>
                                <?php
                                $query = $this->db->where('attach_id', $message['attachment'])->get('attachments');
                                if ($query->num_rows() > 0) {
                                    foreach ($query->result() as $row) {
                                        $file_name = $row->file_name;
                                        ?>
                                        <span class="label label-inverse arrowed arrowed-right">
                                            <a style="color:#fff;" href='<?= site_url() ?>admin/messagesDownload/<?= $row->attach_id; ?>'>
                                                <i class='icon-paper-clip'></i> <?= lang('message_download') ?>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                } else {
                                    $file_name = NULL;
                                }
                                ?></td>

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
