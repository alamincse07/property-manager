<?php $this->load->view('auth/header')?>

<div class="loginFormInner">
	<div class="loginTitle"><?php echo lang('forgot_password_heading');?></div>
	<?php if($message){ ?>
    <div class="alert alert-error">
		<a class="close" data-dismiss="alert" href="#">x</a><?php echo $message;?>
    </div>
    <?php } 
    $email = array_merge($email,array('placeholder' => 'email','class' => 'span5')) 
    ?>
    <?php echo form_open("auth/forgot_password");?>
    <div class="alert alert-info">
    	<a class="close" data-dismiss="alert" href="#">x</a>
    	<?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?>
    </div>
	<?php echo form_input($email);?>
	<div class="loginButton">
       	<a href="<?php echo site_url() ?>/login" class="btn btn-success"><?php echo lang('login_submit_btn');?></a> 
		<?php echo form_submit('submit', lang('forgot_password_submit_btn'),'class="btn btn-info span2"');?>
	</div>
	<?php echo form_close();?>  
</div>

<?php $this->load->view('auth/footer') ?>