<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
class home_model extends CI_Model {

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

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->lang->load('home');
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

    public function getSet($whr) {
        $query = $this->db->get_where('options', array('key' => $whr))->result();
        $query = $query[0];
        return $query;
    }

    public function getContact() {
        $this->db->select('*');
        $this->db->from('options');
        $query = $this->db->where_in('key', array('site_eposta', 'phone','fax_number', 'mobile_phone', 'facebook', 'twitter', 'google', 'linkedin', 'pinterest', 'adress', 'site_footer','site_links','footer_about','site_eposta2'));
        $query = $query->get()->result();
        foreach ($query as $key => $item) {
            $new[$item->key] = $item->value;
        }
        return $new;
    }

    public function getPages() {
        $this->db->select('*');
        $this->db->from('blogpage');
        $this->db->where('type', '1');
        $this->db->where('publish', '1');
        $query = $this->db->get()->result();

        foreach ($query as $key => $item) {
            $new[$item->id] = $item->title;
        }

        if (!empty($new)) {
            return $new;
        }
        return false;
    }

    public function getCategory($whr = NULL) {
        $this->db->select('*');
        $this->db->from('category');

        if ($whr != NULL) {
            $this->db->where($whr);
        }

        $query = $this->db->get()->result();

        foreach ($query as $key => $item) {
            $new[$item->cid] = $item->catName;
        }

        return @$new;
    }

    public function getType($whr = NULL) {
        $this->db->select('*');
        $this->db->from('estatetype');

        if ($whr != NULL) {
            $this->db->where($whr);
        }

        $query = $this->db->get()->result();

        foreach ($query as $key => $item) {
            $new[$item->eid] = $item->estateName;
        }

        return @$new;
    }

    public function getUser($id = NULL) {
        //if no id was passed use the current users id
        $id || $id = $this->session->userdata('user_id');

        $this->db->from('users');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get()->result_array();

        return $query;
    }

    public function getMyestate($id) {

        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('users');
            $query = $this->db->where('id', $id)->get()->result();
            return $query;
        }
    }

    public function getEstates($id = null, $whr = NULL, $order = NULL) {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('estate');
            $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
            $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT');
            $query = $this->db->where('id', $id)->get()->result();
        } else {
            $this->db->select('*');
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

    public function getBlogpage($id = null, $whr = NULL, $order = NULL) {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('blogpage');
            $query = $this->db->where('id', $id)->get()->result();
        } else {
            $this->db->select('*');
            $this->db->from('blogpage');
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

    public function getSearch($data = NULL) {
        if ($data != NULL) {
            $this->db->select('SQL_CALC_FOUND_ROWS *', FALSE);
            $this->db->from('estate');
            $this->db->join('category', 'estate.catID = category.cid', 'LEFT');
            $this->db->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT');

            if ($data['searchstr'])
                $this->db->like('title', $data['searchstr']);
            if ($data['room'])
                $this->db->where('room', $data['room']);
            if ($data['bathroom'])
                $this->db->where('bathroom', $data['bathroom']);
            if ($data['heating'])
                $this->db->where('heating', $data['heating']);
            if ($data['squaremeter'])
                $this->db->where('squaremeter', $data['squaremeter']);
            if ($data['buildstatus'])
                $this->db->where('buildstatus', $data['buildstatus']);
            if ($data['floor'])
                $this->db->where('floor', $data['floor']);
            if ($data['category'])
                $this->db->where('catName', $data['category']);
            if ($data['type'])
                $this->db->where('estateName', $data['type']);
            if ($data['pricemin'])
                $this->db->where('price >=', $data['pricemin']);
            if ($data['pricemax'])
                $this->db->where('price <=', $data['pricemax']);

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
            $count = $this->db->query('select FOUND_ROWS()')->result_array();
        } else {
            return false;
        }
        $data = array(
            'count' => $count,
            'query' => $query,
        );

        return $data;
    }

    public function getSelect($whr) {
        $this->db->select('type,value');
        $this->db->from('selectbox');
        $query = $this->db->where('type', $whr)->get()->result();
        if (!empty($query)) {
            foreach ($query as $key => $item) {
                $new[$item->value] = $item->value;
            }
        } else {
            $new[""] = "";
        }
        if (!empty($new)) {
            return $new;
        }
        return $new;
    }

    public function getPrice($order) {
        $this->db->select('price');
        $this->db->from('estate');
        $this->db->order_by('price', $order);
        $this->db->limit(10);
        $query = $this->db->get()->result();
        if (!empty($query)) {
            foreach ($query as $key => $item) {
                $new[$item->price] = number_format($item->price, 0, '.', ',');
            }
        } else {
            $new[""] = "";
        }
        if (!empty($new)) {
            return $new;
        }
        return false;
    }

    public function getCatType($table, $column) {
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get()->result();
        if (!empty($query)) {
            foreach ($query as $key => $item) {
                $new[$item->$column] = $item->$column;
            }
        } else {
            $new[""] = "";
        }
        if (!empty($new)) {
            return $new;
        }
        return false;
    }

    public function paginationLimit($limit, $offset) {
        $this->_ion_limit = $limit;
        $this->_ion_offset = $offset;
    }

    public function num_row_user($whr = NULL) {
        if ($whr != NULL) {
            $this->db->where($whr);
        } else {
            $this->db->where('publish', 1);
        }
        return $this->db->get('estate')->num_rows();
    }

    public function num_row_blogpage($whr = NULL) {
        if ($whr != NULL) {
            $this->db->where($whr);
        } else {
            $this->db->where('publish', 1);
        }
        return $this->db->get('blogpage')->num_rows();
    }

    public function getProperties($id = null) {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('propertyfield');
            $this->db->join('property', "propertyfield.pfid = property.propID and estateID = $id", 'LEFT');
            $this->db->order_by('typeID', 'ASC');
            $query = $this->db->get()->result();
        } else {
            return false;
        }
        return $query;
    }

    public function mailsetting() {
        $values = array();

        $query = $this->db->query("select options.key,value from options where options.key in ('site_title','from_email','from_name','mail_type','smtp_username','smtp_password','smtp_port','smtp_ssl',  
          'smtp_auth','smtp_host','mail_charset','mail_encoding')")->result();

        foreach ($query as $key => $item) {
            $items[$item->key] = $item->value;
        }
        return $items;
    }

    /**
     * set_message_delimiters
     *
     * Set the message delimiters
     *
     * @return void
     * @author Ben Edmunds
     * */
    public function set_message_delimiters($start_delimiter, $end_delimiter) {
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
    public function set_error_delimiters($start_delimiter, $end_delimiter) {
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
    public function set_message($message) {
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
    public function messages() {
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
    public function messages_array($langify = TRUE) {
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
    public function set_error($error) {
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
    public function errors() {
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
    public function errors_array($langify = TRUE) {
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

}