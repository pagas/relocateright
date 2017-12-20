<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:12
 */

class AdminController extends Controller
{

    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $loginHelper = new LoginHelper();
        $result = $loginHelper->login($password, $username);
        $this->respondJSON(array('success' => $result));
    }

    public function logout() {
        $loginHelper = new LoginHelper();
        if ($loginHelper->logout()) {
            $this->redirect('');
        }
    }

    public function uploadFile() {
        Authorization::checkAccess();

        $output_dir = "server/uploads/";
        if(isset($_FILES["myfile"]))
        {
            $ret = array();

            //	This is for custom errors;
            /*	$custom_error= array();
                $custom_error['jquery-upload-file-error']="File already exists";
                echo json_encode($custom_error);
                die();
            */
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                $ret[]= $fileName;
            }
            else  //Multiple files, file[]
            {
                $fileCount = count($_FILES["myfile"]["name"]);
                for($i=0; $i < $fileCount; $i++)
                {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
                    $ret[]= $fileName;
                }

            }
            echo json_encode($ret);
        }
    }

}