<?php echo $header;echo $sidebar ?>
<div class="span10 content">
	<?php echo $hugemenu ?>
	
	<h1><?php echo lang('estate_attrib'); ?></h1>
	<div class="btn-toolbar">                
	
		<a class="btn" href="<?php echo site_url();?>admin/estateAttribAdd"><i class="icon-plus"></i> <?php echo lang('estate_attrib_addbtn')?></a>
	</div>                        
	
	<?php if ($this->session->flashdata('message')) { ?>                
		<div class="alert alert-success">                    
			<?php echo $this->session->flashdata('message') ?>                
		</div>            
	<?php } ?>            
	
	<div>                
		<table class="table table-striped table-bordered table-hover table-primary">                    
			<thead>                        
				<tr>                            
					<th><?php echo lang('estate_attrib_id'); ?></th>                            
					<th><?php echo lang('estate_attrib_name'); ?></th>                            
					<th class="action"><?php echo lang('estate_add_selectbox_action'); ?></th>                       
				</tr>                    
			</thead>                    
			<tbody>                        
				<?php foreach ($estateattrib as $attrib): ?>                            
				<tr>                                
					<td><?php echo $attrib->eaid; ?></td>
					<td><?php echo $attrib->attribName; ?></td>
					<td class="action">
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_attrib_tooltip_edit') ?>" href="<?php echo site_url() . "admin/estateAttribAdd/" . $attrib->eaid ?>"><i class="icon-edit"></i></a>
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_attrib_tooltip_delete') ?>" href="<?php echo site_url() . 'admin/estateDeleteAttrib/' . $attrib->eaid ?>" role="button" data-bb="confirm"><i class="icon-trash"></i></a>
					</td>
				</tr>
				<?php endforeach; ?>                    
			</tbody>
		</table>
	</div>
	
	<?php echo $pagination ?>
</div><!--/content-->

<?php echo $footer ?>