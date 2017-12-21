<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 10:16
 */

class PropertyManager extends BaseModel
{
    protected $table = 'properties';
    protected $validationRules = array(
        array('title, description, rentalProperty, propertyType, noOfBedrooms, area', 'required'),
        array('title', 'maxLength', 100),
        array('price', 'price'),
        array('postcode', 'postcode'),
        array('description, images', 'maxLength', 2000)
    );

    protected $fieldLabels = array(
        'title' => 'Title',
        'description' => 'Description',
        'rentalProperty' => 'Rent or Buy',
        'propertyType' => 'Property type',
        'noOfBedrooms' => 'No. of bedrooms',
        'postcode' => 'Postcode',
        'price' => 'Price',
        'area' => 'Area'
    );

    protected $dopDownValueLabels = array(
        'propertyType' => array(
            "" => "Please select",
            "houses" => "Houses",
            "flats" => "Flats / Apartments",
            "bungalows" => "Bungalows",
            "commercial" => "Commercial Property",
            "other" => "Other"
        ),
        'status' => array(
            "available" => "Available",
            "underOffer" => "Under Offer",
            "unavailable" => "Unavailable"
        ),
        'noOfBedrooms' => array(
            "" => "Please select",
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5+' => '5 and over'
        ),
        'areas' => array(
            "" => "Any",
            "abbeywood" => "Abbey Wood",
            "greenwich" => "Greenwich",
            "kingscross" => "King's Cross",
            "plaistow" => "Plaistow",
            "upminster" => "Upminster"
        )
    );

    public function validate($record)
    {
        parent::validate($record);
        return empty($this->errors);
    }

    public function update($property)
    {
        $result = false;
        if (isset($property['id'])) {
            $id = $property['id'];
            unset($property['id']);
        }
        if ($this->validate($property)) {
            $property['updated_at'] = date("Y-m-d H:i:s");
            $this->processPropertyForSaving($property);
            $result = DB::updateById($this->table, $id, $property);
        };
        return $result;
    }

    public function create($property)
    {
        $result = false;
        if ($this->validate($property)) {
            $property['created_at'] = date("Y-m-d H:i:s");
            $this->processPropertyForSaving($property);
            $result = DB::insert($this->table, $property);
        }
        return $result;
    }

    public function processPropertyForSaving(&$property) {
        $property['rentalProperty'] = $property['rentalProperty'] === 'rent' ? true : false;
        $location = Core::getLatLng($property['postcode']);
        $property['lat'] = $location[0];
        $property['lng'] = $location[1];
    }

    // Returns an property from the database based on a URL
    public function getPropertyById($id)
    {
        return Db::queryOne($this->table, array('id' => $id));
    }

    public function delete($propertyId) {
        return Db::delete($this->table, $propertyId);
    }

    public function findProperties($searchParams)
    {
        if (!empty($searchParams['rentalProperty'])) {
            $where = 'rentalProperty = :rentalProperty ';
            $params['rentalProperty'] = $searchParams['rentalProperty'] === 'rent' ? 1 : 0;
        }

        if (!empty($searchParams['propertyType'])) {
            $where .= 'AND propertyType = :propertyType ';
            $params['propertyType'] = $searchParams['propertyType'];
        }

        if (!empty($searchParams['priceRange'])) {
            if ($searchParams['rentalProperty'] === 'buy') {
                switch ($searchParams['priceRange']) {
                    case "0-50":
                        $where .= 'AND price >= 0 AND price <= 50000';
                        break;
                    case "50-75":
                        $where .= 'AND price >= 50000 AND price <= 75000';
                        break;
                    case "75-100":
                        $where .= 'AND price >= 75000 AND price <= 100000';
                        break;
                    case "100-150":
                        $where .= 'AND price >= 100000 AND price <= 150000';
                        break;
                    case "150-200":
                        $where .= 'AND price >= 150000 AND price <= 200000';
                        break;
                    case "200-250":
                        $where .= 'AND price >= 200000 AND price <= 250000';
                        break;
                    case "250-300":
                        $where .= 'AND price >= 250000 AND price <= 300000';
                        break;
                    case "350-500":
                        $where .= 'AND price >= 300000 AND price <= 350000';
                        break;
                    case "500+":
                        $where .= 'AND price >= 500000';
                        break;
                }
            } else {

                switch ($searchParams['priceRange']) {
                    case "0-50":
                        $where .= 'AND price >= 0 AND price <= 50';
                        break;
                    case "50-100":
                        $where .= 'AND price >= 50 AND price <= 100';
                        break;
                    case "100-150":
                        $where .= 'AND price >= 100 AND price <= 150';
                        break;
                    case "150-200":
                        $where .= 'AND price >= 150 AND price <= 200';
                        break;
                    case "200-300":
                        $where .= 'AND price >= 200 AND price <= 300';
                        break;
                    case "300-400":
                        $where .= 'AND price >= 300 AND price <= 400';
                        break;
                    case "400-500":
                        $where .= 'AND price >= 400 AND price <= 500';
                        break;
                    case "500-600":
                        $where .= 'AND price >= 500 AND price <= 600';
                        break;
                    case "600+":
                        $where .= 'AND price >= 600';
                        break;
                }
            }
        }

        if (!empty($searchParams['minBedroomNo'])) {
            if ($searchParams['minBedroomNo'] == '5+') {
                $where .= ' AND noOfBedrooms >= :minBedroomNo';
                $params['minBedroomNo'] = $searchParams['minBedroomNo'];
            } else {
                $where .= ' AND noOfBedrooms <= :minBedroomNo';
                $params['minBedroomNo'] = $searchParams['minBedroomNo'];
            }
        }

        if (!empty($searchParams['area'])) {
            $where .= ' AND area = :area';
            $params['area'] = $searchParams['area'];
        }

        switch ($searchParams['orderBy']) {
            case "createdAsc":
                $orderBy = 'created_at ASC';
                break;
            case "priceAsc":
                $orderBy = 'price ASC';
                break;
            case "priceDesc":
                $orderBy = 'price DESC';
                break;
            default:
                $orderBy = 'created_at DESC';
                break;
        }

        return Db::queryAll("
                        SELECT *
                        FROM properties
                        WHERE $where
                        ORDER BY $orderBy
                ", $params
        );
    }


}