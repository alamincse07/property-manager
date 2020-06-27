<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Ramazan APAYDIN
 *         apaydinweb@gmail.com
 *
 * Website: http://ramazanapaydin.com
 *
 * Created:  04.15.2013
 */
class admin_estate_model extends CI_Model
{

    /**
     * Limit
     *
     * @var string
     * */
    public $_ion_limit = NULL;

    /**
     * Offset
     *
     * @var string
     * */
    public $_ion_offset = NULL;

    /**
     * message (uses lang file)
     *
     * @var string
     * */
    protected $messages;

    /**
     * error message (uses lang file)
     *
     * @var string
     * */
    protected $errors;

    /**
     * error start delimiter
     *
     * @var string
     * */
    protected $error_start_delimiter;

    /**
     * error end delimiter
     *
     * @var string
     * */
    protected $error_end_delimiter;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->lang->load('admin_estate');
        $this->load->helper('date');

        //Load the session, CI2 as a library, CI3 uses it as a driver
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->load->library('session');
        } else {
            $this->load->driver('session');
        }

        //initialize messages and error
        $this->messages = array();
        $this->errors = array();
        $this->message_start_delimiter = "<p>";
        $this->message_end_delimiter = "</p>";
        $this->error_start_delimiter = "<p>";
        $this->error_end_delimiter = "</p>";
    }

    public function num_row_user($whr = NULL)
    {
        if ($whr != NULL) {
            $this->db->where($whr);
        } else {
            $this->db->where('publish', 1);
        }
        return $this->db->get('estate')->num_rows();
    }

    public function num_row_selectbox()
    {
        return $this->db->get('selectbox')->num_rows();
    }

    public function num_row_getName($table_name, $where = NULL)
    {
        if ($where != NULL) {
            $this->db->where($where);
        }
        return $this->db->get($table_name)->num_rows();
    }

    public function num_row_category()
    {
        return $this->db->get('category')->num_rows();
    }

    public function num_row_type()
    {
        return $this->db->get('estatetype')->num_rows();
    }

    public function estates($id = null, $whr = NULL, $order = NULL, $select = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('estate');
            $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
            $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT');
            $query = $this->db->where('id', $id)->get()->result();
        } else {
            if ($select != NULL) {
                $this->db->select($select);
            } else {
                $this->db->select('*');
            }
            $this->db->from('estate');
            $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
            $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT');
            if ($whr != NULL) {
                $this->db->where($whr);
            } else {

                $this->db->where('publish', 1);
            }
            if ($order != NULL) {
                $this->db->order_by('id', 'DESC');
            }
            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }
