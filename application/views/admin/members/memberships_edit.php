
<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>

    <h1><?php echo lang('edit'); ?> <?php echo lang('membership'); ?></h1>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php endif; ?>


    <div class="widget">
        <div class="widget-head">
            <h3 class="heading"><?php echo $membership_profile['membership']; ?></h3>
        </div>

        <div class="widget-body">
            <form class="form-horizontal" action="<?php echo site_url('admin/membershipsEdit') ?>" method="post"  >
                <input type="hidden" name="m_id"  value="<?php echo $membership_profile['m_id']; ?>"/>
                <div class="control-group">
                    <label class="control-label" for="form-field-1"><?php echo lang('membership') ?> <?php echo lang('name') ?></label>
                    <div class="controls">
                        <div class="input-prepend">
                            <input type="text" class="span12" name="membership" required="required" value="<?php echo $membership_profile['membership']; ?>" >
                            <span class="add-on"><i class="icon-certificate"></i></span>
                        </div>

                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-field-1"><?php echo lang('amount') ?></label>
                    <div class="controls">
                        <div class="input-prepend">
                            <input type="text" name="amount" id="form-field-1" class="span12" value="<?php echo $membership_profile['amount']; ?>"  >
                            <span class="add-on"><i class="icon-star orange"></i></span>
                        </div>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="form-field-1"><?php echo lang('valid') ?> <?php echo lang('days') ?></label>
                    <div class="controls">
                        <div class="input-prepend">
                            <input type="text" name="days" id="form-field-1" class="span12" value="<?php echo $membership_profile['valid_days']; ?>"  >
                            <span class="add-on"><i class="icon-time orange"></i></span>
                        </div>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="form-field-1"><?php echo lang('description') ?></label>
                    <div class="controls">
                        <span class="span6 input-icon input-icon-right">
                            <textarea name="description" class="span12" rows="10" id="form-field-8"><?php echo $membership_profile['description']; ?></textarea>
                        </span>

                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-info" type="submit"><i class="icon-ok"></i> Save</button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset"><i class="icon-undo"></i> <?php echo lang('reset') ?></button>

                </div>

            </form>
            <a style="color:#fff;" href="<?php echo site_url('admin/memberships') ?>">
                <button class="btn btn-medium btn-inverse">
                    <i class="icon icon-certificate"></i> <?php echo lang('memberships') ?></button>
            </a>
        </div>
    </div>

</div><!--/content-->

<?php echo $footer; ?>