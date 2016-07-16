<?php echo $header;
echo $sidebar ?>		
<div class="span10 content">
<?php echo $hugemenu ?>
    <h1><?php echo ($name['value']) ? lang('estate_apartment_update_head') : lang('estate_apartment_add_head') ;?></h1>

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
    <div class="alert alert-info"><?php echo lang('estate_apartment_add_message'); ?></div>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('apartment_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
<?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('estate_apartment_name', 'Name'); ?></label>
							<div class="controls">
                                <?php $name['class'] = 'span12';
                                echo form_input($name);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_apartment_address', 'Address'); ?></label>
                            <div class="controls">
                                <?php $address['class'] = 'span12';
                                echo form_input($address);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_apartment_state', 'State'); ?></label>
							<div class="controls">
                                <?php $state['class'] = 'span12';
                                echo form_input($state);
                                ?>
                            </div><br>
                            <label class="control-label"><?php echo lang('estate_apartment_city', 'City'); ?></label>
                            <div class="controls">
                                <?php $city['class'] = 'span12';
                                echo form_input($city);
                                ?>
                            </div>
							<br>
                            <label class="control-label"><?php echo lang('estate_apartment_zipcode', 'Zip Code'); ?></label>
                            <div class="controls">
                                <?php $zipcode['class'] = 'span12';
                                echo form_input($zipcode);
                                ?>
                            </div>
							<br>
                            <label class="control-label"><?php echo lang('estate_apartment_landlord', 'landlord'); ?></label>
                            <div class="controls">
                                <?php $lanlord['class'] = 'span12';
								
									foreach ($estatelanlord as $lan): 
									
										if($this->data[landlord][value]==$lan->id)
										{
											$option .="<option selected='selected' value='$lan->id'>$lan->name</option>";
										}
										else
										{
											$option .="<option value='$lan->id'>$lan->name</option>";
										}
									endforeach; 
																			
			
									?>  
								<select id="landlord" name="landlord">
								<?php echo $option; ?>
								</select>
                            </div>
							<br>
                            <label class="control-label"><?php echo lang('estate_apartment_add_rent', 'Total Rent'); ?></label>
                            <div class="controls">
                                <?php $rent['class'] = 'span12';
                                echo form_input($rent);
                                ?>
                            </div>
				<br>
                            <label class="control-label"><?php echo lang('estate_apartment_longtitude', 'Longtitude'); ?></label>
                            <div class="controls">
                                <?php $logtitude['class'] = 'span12';
                                echo form_input($logtitude);
                                ?>
                            </div>
                           <br/>
                           <label class="control-label"><?php echo lang('estate_apartment_latitude', 'Latitude'); ?></label>
                            <div class="controls">
                                <?php $latitude['class'] = 'span12';
                                echo form_input($latitude);
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
<?php echo form_submit('submit',($name['value']) ? lang('estate_apartment_update_head') : lang('estate_apartment_add_head'), 'class="btn btn-info span8"'); ?>
                            </div>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
<?php echo form_close(); ?>	
    </div>

</div>
<!--/content-->

<?php echo $footer ?>