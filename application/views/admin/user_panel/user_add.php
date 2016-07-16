<?php echo $header;echo $sidebar ?>		
		<div class="span10 content">
			<?php echo $hugemenu ?>

			<h1><?php echo lang('create_user_heading');?></h1>
			
			<?php if($message){ ?>
		    <div class="alert alert-error">
				<a class="close" data-dismiss="alert" href="#">x</a><?php echo $message;?>
		    </div>
		    <?php } ?>
			<?php if($success){ ?>
		    <div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#">x</a><?php echo $success;?>
		    </div>
		    <?php } ?>
		    
			<div class="alert alert-info"><?php echo lang('create_user_subheading');?></div>
			
			<div class="contentArea">	
			<?php echo form_open(current_url(),array('class' => 'form-horizontal'));?>
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('user_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_fname_label', 'first_name');?></label>
				<div class="controls">
					<?php $first_name['class'] = 'span'; echo form_input($first_name);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_lname_label', 'last_name');?></label>
				<div class="controls">
					<?php $last_name['class'] = 'span'; echo form_input($last_name);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_validation_username_label', 'username');?></label>
				<div class="controls">
					<?php $username['class'] = 'span'; echo form_input($username);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_email_label', 'email');?></label>
				<div class="controls">
					<?php $email['class'] = 'span'; echo form_input($email);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_password_label', 'password');?></label>
				<div class="controls">
					<?php $password['class'] = 'span'; echo form_input($password);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_password_confirm_label', 'password_confirm');?></label>
				<div class="controls">
					<?php $password_confirm['class'] = 'span'; echo form_input($password_confirm);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_company_label', 'company');?></label>
				<div class="controls">
					<?php $company['class'] = 'span'; echo form_input($company);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo lang('create_user_phone_label', 'phone1');?></label>
				<div class="controls">
					<?php $phone1['class'] = 'span2';$phone1['maxlength'] = '4'; echo form_input($phone1);?>
					<?php $phone2['class'] = 'span10';$phone2['maxlength'] = '15'; echo form_input($phone2);?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"></label>
		      	<div class="controls">
		       		<?php echo form_submit('submit', lang('create_user_submit_btn'),'class="btn btn-success span6"');?>
		      	</div>
			</div>
			</div>
			</div>
			</div>
			</div>
			<?php echo form_close();?>	
			
			
			</div>

		</div>
		<!--/content-->
<?php echo $footer ?>