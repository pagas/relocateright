<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 22/11/2017
 * Time: 23:34
 */

class LoginHelper
{

    public function login($password, $username) {
        $result = false;
        $userManager = new UserManager();
        $user = $userManager->findUserForSignIn($username);
        if (!empty($user) && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $result = true;
        }
        return $result;
    }

    public function logout() {
        // At the moment we are storing only user's data in the session.
        return session_destroy();
    }

}