<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:32
 */

class SellingController extends Controller
{

    protected $loadScript = array(
        Config::CLIENT_SRC_PATH . "sellingPage.js",
    );

    public function index()
    {
        $this->view = 'selling';
    }


    public function sendRequest() {
        $requestData = $_POST;
        $sellingRequestValidator = new SellingRequestValidator();
        $successfullyValidatedAndSaved = $sellingRequestValidator->save($requestData);

        if ($successfullyValidatedAndSaved) {
            $emailSender = new EmailSender();
            $message = "Customer {$requestData['name']} made a request to {$requestData['requestType']} his property. " .
                "It is a {$requestData['noOfBedrooms']} bedroom {$requestData['propertyType']}" .
                "Which is located in {$requestData['postcode']} area.";
            $subject = "{$requestData['name']}'s {$requestData['requestType']} request.";
            $emailSent = $emailSender->send(Config::ADMIN_EMAIL, $subject, $message, $requestData['email']);
        }

        $this->respondJSON(array(
            'success' => $successfullyValidatedAndSaved && $emailSent,
            'errors' => $sellingRequestValidator->getErrors()
        ));
    }
}