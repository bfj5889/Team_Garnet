<?php
require_once '../../data/HistoricFilterData.class.php';
require_once '../../data/GraveObjectData.class.php';
require_once '../../models/HistoricFilter.class.php';
/**
 */

class HistoricFilterService {
    public function __construct(){
    }

    public function getAllHistoricFilterEntries() {
        $historicFilterDataClass = new HistoricFilterData();
        $allHistoricFilterDataObjects =  $historicFilterDataClass -> readHistoricFilter();
        $allHistoricFilterData = array();

        foreach ($allHistoricFilterDataObjects as $historicFilterArray) {
            $historicFilterObject = new HistoricFilter($historicFilterArray['idHistoricFilter'], $historicFilterArray['historicFilterName'], $historicFilterArray['dateStart'], $historicFilterArray['dateEnd'], $historicFilterArray['description'], $historicFilterArray['buttonColor']);

            array_push($allHistoricFilterData, $historicFilterObject);
        }
        return $allHistoricFilterData;
    }

    public function createHistoricFilterEntry($historicFilterName, $dateStart, $description, $dateEnd, $buttonColor) {
        $dateStart = filter_var($dateStart, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $dateEnd = filter_var($dateEnd, FILTER_SANITIZE_STRING);
        $buttonColor = filter_var($buttonColor, FILTER_SANITIZE_STRING);
        $historicFilterName = filter_var($historicFilterName, FILTER_SANITIZE_STRING);

        //create HistoricFilter Object
        $historicFilterDataClass = new HistoricFilterData();
        $historicFilterDataClass -> createHistoricFilter($historicFilterName, $dateStart, $description, $dateEnd, $buttonColor);
    }

    public function updateHistoricFilterEntry($idHistoricFilter, $historicFilterName, $dateStart, $description, $dateEnd, $buttonColor) {
        $dateStart = filter_var($dateStart, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $dateEnd = filter_var($dateEnd, FILTER_SANITIZE_STRING);
        $buttonColor = filter_var($buttonColor, FILTER_SANITIZE_STRING);
        $historicFilterName = filter_var($historicFilterName, FILTER_SANITIZE_STRING);
        $idHistoricFilter = filter_var($idHistoricFilter, FILTER_SANITIZE_NUMBER_INT);

        $historicFilterDataClass = new HistoricFilterData();
        $historicFilterDataClass -> updateHistoricFilter($idHistoricFilter, $historicFilterName, $dateStart, $description, $dateEnd, $buttonColor);
    }

    public function deleteHistoricFilterEntry($idHistoricFilter) {
        $idHistoricFilter = filter_var($idHistoricFilter, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idHistoricFilter) || $idHistoricFilter == "") {
            return;
        } else {
            $graveDataClass = new GraveObjectData();
            $graveDataClass -> unsetHistoricFilterId($idHistoricFilter);

            $historicFilterDataClass = new HistoricFilterData();
            $historicFilterDataClass -> deleteHistoricFilter($idHistoricFilter);
        }
    }

    public function getAllEntriesAsRows() {
        $allModels = $this -> getAllHistoricFilterEntries();
        $html = "";
        //TODO may need to make a function that makes a JS array to hold the info
        foreach ($allModels as $model) {
            $objectRowID = strval($model->getIdHistoricFilter()) . "historicFilter";
            $editAndDelete = "</td><td><button onclick='updateHistoricFilter("
                . "'" . $objectRowID . "'"
                . ")'>Update</button>"
                . "</td><td><button onclick=" . '"deleteObject('
                . $objectRowID
                . ')"> Delete</button>';
            $html = $html . "<tr><td>" . $model->getHistoricFilterName()
                . "</td><td>" . $model->getDateStart()
                . "</td><td>" . $model->getDateEnd()
                . "</td><td>" . $model->getDescription()
                . "</td><td>" . $model->getButtonColor()
                . $editAndDelete
                . "</td></tr>";
        }
        return $html;
    }
}