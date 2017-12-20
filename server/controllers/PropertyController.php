<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 10:39
 */

class PropertyController extends Controller
{

    protected $loadScript = array(
        "https://maps.googleapis.com/maps/api/js?key=AIzaSyDmP2ModT_nKjuqloZFYqOBtewPGLN0SC4",
        Config::CLIENT_ASSET_PATH . "javascript/gmaps.js",
        Config::CLIENT_ASSET_PATH . "javascript/jquery.uploadfile.min.js",
        Config::CLIENT_ASSET_PATH . "javascript/dropzone.js",
        Config::CLIENT_SRC_PATH . "propertiesPage.js",
        Config::CLIENT_SRC_PATH . "propertyPage.js"
    );

    function index()
    {
        $this->params['dropDowns'] = PropertyManager::getAllDropDownLabels();
        $this->view = 'properties';
    }


    function view($request)
    {
        $propertyManager = new PropertyManager();
        $property = array();
        if (!empty($request['actionParams'])) {
            $propertyId = $request['actionParams'][0];
            // Retrieves an property based on its URL
            $property = $propertyManager->getPropertyById($propertyId);
            // If no article was found we redirect to ErrorController
            if (!$property) {
                $this->redirect('error');
            }
        }
        $this->params['property'] = $property;
        $this->view = 'property';
    }

    function edit($request)
    {
        Authorization::checkAccess();

        $propertyManager = new PropertyManager();
        $property = array();
        if (!empty($request['actionParams'])) {
            $propertyId = $request['actionParams'][0];
            // Retrieves an property based on its URL
            $property = $propertyManager->getPropertyById($propertyId);
            // If no article was found we redirect to ErrorController
            if (!$property) {
                $this->redirect('error');
            }
        }
        $this->params['property'] = $property;
        $this->params['dropDowns'] = PropertyManager::getAllDropDownLabels();
        $this->view = 'edit-property';
    }

    function save()
    {
        Authorization::checkAccess();

        $files = $_FILES;
        $property = $_POST;

        $propertyManager = new PropertyManager();
        if (empty($property['id'])) {
            $result = $propertyManager->create($property);
        } else {
            $result = $propertyManager->update($property);
        }

        $this->respondJSON(array(
            'success' => $result,
            'errors' => $propertyManager->getErrors()
        ));
    }

    function create()
    {
        Authorization::checkAccess();

        $this->params['property'] = array(
            'title' => '',
            'description' => '',
            'price' => '',
            'postcode' => ''
        );
        $this->params['dropDowns'] = PropertyManager::getAllDropDownLabels();
        $this->view = 'edit-property';
    }

    function find($request)
    {
        $propertyManager = new PropertyManager();
        $properties = $properties = $propertyManager->findProperties($request['queryParams']);
        foreach ($properties as &$property) {
            $property['status'] = PropertyManager::getDropDownValueLabel('status', $property['status']);
            $property['area'] = PropertyManager::getDropDownValueLabel('areas', $property['area']);
        }

        $this->respondJSON(array(
            'properties' => $properties
        ));
    }

    function delete($request) {
        $propertyManager = new PropertyManager();
        $propertyManager->delete($request['actionParams'][0]);

        $this->redirect("property?message='Property successfully deleted.'");
    }
}