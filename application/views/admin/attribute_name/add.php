<?php 
	echo $header;
	echo $sidebar; 
?>
<div class="span10 content">
	<?php echo $hugemenu ?>
	<h1><?php echo lang('estate_attr_name_addbtn'); ?></h1>
	<?php if ($message) { ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
        </div>
    <?php } ?>
	<?php if ($success) { ?>
	        <div class="alert alert-success">
	            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
	        </div>
	<?php } ?>
	<div class="widget">
		<div class="alert alert-info"><?php echo lang('estate_attr_name_message'); ?></div>
		<div class="widget-head">
			<h3 class="heading">
				<?php 
					echo lang('estate_attr_name_addfrm');
				?>
			</h3>
		</div>
		<div class="widget-body">
			<?php echo form_open('admin/estateAttributeManage'); ?>
				<table width="100%" cellpadding="5" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td><?php echo lang('sidebar_estate_attr_name') ?></td>
							<td><input type="text" name="name" required class="span" value="<?php echo set_value('name')?>"></td>
						</tr>
						<tr>
							<td><?php echo lang('sidebar_estate_attr_position') ?></td>
							<td><input type="text" name="position" required class="span" value="<?php echo set_value('position')?>"></td>
						</tr>
						<tr>
							<td colspan="2">
								<button class="btn btn-primary" type="submit"><?php echo lang('btn_submit');?></button> 
								<button type="reset" class="btn btn-primary"><?php echo lang('btn_cancel');?></button>
							</td>
						</tr>
					</tbody>
				</table>
			<?php echo form_close(); ?>	
		</div>
	</div>
</div>