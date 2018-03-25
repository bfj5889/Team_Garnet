<?php
require_once '../../data/FAQData.class.php';
require_once '../../models/FAQ.class.php';
/**
 */

class FAQService {
    public function __construct(){
    }

    public function getAllFAQEntries() {
        $fAQDataClass = new FAQData();
        $allFAQDataObjects =  $fAQDataClass -> readFAQ();
        $allFAQData = array();

        foreach ($allFAQDataObjects as $fAQArray) {
            $fAQObject = new FAQ($fAQArray['idFAQ'], $fAQArray['question'], $fAQArray['answer']);

            array_push($allFAQData, $fAQObject);
        }
        return $allFAQData;
    }

    public function createFAQEntry($question, $answer) {
        $question = filter_var($question, FILTER_SANITIZE_EMAIL);
        $answer = filter_var($answer, FILTER_SANITIZE_STRING);

        //create FAQ Object
        $fAQDataClass = new FAQData();
        $fAQDataClass -> createFAQ($question, $answer);
    }

    public function updateFAQEntry($idFAQ, $question, $answer) {
        $question = filter_var($question, FILTER_SANITIZE_EMAIL);
        $answer = filter_var($answer, FILTER_SANITIZE_STRING);

        $fAQDataClass = new FAQData();
        $fAQDataClass -> updateFAQ($idFAQ, $question, $answer);
    }

    public function deleteFAQEntry($idFAQ) {
        $idFAQ = filter_var($idFAQ, FILTER_SANITIZE_NUMBER_INT);
        if (empty($idFAQ) || $idFAQ == "") {
            return;
        } else {
            $fAQDataClass = new FAQData();
            $fAQDataClass -> deleteFAQ($idFAQ);
        }
    }

    public function getAllEntriesAsRows() {
        $allModels = $this -> getAllFAQEntries();
        $html = "";
        foreach ($allModels as $model) {
            $editAndDelete = "</td><td><button href='updateObjectInfo()'>Update</button>"
                . "</td><td><button href='deleteObjectInfo("
                . "'" . "faq" . "',"
                . $model->getIdFAQ()
                . ")'> Delete</button>";
            $html = $html . "<tr><td>" . $model->getQuestion()
                . "</td><td>" . $model->getAnswer()
                . $editAndDelete
                . "</td></tr>";
        }
        return $html;
    }
}