<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 14:23
 */

class Core
{

    static function requests()
    {
        $full_uri = explode("?", $_SERVER['REQUEST_URI']);
        $path_uri = $full_uri[0];            // Ignoring querystring from URI

        $uri = explode('/', $path_uri);

        # Filter request array
        $uri = array_filter($uri, array(__CLASS__, "filter_request_array"));
        # Re-Index request array
        $requests = array_values($uri);
        return $requests;
    }

    ## Array Filter Call Back
    static function filter_request_array($element)
    {
        # Remove everything excepts Alphu-Num, dash and underscore
        return preg_replace('/\W\-\_/si', '', $element);
    }

    static function getController()
    {
        global $config;
        $requests = requests();

        if ($requests[0])
            return $requests[0];
        else
            return $config['default-controller'];
    }

    static function getRealIpAddr()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    static function getLatLng($postcode, $address = '') {
        $address = "$address, London, $postcode UK";
        $coordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true&key=AIzaSyDmP2ModT_nKjuqloZFYqOBtewPGLN0SC4');
        $coordinates = json_decode($coordinates);

        return array(
            $coordinates->results[0]->geometry->location->lat,
            $coordinates->results[0]->geometry->location->lng
        );
    }

    /**
     * Custom validation callback to validate UK postcodes.
     *
     * It also tries to format provided postcode in correct format.
     *
     * Note: It's only usable for "postcode" fields.
     */
    static public function check_postcode_uk($original_postcode)
    {
        // Set callback's custom error message (CI specific)
        // $this->set_message('check_postcode_uk', 'Invalid UK postcode format.');

        // Permitted letters depend upon their position in the postcode.
        // Character 1
        $alpha1 = "[abcdefghijklmnoprstuwyz]";
        // Character 2
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";
        // Character 3
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";
        // Character 4
        $alpha4 = "[abehmnprvwxy]";
        // Character 5
        $alpha5 = "[abdefghjlnpqrstuwxyz]";

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[0] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Expression for postcodes: ANA NAA
        $pcexp[1] = '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Expression for postcodes: AANA NAA
        $pcexp[2] = '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Exception for the special postcode GIR 0AA
        $pcexp[3] = '/^(gir)([[:space:]]{0,})(0aa)$/';

        // Standard BFPO numbers
        $pcexp[4] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

        // c/o BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

        // Overseas Territories
        $pcexp[6] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

        // Anquilla
        $pcexp[7] = '/^ai-2640$/';

        // Load up the string to check, converting into lowercase
        $postcode = strtolower($original_postcode);

        // Assume we are not going to find a valid postcode
        $valid = FALSE;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp)
        {
            if (preg_match($regexp, $postcode, $matches))
            {
                // Load new postcode back into the form element
                $postcode = strtoupper ($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace ('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                preg_match($pcexp[7], strtolower($original_postcode), $matches) AND $postcode = 'AI-2640';

                // Remember that we have found that the code is valid and break from loop
                $valid = TRUE;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        return $valid ? $postcode : FALSE;
    }


}