<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 16/11/2017
 * Time: 03:29
 */

class LayoutControl
{

    static public function isRouteRootActive($routeRoot) {
        $route = new Router($_SERVER['REQUEST_URI']);
        $path = $route->parseUrl();
        return $path[0] === $routeRoot ? 'active' : 'www';
    }

    static public function showSelectControl($options, $selectedValue = '') {
        foreach ($options as $value => $label) {
            $selected = $selectedValue===$value?'selected':'';
            echo "<option value='$value' $selected> $label </option>";
        }
    }
}