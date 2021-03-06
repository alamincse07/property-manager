<?php echo $header;echo $sidebar ?>		
		<div class="span10 content">	
			<?php echo $hugemenu ?> 
			<h1><?php echo lang('sidebar_pmp_apartment'); ?></h1> 
			<!--div class="btn-toolbar">  
				<a class="btn" href="<?php echo site_url();?>admin/estateLanlordAdd">
					<i class="icon-plus"></i>
					<?php // echo lang('estate_lanlord_addbtn')?></a>  
			</div-->      
			<?php if ($this->session->flashdata('message')) { ?>
					<div class="alert alert-success">
						<?php echo $this->session->flashdata('message') ?> 
					</div>   
			<?php } ?> 
		<div>   
			<table class="table table-striped table-bordered table-hover table-primary">
				<thead> 
				<tr>   
					<th><?php echo lang('estate_apartment_id'); ?></th>
					<th><?php echo lang('estate_apartment_name'); ?></th>
					<th><?php echo lang('estate_apartment_address'); ?></th>
					<th><?php echo lang('estate_apartment_city'); ?></th>
					<th class="action"><?php echo lang('estate_add_selectbox_action'); ?></th> 
				</tr>    
			</thead> 
			<tbody>  
				<?php foreach ($estateapartment as $lan): ?>   
				<tr>    
					<td><?php echo $lan->id; ?></td>
					<td><?php echo $lan->name; ?></td>
					<td><?php echo $lan->address; ?></td>
					<td><?php echo $lan->city; ?></td>
					<td class="action">   
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_apartment_message_edit') ?>" href="<?php echo site_url() . "admin/estateApartmentAdd/" . $lan->id ?>">
							<i class="icon-edit"></i>
						</a> 
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_apartment_message_delete') ?>" href="<?php echo site_url() . 'admin/estateDeleteApartment/' . $lan->id ?>" role="button" data-bb="confirm">
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