/*Start XML part*/
    public function exportView($id=null){
        $this->db->select('*');
        if($id!=null)
            return $this->db->get_where('exports', array('id' => $id))->row();
        else
            return $this->db->get('exports')->result();
    }
    public function driverData($id=null){
        $this->db->select('*');
        if($id!=null)
            return $this->db->get_where('export_drivers', array('id' => $id))->row();
        else
            return $this->db->get('export_drivers')->result();
    }
    /**
     * @return mixed
     */
    public function getPropertyData($id=null)
    {

       # $condition=array('addedUserID'=>$this->ion_auth->get_user_id());

        if($id){
            $condition['id']=$id ;

        }else{
            $condition['published']=1 ;
        }
      #  \application\helpers\Generic::_setTrace($condition);
        $this->db->select('estate.*,estatetype.estateName');
        $this->db->from('estate');
        $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'INNER');
        $this->db->where($condition);
        //$this->db->where('addedUserID', 1);
        #$v= $this->db->get()->query();

        $res= $this->db->get()->result();
       # \application\helpers\Generic::_setTrace($res);
        $res=json_decode(json_encode($res), true);
        return $res;
    }

    /**
     * @param $estate_id
     * @return mixed
     */
    public function getAmenityData($estate_id)
    {
        $this->db->select('propertyfield.`name`');
        $this->db->from('property');
        $this->db->join('propertyfield', 'property.propID = propertyfield.pfid', 'INNER');
        return $this->db->where('estateID', $estate_id)->get()->result();
    }

    public function estateEdit($id)
    {
        $admin=$this->ion_auth->is_admin();

        if ($id) {
            $this->db->select('*');
            $this->db->from('estate');
            $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
            $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT');
            $query = $this->db->where('id', $id)->get()->result();
        }
        return $query;
    }
    public function priceRatesEdit($id)
    {
        if ($id) {
            $this->db->select('*');
            $this->db->from('price_rates');
            return $this->db->where('estate_id', $id)->get()->result();
        }
    }
    public  function unavailability($id){
        if ($id) {
            $this->db->select('unavailability.`unavailable_date`');
            $this->db->from('unavailability');
            $data= $this->db->where('estate_id', $id)->get()->result();
            if($data){

                return $data[0]->unavailable_date;
            }
            //return $this->db->get_where('unavailability', array('estate_id'=> $id))->row();
        }
    }

    public function register($data)
    {
        $insertData = array(
            'title' => $data['title'],
            'price' => $data['price'],
            'content' => $data['content'],
            'country' => $data['country'],
            'province' => $data['province'],
            'address' => $data['address'],
            'postal_code' => $data['postal_code'],
            'gps' => $data['gps'],
            'city' => $data['city'],
            'catID' => $data['category'],
            'estateTypeID' => $data['estatetype'],
            'photo' => $data['photo'][0],
            'photoGallery' => json_encode($data['photo']),
            'addedDate' => date("Y-m-d H:i:s"),
            'addedUserId' => $data['user_id'],
            'publish' => $data['publish'],
            'views' => 0,
            'telephone' => $data['telephone'],
            'gsm' => $data['gsm'],
            'email' => $data['email'],
            'guest_count' => $data['guest_count'],
            'sleep' => $data['sleep'],
            'room' => $data['room'],
            'bathroom' => $data['bathroom'],
            'heating' => $data['heating'],
            'squaremeter' => $data['squaremeter'],
            'squarefoot' => $data['squarefoot'],
            'buildstatus' => $data['buildstatus'],
            'floor' => $data['floor'],
            'showcase' => $data['showcase'],
            'fk' => $data['fk'],
            'vrbo' => $data['vrbo'],
            'hw' => $data['hw'],
            'ht' => $data['ht'],
            'bk' => $data['bk'],
            'rm' => $data['rm'],
            'vast' => $data['vast'],
            'otalo' => $data['otalo'],
            'airbnb' => $data['airbnb'],
            'default_min_los' => $data['default_min_los'],
            'default_nightly' => $data['default_nightly'],
            'default_weekly' => $data['default_weekly'],
        );
        //\application\helpers\Generic::_setTrace($insertData);
        $this->db->insert('estate', $insertData);
        $query = $this->db->affected_rows();

        if ($query == 1) {
            $id = $this->db->insert_id();
            if(!empty($data['optional_rates'])){
                foreach($data['optional_rates'] as $k=>$val){

                    $rates_data = array(
                        'estate_id'=>$id,
                        'title'=>$val['rate_title'],
                        'start_date'=>$val['start_date'],
                        'end_date'=>$val['end_date'],
                        'min_los'=>$val['min_los'],
                        'nightly_price'=>$val['nightly'],
                        'weekly_price'=>$val['weekly'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    );
                    //\application\helpers\Generic::_setTrace($rates_data);
                    $this->db->insert('price_rates', $rates_data);

                }
            }
            // now entry not available date
            if(isset($data['not_available_date_list'])){
                $date_array=explode(',',$data['not_available_date_list']);
                $date_data = array(
                    'estate_id'=>$id,
                    'unavailable_date'=>json_encode($date_array),
                     'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                );
                //\application\helpers\Generic::_setTrace($rates_data);
                $this->db->insert('unavailability', $date_data);

            }

            if ($data['checkbox'] == false) {
                $insertProp = array(
                    'estateID' => $id,
                    'value' => 0,
                    'propID' => 1,
                );
                $query = $this->db->insert('property', $insertProp);
            } else {
                foreach ($data['checkbox'] as $key => $item) {
                    $insertProp = array(
                        'estateID' => $id,
                        'value' => 1,
                        'propID' => $item,
                    );
                    $query = $this->db->insert('property', $insertProp);
                }
            }
        } else {
            $this->set_error('estate_add_error');
            return false;
        }
        $this->set_message('estate_add_success');
        return true;
    }

    public function updateEstate($data, $id)
    {
        $insertData = array(
            'title' => $data['title'],
            'price' => $data['price'],
            'content' => $data['content'],
            'country' => $data['country'],
            'province' => $data['province'],
            'city' => $data['city'],
            'address' => $data['address'],
            'postal_code' => $data['postal_code'],
            'gps' => $data['gps'],
            'catID' => $data['category'],
            'estateTypeID' => $data['estatetype'],
            'photo' => $data['photo'][0],
            'photoGallery' => json_encode($data['photo']),
            'addedDate' => date("Y-m-d H:i:s"),
            'addedUserId' => $data['user_id'],
            'publish' => $data['publish'],
            'views' => 0,
            'telephone' => $data['telephone'],
            'gsm' => $data['gsm'],
            'email' => $data['email'],
            'room' => $data['room'],
            'sleep' => $data['sleep'],
            'bathroom' => $data['bathroom'],
            'heating' => $data['heating'],
            'squaremeter' => $data['squaremeter'],
            'squarefoot' => $data['squarefoot'],
            'buildstatus' => $data['buildstatus'],
            'floor' => $data['floor'],
            'showcase' => $data['showcase'],
            'fk' => $data['fk'],
            'vrbo' => $data['vrbo'],
            'hw' => $data['hw'],
            'bk' => $data['bk'],
            'ht' =>$data['ht'],
            'rm' => $data['rm'],
            'vast' =>$data['vast'],
            'airbnb' => $data['airbnb'],
            'otalo' => $data['otalo'],
           // 'sleep' => $data['sleep'],

            'default_min_los' => $data['default_min_los'],
            'default_nightly' => $data['default_nightly'],
            'default_weekly' => $data['default_weekly'],

        );
        $this->db->where('id', $id);
        $this->db->update('estate', $insertData);

        if (isset($id)) {
            if ($data['checkbox'] == false) {

            } else {
                //$updateProp = implode(',', $data['checkbox']);
                foreach ($data['checkbox'] as $key => $item) {
                    $insertProp = array(
                        'estateID' => $id,
                        'value' => 1,
                        'propID' => $item,
                    );
                    if ($this->db->get_where('property', array('estateID' => $id, 'propID' => $item))->num_rows() < 1) {
                        $query = $this->db->insert('property', $insertProp);
                    }
                }
                $this->db->where('estateID', $id);
                $this->db->where_in('propID', $data['checkbox']);
                $query = $this->db->update('property', array('value' => 1));

                $this->db->where('estateID', $id);
                $this->db->where_not_in('propID', $data['checkbox']);
                $query = $this->db->update('property', array('value' => 0));
            }

            if(!empty($data['optional_rates'])){
            //delete previous records                // delet
                $sql = "DELETE FROM price_rates WHERE estate_id=".$id;
                $this->db->query($sql);
                // insert as new
                foreach($data['optional_rates'] as $k=>$val){

                    $rates_data = array(
                        'estate_id'=>$id,
                        'title'=>$val['rate_title'],
                        'start_date'=>$val['start_date'],
                        'end_date'=>$val['end_date'],
                        'min_los'=>$val['min_los'],
                        'nightly_price'=>$val['nightly'],
                        'weekly_price'=>$val['weekly'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    );
                    //\application\helpers\Generic::_setTrace($rates_data);
                    $this->db->insert('price_rates', $rates_data);

                }
            }
            // now entry not available date
            $sql = "DELETE FROM unavailability WHERE estate_id=".$id;
            $this->db->query($sql);

            if(isset($data['not_available_date_list'])){
                $date_array=explode(',',$data['not_available_date_list']);
                $date_data = array(
                    'estate_id'=>$id,
                    'unavailable_date'=>json_encode($date_array),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                );
               $this->db->insert('unavailability', $date_data);

            }

        } else {
            $this->set_error('estate_edit_error');
            return false;
        }
        $this->set_message('estate_edit_success');
        return true;
    }

    public function category($id = NULL)
    {
        if ($id != NULL) {
            $query = $this->db->get_where('category', array('cid', $id), 1)->result();
        } else {
            $query = $this->db->get_where('category')->result();
            if (!empty($query)) {
                foreach ($query as $item) {
                    $items[$item->cid] = $item->catName;
                }
            } else {
                $items[''] = "";
            }
            return @$items;
        }
        return $query;
    }

    public function categoryArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('category');
            $query = $this->db->where('cid', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('category');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function estateTypeArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('estatetype');
            $query = $this->db->where('eid', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('estatetype');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function selectboxGet($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('selectbox');
            $query = $this->db->where('id', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('selectbox');
            $this->db->order_by('type,id');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function selectboxType($type = NULL)
    {
        if ($type != NULL) {
            $query = $this->db->get_where('selectbox', array('type' => $type))->result_array();
            foreach ($query as $item) {
                $results[$item['value']] = $item['value'];
            }
            if ($query == NULL) {
                $results[' '] = ' ';
            }
        } else {
            return false;
        }
        return $results;
    }

    public function selectRegister($type = null, $value = null)
    {
        if ($type != NULL and $value != NULL) {
            $data = array(
                'type' => $type,
                'value' => $value
            );
            $query = $this->db->insert('selectbox', $data);
        } else {
            $this->set_error('estate_add_error');
            return false;
        }
        $this->set_message('estate_add_success');
        return true;
    }

    public function selectUpdate($type = null, $value = null, $id)
    {
        if ($type != NULL and $value != NULL and $id) {
            $data = array(
                'type' => $type,
                'value' => $value
            );
            $this->db->where('id', $id);
            $query = $this->db->update('selectbox', $data);
        } else {
            $this->set_error('estate_add_error');
            return false;
        }
        $this->set_message('estate_add_success');
        return true;
    }

    public function propertyRegister($type = null, $value = null)
    {
        if ($type != NULL and $value != NULL) {
            $data = array(
                'typeID' => $type,
                'name' => $value
            );
            $query = $this->db->insert('propertyfield', $data);
        } else {
            $this->set_error('estate_propperty_add_error');
            return false;
        }
        $this->set_message('estate_propperty_add_success');
        return true;
    }

    public function propertyUpdate($type = null, $value = null, $id)
    {
        if ($type != NULL and $value != NULL and $id) {
            $data = array(
                'typeID' => $type,
                'name' => $value
            );
            $this->db->where('pfid', $id);
            $query = $this->db->update('propertyfield', $data);
        } else {
            $this->set_error('estate_propperty_add_error');
            return false;
        }
        $this->set_message('estate_propperty_add_success');
        return true;
    }

    public function propertyGet($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('propertyfield');
            $query = $this->db->where('pfid', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('propertyfield');
            $this->db->order_by('typeID,pfid', 'ASC');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function categoryRegister($value = null)
    {
        if ($value != NULL) {
            $data = array(
                'catName' => $value
            );
            $query = $this->db->insert('category', $data);
        } else {
            $this->set_error('estate_category_error');
            return false;
        }
        $this->set_message('estate_category_success');
        return true;
    }

    public function categoryUpdate($value = null, $id = NULL)
    {
        if ($value != NULL and $id != NULL) {
            $data = array(
                'catName' => $value
            );
            $this->db->where('cid', $id);
            $query = $this->db->update('category', $data);
        } else {
            $this->set_error('estate_category_error');
            return false;
        }
        $this->set_message('estate_category_success');
        return true;
    }

    public function estateTypeRegister($value = null)
    {
        if ($value != NULL) {
            $data = array(
                'estateName' => $value
            );
            $query = $this->db->insert('estatetype', $data);
        } else {
            $this->set_error('estate_type_error');
            return false;
        }
        $this->set_message('estate_type_success');
        return true;
    }

    public function estateTypeUpdate($value = null, $id = NULL)
    {
        if ($value != NULL and $id != NULL) {
            $data = array(
                'estateName' => $value
            );
            $this->db->where('eid', $id);
            $query = $this->db->update('estatetype', $data);
        } else {
            $this->set_error('estate_type_error');
            return false;
        }
        $this->set_message('estate_type_success');
        return true;
    }

    public function propertyField($id = NULL)
    {
        if ($id != NULL) {
            $query = $this->db->get_where('propertyfield', array('propID', $id), 1)->result();
        } else {
            $this->db->order_by('typeID,pfid', 'ASC');
            $query = $this->db->get_where('propertyfield');
        }
        return $query;
    }

    public function propertyALL($id)
    {
        $query = $this->db->query("select * from propertyfield LEFT JOIN property on (propertyfield.pfid = property.propID and property.estateID = $id) ORDER BY typeID,pfid");
        return $query;
    }

    public function estatetype($id = NULL)
    {
        if ($id != NULL) {
            $query = $this->db->get_where('estatetype', array('eid', $id), 1)->result();
        } else {
            $query = $this->db->get_where('estatetype')->result();
            if (!empty($query)) {
                foreach ($query as $item) {
                    $items[$item->eid] = $item->estateName;
                }
            } else {
                $items[''] = "";
            }
            return $items;
        }
        return $query;
    }

    public function activate($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('estate', array('publish' => 1));
            return TRUE;
        } else {
            return false;
        }
    }

    public function deactivate($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('estate', array('publish' => 0));
            return TRUE;
        } else {
            return false;
        }
    }

    public function showcaseOn($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('estate', array('showcase' => 1));
            return TRUE;
        } else {
            return false;
        }
    }

    public function showcaseOff($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('estate', array('showcase' => 0));
            return TRUE;
        } else {
            return false;
        }
    }

    public function blogpageAC($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('blogpage', array('publish' => 1));
            return TRUE;
        } else {
            return false;
        }
    }

    public function blogpageDAC($id = null)
    {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->update('blogpage', array('publish' => 0));
            return TRUE;
        } else {
            return false;
        }
    }

    public function deleteBlogPage($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM blogpage where id=$id");
            return TRUE;
        } else {
            return false;
        }
    }

    public function delete($id = null)
    {
        if ($id != NULL) {
            $this->db->query("delete from estate where estate.id = $id");
            $this->db->query("delete from property where property.estateID = $id");
            $this->set_message('estate_delete_item');
            return TRUE;
        } else {
            return false;
        }
    }

    public function deleteSelect($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM selectbox where id=$id");
            $this->set_message('estate_delete_select');
            return TRUE;
        } else {
            return false;
        }
    }

    public function deleteProperty($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM propertyfield where pfid=$id");
            return TRUE;
        } else {
            return false;
        }
    }

    public function deleteCategory($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM category where cid=$id");
            $this->set_message('estate_category_delete');
            return TRUE;
        } else {
            return false;
        }
    }

    public function deleteType($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM estatetype where eid=$id");
            $this->set_message('estate_type_delete');
            return TRUE;
        } else {
            return false;
        }
    }

    public function blogPage($id = null, $publish = 10, $whr = array('type' => '0'), $order = NULL, $select = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('blogpage');
            $query = $this->db->where('id', $id)->get()->result();
        } else {
            if ($select != NULL) {
                $this->db->select($select);
            } else {
                $this->db->select('*');
            }
            $this->db->from('blogpage');
            if ($publish != 10) {
                $this->db->where('publish', $publish);
            }
            $this->db->where($whr);
            if ($order != NULL) {
                $this->db->order_by('id', 'DESC');
            }

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function blogRegister($data)
    {
        $insertData = array(
            'title' => $data['title'],
            'content' => $data['content'],
            'photo' => $data['photo'],
            'desc' => $data['desc'],
            'tags' => $data['tags'],
            'addedDate' => date("Y-m-d H:i:s"),
            'publish' => $data['publish'],
            'type' => $data['type']
        );
        $this->db->insert('blogpage', $insertData);
        $query = $this->db->affected_rows();

        if ($query != 1) {
            $this->set_error('estate_add_error');
            return false;
        }
        $this->set_message('estate_add_success');
        return true;
    }

    public function blogUpdate($data, $id)
    {
        $insertData = array(
            'title' => $data['title'],
            'content' => $data['content'],
            'photo' => $data['photo'],
            'desc' => $data['desc'],
            'tags' => $data['tags'],
            'addedDate' => date("Y-m-d H:i:s"),
            'publish' => $data['publish'],
        );
        $this->db->where('id', $id);
        $query = $this->db->update('blogpage', $insertData);

        if (!$query) {
            $this->set_error('blog_message_update_error');
            return false;
        }
        $this->set_message('blog_message_update');
        return true;
    }

    public function updateGeneral($data)
    {
        foreach ($data as $key => $value) {
            $this->db->where('key', $key);
            $this->db->update('options', array('value' => $value));
        }
        $this->set_message('set_genaral_message');
        return true;
    }

    public function getSetings($whr)
    {
        $query = $this->db->get_where('options', array('key' => $whr))->result();
        $query = $query[0];
        return $query;
    }
    public function getDrivers($whr=null){
        if($whr==1)
            return $this->db->get_where('export_drivers', array('status' => $whr))->result();
        else
            return $this->db->get('export_drivers')->result();
    }

    public function paginationLimit($limit, $offset)
    {
        $this->_ion_limit = $limit;
        $this->_ion_offset = $offset;
    }

    public function getUser($whr = NULL, $order = NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        if ($whr != NULL) {
            $this->db->where($whr);
        }
        if ($order != NULL) {
            $this->db->order_by('id', 'DESC');
        }
        if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);
            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        }

        return $query = $this->db->get()->result();
    }

    public function estateSearch($like = NULL, $whr = NULL, $count = NULL)
    {
        $this->db->select('*');
        $this->db->from('estate');
        $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
        $this->db->like('title', $like);
        if ($whr != NULL) {
            $this->db->where($whr);
        }
        if ($count == NULL) {
            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }
        } else {
            $query = $this->db->get()->num_rows();
            return $query;
        }

        $query = $this->db->get()->result();

        return $query;
    }

    /**
     * set_message_delimiters
     *
     * Set the message delimiters
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function set_message_delimiters($start_delimiter, $end_delimiter)
    {
        $this->message_start_delimiter = $start_delimiter;
        $this->message_end_delimiter = $end_delimiter;

        return TRUE;
    }

    /**
     * set_error_delimiters
     *
     * Set the error delimiters
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function set_error_delimiters($start_delimiter, $end_delimiter)
    {
        $this->error_start_delimiter = $start_delimiter;
        $this->error_end_delimiter = $end_delimiter;

        return TRUE;
    }

    /**
     * set_message
     *
     * Set a message
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function set_message($message)
    {
        $this->messages[] = $message;

        return $message;
    }

    /**
     * messages
     *
     * Get the messages
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function messages()
    {
        $_output = '';
        foreach ($this->messages as $message) {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
        }

        return $_output;
    }

    /**
     * messages as array
     *
     * Get the messages as an array
     *
     * @return array
     * @author Raul Baldner Junior
     * */
    public function messages_array($langify = TRUE)
    {
        if ($langify) {
            $_output = array();
            foreach ($this->messages as $message) {
                $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
                $_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
            }
            return $_output;
        } else {
            return $this->messages;
        }
    }

    /**
     * set_error
     *
     * Set an error message
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function set_error($error)
    {
        $this->errors[] = $error;

        return $error;
    }

    /**
     * errors
     *
     * Get the error message
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function errors()
    {
        $_output = '';
        foreach ($this->errors as $error) {
            $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
            $_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
        }

        return $_output;
    }

    /**
     * errors as array
     *
     * Get the error messages as an array
     *
     * @return array
     * @author Raul Baldner Junior
     * */
    public function errors_array($langify = TRUE)
    {
        if ($langify) {
            $_output = array();
            foreach ($this->errors as $error) {
                $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
                $_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
            }
            return $_output;
        } else {
            return $this->errors;
        }
    }

    /*
     * Message Model Methods
    *
    * @developer   Nurul Amin Muhit
    * @package     Real State CMS Pro
    *
    */

    function get_inbox($user = NULL)
    {
        $this->db->where('user_to', $user);
        $this->db->where('deleted', 0);
        $this->db->join('users', 'users.id = messages.user_from');

        if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);
            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        }

        $query = $this->db->get('messages');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function get_sent($user = NULL)
    {
        $this->db->where('user_from', $user);
        $this->db->join('users', 'users.id = messages.user_to');

        if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);
            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        }

        $query = $this->db->get('messages');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function msg_details($msg = NULL)
    {
        $this->_read_msg($msg);
        $this->db->where('msg_id', $msg);
        $this->db->where('user_to', $this->session->userdata('user_id'));
        $this->db->join('users', 'users.id = messages.user_from');
        $query = $this->db->get('messages');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function sent_details($msg = NULL)
    {
        $this->db->where('msg_id', $msg);
        $this->db->where('user_from', $this->session->userdata('user_id'));
        $this->db->join('users', 'users.id = messages.user_to');
        $query = $this->db->get('messages');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function insert_file($filename, $user_to, $subject, $message)
    {
        if ($filename) {
            $file = $this->_insert_attachment($filename);
        } else {
            $file = 0;
        }
        $data = array(
            'user_to' => $user_to,
            'user_from' => $this->session->userdata('user_id'),
            'subject' => $subject,
            'message' => $message,
            'attachment' => $file ? $file : 0,
        );
        $this->db->insert('messages', $data);
    }

    function _insert_attachment($filename)
    {
        $this->db->set('file_name', $filename);
        $this->db->insert('attachments');
        return $this->db->insert_id();
    }

    function get_attached($msg = NULL)
    {
        $this->db->where('msg_id', $msg);
        $query = $this->db->get('messages');
        foreach ($query->result() as $row) {
            return $row->attachment;
        }
    }

    public function get_user($username)
    {
        return $this->db->select('id')
            ->from('users')
            ->where('username', $username)
            ->get()
            ->row()->id;
    }

    public function get_file($file_id)
    {
        return $this->db->select()
            ->from('attachments')
            ->where('attach_id', $file_id)
            ->get()
            ->row();
    }

    function _read_msg($msg)
    {
        $this->db->where('msg_id', $msg);
        $this->db->set('status', 'Read');
        $this->db->update('messages');
    }

    function msg_count_by($params = array())
    {

        if (!empty($params['user_to'])) {
            $this->db->where('messages.user_to', $params['user_to']);
            $this->db->where('messages.deleted', 0);
        }

        if (!empty($params['user_from'])) {
            $this->db->where('messages.user_from', $params['user_from']);
        }

        return (int)$this->db->get('messages')->num_rows();
    }

    /**
     * @author melvin angelo e. jabonillo
     *   getting all attribute names
     */
    function getAllAttribute()
    {
        $query = $this->db->get('attribute_name');
        return $query->result_array();
    }

    /**
     *
     * Members Model Methods
     *
     * @developer   Nurul Amin Muhit
     * @email       muhit18@gmail.com
     * @package     Real Estate CMS Pro
     *
     * @created     2014-03-01
     * @updated     2014-03-01
     */

    function memberships()
    {
        $query = $this->db->get('memberships');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function membership_profile($profile)
    {
        $this->db->where('m_id', $profile);
        $query = $this->db->get('memberships');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
    }

    function login_activities()
    {
        $this->db
            ->select('auth_log.*')
            ->select('users.username')
            ->join('users', 'users.id = auth_log.user');
        $query = $this->db->get('auth_log');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function membership_count_by($params = array())
    {

        return (int)$this->db->get('memberships')->num_rows();
    }

    function tags()
    {
        $this->db->from('neighbor_tag');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            return $query->result();
        }

    }

    function onetag($id)
    {
        $this->db->where('id', $id);
        if ($query = $this->db->get('neighbor_tag')) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return FALSE;
    }

    function updatetag($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('neighbor_tag', $data) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function addtag($data)
    {
        $this->db->set($data);
        if ($this->db->insert('neighbor_tag') !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    //----------------photographer------------------------
    function addphotographer($data)
    {

        $this->db->set($data);
        $this->db->insert('neighbor_photographer');


        $config['upload_path'] = 'images/uploads/';
        $config['file_name'] = $data['image'];
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '3000';
        $config['max_width'] = '';
        $config['max_height'] = '';
        $config['overwrite'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('photo_grapher_image')) {
            $image_data = $this->upload->data();

            $fileName = $config['upload_path'] . $image_data['file_name'];
            $return = array('file_name' => $fileName, 'error' => '');
            return $return;
        } else {
            $err = '';
            $err = $this->upload->display_errors();

            $return = array('file_name' => '', 'error' => $err);
            return $return;
        }


    }

    function viewphotographer()
    {
        $this->db->from('neighbor_photographer');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function onephotographer($id)
    {
        $this->db->where('id', $id);
        if ($query = $this->db->get('neighbor_photographer')) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return FALSE;
    }

    function updatephotographer($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('neighbor_photographer', $data) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function deletephotographer($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('neighbor_photographer');
    }

    public function availabilityarray()
    {
        $this->db->select('*');
        $this->db->from('availability');
        $this->db->where('status', 1);
        $query = $this->db->get()->result();
        return $query;
    }

    //------------------------add post-------------------------

    function addpost($data)
    {
        $this->db->set($data);
        if ($this->db->insert('neighbor_post') !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function viewpost()
    {
        $this->db->from('neighbor_post');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function onepost($id)
    {
        $this->db->where('id', $id);
        if ($query = $this->db->get('neighbor_post')) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return FALSE;
    }

    function updatepost($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('neighbor_post', $data) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function deletepost($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('neighbor_post');
    }


    function viewcategory()
    {
        $this->db->from('neighbor_category');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function onecategory($id)
    {
        $this->db->where('id', $id);
        if ($query = $this->db->get('neighbor_category')) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return FALSE;
    }

    function updatecategory($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('neighbor_category', $data) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function addcategory($data)
    {
        $this->db->set($data);
        if ($this->db->insert('neighbor_category') !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    //----------------city------------------------
    function addcity($data)
    {
        $this->db->set($data);
        if ($this->db->insert('neighbor_city') !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function viewcity()
    {
        $this->db->from('neighbor_city');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function onecity($id)
    {
        $this->db->where('id', $id);
        if ($query = $this->db->get('neighbor_city')) {
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return FALSE;
    }

    function updatecity($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('neighbor_city', $data) !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    function deletecity($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('neighbor_city');
    }

    function getplace($conditions = array(), $like = array(), $like_or = array())
    {
        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);

        //Check For like statement
        if (is_array($like_or) and count($like_or) > 0)
            $this->db->or_like($like_or);
// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->from('neighbor_city');
        $this->db->select();
        $result = $this->db->get();
        return $result;

    }

    //End of getFaqs Function


    function getplace1($conditions = array(), $like = array(), $like_or = array())
    {
        //Check For like statement
        if (is_array($like) and count($like) > 0)
            $this->db->like($like);

        //Check For like statement
        if (is_array($like_or) and count($like_or) > 0)
            $this->db->or_like($like_or);
// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
        if (count($conditions) > 0)
            $this->db->where($conditions);

        $this->db->from('neighbor_area');
        $this->db->select();
        $result = $this->db->get();
        return $result;

    }

    function updatePage($updateKey = array(), $updateData = array())
    {
        $this->db->update('neighbor_area', $updateData, $updateKey);

    }

    function addplace($country, $state, $city)
    {
        $data = array(
            'country' => $country,
            'state' => $state,
            'city' => $city


        );
        $this->db->insert('neighbor_city', $data);
    }

    function addplace1($city_id, $area)
    {
        $data = array(
            'city_id' => $city_id,
            'area' => $area

        );
        $this->db->insert('neighbor_area', $data);
    }

    function deleteplace($id = 0, $conditions = array())
    {
        if (is_array($conditions) and count($conditions) > 0)
            $this->db->where($conditions);
        else
            $this->db->where('id', $id);
        $this->db->delete('neighbor_area');

    }

    /*
    * Message Model Methods
    *
    * @developer   Ghanshyam Sharma
    * @package     Real State CMS Pro - Landlord module
    *
    */


    public function lanlordArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('landlords');
            $query = $this->db->where('id', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('landlords');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }


    public function lanlordRegister($name = null, $phonenumber = null, $email = null, $fax = null)
    {
        if ($name != NULL) {
            $data = array(
                'name' => $name,
                'phone_number' => $phonenumber,
                'email' => $email,
                'fax' => $fax
            );
            $query = $this->db->insert('landlords', $data);
        } else {
            $this->set_error('estate_lanlord_error');
            return false;
        }
        $this->set_message('estate_lanlord_success');
        return true;
    }

    public function lanlordUpdate($name = null, $phonenumber = null, $email = null, $fax = null, $id = NULL)
    {
        if ($name != NULL and $id != NULL) {
            $data = array(
                'name' => $name,
                'phone_number' => $phonenumber,
                'email' => $email,
                'fax' => $fax
            );
            $this->db->where('id', $id);
            $query = $this->db->update('landlords', $data);
        } else {
            $this->set_error('estate_lanlord_error');
            return false;
        }
        $this->set_message('estate_lanlord_success');
        return true;
    }

    public function deleteLandlord($id = null)
    {
        if ($id != NULL) {
            $this->db->query("delete from landlords where landlords.id = $id");
            return TRUE;
        } else {
            return false;
        }
    }

    public function bookingList($id, $Anno, $Mese, $status)
    {

        if ($status == 1) {
            $query = $this->db->query("SELECT  * FROM booking WHERE customerid='" . $id . "' AND (YEAR(start_time)>=" . ($Anno - 1) . " AND YEAR(end_time)=" . $Anno . " AND  MONTH(start_time)<=12 AND MONTH(end_time)>=" . $Mese . " )  ORDER BY start_time");
            return $query;
        }

        if ($status == 2) {
            $query = $this->db->query("SELECT *
                FROM booking
                WHERE 
                customerid = '" . $id . "'
                AND 
                (
                    YEAR( start_time ) =" . $Anno . "  AND MONTH( start_time ) <=12
                    AND 
                    (
                        (YEAR( end_time ) =" . $Anno . "  AND MONTH( end_time ) =12)
                        OR 
                        (YEAR( end_time ) =" . ($Anno + 1) . "  )
                    )
                )  ORDER BY start_time");
            return $query;
        }

        if ($status == 3) {
            $query = $this->db->query("
                    SELECT * FROM booking WHERE customerid='" . $id . "' AND
                    (
                        YEAR(start_time) =" . $Anno . "  AND MONTH(start_time) <=" . $Mese . "
                        AND 
                        (
                            (YEAR( end_time ) =" . $Anno . "  AND MONTH(end_time) >=" . $Mese . ")
                            OR 
                            (YEAR( end_time ) =" . ($Anno + 1) . ")
                        )
                    )
                 ORDER BY start_time");
            return $query;
        }


    }


    public function apartmentArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('apartments');
            $query = $this->db->where('id', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('apartments');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }


    public function apartmentRegister($name = null, $address = null, $state = null, $city = null, $zipcode = null, $landlord = null, $rent = null)
    {
        if ($name != NULL) {
            $data = array(
                'name' => $name,
                'address' => $address,
                'state' => $state,
                'city' => $city,
                'zip_code' => $zipcode,
                'landlord_id' => $landlord,
                'total_rent' => $rent
            );
            $query = $this->db->insert('apartments', $data);
        } else {
            $this->set_error('estate_apartment_error');
            return false;
        }
        $this->set_message('estate_apartment_success');
        return true;
    }

    public function apartmentUpdate($name = null, $address = null, $state = null, $city = null, $zipcode = null, $landlord = null, $rent = null, $id = NULL)
    {
        if ($name != NULL and $id != NULL) {
            $data = array(
                'name' => $name,
                'address' => $address,
                'state' => $state,
                'city' => $city,
                'zip_code' => $zipcode,
                'landlord_id' => $landlord,
                'total_rent' => $rent
            );
            $this->db->where('id', $id);
            $query = $this->db->update('apartments', $data);
        } else {
            $this->set_error('estate_apartment_error');
            return false;
        }
        $this->set_message('estate_apartment_success');
        return true;
    }

    public function deleteApartment($id = null)
    {
        if ($id != NULL) {
            $this->db->query("delete from apartments where apartments.id = $id");
            return TRUE;
        } else {
            return false;
        }
    }

    public function residentArray($id = NULL)
    {
        $date = date("Y-m-d H:i:s");

        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('residents');
            $query = $this->db->where('move_out_time >', $date)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('residents');
            $this->db->where('move_out_time >', $date);

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function deleteResident($id = null)
    {
        if ($id != NULL) {
            $this->db->query("delete from residents where residents.id = $id");
            return TRUE;
        } else {
            return false;
        }
    }

    public function residentRegister($name = null, $phonenumber = null, $email = null, $moveintime = null, $moveouttime = null, $apartment = null, $rent = null)
    {
        if ($name != NULL) {
            $data = array(
                'name' => $name,
                'phone_number' => $phonenumber,
                'email' => $email,
                'move_in_time' => $moveintime,
                'move_out_time' => $moveouttime,
                'apartment_id' => $apartment,
                'rent' => $rent
            );
            $query = $this->db->insert('residents', $data);
        } else {
            $this->set_error('estate_resident_error');
            return false;
        }
        $this->set_message('estate_resident_success');
        return true;
    }

    public function residentUpdate($name = null, $phonenumber = null, $email = null, $moveintime = null, $moveouttime = null, $apartment = null, $rent = null, $id = NULL)
    {
        if ($name != NULL and $id != NULL) {
            $data = array(
                'name' => $name,
                'phone_number' => $phonenumber,
                'email' => $email,
                'move_in_time' => $moveintime,
                'move_out_time' => $moveouttime,
                'apartment_id' => $apartment,
                'rent' => $rent
            );
            $this->db->where('id', $id);
            $query = $this->db->update('residents', $data);
        } else {
            $this->set_error('estate_resident_error');
            return false;
        }
        $this->set_message('estate_resident_success');
        return true;
    }

    public function pastResidentArray($id = NULL)
    {
        $date = date("Y-m-d H:i:s");

        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('residents');
            $query = $this->db->where('move_out_time <=', $date)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('residents');
            $this->db->where('move_out_time <=', $date);

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }

        return $query;
    }

    public function estateAttribArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('estateattrib');
            $query = $this->db->where('eaid', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('estateattrib');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }

    public function estateAttribName($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('attribName');
            $this->db->from('estateattrib');
            $query = $this->db->where('eaid', $id)->get()->result_array();
        }
        return $query;
    }

    public function estateAttribRegister($value = null)
    {
        if ($value != NULL) {
            $data = array(
                'attribName' => $value
            );
            $query = $this->db->insert('estateattrib', $data);
        } else {
            $this->set_error('estate_attrib_error');
            return false;
        }
        $this->set_message('estate_attrib_success');
        return true;
    }

    public function estateAttribUpdate($value = null, $id = NULL)
    {
        if ($value != NULL and $id != NULL) {
            $data = array(
                'attribName' => $value
            );
            $this->db->where('eaid', $id);
            $query = $this->db->update('estateattrib', $data);
        } else {
            $this->set_error('estate_attrib_error');
            return false;
        }
        $this->set_message('estate_attrib_success');
        return true;
    }

    public function deleteAttrib($id = null)
    {
        if ($id != NULL) {
            $this->db->query("DELETE FROM estateattrib where eaid=$id");
            $this->set_message('estate_attrib_delete');
            return TRUE;
        } else {
            return false;
        }
    }

    public function apartmentAvilabilityArray($id = NULL)
    {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('reservation_availability');
            $query = $this->db->where('id ', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('reservation_availability');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }
            $query = $this->db->get()->result();
        }
        return $query;
    }

}