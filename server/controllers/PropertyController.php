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
            if (empty($property['images'])) {
                $property['images'] = 'default.png';
            }

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

        $property = $_POST;
        $propertyManager = new PropertyManager();
        if (empty($property['id'])) {
            $property['images'] = join(',', $this->processImages());
            $result = $propertyManager->create($property);
        } else {
            $originalProperty = $propertyManager->getPropertyById($property['id']);
            $imagesToRemove = array_diff(Core::explodeComma($originalProperty['images']), Core::explodeComma($property['images']));
            $this->removeImages($imagesToRemove);
            $updatedImages = array_merge(Core::explodeComma($property['images']), $this->processImages());
            $property['images'] = join(',', $updatedImages);
            $result = $propertyManager->update($property);
        }

        $this->respondJSON(array(
            'success' => $result,
            'errors' => $propertyManager->getErrors()
        ));
    }

    public function removeImages($images)
    {
        foreach ($images as $image) {
            $files = Core::explodeComma($image);
            unlink(Config::IMAGE_UPLOAD_DIR . $files);
        }
    }

    public function processImages()
    {
        $ds = DIRECTORY_SEPARATOR;
        $storeFolder = 'uploads';
        $uploadedFiles = array();

        if (!empty($_FILES)) {
            foreach ($_FILES['file']['tmp_name'] as $tempFile) {
                $newFileName = uniqid();
                $targetPath = Config::SERVER_PATH . $ds . $storeFolder . $ds;  //4
                $targetFile = $targetPath . $newFileName;
                $result = move_uploaded_file($tempFile, $targetFile);
                $uploadedFiles[] = $newFileName;
            }
        }
        return $uploadedFiles;
    }


    function create()
    {
        Authorization::checkAccess();

        $this->params['property'] = array(
            'title' => '',
            'rentalProperty' => 0,
            'description' => '',
            'price' => '',
            'postcode' => '',
            'images' => ''
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
            if (empty($property['images'])) {
                $property['image'] = 'default.png';
            } else {
                $property['image'] = Core::explodeComma($property['images'])[0];
            }
        }

        $this->respondJSON(array(
            'properties' => $properties
        ));
    }

    function delete($request)
    {
        $propertyManager = new PropertyManager();
        $propertyManager->delete($request['actionParams'][0]);

        $this->redirect("property?message=Property successfully deleted.");
    }
}