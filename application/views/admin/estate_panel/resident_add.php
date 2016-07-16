<?php echo $header;
echo $sidebar ?>		
<div class="span10 content">
<?php echo $hugemenu ?>
    <h1><?php echo ($name['value']) ? lang('estate_resident_update_head') : lang('estate_resident_add_head') ;?></h1>

	
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/jquery-ui.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/jquery-ui.js"></script>
            
	<script>
  $(function() {
  
  
  
    $("#moveintime").datepicker( {
        dateFormat:"yy-mm-dd"
    });
	 $("#moveouttime").datepicker({
        dateFormat:"yy-mm-dd"
    });
  });
  </script>
  <style>
  
  .leftflow{ float:left; width:80px;}
  </style>
	
<?php if ($message) { ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
        </div>
    <?php } ?>
<?php if ($success) { ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
        </div>
<?php } 

?><br>
    <div class="alert alert-info"><?php echo lang('estate_resident_add_message'); ?></div>

    <div class="contentArea">
        <div class="widget">
            <div class="widget-head"><h3 class="heading"><?php echo lang('resident_add_h2_title') ?></h3></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
<?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
                        <div class="control-group">
                            
							
                            <label class="control-label"><?php echo lang('estate_resident_apartment', 'Apartment'); ?></label>
                            <div class="controls">
                                <?php $apartment['class'] = 'span12';
								
									foreach ($estateapartment as $lan): 
									
										if($this->data[apartment][value]==$lan->id)
										{
											$option .="<option selected='selected' value='$lan->id'>$lan->name</option>";
										}
										else
										{
											$option .="<option value='$lan->id'>$lan->name</option>";
										}
									endforeach; 
																			
			
									?>  
								<select id="apartment" name="apartment">
								<?php echo $option; ?>
								</select>
                            </div>
							<br>
							<label class="control-label"><?php echo lang('estate_resident_name', 'Name'); ?></label>
							<div class="controls">
                                <?php $name['class'] = 'span12';
                                echo form_input($name);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_resident_phonenumber', 'Phone Number'); ?></label>
                            <div class="controls">
                                <?php $phonenumber['class'] = 'span12';
                                echo form_input($phonenumber);
                                ?>
                            </div><br>
							<label class="control-label"><?php echo lang('estate_resident_email', 'Email'); ?></label>
							<div class="controls">
                                <?php $email['class'] = 'span12';
                                echo form_input($email);
                                ?>
                            </div><br>
                            <label class="control-label"><?php echo lang('estate_resident_moveintime', 'Move In Time'); ?></label>
                            <div class="controls">
                                <?php $moveintime['class'] = 'span12';
								$moveintime['style'] = 'width:150px;float:left;';
                                echo form_input($moveintime);
								
								
								$hours = array(
												"00"=>"00",
												"01"=>"01",
												"02"=>"02",
												"03"=>"03",
												"04"=>"04",
												"05"=>"05",
												"06"=>"06",
												"07"=>"07",
												"08"=>"08",
												"09"=>"09",
												"10"=>"10",
												"11"=>"11",
												"12"=>"12",
												"13"=>"13",
												"14"=>"14",
												"15"=>"15",
												"16"=>"16",
												"17"=>"17",
												"18"=>"18",
												"09"=>"19",
												"20"=>"20",
												"21"=>"21",
												"22"=>"22",
												"23"=>"23"
												);
								$mm = array(
												"01"=>"01",
												"02"=>"02",
												"03"=>"03",
												"04"=>"04",
												"05"=>"05",
												"06"=>"06",
												"07"=>"07",
												"08"=>"08",
												"09"=>"09",
												"10"=>"10",
												"11"=>"11",
												"12"=>"12",
												"13"=>"13",
												"14"=>"14",
												"15"=>"15",
												"16"=>"16",
												"17"=>"17",
												"18"=>"18",
												"19"=>"19",
												"20"=>"20",
												"21"=>"21",
												"22"=>"22",
												"23"=>"23",
												"24"=>"24",
												"25"=>"25",
												"26"=>"26",
												"27"=>"27",
												"28"=>"28",
												"29"=>"29",
												"30"=>"30",
												"31"=>"31",
												"32"=>"32",
												"33"=>"33",
												"34"=>"34",
												"35"=>"35",
												"36"=>"36",
												"37"=>"37",
												"38"=>"38",
												"39"=>"39",
												"40"=>"40",
												"41"=>"41",
												"42"=>"42",
												"43"=>"43",
												"44"=>"44",
												"45"=>"45",
												"46"=>"46",
												"47"=>"47",
												"48"=>"48",
												"49"=>"49",
												"50"=>"50",
												"51"=>"51",
												"52"=>"52",
												"53"=>"53",
												"54"=>"54",
												"55"=>"55",
												"56"=>"56",
												"57"=>"57",
												"58"=>"58",
												"59"=>"59",
												"60"=>"60"
												
											);			
								
                              
								?>
									<select name="movein_hour" id="movein_hour" class="leftflow">
										<option value="">HH</option>
										<?php
										
											foreach($hours as $key => $value) {
										
											if($this->data['movein_hour']['value']==$key)
											{
												$optionsinhr .="<option selected='selected' value='$key'>$value</option>";
											}
											else
											{
												$optionsinhr .="<option value='$key'>$value</option>";
											}
										
										}  echo $optionsinhr; ?>
									</select>
									<select name="movein_min" id="movein_min"  class="leftflow">
										<option value="">MM</option>
										<?php
										foreach($mm as $key1 => $value1) {
										
											if($this->data[movein_min][value]==$key1)
											{
												$optionsinmm .="<option selected='selected' value='$key1'>$value1</option>";
											}
											else
											{
												$optionsinmm .="<option value='$key1'>$value1</option>";
											}
										
										} echo $optionsinmm; ?>
									</select> 
								
                            </div>
							<br><br><br>
                            <label class="control-label"><?php echo lang('estate_resident_moveouttime', 'Move Out Time'); ?></label>
                            <div class="controls">
                                <?php $moveouttime['class'] = 'span12';
								$moveouttime['style'] = 'width:150px;float:left;';
                                echo form_input($moveouttime);
                                ?>
								<select name="moveout_hour" id="moveout_hour" class="leftflow">
										<option value="">HH</option>
										<?php
										foreach($hours as $key => $value) {
										
											if($this->data[moveout_hour][value]==$key)
											{
												$optionsouthr .="<option selected='selected' value='$key'>$value</option>";
											}
											else
											{
												$optionsouthr .="<option value='$key'>$value</option>";
											}
										} echo $optionsouthr; ?>
									</select>
									<select name="moveout_min" id="moveout_min" class="leftflow">
										<option value="">MM</option>
										<?php
										foreach($mm as $key1 => $value1) {
										
											if($this->data[moveout_min][value]==$key1)
											{
												$optionsoutmm .="<option selected='selected' value='$key1'>$value1</option>";
											}
											else
											{
												$optionsoutmm .="<option value='$key1'>$value1</option>";
											}
										
										} echo $optionsoutmm; ?>
									</select> 
									
                            </div>
							<br><br><br>
                            <label class="control-label"><?php echo lang('estate_resident_totalrent', 'Rent'); ?></label>
                            <div class="controls">
                                <?php $rent['class'] = 'span12';
                                echo form_input($rent);
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
<?php echo form_submit('submit',($name['value']) ? lang('estate_resident_update_head') : lang('estate_resident_add_head'), 'class="btn btn-info span6"'); ?>
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