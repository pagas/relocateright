<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 09:32
 */

class ContactController extends Controller
{
    protected $loadScript = array(
        "https://maps.googleapis.com/maps/api/js?key=AIzaSyDmP2ModT_nKjuqloZFYqOBtewPGLN0SC4",
        Config::CLIENT_ASSET_PATH . "javascript/gmaps.js",
        Config::CLIENT_SRC_PATH . "contactPage.js"
    );
    public function index()
    {

        $this->view = 'contact';
    }

    public function sendEnquiry() {
        $enquiry = $_POST;
        $enquiryValidator = new EnquiryValidator();
        $successfullyValidatedAndSaved = $enquiryValidator->save($enquiry);

        if ($successfullyValidatedAndSaved) {
            $emailSender = new EmailSender();
            $emailSent = $emailSender->send(Config::ADMIN_EMAIL, $enquiry['subject'], $enquiry['message'], $enquiry['email']);
        }

        $this->respondJSON(array(
            'success' => $successfullyValidatedAndSaved && $emailSent,
            'errors' => $enquiryValidator->getErrors()
        ));
    }

    public function getEnquiries() {
        $enquiryValidator = new EnquiryValidator();
        $this->respondJSON(array('data' => $enquiryValidator->getAll()));
        exit();
    }

}