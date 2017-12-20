<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:12
 */

class ErrorController extends Controller
{
    public function process($params)
    {
        // HTTP header
        header("HTTP/1.0 404 Not Found");
        // HTML header
        $this->params['title'] = 'Error 404';
        // Sets the template
        $this->view = 'error';
    }
}