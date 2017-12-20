    <?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 04/11/2017
 * Time: 12:47
 */

abstract class Controller
{

    protected $layout;              // Main page view, can be defined per controller
    protected $params = array();    // Params presented in the main view
    protected $view = "";           // The action specific view
    protected $loadScript = array();

    public function renderView()
    {
        if ($this->view) {
            extract($this->params);
            require(Config::SERVER_PATH . "views/" . $this->view . ".phtml");
        }
    }

    public function renderScript() {
        foreach ($this->loadScript as $fileUrl) {
            echo "<script src='$fileUrl'></script>";
        }
    }

    public function getParams() {
        return $this->params;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function redirect($url)
    {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }

    protected function respondJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }

}