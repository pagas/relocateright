<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:32
 */

class UserController extends Controller
{

    public function register() {
        $userManager = new UserManager();
        $result = $userManager->createUser($_POST);

        $this->respondJSON(array(
            'success' => $result,
            'errors' => $userManager->getErrors()
        ));
    }
}