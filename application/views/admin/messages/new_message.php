<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('message_new_message_title'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?= $this->session->flashdata('message') ?></p>
        </div>
    <?php endif; ?>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('message_new_message_title') ?></h3></div>

            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">

                        <form class="form-horizontal" role="form" action="<?= site_url() ?>admin/messagesInsert" method="post"  enctype="multipart/form-data" >

                            <div class="control-group">
                                <label class="control-label" for="form-field-1"><?= lang('message_recipient') ?></label>
                                <div class="controls">
                                    <div class="input-prepend">
                                        <input type="text" class="span12" name="user_to" required="required" placeholder="<?= lang('message_username') ?>"  autocomplete="off" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='[<?php
                                        $this->db->where('username !=', $this->ion_auth->user()->row()->username);
                                        $users = $this->ion_auth->users()->result();
                                        if (!empty($users)) {
                                            foreach ($users as $row) {
                                                echo '"' . $row->username . '",';
                                            }
                                        }
                                        ?>"null"]'>
                                        <span class="add-on"><i class="icon-user"></i></span>
                                    </div><span class="help-inline"><?= lang('message_start_typing_username') ?></span>

                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="form-field-1"><?= lang('message_subject') ?></label>
                                <div class="controls">
                                    <div class="input-prepend">
                                        <input type="text" required="required" name="subject" id="form-field-1" class="span12" placeholder="<?= lang('message_subject') ?>" >
                                        <span class="add-on"><i class="icon-coffee"></i></span>
                                    </div>

                                </div>
                            </div>
                            <div class="control-group">
                                <div id="attachment">
                                    <label class="control-label" for="userfile">
                                        <?= lang('message_attach') ?> <?= lang('message_file') ?>
                                    </label>
                                    <div class="controls">
                                        <?php echo form_upload('userfile', '', 'id="userfile"'); ?> 
                                    </div>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="message"><?= lang('message_message') ?></label>
                                <div class="controls">
                                    <div class="input-prepend">
                                        <textarea name="message" class="span12" rows="5" id="form-field-8" placeholder="<?= lang('message_message') ?>" required="required"></textarea>
                                        <span class="add-on"><i class="icon-envelope"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button class="btn btn-info span4" type="submit"><i class="icon-ok"></i> <?= lang('message_send') ?></button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn span4" type="reset"><i class="icon-undo"></i> <?= lang('message_reset') ?></button>

                            </div>

                        </form>
                        <a style="color:#fff;" href='<?= site_url() ?>admin/messages/inbox'>
                            <button class="btn btn-medium btn-inverse span4">
                                <i class="icon icon-chevron-down"></i> <?= lang('message_inbox') ?> <?= lang('message_messages') ?></button>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!--/content-->

<?php echo $footer; ?>
