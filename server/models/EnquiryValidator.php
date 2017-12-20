<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 18/12/2017
 * Time: 22:13
 */

class EnquiryValidator extends BaseModel
{
    protected $table = 'enquiries';
    protected $validationRules = array(
        array('name, email, subject, message', 'required'),
        array('email','email'),
        array('name', 'maxLength', 100),
        array('subject', 'maxLength', 500),
        array('details', 'maxLength', 10000),
    );
    protected $fieldLabels = array(
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'message' => 'Message',
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