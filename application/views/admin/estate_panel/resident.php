<?php echo $header;echo $sidebar ?>		
		<div class="span10 content">	
			<?php echo $hugemenu ?> 
			<h1><?php echo lang('sidebar_pmp_resident'); ?></h1> 
			<?php if ($this->session->flashdata('message')) { ?>
					<div class="alert alert-success">
						<?php echo $this->session->flashdata('message') ?> 
					</div>   
			<?php } ?> 
		<div>   
			<table class="table table-striped table-bordered table-hover table-primary">
				<thead> 
				<tr>   
					<th><?php echo lang('estate_resident_id'); ?></th>
					<th><?php echo lang('estate_resident_name'); ?></th>
					<th><?php echo lang('estate_resident_phonenumber'); ?></th>
					<th><?php echo lang('estate_resident_email'); ?></th>
					<th><?php echo lang('estate_resident_moveintime'); ?></th>
					<th><?php echo lang('estate_resident_moveouttime'); ?></th>
					<th><?php echo lang('estate_resident_totalrent'); ?></th>
					<th class="action"><?php echo lang('estate_add_selectbox_action'); ?></th> 
				</tr>    
			</thead> 
			<tbody>  
				<?php foreach ($estateresident as $lan): ?>   
				<tr>    
					<td><?php echo $lan->id; ?></td>
					<td><?php echo $lan->name; ?></td>
					<td><?php echo $lan->phone_number; ?></td>
					<td><?php echo $lan->email; ?></td>
					<td><?php echo $lan->move_in_time; ?></td>
					<td><?php echo $lan->move_out_time; ?></td>
					<td><?php echo $lan->rent; ?></td>
					<td class="action">   
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_resident_message_edit') ?>" href="<?php echo site_url() . "admin/estateResidentAdd/" . $lan->id ?>">
							<i class="icon-edit"></i>
						</a> 
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_resident_message_delete') ?>" href="<?php echo site_url() . 'admin/estateDeleteResident/' . $lan->id ?>" role="button" data-bb="confirm">
							<i class="icon-trash"></i>
						</a>   
					</td>
				</tr>    
				<?php endforeach; ?>  
			</tbody>  
			</table>  
		</div>   
		<?php echo $pagination ?>  
		</div><!--/content-->
		<?php echo $footer ?>