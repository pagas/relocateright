<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 04/11/2017
 * Time: 12:02
 */

class UserManager extends BaseModel
{
    protected $table = 'users';
    protected $validationRules = array(
        array('firstname, lastname, username, email, password', 'required'),
        array('email','email'),
        array('firstname, lastname, username, password', 'maxLength', 100),
        array('username, email', 'unique'),
    );
    protected $fieldLabels = array(
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'username' => 'Username',
        'email' => 'Email',
        'password' => 'Password'
    );

    public function validate($user)
    {
        parent::validate($user);
        if (isset($record['password']) && strlen($user['password']) < 6) {
            $this->addError('password', 'Password is too short.');
        }

        return empty($this->errors);
    }

    public function findUserForSignIn($username)
    {
        return Db::queryOne($this->table, array('username' => $username));
    }

    public function createUser($user)
    {
        $result = false;
        if ($this->validate($user)) {
            $user['created_at'] = date("Y-m-d H:i:s");
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
            $result = DB::insert($this->table, $user);
        }
        return $result;
    }

//    /**
//     * This function is not used
//     * @param $id
//     * @param $user
//     */
//    public function updateUser($id, $user)
//    {
//        if ($this->validate($user)) {
//            $user['updated_at'] = date("Y-m-d H:i:s");
//            DB::save($this->table, $user);
//        }
//    }

}