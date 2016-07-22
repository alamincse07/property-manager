<?php
namespace application\helpers;

/**
 * Created by PhpStorm.
 * User: w3e-08
 * Date: 7/22/16
 * Time: 11:55 AM
 */
class solr_import
{


    static  $to_solr_base_url='http://52.36.205.20:8983/solr/';
    static  $core='rental_properties';


    /**
     * @param array $properties
     * @return bool
     */
    public function importData($properties = array())
    {
        //Preparing date for update
        $import_properties = array();
        foreach($properties as $property){
            $prepare_data = array();
            foreach($property  as $key=>$value){
                $prepare_data[$key]  = $value;
                if($key!='id'){
                    $prepare_data[$key] = array("set"=> is_array($value) ? array_values($value) : $value);
                }

            }

            unset($prepare_data["_version_"]);
            $import_properties[] = $prepare_data;
        }
        //Update data to core
        $status = $this->updateDataToSolr(self::$core,$import_properties);
        return $status;
    }
    /**
     * @param string $core
     * @param array $data
     * @return bool
     */

  public  function PrepareSolrDataFormat($array_data){

        $solr_data = [];

        if(!isset($array_data[0])){
            $single = $array_data;
            unset($array_data);         //remove elements for single element
            $array_data[0] = $single;
        }


        foreach($array_data as $k=>$array){



            //print_r($array);die;
            if(isset($array['property']['property_details']['property_id']) && $array['property']['property_details']['property_id']!=''){

                $solr_data[$k]['id'] = @$array['property']['property_details']['property_id'];
                $solr_data[$k]['feed_provider_id'] = @explode('-',$solr_data[$k]['id'])[1];
                $currency = $this->checkIsset($array['property']['property_details']['currency']);
                $solr_data[$k]['owner_id']  = @$array['property']['property_details']['owner_id'];
                $solr_data[$k]['landlord_telephone']  = @$array['property']['property_details']['telephone'];
                $solr_data[$k]['property_name']  = @$array['property']['property_details']['property_name'];

                $solr_data[$k]["feed"] = 50;
                $solr_data[$k]["feed_name"] = 'Property Manager';


                $max_sleep = (is_numeric(@$array['property']['property_details']['occupancy'])) ? $array['property']['property_details']['occupancy'] : 0;
                $solr_data[$k]['property_type'] = isset($array['property']['property_details']['property_type']) ? ucfirst($array['property']['property_details']['property_type']) : '';
                $solr_data[$k]["bedroom_count"] = (is_numeric(@$array['property']['property_details']['bedroom_count'])) ? $array['property']['property_details']['bedroom_count'] : 0;
                $solr_data[$k]["bathroom_count"] = (is_numeric(@$array['property']['property_details']['bathroom_count'])) ? $array['property']['property_details']['bathroom_count'] : 0;
                $solr_data[$k]["max_occupancy"] = $max_sleep;
                $solr_data[$k]["occupancy"] = $max_sleep;


                $feed_provider_url = $this->checkIsset($array['property']['property_details']['url']);
                $solr_data[$k]["feed_provider_url"] = $feed_provider_url;
                $solr_data[$k]["published"] = (!empty($feed_provider_url)) ? 1 : 0;
                $solr_data[$k]['property_description'] = $this->checkIsset($array['property']['property_descriptions']);


                $country = @$array['property']['property_addresses']['country'];


                if ($country == 'United States') {
                    $country = 'USA';
                    $solr_data[$k]['country'] = $country;
                }

                $city = $this->checkIsset($array['property']['property_addresses']['city']);
                $state = $this->checkIsset($array['property']['property_addresses']['state']);
                $latitude = $this->checkIsset($array['property']['property_addresses']['latitude']);
                $longitude = $this->checkIsset($array['property']['property_addresses']['longitude']);
                $solr_data[$k]['address1'] = $this->checkIsset($array['property']['property_addresses']['address1']);
                $solr_data[$k]['zip'] = $this->checkIsset($array['property']['property_addresses']['zip']);
                $solr_data[$k]["latlon"] = $this->isValidlatLong('lat', $this->formatValue(@$latitude, 0)) . "," . $this->isValidlatLong('long', $this->formatValue(@$longitude, 0));


                if($currency!=''){
                    $solr_data[$k]['night_rate_min'] = @$array['property']['property_rate_summary']['day_min_rate'].','.$currency;
                    $solr_data[$k]['week_rate_min'] = @$array['property']['property_rate_summary']['week_min_rate'].','.$currency;
                    $solr_data[$k]['week_rate_max'] = @$array['property']['property_rate_summary']['week_max_rate'].','.$currency;
                }

                $amenities = $this->checkIsset($array['property']['property_amenities']['property_amenity']);
                $amenities =  json_decode($amenities,1);
                $solr_data[$k]['amenities'] = $amenities;

                $booked_date = json_decode($this->checkIsset($array['property']['booked_dates']['booked_date']),1);
                $solr_data[$k]['booked_date'] = array_map(function($val) { return $val.'T00:00:00Z';} , $booked_date);

                $property_rates = $array['property']['property_rates']['property_rate'];

                $min_stay = $this->getMin($property_rates);

                $solr_data[$k]['min_stay'] = ($min_stay > 0) ? $min_stay : 1;
                $solr_data[$k]['changeover_day'] = [0, 1, 2, 3, 4, 5, 6];



                $solr_data[$k]["amenities_count"] = !empty($amenities) ? count($amenities) : 0;
                $images =array_values (array_filter($array['property']['property_photos']['property_photo']));
                $solr_data[$k]['images'] = $images;
                $solr_data[$k]["feature_image"] =$images[0];

                // for display

                $d[] = ucwords($city);
                $d[] = ucwords($state);
                $d[] = ucwords($country);

                $solr_data[$k]['display'] = implode(", ",array_filter($d));

            }else{
                die('property id missing');
            }
        }

        return $solr_data;

    }


