<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 12/11/2017
 * Time: 10:45
 */

class Authorization
{
    static public function hasAdminAccess() {
        return !empty($_SESSION['user']);
    }

    static public function checkAccess() {
        if (!self::hasAdminAccess()) {
            exit("You don't have access.");
        }
    }
}