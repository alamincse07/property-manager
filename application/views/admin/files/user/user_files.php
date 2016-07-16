<div class="row-fluid">
	<?php
if($this->session->flashdata('message')){ ?>
	<div class="alert alert-block alert-success">
			<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
			<p>
			<?=$this->session->flashdata('message')?>
			</p>
			
		</div>
	
<?php } ?>
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<thead>
				
				<tr>
					<th><?=lang('file')?> <?=lang('name')?></th>
					<th><i class='icon-folder-open'></i> <?=lang('file')?></th>
					<th><?=lang('options')?></th>
				</tr>
			</thead>
									
			<tbody>
				<?php
				if (!empty($files)) { ?>
				<?php
				foreach ($files as $key => $file) { ?>
				<?php
						$ar = unserialize($file['packages']);
						foreach ($ar as $key => $value) {
							if ($value == $this->tank_auth->membership()) { ?>
								<tr>
					<td><?=$file['name']?></td>
					<td><?=$file['file']?></td>
					<td align="right">
						<div class='btn-group'>
							
							<a href='<?=site_url()?>/files/view/file/<?=$file['file_id']*1200?>'>
							<button class='btn btn-mini btn-yellow'><i class='icon-folder-open'></i><?=lang('view')?></button>
							</a>
							<a href='<?=site_url()?>/files/user_files/download/<?=$file['file_id']?>'>
							<button class='btn btn-mini btn-pink'><i class='icon-download'></i><?=lang('download')?></button>
							</a>
						</div>
					</td>
				</tr>
								
								
							<?php }
						}
						?>
				
					<?php
				}
				?>
				
				<?php }else{
					
				}
				?>
				
							</tbody>
		</table>
</div>