    public static function updateDataToSolr($core = '', $data = array())
    {
        $solr_data = json_encode($data);
        $solr_url = self::$to_solr_base_url . "$core/";
        $ch = curl_init($solr_url . 'update?commit=true');
        //$ch = curl_init($solr_url . 'update');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $solr_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (isset($result['responseHeader']['status']) && $result['responseHeader']['status'] == 0) {
            return true;
        }else{
            \application\helpers\Generic::_setTrace($result);
            return false;
        }

    }
    /**
     * @param $value
     * @param null $default
     * @param bool|false $max
     * @param bool|false $debug
     * @return array|int|null|string
     */
   public function formatValue($value, $default = null, $max = false, $debug = false)
    {
        if (is_string($value)) {
            if ($max) {
                $value = substr(strip_tags($value), 0, 255);
            }
            $value = trim($value);
        }
        if (($default || is_numeric($default)) && !$value) {
            $value = $default;
        }
        if (is_numeric($value)) {
            return $value;
        }
        if ($value && is_array($value)) {
            $value = array_filter($value);
            $value = json_encode($value);
            return $value;
        }
        if (!$value) {
            $value = $default;
        }
        return $value;
    }
    /**
     * @param $type
     * @param $val
     * @return string
     */
    public  function isValidlatLong($type, $val)
    {
        //======== type lat, lonn ======
        if ($type == "lat") {
            if (preg_match("/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", $val)) {
                return $val;
            } else {
                return ".001";
            }
        } else if ($type == "long") {
            if (preg_match("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/",
                $val)) {
                return $val;
            } else {
                return ".001";
            }
        } else {
            die($val);
        }
    }
    /**
     * @param $field_name
     * @return array|int|null|string
     */
    public  function checkIsset($field_name){
        return (isset($field_name) && $field_name!='') ? $this->formatValue($field_name) : '';
    }
    /**
     * @param $array
     * @return int|mixed
     */
    public  function getMin( $array )
    {
        $min = 100;
        foreach( $array as $k => $v )
        {
            $min = min( array( $min, $v['min_stay_count'] ) );
        }
        return $min;
    }


}