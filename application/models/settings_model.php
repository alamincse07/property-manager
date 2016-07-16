<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	STOCK MANAGER ADVANCE 
| -----------------------------------------------------
| AUTHER:			MIAN SALEEM 
| -----------------------------------------------------
| EMAIL:			saleem@tecdiary.com 
| -----------------------------------------------------
| COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
| -----------------------------------------------------
| WEBSITE:			http://tecdiary.net
| -----------------------------------------------------
|
| MODULE: 			Products
| -----------------------------------------------------
| This is products module model file.
| -----------------------------------------------------
*/


class Settings_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	
	public function updateLogo($photo)
	{

			$logo = array(
				'logo'	     			=> $photo
			);
			
		if($this->db->update('settings', $logo)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateInvoiceLogo($photo)
	{

			$logo = array(
				'invoice_logo'	     			=> $photo
			);
			
		if($this->db->update('settings', $logo)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getSettings() 
	{
				
		$q = $this->db->get('settings'); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	public function getDateFormats() 
	{
		$q = $this->db->get('date_format');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function updateSetting($data)
	{
		
		$this->db->where('setting_id', '1');
		if($this->db->update('settings', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addTaxRate($data)
	{

			$taxData = array(
				'name'	     	=> $data['name'],
				'rate' 			=> $data['rate'],
				'type' 			=> $data['type']
			);

		if($this->db->insert('tax_rates', $taxData)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateTaxRate($id, $data = array())
	{
		
		$taxData = array(
				'name'	     	=> $data['name'],
				'rate' 			=> $data['rate'],
				'type' 			=> $data['type']
			);
			
		$this->db->where('id', $id);
		if($this->db->update('tax_rates', $taxData)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getAllTaxRates() 
	{
		$q = $this->db->get('tax_rates');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getTaxRateByID($id) 
	{

		$q = $this->db->get_where('tax_rates', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	public function updateCompany($data = array())
	{
		
		
		// company data
		$companyData = array(
		    'name'	     		=> $data['name'],
		    'email'   			=> $data['email'],
			'company'      		=> $data['company'],
		    'cf1'      			=> $data['cf1'],
			'cf2'      			=> $data['cf2'],
			'cf3'      			=> $data['cf3'],
			'cf4'      			=> $data['cf4'],
			'cf5'      			=> $data['cf5'],
			'cf6'      			=> $data['cf6'],
		    'address' 			=> $data['address'],
			'city'	     		=> $data['city'],
		    'state'   			=> $data['state'],
		    'postal_code'   	=> $data['postal_code'],
		    'country' 			=> $data['country'],
			'phone'	     		=> $data['phone']

		);
		$this->db->where('id', 1);
		if($this->db->update('company', $companyData)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCompanyDetails() 
	{

		$q = $this->db->get_where('company', array('id' => 1), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}

	
	public function deleteTaxRate($id) 
	{
		if($this->db->delete('tax_rates', array('id' => $id))) {
			return true;
		}
	return FALSE;
	}
	

}
