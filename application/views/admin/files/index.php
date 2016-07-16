<?php echo $header; ?>
<?php echo $sidebar; ?>		

<div class="span10 content">

    <?php echo $hugemenu; ?>
    
		<table id="table_report" class="table table-striped table-bordered table-hover table-primary">
			<thead>
				
				<tr>
					<th><i class='icon-user'></i><?=lang('owner')?></th>
					<th><i class='icon-folder-open'></i> <?=lang('file')?></th>
					<th><?=lang('file')?> <?=lang('name')?></th>
					<th><?=lang('private')?></th>
					<th><?=lang('memberships')?></th>
					<th><?=lang('options')?></th>
				</tr>
			</thead>
									
			<tbody>
				<?php
				if (!empty($files)) { ?>
				<?php
				foreach ($files as $key => $file) { ?>
				
				<tr>
					<td><a href='<?=site_url()?>/members/view/profile/<?=$file['id']?>'><?=$file['username']?></a></td>
					<td><?=$file['file']?></td>
					<td><?=$file['name']?></td>
					<td><span class="badge badge-pink"><?=$file['private'] ? 'Yes':'No'?></span></td>
					<td><span class="badge badge-purple"><?php
                                	foreach (unserialize($file['packages']) as $key => $value) {
			$this->db->where('m_id', $value); 
    		$this->db->select('membership');
    		$query = $this->db->get('fx_memberships');
    		foreach ($query->result() as $row)
			{
				$package_name= $row->membership;
				echo '<li>'.$package_name.'</li>';
			}
									}
									
                                	?></span></td>
					<td align="right">
						<div class='btn-group'>
							<a href='<?=site_url()?>admin/files/edit/<?=$file['file_id']?>'>
							<button class='btn btn-mini btn-inverse'><i class='icon-pencil'></i><?=lang('edit')?></button>
							</a>
							<a href='<?=site_url()?>admin/files/view/file/<?=$file['file_id']*1200?>'>
							<button class='btn btn-mini btn-yellow'><i class='icon-folder-open'></i><?=lang('view')?></button>
							</a>
							<a href='<?=site_url()?>admin/files/download/<?=$file['file_id']?>'>
							<button class='btn btn-mini btn-pink'><i class='icon-download'></i><?=lang('download')?></button>
							</a>
						</div>
					</td>
				</tr>
					<?php
				}
				?>
				
				<?php }else{
					
				}
				?>
				
							</tbody>
		</table>
		<a href='<?=site_url()?>admin/files/view/add_file'>
		<button class="btn btn-medium btn-danger"><i class='icon-cloud-upload'></i><?=$this->lang->line('upload')?> <?=$this->lang->line('file')?></button>
		</a>
    
<?php echo $pagination['links']; ?>

</div> <!-- .content -->

<?php echo $footer; ?>
