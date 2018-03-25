<?php
include_once '../../data/WiderAreaMapData.class.php';
include_once '../../data/EventData.class.php';
include_once '../../models/WiderAreaMap.class.php';
/**
 */

class WiderAreaMapService {
    public function __construct(){
    }

    public function getAllWiderAreaMapEntries() {
        $widerAreaMapDataClass = new WiderAreaMapData();
        $allWiderAreaMapDataObjects =  $widerAreaMapDataClass -> readWiderAreaMap();
        $allWiderAreaMapData = array();

        foreach ($allWiderAreaMapDataObjects as $widerAreaMapArray) {
            $widerAreaMapObject = new WiderAreaMap($widerAreaMapArray['idWiderAreaMap'], $widerAreaMapArray['name'],$widerAreaMapArray['description'], $widerAreaMapArray['longitude'], $widerAreaMapArray['latitude'] , $widerAreaMapArray['address'], $widerAreaMapArray['city'], $widerAreaMapArray['state'], $widerAreaMapArray['zipcode']);

            array_push($allWiderAreaMapData, $widerAreaMapObject);
        }
        return $allWiderAreaMapData;
    }

    public function createWiderAreaMapEntry($url, $name, $description, $longitude, $latitude, $address, $city, $state, $zipcode) {
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $longitude = filter_var($longitude, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION);
        $latitude = filter_var($latitude, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION);
        $city = filter_var($city, FILTER_SANITIZE_STRING);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $zipcode = filter_var($zipcode, FILTER_SANITIZE_STRING);
        $state = filter_var($state, FILTER_SANITIZE_STRING);
        $url = filter_var($url, FILTER_SANITIZE_EMAIL);
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        $zipcode = filter_var($zipcode, FILTER_SANITIZE_NUMBER_INT);


        //create WiderAreaMap Object
        $widerAreaMapDataClass = new WiderAreaMapData();
        $widerAreaMapDataClass -> createWiderAreaMap($url, $name, $description, $longitude, $latitude, $address, $city, $state, $zipcode);
    }

    public function updateWiderAreaMapEntry($idWiderAreaMap, $url, $name, $description, $longitude, $latitude, $address, $city, $state, $zipcode) {
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $longitude = filter_var($longitude, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION);
        $latitude = filter_var($latitude, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION);
        $city = filter_var($city, FILTER_SANITIZE_STRING);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $zipcode = filter_var($zipcode, FILTER_SANITIZE_STRING);
        $state = filter_var($state, FILTER_SANITIZE_STRING);
        $url = filter_var($url, FILTER_SANITIZE_EMAIL);
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        $zipcode = filter_var($zipcode, FILTER_SANITIZE_NUMBER_INT);

        $widerAreaMapDataClass = new WiderAreaMapData();
        $widerAreaMapDataClass -> updateWiderAreaMap($idWiderAreaMap, $url, $name, $description, $longitude, $latitude, $address, $city, $state, $zipcode);
    }

    public function deleteWiderAreaMapEntry($idWiderAreaMap) {
        $idWiderAreaMap = filter_var($idWiderAreaMap, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idWiderAreaMap) || $idWiderAreaMap == "") {
            return;
        } else {
            $eventDataClass = new EventData();
            $eventDataClass -> deleteLocationConnectedEvents($idWiderAreaMap);

            $widerAreaMapDataClass = new WiderAreaMapData();
            $widerAreaMapDataClass -> deleteWiderAreaMap($idWiderAreaMap);
        }
    }

    public function getAllEntriesAsRows() {
        $allmodels = $this -> getAllWiderAreaMapEntries();
        $html = "";
        foreach ($allmodels as $model) {
            $objectRowID = "16" . strval($model->getIdWiderAreaMap());
            $editAndDelete = "</td><td><button onclick='updateLocation("
                . $objectRowID
                . ")'>Update</button>"
                . "</td><td><button onclick=" . '"deleteLocation('
                . $model->getIdWiderAreaMap()
                . ')"> Delete</button>';
            $html = $html . "<tr id='" . $objectRowID . "'><td>" . $model->getName()
                . "</td><td>" . $model->getDescription()
                . "</td><td>" . $model->getLongitude()
                . "</td><td>" . $model->getLatitude()
                . "</td><td>" . $model->getAddress()
                . "</td><td>" . $model->getCity()
                . "</td><td>" . $model->getState()
                . "</td><td>" . $model->getZipcode()
                . $editAndDelete
                . "</td></tr>";
        }
        return $html;
    }
}

if (isset($_GET['delete'])) {
    $service = new WiderAreaMapService();
    $service -> deleteWiderAreaMapEntry($_GET['delete']);
    unset($_GET['delete']);
}