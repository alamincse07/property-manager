<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('message_reply_title'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?= $this->session->flashdata('message') ?></p>
        </div>
    <?php endif; ?>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('message_reply_title') ?></h3></div>

            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <form class="form-horizontal" action="<?= site_url() ?>admin/messagesReply" method="post" >
                            <input type="hidden" name="user_to" value="<?= $this->uri->segment(3) ?>" />
                            <div class="control-group">
                                <label class="control-label" for="form-field-1"><?= lang('message_subject') ?></label>
                                <div class="controls">
                                    <input type="text" required="required" name="subject" class="span12" id="form-field-1" placeholder="<?= lang('message_subject') ?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="form-field-1"><?= lang('message_message') ?></label>
                                <div class="controls">
                                    <textarea name="message" required="required" class="span12" id="form-field-8" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button class="btn btn-info span5" type="submit"><i class="icon-ok"></i> <?= lang('message_reply') ?></button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn span5" type="reset"><i class="icon-undo"></i> <?= lang('message_reset') ?></button>
                            </div>
                        </form>

                        <button class="btn btn-small btn-danger span4">
                            <a style="color:#fff;" href='<?= site_url() ?>admin/messages/inbox'>
                                <i class="icon icon-envelope"></i> <?= lang('message_inbox') ?> <?= lang('message_messages') ?>
                            </a>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!--/content-->

<?php echo $footer; ?>
