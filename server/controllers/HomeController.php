<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:12
 */

class HomeController extends Controller
{

    protected $loadScript = array(
        "https://maps.googleapis.com/maps/api/js?key=AIzaSyDmP2ModT_nKjuqloZFYqOBtewPGLN0SC4",
        Config::CLIENT_ASSET_PATH . "javascript/gmaps.js",
        Config::CLIENT_SRC_PATH . "propertiesPage.js",
        Config::CLIENT_SRC_PATH . "propertyPage.js"
    );


    public function index() {
        $this->params['dropDowns'] = PropertyManager::getAllDropDownLabels();
        $this->view = 'properties';
    }
}