<?php $this->load->view('auth/header')?>

<div class="loginFormInner registerForm">
	<div class="loginTitle"><?php echo lang('reset_password_heading');?></div>
	<?php if($message){ ?>
    <div class="alert alert-error">
		<a class="close" data-dismiss="alert" href="#">x</a><?php echo $message;?>
    </div>
    <?php } ?>
    <?php echo form_open('auth/reset_password/' . $code,array('class' => 'form-horizontal'));?>
	<div class="control-group">
		<label class="control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
		<div class="controls">
			<?php $new_password['class'] = 'span5'; echo form_input($new_password);?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
		<div class="controls">
			<?php $new_password_confirm['class'] = 'span5'; echo form_input($new_password_confirm);?>
		</div>
	</div>
	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>
	<div class="loginButton">
       	<a href="<?php echo site_url() ?>/login" class="btn btn-success"><?php echo lang('login_submit_btn');?></a> 
		<?php echo form_submit('submit', lang('reset_password_submit_btn'),'class="btn btn-info span2"');?>
	</div>
	<?php echo form_close();?>  
</div>

<?php $this->load->view('auth/footer') ?>