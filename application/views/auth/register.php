<?php $this->load->view('auth/header') ?>

<div class="loginFormInner registerForm">
	<div class="loginTitle"><?php echo lang('create_user_heading');?></div>
	<?php if($message){ ?>
    <div class="alert alert-error">
		<a class="close" data-dismiss="alert" href="#">x</a><?php echo $message;?>
    </div>
    <?php } ?>
    
    <?php echo form_open("auth/register",array('class' => 'form-horizontal'));?>
    <div class="alert alert-info">
    	<a class="close" data-dismiss="alert" href="#">x</a>
    	<?php echo lang('create_user_subheading');?>
    </div>
	<div class="control-group">
		<label class="control-label"><?php echo lang('create_user_fname_label', 'first_name');?></label>
		<div class="controls">
			<?php $first_name['class'] = 'span5'; echo form_input($first_name);?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo lang('create_user_lname_label', 'last_name');?></label>
		<div class="controls">
			<?php $last_name['class'] = 'span5'; echo form_input($last_name);?>
		</div>
	</div>
	<!--<div class="control-group">
		<label class="control-label"><?php /*echo lang('create_user_validation_username_label', 'username');*/?></label>
		<div class="controls">
			<?php /*$username['class'] = 'span5'; echo form_input($username);*/?>
		</div>
	</div>-->
	<div class="control-group">
		<label class="control-label"><?php echo lang('create_user_email_label', 'email');?></label>
		<div class="controls">
			<?php $email['class'] = 'span5'; echo form_input($email);?>
		</div>
	</div>
	<!--<div class="control-group">
		<label class="control-label"><?php /*echo lang('create_user_password_label', 'password');*/?></label>
		<div class="controls">
			<?php /*$password['class'] = 'span5'; echo form_input($password);*/?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php /*echo lang('create_user_password_confirm_label', 'password_confirm');*/?></label>
		<div class="controls">
			<?php /*$password_confirm['class'] = 'span5'; echo form_input($password_confirm);*/?>
		</div>
	</div>-->
	<div class="control-group">
		<label class="control-label"><?php echo lang('create_user_company_label', 'company');?></label>
		<div class="controls">
			<?php $company['class'] = 'span5'; echo form_input($company);?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo lang('create_user_phone_label', 'phone');?></label>
		<div class="controls">
			<?php $phone1['class'] = 'span1';$phone1['maxlength'] = '3'; echo form_input($phone1);?>
			<?php $phone2['class'] = 'span4';$phone2['maxlength'] = '17'; echo form_input($phone2);?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"></label>
      	<div class="controls">
       		<?php echo form_submit('submit', 'Register','class="btn btn-success span3"');?>

      	    <!--<a href="<?php /*echo site_url() */?>/login" class="btn btn-info"><?php /*echo lang('login_submit_btn');*/?></a>-->
      	</div>
	</div>
	<?php echo form_close();?>	
</div>

<?php $this->load->view('auth/footer')?>