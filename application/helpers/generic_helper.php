<?php
/**
 * Created by PhpStorm.
 * User: Raaz
 * Date: 6/1/14
 * Time: 11:45 AM
 */

namespace application\helpers;

class Generic {
    /**
     * Set Trace
     * @param $data
     * @param bool $die
     */
    public static function _setTrace($data=null,$die=true){
        if(is_string($data)){
            print $data;
        }else{

            print "<pre>";
            print_r($data);
            print "</pre>";
        }
        print "<hr />";
        if($die){
            exit();
        }
    }
} 