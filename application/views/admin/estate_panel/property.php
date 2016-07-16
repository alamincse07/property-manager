<?php echo $header;echo $sidebar ?>	
			<div class="span10 content">			
			<?php echo $hugemenu ?>            
			
			<h1><?php echo lang('estate_propperty_head'); ?></h1>
			            
			<div class="btn-toolbar">                
				<a class="btn" href="<?php echo site_url();?>admin/estatePropertyAdd">
					<i class="icon-plus"></i> <?php echo lang('estate_propperty_addbtn')?>
				</a>            
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
							<th><?php echo lang('estate_add_selectbox_id'); ?></th>                            
							<th><?php echo lang('estate_property_grup'); ?></th>                            
							<th><?php echo lang('estate_property_name'); ?></th>                            
							<th class="action"><?php echo lang('estate_add_selectbox_action'); ?></th>                        
						</tr>                    
					</thead>                    
				
					<tbody>                        
						<?php foreach ($property as $prop): ?>
						<tr>                                
							<td><?php echo $prop->pfid; ?></td>
							<td><?php echo $attrib[$prop->typeID]; ?></td>                                
							<!-- <td><?php echo $prop->typeID."--".lang('estate_propperty_'.$prop->typeID); ?></td> -->
							<td><?php echo $prop->name; ?></td>                                
							<td class="action">
								<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_propperty_tooltip_edit') ?>" href="<?php echo site_url() . "admin/estatePropertyAdd/" . $prop->pfid ?>"><i class="icon-edit"></i></a>
								<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_propperty_tooltip_delete') ?>" href="<?php echo site_url() . 'admin/estateDeleteProperty/' . $prop->pfid ?>" role="button" data-bb="confirm"><i class="icon-trash"></i></a>
							</td>                            
						</tr>                        
						<?php endforeach; ?>                    
					</tbody>                
				</table>            
			</div>            
			
			<?php echo $pagination ?>
			</div><!--/content-->
			<?php echo $footer ?>