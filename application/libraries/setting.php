<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting 
{

	public function getSetting() 
	{
		
		$setting = $this->get_setting();
		//$logo = $setting->logo;
		return $setting;	
		
	}
	
	private function get_setting() 
	{
		$CI =& get_instance();

		$CI->load->database();
		
		$q = $CI->db->get('settings'); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	
}

