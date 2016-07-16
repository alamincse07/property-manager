<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('message_detail_title'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?= $this->session->flashdata('message') ?></p>
        </div>
    <?php endif; ?>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('message_detail_title') ?></h3></div>

            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span12">

                        <?php if (!empty($msg_details)): ?>
                            <?php foreach ($msg_details as $message): ?>

                                <form class="form-horizontal" method="post">
                                    <?= lang('message_from_name') ?> : <strong><?= ($message['first_name'] and $message['last_name']) ? $message['first_name'] . ' ' . $message['last_name'] : $message['username'] ?></strong>
                                    <hr />
                                    <?= lang('message_subject') ?> : <strong> <?= $message['subject'] ?> </strong>
                                    <hr />
                                    <?= lang('message_message') ?> : <?= $message['message'] ?>
                                </form>

                                <hr />

                                <?php if ($message['attachment'] != 0): ?>
                                    <a style="color:#fff;" href='<?= site_url() ?>admin/messagesDownload/<?= $message['attachment']; ?>'>
                                        <button class='btn btn-mini btn-inverse span2'><i class='icon-paper-clip'></i> <?= lang('message_download') ?> <?= lang('message_file') ?></button>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <a href='<?= site_url() ?>admin/messagesReply/<?= $message['user_from'] ?>'>
                                <button class="btn btn-mini btn-danger span2" style="margin-left: 10px !important;">
                                    <i class="icon-retweet"></i>
                                    <?= lang('message_reply') ?>
                                </button>
                            </a>
                            <a href='<?= site_url() ?>admin/messagesDelete/<?= $message['msg_id'] ?>' role="button" data-bb="confirm">
                                <button class="btn btn-mini btn-warning span2" style="margin-left: 10px !important;" id="message_delete">
                                    <i class="icon-trash"></i>
                                    <?= lang('message_delete') ?>
                                </button>
                            </a>
                        <?php else: ?>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!--/content-->

<?php echo $footer; ?>