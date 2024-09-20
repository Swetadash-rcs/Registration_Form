<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'libraries/entity/base_entity.php';
class User_entity extends Base_entity
{
    /**
     * Define the mapping between database column names and class property names.
     */
    protected $datamap = array(
        'userId' => 'user_id',
        'emailId' => 'email_id',
        'password' => 'password',
    );

    /**
     * Define the default values for class properties.
     */
    protected $attributes = array(
        'user_id' => null,
        'email_id' => null,
        'password' => null
    );

 
}
