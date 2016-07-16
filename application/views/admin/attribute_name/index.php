<?php 
	echo $header;
	echo $sidebar; 
?>
<div class="span10 content">
	<?php echo $hugemenu;?>
	<h1><?php echo lang('sidebar_estate_attribute_name'); ?></h1>
	<div class="btn-toolbar">
		<a class="btn" href="<?php echo site_url();?>admin/estateAttributeManage"><i class="icon-plus"></i> <?php echo lang('estate_attr_name_addbtn')?></a>
	</div>
	<div>
		<table class="table table-striped table-bordered table-hover table-primary">
			<thead>
				<tr>
					<th><?php echo lang('sidebar_estate_attr_id'); ?></th>
					<th><?php echo lang('sidebar_estate_attr_name'); ?></th>
					<th><?php echo lang('sidebar_estate_attr_position'); ?></th>
					<th><?php echo lang('sidebar_estate_attr_action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($attribute_name as $row){
				?>
				<tr>
					<td><?php echo $row['id'];?></td>
					<td><?php echo $row['name'];?></td>
					<td><?php echo $row['position'];?></td>
					<td class="action">
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_attr_name_message_edit') ?>" href="<?php echo site_url() . "admin/estateAttributeEdit/" . $row['id']; ?>"><i class="icon-edit"></i></a>
						<a class="tooltp" data-toggle="tooltip" title="<?php echo lang('estate_attr_name_message_delete') ?>" href="<?php echo site_url() . 'admin/estateDeleteCat/' . $row['id']; ?>" role="button" data-bb="confirm"><i class="icon-trash"></i></a> 
					</td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<?php #echo $pagination ?>        
</div>
<!--/content-->
<?php echo $footer ?>