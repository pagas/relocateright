<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 18/12/2017
 * Time: 22:13
 */

class SellingRequestValidator extends BaseModel
{
    protected $table = 'sellingRequests';

    protected $validationRules = array(
        array('requestType, propertyType, noOfBedrooms, postcode, name, email', 'required'),
        array('email','email'),
        array('price', 'price'),
        array('name, postcode', 'maxLength', 100),
        array('details', 'maxLength', 10000),
    );
    protected $fieldLabels = array(
        'requestType' => 'Buy or sell',
        'propertyType' => 'Property type',
        'noOfBedrooms' => 'No. of bedrooms',
        'postcode' => 'Postcode',
        'name' => 'Name',
        'email' => 'Email',
    );

    public function save($message) {
        $result = false;
        if ($this->validate($message)) {
            $message['created_at'] = date("Y-m-d H:i:s");
            $message['IP'] = Core::getRealIpAddr();
            $result = DB::insert($this->table, $message);
        }
        return $result;
    }

    public function getAll() {
        return Db::queryAll("
                        SELECT *
                        FROM $this->table
                        ORDER BY created_at DESC
                "
        );
    }


}