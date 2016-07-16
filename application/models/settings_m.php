<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package     Real Estate CMS Pro
 * @author      Nurul Amin Muhit
 * @email       muhit18@gmail.com
 */
class Settings_m extends CI_Model {

    protected $_table = 'options';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db
                ->select('options.*')
                ->order_by('options.id', 'desc');

        return $this->db->get($this->_table)->result();
    }

    public function get($id) {
        $this->db
                ->select('options.*')
                ->where('options.id', $id);

        return $this->db->get($this->_table)->row();
    }

    public function get_by($key, $value = '') {
        $this->db
                ->select('options.*');

        if (is_array($key)) {
            $this->db->where($key);
        } else {
            $this->db->where($key, $value);
        }

        return $this->db->get($this->_table)->row();
    }

    public function get_many_by($params = array()) {

        if (!empty($params['key'])) {
            $this->db->where('options.key', $params['key']);
        }

        if (!empty($params['value'])) {
            $this->db->where('options.value', $params['value']);
        }

        return $this->get_all();
    }

    public function insert($input) {
        return parent::insert($input);
    }

    public function update($id, $input) {
        return parent::update($id, $input);
    }

    public function check_exists($field, $value = '', $id = 0) {
        if (is_array($field)) {
            $params = $field;
            $id = $value;
        } else {
            $params[$field] = $value;
        }
        $params['options.id !='] = (int) $id;

        return parent::count_by($params) == 0;
    }

}

/* End of file settings_m.php */
/* Location: ./application/models/settings_m.php */