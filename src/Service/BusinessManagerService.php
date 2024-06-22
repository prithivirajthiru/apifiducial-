<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RefRequeststatus;
use App\Entity\OptionsTabel;
use App\Entity\RefZipcode;
use App\Entity\DataClient;
use App\Entity\DataAttorney;
use App\Entity\DataRequest;
use App\Entity\RefLegalform;
use App\Entity\DataFieldIssue;
use App\Entity\RefField;
use App\Entity\RefEpa;
use App\Entity\RefTown;
use App\Utils\AverageCalculation;
use App\Utils\RequestCount;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\DataTreatment;

class BusinessManagerService
{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    public function _getRequestByStatusWithPeriod($period)
    {
        if ($period == "day") {
            $period = "yesterday";
        }
        if ($period == "week") {
            $period = "last week";
        }
        if ($period == "month") {
            $period = "last month";
        }
        if ($period == "year") {
            $period = "last year";
        }
        $d = strtotime("today");
        $todate = date("Y-m-d", $d);
        $d = strtotime($period);
        $fromdate = date("Y-m-d", $d);
        $refrequeststatusrepo = $this->EM->getRepository(RefRequeststatus::class);
        $requestStatuses = $refrequeststatusrepo->findBy(['active_requeststatus' => 'Active']);
        $arrAllRequestCount = array();
        foreach ($requestStatuses as $requestStatus) {
            $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND dateupd_request BETWEEN :fromdate AND :todate";
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);

            // $stmt->bindValue('requeststatus_id',(int)$requestStatus->getId());
            // $stmt->bindValue('fromdate',(string)$fromdate);
            // $stmt->bindValue('todate',(string)$todate);
            // $stmt->execute();
            // $result = $stmt->fetch();

            $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId(), 'fromdate' => (string)$fromdate, 'todate' => (string)$todate])->fetch();
            if ($result["count(id)"] != 0) {
                $arrAllRequestCount[$requestStatus->getStatusRequeststatus()] = $result["count(id)"];
            }
        }
        return $arrAllRequestCount;
    }

    public function _getRequestByStatusWithDate($date)
    {
        $refrequeststatusrepo = $this->EM->getRepository(RefRequeststatus::class);
        $requestStatuses = $refrequeststatusrepo->findBy(['active_requeststatus' => 'Active']);
        $arrAllRequestCount = array();
        foreach ($requestStatuses as $requestStatus) {
            $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND CAST(dateupd_request AS DATE) =" . ':requestdate';
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
            // $stmt->bindValue('requestdate', (string)$date);
            // $stmt->execute();
            // $result = $stmt->fetch();
            $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId(), 'requestdate' => (string)$date])->fetch();
            if ($result["count(id)"] != 0) {
                $arrAllRequestCount[$requestStatus->getStatusRequeststatus()] = $result["count(id)"];
            }
        }
        return $arrAllRequestCount;
    }

    public function _getRequestByOriginWithPeriod($period)
    {
        if ($period == "day") {
            $period = "yesterday";
        }
        if ($period == "week") {
            $period = "last week";
        }
        if ($period == "month") {
            $period = "last month";
        }
        if ($period == "year") {
            $period = "last year";
        }
        $d = strtotime("today");
        $todate = date("Y-m-d", $d);
        $d = strtotime($period);
        $fromdate = date("Y-m-d", $d);
        $refrequeststatusrepo = $this->EM->getRepository(RefRequeststatus::class);
        $requestStatuses = $refrequeststatusrepo->findBy(['active_requeststatus' => 'Active']);
        $arrAllRequestCount = array();
        foreach ($requestStatuses as $requestStatus) {
            $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND dateupd_request BETWEEN :fromdate AND :todate";
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
            // $stmt->bindValue('fromdate', (string)$fromdate);
            // $stmt->bindValue('todate', (string)$todate);
            // $stmt->execute();
            // $result = $stmt->fetch();
            $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId(), 'fromdate' => (string)$fromdate, 'todate' => (string)$todate])->fetch();

            if ($result["count(id)"] != 0) {
                if (array_key_exists($requestStatus->getOrigine(), $arrAllRequestCount)) {
                    $arrAllRequestCount[$requestStatus->getOrigine()] = $arrAllRequestCount[$requestStatus->getOrigine()] + $result["count(id)"];
                } else {
                    $arrAllRequestCount[$requestStatus->getOrigine()] = $result["count(id)"];
                }
            }
        }
        return $arrAllRequestCount;
    }

    public function _getRequestByOriginWithDate($date)
    {
        $refrequeststatusrepo = $this->EM->getRepository(RefRequeststatus::class);
        $requestStatuses = $refrequeststatusrepo->findBy(['active_requeststatus' => 'Active']);
        $arrAllRequestCount = array();
        foreach ($requestStatuses as $requestStatus) {
            $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND CAST(dateupd_request AS DATE) =" . ':requestdate';
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
            // $stmt->bindValue('requestdate', (string)$date);
            // $stmt->execute();
            // $result = $stmt->fetch();
            $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId(), 'requestdate' => (string)$date])->fetch();

            if ($result["count(id)"] != 0) {
                if (array_key_exists($requestStatus->getOrigine(), $arrAllRequestCount)) {
                    $arrAllRequestCount[$requestStatus->getOrigine()] = $arrAllRequestCount[$requestStatus->getOrigine()] + $result["count(id)"];
                } else {
                    $arrAllRequestCount[$requestStatus->getOrigine()] = $result["count(id)"];
                }
            }
        }
        return $arrAllRequestCount;
    }

    public function _getRequestByPeriod($period, $type)
    {
        if ($period == "day") {
            $period = "yesterday";
        }
        if ($period == "week") {
            $period = "last week";
        }
        if ($period == "month") {
            $period = "last month";
        }
        if ($period == "year") {
            $period = "last year";
        }
        $d = strtotime("today");
        $todate = date("Y-m-d", $d);
        $d = strtotime($period);
        $fromdate = date("Y-m-d", $d);

        $arrRequestIds = array();
        $query = "SELECT id FROM data_request WHERE dateupd_request BETWEEN :fromdate AND :todate";
        $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->bindValue('fromdate', (string)$fromdate);
        // $stmt->bindValue('todate', (string)$todate);
        // $stmt->execute();
        // $requestIds = $stmt->fetchAll();
        // return $requestIds;
        $requestIds = $conn->executeQuery($query, ['fromdate' => (string)$fromdate, 'todate' => (string)$todate])->fetchAll();

        foreach ($requestIds as $requestid) {
            array_push($arrRequestIds, $requestid['id']);
        }

        switch ($type) {
            case "epa":
                $result = $this->getRequestCountEpa($arrRequestIds);
                break;
            case "legalform":
                $result = $this->getRequestCountLegalForm($arrRequestIds);
                break;
            case "zipcode":
                $result = $this->getRequestCountZipCode($arrRequestIds);
                break;
            case "typeclient":
                $result = $this->getRequestCountTypeClient($arrRequestIds);
                break;
        }
        return $result;
    }

    public function getStatusIds($typeIds, $requestIds)
    {
        $arrIds = array();
        foreach ($typeIds as $id) {
            $arrinfo = array_keys($requestIds, $id);
            foreach ($arrinfo as $info) {
                array_push($arrIds, $info);
            }
        }
        return $arrIds;
    }

    public function _getRequestforStatus($request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();


        $normalizers = array($normalizer, new DateTimeNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $content = $request->getContent();
        $averagecalculation = $serializer->deserialize($content, AverageCalculation::class, 'json');
        $status = $averagecalculation->getStatus();

        if ($averagecalculation->getDate()) {
            $date = $averagecalculation->getDate();
            $datecount = count($date);

            if ($status != "newdemand") {

                // return $datecount;
                if ($datecount == 1) {
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id FROM data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') = '$date[0]'";
                    // $conn = $this->EM->getconnection();
                    // $stmt = $conn->prepare($query);
                    // $stmt->execute();
                    // $requestIds = $stmt->fetchAll();
                    $connection = $this->EM->getconnection();
                    $requestIds = $connection->executeQuery($query)->fetchAll();
                    // return $requestIds; 
                }
                if ($datecount == 2) {
                    $arrRequestIds = array();
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id FROM data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN '$date[0]' AND '$date[1]'";
                    // $conn = $this->EM->getconnection();
                    // $stmt = $conn->prepare($query);
                    // $stmt->execute();
                    // $requestIds = $stmt->fetchAll();
                    $connection = $this->EM->getconnection();
                    $requestIds = $connection->executeQuery($query)->fetchAll();
                }
            } else {
                // return $datecount;
                if ($datecount == 1) {
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id FROM data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') = '$date[0]'";
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN '" . $date[0] . "' and '" . $date[0] . "' AND id_requeststatus_id = 4";
                    // $conn = $this->EM->getconnection();
                    // $stmt = $conn->prepare($query);
                    // $stmt->execute();
                    // $requestIds = $stmt->fetchAll();
                    $connection = $this->EM->getconnection();
                    $requestIds = $connection->executeQuery($query)->fetchAll();
                    // return $requestIds; 
                }

                if ($datecount == 2) {
                    $arrRequestIds = array();
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id FROM data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN '$date[0]' AND '$date[1]'";
                    $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN '" . $date[0] . "' and '" . $date[1] . "' AND id_requeststatus_id = 4";
                    // $conn = $this->EM->getconnection();
                    // $stmt = $conn->prepare($query);
                    // $stmt->execute();
                    // $requestIds = $stmt->fetchAll();
                    $connection = $this->EM->getconnection();
                    $requestIds = $connection->executeQuery($query)->fetchAll();
                }
            }
        }
        if ($averagecalculation->getPeriod()) {
            $fromdatetodate = $this->_getFromdateTodate($averagecalculation->getPeriod());
            $fromdate = $fromdatetodate['fromdate'];
            $todate = $fromdatetodate['todate'];
            if ($status != "newdemand") {
                $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id FROM data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN :fromdate AND :todate";
            } else {
                $query = "SELECT id_request_id as id, id_requeststatus_id as requeststatus_id from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN :fromdate AND :todate AND id_requeststatus_id = 4";
            }
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->bindValue('fromdate', (string)$fromdate);
            // $stmt->bindValue('todate', (string)$todate);
            // $stmt->execute();
            // $requestIds = $stmt->fetchAll();
            // return $requestIds;
            $requestIds = $conn->executeQuery($query, ['fromdate' => (string)$fromdate, 'todate' => (string)$todate])->fetchAll();
        }
        if ($averagecalculation->getPeriod() == "all") {
            $query = "SELECT id,requeststatus_id FROM data_request";
            // $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->execute();
            // $requestIds = $stmt->fetchAll();
            $connection = $this->EM->getconnection();
            $requestIds = $connection->executeQuery($query)->fetchAll();
        }
        // return $requestIds;
        $arrdata = array();
        foreach ($requestIds as $requestId) {
            $arrdata[$requestId['id']] = $requestId['requeststatus_id'];
        }

        // return $arrdata;
        if ($averagecalculation->getTypeclient()) {
            $arrdata = $this->typeclientCount($arrdata, $averagecalculation->getTypeclient());
        }
        $status = $averagecalculation->getStatus();
        $type = $averagecalculation->getType();
        $arrRequestIds = array();
        switch ($status) {
            case "prospect":
                $arrRequestIds = $this->getStatusIds([1, 2], $arrdata);
                break;
            case "newdemand":
                $arrRequestIds = $this->getStatusIds([4], $arrdata);
                break;
            case "accept":
                $arrRequestIds = $this->getStatusIds([19, 37], $arrdata);
                break;
            case "refuese":
                $arrRequestIds = $this->getStatusIds([16, 35, 36], $arrdata);
                break;
            case "abandonnes":
                $arrRequestIds = $this->getStatusIds([100], $arrdata);
                break;
            case "all":
                $arrRequestIds = $this->getStatusIds([19, 37, 16, 35, 36, 100], $arrdata);
                break;
        }
        // $arrRequestIds = array();
        // return $arrRequestIds;
        // foreach($requestIds as $requestid){
        //     array_push($arrRequestIds,$requestid['id']);
        // }

        switch ($type) {
            case "epa":
                $result = $this->getRequestCountEpa($arrRequestIds);
                break;
            case "legalform":
                $result = $this->getRequestCountLegalForm($arrRequestIds);
                break;
            case "zipcode":
                $result = $this->getRequestCountZipCode($arrRequestIds);
                break;
            case "typeclient":
                $result = $this->getRequestCountTypeClient($arrRequestIds);
                break;
            case "offer":
                $result = $this->getRequestCountOffer($arrRequestIds);
                break;
            case "turnover":
                $result = $this->getRequestCountTurnover($arrRequestIds);
                break;
            case "fieldissue":
                $result = $this->getRequestCountFieldissue($arrRequestIds);
                break;
            case "clientdetail":
                $result = $this->getRequestCountClientDetail($arrRequestIds);
                break;
            case "all":
                $result = $this->getRequestCountForAll($arrRequestIds);
                break;
        }
        return $result;
    }
    public function getRequestCountForAll($arrRequestIds)
    {
        $epa = $this->getRequestCountEpa($arrRequestIds);
        $legalform = $this->getRequestCountLegalForm($arrRequestIds);
        $zipcode = $this->getRequestCountZipCode($arrRequestIds);
        $typeclient = $this->getRequestCountTypeClient($arrRequestIds);
        $offer = $this->getRequestCountOffer($arrRequestIds);
        $turnover = $this->getRequestCountTurnover($arrRequestIds);
        $fieldissue = $this->getRequestCountFieldissue($arrRequestIds);
        $clientdetail = $this->getRequestCountClientDetail($arrRequestIds);
        return ['epa' => $epa, 'legalform' => $legalform, 'zipcode' => $zipcode, 'typeclient' => $typeclient, 'offer' => $offer, 'turnover' => $turnover, "fieldissue" => $fieldissue, "clientdetail" => $clientdetail];
    }
    public function _getRequestByTypeWithData($period)
    {
        if ($period == "day") {
            $period = "yesterday";
        }
        if ($period == "week") {
            $period = "last week";
        }
        if ($period == "month") {
            $period = "last month";
        }
        if ($period == "year") {
            $period = "last year";
        }
        $d = strtotime("today");
        $todate = date("Y-m-d", $d);
        $d = strtotime($period);
        $fromdate = date("Y-m-d", $d);

        $arrRequestIds = array();
        $query = "SELECT id FROM data_request WHERE dateupd_request BETWEEN :fromdate AND :todate";
        $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->bindValue('fromdate', (string)$fromdate);
        // $stmt->bindValue('todate', (string)$todate);
        // $stmt->execute();
        // $requestIds = $stmt->fetchAll();
        $requestIds = $conn->executeQuery($query, ['fromdate' => (string)$fromdate, 'todate' => (string)$todate])->fetchAll();

        // return $requestIds;
        foreach ($requestIds as $requestid) {
            array_push($arrRequestIds, $requestid['id']);
        }

        $epa = $this->getRequestCountEpa($arrRequestIds);
        $legalform = $this->getRequestCountLegalForm($arrRequestIds);
        $zipcode = $this->getRequestCountZipCode($arrRequestIds);
        $typeclient = $this->getRequestCountTypeClient($arrRequestIds);
        $arrOverall = array();
        $arrOverall = ['epa' => $epa, "legalform" => $legalform, "zipcode" => $zipcode, "typeclient" => $typeclient];
        return $arrOverall;
    }


    public function _getRequestByDate($date, $type)
    {

        $arrRequestIds = array();
        if ($date == "all") {
            $query = "SELECT id FROM data_request";
        } else {
            $query = "SELECT id FROM data_request WHERE CAST(dateupd_request AS DATE) =" . ':requestdate';
        }
        $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->bindValue('requestdate', (string)$date);
        // $stmt->execute();
        // $requestIds = $stmt->fetchAll();
        $requestIds = $conn->executeQuery($query, ['requestdate' => (string)$date])->fetchAll();

        foreach ($requestIds as $requestid) {
            array_push($arrRequestIds, $requestid['id']);
        }
        switch ($type) {
            case "epa":
                $result = $this->getRequestCountEpa($arrRequestIds);
                break;
            case "legalform":
                $result = $this->getRequestCountLegalForm($arrRequestIds);
                break;
            case "zipcode":
                $result = $this->getRequestCountZipCode($arrRequestIds);
                break;
            case "typeclient":
                $result = $this->getRequestCountTypeClient($arrRequestIds);
                break;
        }
        return $result;
    }

    public function _getRequestByTypeWithDataDate($date)
    {

        $arrRequestIds = array();
        if ($date == 'all') {
            $query = "SELECT id FROM data_request";
        } else {
            $query = "SELECT id FROM data_request WHERE CAST(dateupd_request AS DATE) =" . ':requestdate';
        }
        $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->bindValue('requestdate', (string)$date);
        // $stmt->execute();
        // $requestIds = $stmt->fetchAll();
        $requestIds = $conn->executeQuery($query, ['requestdate' => (string)$date])->fetchAll();

        foreach ($requestIds as $requestid) {
            array_push($arrRequestIds, $requestid['id']);
        }
        $epa = $this->getRequestCountEpa($arrRequestIds);
        $legalform = $this->getRequestCountLegalForm($arrRequestIds);
        $zipcode = $this->getRequestCountZipCode($arrRequestIds);
        $typeclient = $this->getRequestCountTypeClient($arrRequestIds);
        $arrOverall = array();
        $arrOverall = ['epa' => $epa, "legalform" => $legalform, "zipcode" => $zipcode, "typeclient" => $typeclient];
        return $arrOverall;
    }

    public function getRequestCountOffer($arrRequestIds)
    {
        $optionsrepo = $this->EM->getRepository(OptionsTabel::class);
        $proplusCheque = 0;
        $proexeCheque = 0;
        $probaseCheque = 0;
        $proplusCash = 0;
        $proexeCash = 0;
        $probaseCash = 0;
        $proplusTpc = 0;
        $proexeTpc = 0;
        $probaseTpc = 0;
        $chkprobase_count = 0;
        $chkproExcellence_count = 0;
        $chkproPlus_count = 0;
        $optionsObjects = $optionsrepo->findBy(array('client' => $arrRequestIds));

        // return $optionsObjects;		
        foreach ($optionsObjects as $optionsObject) {

            if ($optionsObject->getProduct() != null) {
                $product_id = $optionsObject->getProduct()->getId();
                $chkprobase = in_array($product_id, [48, 49]);
                $chkproExcellence = in_array($product_id, [52, 53]);
                // return [$chkproExcellence,$product_id];
                $chkproPlus = in_array($product_id, [50, 51]);
                if ($chkprobase == true) {
                    $chkprobase_count = $chkprobase_count + 1;
                    if ($optionsObject->getCheque() == true) {
                        $probaseCheque = $probaseCheque + 1;
                    }
                    if ($optionsObject->getTpc() == true) {
                        $probaseTpc = $probaseTpc + 1;
                    }
                    if ($optionsObject->getCash() == true) {
                        $probaseCash = $probaseCash + 1;
                    }
                }
                if ($chkproExcellence == true) {
                    $chkproExcellence_count = $chkproExcellence_count + 1;
                    if ($optionsObject->getCheque() == true) {
                        $proexeCheque = $proexeCheque + 1;
                    }
                    if ($optionsObject->getTpc() == true) {
                        $proexeTpc = $proexeTpc + 1;
                    }
                    if ($optionsObject->getCash() == true) {
                        $proexeCash = $proexeCash + 1;
                    }
                }
                if ($chkproPlus == true) {
                    $chkproPlus_count = $chkproPlus_count + 1;
                    if ($optionsObject->getCheque() == true) {
                        $proplusCheque = $proplusCheque + 1;
                    }
                    if ($optionsObject->getTpc() == true) {
                        $proplusTpc = $proplusTpc + 1;
                    }
                    if ($optionsObject->getCash() == true) {
                        $proplusCash = $proplusCash + 1;
                    }
                }
            }
        }
        $arrprobase = ["count" => $chkprobase_count, "cheque" => $probaseCheque, "cash" => $probaseCash, "tpc" => $probaseTpc];
        $arrproexc = ["count" => $chkproExcellence_count, "cheque" => $proexeCheque, "cash" => $proexeCash, "tpc" => $proexeTpc];
        $arrplus = ["count" => $chkproPlus_count, "cheque" => $proplusCheque, "cash" => $proplusCash, "tpc" => $proplusTpc];
        return $arrres = ["probase" => $arrprobase, "proExcellence" => $arrproexc, "proPlus" => $arrplus];
    }
    public function getRequestCountEpa($arrRequestIds)
    {
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $requestObjects = $requestrepo->findBy(array('id' => $arrRequestIds));
        $arrRequestCountwithEpa = array();
        $arrepaIds = array();
        foreach ($requestObjects as $requestObject) {
            if ($requestObject->getClient()->getEpa()) {
                array_push($arrepaIds, $requestObject->getClient()->getEpa()->getId());
            }
        }
        $arrepacount = array_count_values($arrepaIds);
        foreach ($arrepacount as $key => $epacount) {
            $eparepo = $this->EM->getRepository(RefEpa::class);
            $epaObject = $eparepo->findOneBy(['id' => $key]);
            array_push($arrRequestCountwithEpa, ["code" => $epaObject->getEpaCode(), "name" => $epaObject->getDescEpa(), "count" => $epacount]);
        }
        return $arrRequestCountwithEpa;
    }

    public function getRequestCountTurnover($arrRequestIds)
    {
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $requestObjects = $requestrepo->findBy(array('id' => $arrRequestIds));
        // return $requestObjects;
        $arrRequestCountwithEpa = array();
        $less1 = 0;
        $less5 = 0;
        $less1c = 0;
        $mor1c = 0;
        foreach ($requestObjects as $requestObject) {
            //if($requestObject->getClient()->getEpa()){
            $turnover = $requestObject->getClient()->getTurnoverClient();
            if ($turnover > 0 && $turnover <= 100000) {
                $less1 = $less1 + 1;
            }
            if ($turnover > 100000 && $turnover <= 500000) {
                $less5 = $less5 + 1;
            }
            if ($turnover > 500000 && $turnover <= 1000000) {
                $less1c = $less1c + 1;
            }
            if ($turnover > 1000000) {
                $mor1c = $mor1c + 1;
            }
            //}
        }
        return $arrturnover = ["0-1" => $less1, "1-5" => $less5, "5-1c" => $less1c, "more1c" => $mor1c];
    }

    public function getRequestCountLegalForm($arrRequestIds)
    {
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $requestObjects = $requestrepo->findBy(array('id' => $arrRequestIds));
        $arrRequestCountwithLF = array();
        $arrlegalformIds = array();
        foreach ($requestObjects as $requestObject) {
            if ($requestObject->getClient()->getLegalform()) {
                array_push($arrlegalformIds, $requestObject->getClient()->getLegalform()->getId());
            }
        }
        $arrlegalformcount = array_count_values($arrlegalformIds);
        foreach ($arrlegalformcount as $key => $legalformcount) {
            $legalformrepo = $this->EM->getRepository(RefLegalform::class);
            $legalformObject = $legalformrepo->findOneBy(['id' => $key]);
            $legalformObject->getLegalformCode();
            $legalformObject->getDescLegalform();
            array_push($arrRequestCountwithLF, ["code" => $legalformObject->getLegalformCode(), "name" => $legalformObject->getDescLegalform(), "count" => $legalformcount]);
        }
        return $arrRequestCountwithLF;
    }

    public function getRequestCountFieldissue($arrRequestIds)
    {
        $fieldissuerepo = $this->EM->getRepository(DataFieldIssue::class);
        $objfieldissue = $fieldissuerepo->findBy(array('client' => $arrRequestIds));
        $arrRequestCountwithfieldissue = array();
        $arrRequestCountwithfileissue = array();
        $arrfieldissue = array();
        $arrfileissue = array();
        foreach ($objfieldissue as $fieldissue) {
            if ($fieldissue->getField()) {
                if ($fieldissue->getField()->getFieldGroup() != 'FILE') {
                    array_push($arrfieldissue, $fieldissue->getField()->getId());
                } else {
                    array_push($arrfileissue, $fieldissue->getField()->getId());
                }
            }
        }
        $arrfieldissuecount = array_count_values($arrfieldissue);
        $arrfileissuecount = array_count_values($arrfileissue);
        // return $arrfileissuecount;
        foreach ($arrfieldissuecount as $key => $fieldissuecount) {
            $reffieldrepo = $this->EM->getRepository(RefField::class);
            $reffieldObject = $reffieldrepo->findOneBy(['id' => $key]);
            array_push($arrRequestCountwithfieldissue, ["name" => $reffieldObject->getLabel(), "count" => $fieldissuecount]);
        }
        foreach ($arrfileissuecount as $key => $fileissuecount) {
            $reffilerepo = $this->EM->getRepository(RefField::class);
            $reffileObject = $reffilerepo->findOneBy(['id' => $key]);
            array_push($arrRequestCountwithfileissue, ["name" => $reffileObject->getLabel(), "count" => $fileissuecount]);
        }
        return ["fieldissue" => $arrRequestCountwithfieldissue, "fileissue" => $arrRequestCountwithfileissue];
    }
    public function getRequestCountClientDetail($arrRequestIds)
    {
        $dataclientrepo = $this->EM->getRepository(DataClient::class);
        $dataattorneyrepo = $this->EM->getRepository(DataAttorney::class);
        $objdataclient = $dataclientrepo->findBy(array('id' => $arrRequestIds));
        // return $arrRequestIds;
        $arrfinaldetail = array();
        foreach ($objdataclient as $dataclient) {
            // return $dataclient;
            $objattorney = $dataattorneyrepo->findOneBy(['client' => $dataclient->getId(), 'ismandatory_attorney' => true]);
            $name = "";
            if ($objattorney) {
                $name = $objattorney->getNameAttorney() . " " . $objattorney->getSurnameAttorney() . " " . $objattorney->getBirthName();
            }
            $id = $dataclient->getId();
            $epa = array();
            $legalform = array();
            $typeclient = "";
            $companytype = "";
            $siren = $dataclient->getSiren();

            if ($dataclient->getEpa()) {
                $epa = ['desc' => $dataclient->getEpa()->getDescEpa(), 'code' => $dataclient->getEpa()->getEpaCode()];
            }
            if ($dataclient->getLegalform()) {
                $legalform = ['desc' => $dataclient->getLegalform()->getDescLegalform(), 'code' => $dataclient->getLegalform()->getLegalformCode()];
            }
            if ($dataclient->getCompanytype()) {
                $companytype = $dataclient->getCompanytype()->getDescCompanytype();
            }
            if ($dataclient->getTypeclient()) {
                $typeclient = $dataclient->getTypeclient()->getDescTypeclient();
            }
            array_push($arrfinaldetail, ['id' => $id, 'name' => $name, 'epa' => $epa, 'legalform' => $legalform, 'typeclient' => $typeclient, 'companytype' => $companytype, 'siren' => $siren]);
        }
        return $arrfinaldetail;
    }
    public function getRequestCountZipCode($arrRequestIds)
    {
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $requestObjects = $requestrepo->findBy(array('id' => $arrRequestIds));
        $arrRequestCountwithZcode = array();
        foreach ($requestObjects as $requestObject) {
            if ($requestObject->getClient()->getZipcodeClient()) {
                $zipCode = $requestObject->getClient()->getZipcodeClient();
                $dep = substr($zipCode, 0, 2);
                // return $des;
                $townrepo = $this->EM->getRepository(RefTown::class);
                $townobj = $townrepo->findOneBy(['dep' => $dep]);
                $town = "others";
                if ($townobj) {
                    $town = $townobj->getName();
                }
                if (array_key_exists("$town", $arrRequestCountwithZcode)) {
                    $arrRequestCountwithZcode["$town"] = $arrRequestCountwithZcode["$town"] + 1;
                } else {
                    $arrRequestCountwithZcode["$town"] = 1;
                }
            }
        }
        $reszipcodecount = array();
        foreach ($arrRequestCountwithZcode as $key => $RequestCountwithZcode) {
            array_push($reszipcodecount, ['name' => $key, "count" => $RequestCountwithZcode]);
        }
        return $reszipcodecount;
    }

    public function getRequestCountTypeClient($arrRequestIds)
    {
        $requestrepo = $this->EM->getRepository(DataRequest::class);
        $requestObjects = $requestrepo->findBy(array('id' => $arrRequestIds));
        $arrRequestCountwithtypeclient = array();
        foreach ($requestObjects as $requestObject) {
            if ($requestObject->getClient()->getTypeClient()) {
                $typeclientId = $requestObject->getClient()->getTypeClient()->getId();
                if ($typeclientId == 1) {
                    if ($requestObject->getClient()->getLegalForm()) {
                        if ($requestObject->getClient()->getLegalForm()->getIdCompany()) {

                            if ($requestObject->getClient()->getLegalForm()->getIdCompany()->getId() == 2) {

                                if (array_key_exists("individual", $arrRequestCountwithtypeclient)) {
                                    $arrRequestCountwithtypeclient["individual"] = $arrRequestCountwithtypeclient["individual"] + 1;
                                } else {
                                    $arrRequestCountwithtypeclient["individual"] = 1;
                                }
                            }

                            if ($requestObject->getClient()->getLegalForm()->getIdCompany()->getId() == 1) {

                                if (array_key_exists("companyalreadycreated", $arrRequestCountwithtypeclient)) {
                                    $arrRequestCountwithtypeclient["companyalreadycreated"] = $arrRequestCountwithtypeclient["companyalreadycreated"] + 1;
                                } else {
                                    $arrRequestCountwithtypeclient["companyalreadycreated"] = 1;
                                }
                            }
                        }
                    }
                }
                if ($typeclientId == 2) {
                    if (array_key_exists("beingcreated", $arrRequestCountwithtypeclient)) {
                        $arrRequestCountwithtypeclient["beingcreated"] = $arrRequestCountwithtypeclient["beingcreated"] + 1;
                    } else {
                        $arrRequestCountwithtypeclient["beingcreated"] = 1;
                    }
                }
            }
        }
        return $arrRequestCountwithtypeclient;
    }

    public function typeclientCount($arrdata, $typeclient)
    {
        if ($typeclient == "individual") {
            $query = "select id from data_client where legalform_id in(SELECT id FROM `ref_legalform` WHERE `id_company_id`=2)AND `typeclient_id` =1";
        }
        if ($typeclient == "companyalreadycreated") {
            $query = "select id from data_client where legalform_id in(SELECT id FROM `ref_legalform` WHERE `id_company_id`=1)AND `typeclient_id` =1";
        }
        if ($typeclient == "beingcreated") {
            $query = "select id from data_client where `typeclient_id`=2";
        }
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrtypclientIds = array();
        // return $results;
        foreach ($results as $result) {
            if (array_key_exists($result['id'], $arrdata)) {
                $arrtypclientIds[$result['id']] = $arrdata[$result['id']];
            }
        }
        return $arrtypclientIds;
    }

    public function _getActivityRequestByStatusWithYearBased()
    {
        $arryear = array();
        $refrequeststatusrepo = $this->EM->getRepository(RefRequeststatus::class);
        $requestStatuses = $refrequeststatusrepo->findBy(['active_requeststatus' => 'Active']);
        $arrAllRequestCount = array();
        foreach ($requestStatuses as $requestStatus) {
            $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id";
            $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
            // $stmt->execute();
            // $result = $stmt->fetch();
            $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId()])->fetch();

            if ($result["count(id)"] != 0) {
                $arrAllRequestCount[$requestStatus->getStatusRequeststatus()] = $result["count(id)"];
            }
        }
        $limityear = 10;
        for ($year = 0; $year <= $limityear; $year++) {
            array_push($arryear, date("Y", strtotime("-" . $year . " year")));
        }
        $arrrequestbasedyears = array();
        foreach ($arryear as $year) {
            $arrrequestbasedyear = array();
            foreach ($requestStatuses as $requestStatus) {
                $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND year(date_request)=$year";
                $conn = $this->EM->getconnection();
                // $stmt = $conn->prepare($query);
                // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
                // $stmt->execute();
                // $result = $stmt->fetch();
                $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId()])->fetch();


                if (!array_key_exists('year', $arrrequestbasedyear)) {
                    $arrrequestbasedyear['year'] = $year;
                }
                if ($result["count(id)"] != 0) {
                    $arrrequestbasedyear[$requestStatus->getStatusRequeststatus()] = $result["count(id)"];
                }
            }
            if (count($arrrequestbasedyear) > 1) {
                array_push($arrrequestbasedyears, $arrrequestbasedyear);
            }
        }
        $month_lists = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
        $arrrequestbasedmonths = array();
        foreach ($arryear as $year) {
            foreach ($month_lists as $key => $month) {
                $arrrequestbasedmonth = array();
                foreach ($requestStatuses as $requestStatus) {
                    $query = "SELECT count(id) FROM data_request WHERE requeststatus_id =:requeststatus_id AND month(date_request)=$key AND year(date_request) = $year";
                    $conn = $this->EM->getconnection();
                    // $stmt = $conn->prepare($query);
                    // $stmt->bindValue('requeststatus_id', (int)$requestStatus->getId());
                    // $stmt->execute();
                    // $result = $stmt->fetch();
                    $result = $conn->executeQuery($query, ['requeststatus_id' => (int)$requestStatus->getId()])->fetch();

                    if (!array_key_exists('year', $arrrequestbasedmonth)) {
                        $arrrequestbasedmonth['year'] = $year;
                    }
                    if (!array_key_exists('month', $arrrequestbasedmonth)) {
                        $arrrequestbasedmonth['month'] = $month;
                    }
                    if ($result["count(id)"] != 0) {
                        $arrrequestbasedmonth[$requestStatus->getStatusRequeststatus()] = $result["count(id)"];
                    }
                }
                if (count($arrrequestbasedmonth) > 2) {
                    array_push($arrrequestbasedmonths, $arrrequestbasedmonth);
                }
            }
        }
        $myObj = ["all" => $arrAllRequestCount, "year" => $arrrequestbasedyears, "month" => $arrrequestbasedmonths];
        return $myObj;
    }

    public function _getActivityRequestByStatusWithPeriodBased($period)
    {
        if ($period == 'day') {
            return $this->getDayCount();
        }
    }
    public function getDayCount()
    {
        $current_date = date("Y-m-d") . "  00:00:00";
        // return $current_date;
        $des_date = date("Y-m-d") . "  23:59:59";
        $prospectcount = $this->prospectCount($current_date, $des_date);
        $newdemandcount = $this->newdemandCount($current_date, $des_date);
        $acceptcount = $this->acceptCount($current_date, $des_date);
        $response = ["prospect" => $prospectcount, "newdemand" => $newdemandcount, "accept" => $acceptcount];
        return $response;
    }

    public function prospectCount($current_date, $des_date)
    {
        $query = "SELECT CAST(FROM_UNIXTIME( CEILING( UNIX_TIMESTAMP(date_request) / 600 ) * 600 )AS TIME) AS req_time, COUNT(*) AS req_count FROM data_request WHERE requeststatus_id = 1 AND (date_request >= '$current_date' AND date_request < '$des_date') GROUP BY req_time";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requestCountwithdates = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $requestCountwithdates = $connection->executeQuery($query)->fetchAll();
        $arrProspectCount = array();
        foreach ($requestCountwithdates as $requestCountwithdate) {
            $arrProspectCount[$requestCountwithdate['req_time']] = $requestCountwithdate['req_count'];
        }
        return $arrProspectCount;
    }

    public function newdemandCount($current_date, $des_date)
    {
        $query = "SELECT CAST(FROM_UNIXTIME( CEILING( UNIX_TIMESTAMP(date_request) / 600 ) * 600 )AS TIME) AS req_time, COUNT(*) AS req_count FROM data_request WHERE requeststatus_id = 4 AND (date_request >= '$current_date' AND date_request < '$des_date') GROUP BY req_time";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requestCountwithdates = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $requestCountwithdates = $connection->executeQuery($query)->fetchAll();
        $arrNewdemandCount = array();
        foreach ($requestCountwithdates as $requestCountwithdate) {
            $arrNewdemandCount[$requestCountwithdate['req_time']] = $requestCountwithdate['req_count'];
        }
        return $arrNewdemandCount;
    }

    public function acceptCount($current_date, $des_date)
    {
        $query = "SELECT CAST(FROM_UNIXTIME( CEILING( UNIX_TIMESTAMP(date_request) / 600 ) * 600 )AS TIME) AS req_time, COUNT(*) AS req_count FROM data_request WHERE (requeststatus_id = 19 OR requeststatus_id = 37) AND (date_request >= '$current_date' AND date_request < '$des_date') GROUP BY req_time";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requestCountwithdates = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $requestCountwithdates = $connection->executeQuery($query)->fetchAll();
        $arrAcceptCount = array();
        foreach ($requestCountwithdates as $requestCountwithdate) {
            $arrAcceptCount[$requestCountwithdate['req_time']] = $requestCountwithdate['req_count'];
        }
        return $arrAcceptCount;
    }

    public function _getActivityRequestAverageTime($request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();


        $normalizers = array($normalizer, new DateTimeNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $content = $request->getContent();
        $averagecalculation = $serializer->deserialize($content, AverageCalculation::class, 'json');
        if (!$averagecalculation->getRequestId() && $averagecalculation->getRequestStatus() && !$averagecalculation->getPeriod()) {
            return $this->getAllRequestAverage($averagecalculation->getRequestStatus());
        }
        if (!$averagecalculation->getRequestId() && $averagecalculation->getRequestStatus() && $averagecalculation->getPeriod()) {
            return $this->getAllRequestAverageWithPeriod($averagecalculation->getRequestStatus(), $averagecalculation->getPeriod());
        }
        if ($averagecalculation->getRequestId() && $averagecalculation->getRequestStatus() && $averagecalculation->getPeriod()) {
            return $this->getAllRequestAverageWithPeriodAndRequestId($averagecalculation->getRequestId(), $averagecalculation->getRequestStatus(), $averagecalculation->getPeriod());
        }

        // $in = implode(',',$ids);
    }

    public function getAllRequestAverage($requeststatus)
    {
        if (count($requeststatus) > 1) {
            $requeststatus = implode(',', $requeststatus);
            $forAndrequeststatus = str_replace(",", " AND `id_requeststatus_id`>", $requeststatus);
        } else {
            $requeststatus = $requeststatus[0];
            $forAndrequeststatus = $requeststatus;
        }
        $query = "SELECT *, DATEDIFF(T02.drs, T01.date_request_requeststatus) as datedifference FROM (SELECT id_request_id, date_request_requeststatus, login_request_requeststatus,id_requeststatus_id FROM `data_request_requeststatus` WHERE id_requeststatus_id in('$requeststatus')) as T01 INNER JOIN (SELECT id_request_id as id_req, date_request_requeststatus as drs, login_request_requeststatus as lrs,id_requeststatus_id as irs FROM `data_request_requeststatus` t04 WHERE id_requeststatus_id not in('$requeststatus') and date_request_requeststatus > (SELECT date_request_requeststatus FROM `data_request_requeststatus` t03 WHERE id_requeststatus_id >$forAndrequeststatus and t03.id_request_id = t04.id_request_id LIMIT 1) order by date_request_requeststatus asc) as T02 on T01.id_request_id = T02.id_req ORDER BY T01.id_request_id ASC, T02.drs ASC";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requests = $stmt->fetchAll();
        // return $requests;
        $connection = $this->EM->getconnection();
        $requests = $connection->executeQuery($query)->fetchAll();
        $arrRequests = array();
        $average_count = 0;
        foreach ($requests as $request) {
            if (!array_key_exists($request['id_request_id'], $arrRequests)) {
                $arrRequests[$request['id_request_id']] = $request['datedifference'];
                $average_count = $average_count + $request['datedifference'];
                // return $arrRequests;
            }
        }
        $count = count($arrRequests);
        if ($count > 1) {
            $arrRequests['avg'] = $average_count / $count;
            return $arrRequests['avg'];
        }
        return 0;
    }

    public function getAllRequestAverageWithPeriod($requeststatus, $period)
    {
        $day = $this->_getFromdateTodate($period);
        $fromdate = $day['fromdate'];
        $todate = $day['todate'];
        if (count($requeststatus) > 1) {
            $requeststatus = implode(',', $requeststatus);
            $forAndrequeststatus = str_replace(",", " AND `id_requeststatus_id`> ", $requeststatus);
        } else {
            $requeststatus = $requeststatus[0];
            $forAndrequeststatus = $requeststatus;
        }
        $query = "SELECT *, DATEDIFF(T02.drs, T01.date_request_requeststatus) as datedifference FROM (SELECT id_request_id, date_request_requeststatus, login_request_requeststatus,id_requeststatus_id FROM `data_request_requeststatus` WHERE id_requeststatus_id in('$requeststatus')) as T01 INNER JOIN (SELECT id_request_id as id_req, date_request_requeststatus as drs, login_request_requeststatus as lrs,id_requeststatus_id as irs FROM `data_request_requeststatus` t04 WHERE id_requeststatus_id not in('$requeststatus') and date_request_requeststatus > (SELECT date_request_requeststatus FROM `data_request_requeststatus` t03 WHERE id_requeststatus_id >$forAndrequeststatus and t03.id_request_id = t04.id_request_id LIMIT 1) order by date_request_requeststatus asc) as T02 on T01.id_request_id = T02.id_req ORDER BY T01.id_request_id ASC, T02.drs ASC";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requests = $stmt->fetchAll();
        // return $requests;
        $connection = $this->EM->getconnection();
        $requests = $connection->executeQuery($query)->fetchAll();
        $arrRequests = array();
        $average_count = 0;
        $arrcount = 0;
        foreach ($requests as $request) {
            // return [$fromdate,$todate];
            if (!array_key_exists($request['id_request_id'], $arrRequests)) {
                if ($request['date_request_requeststatus'] > $fromdate && $request['date_request_requeststatus'] < $todate) {
                    // return $request;
                    $arrRequests[$request['id_request_id']] = (int)$request['datedifference'];
                    $average_count = $average_count + $request['datedifference'];
                    $arrcount = $arrcount + 1;
                } else {
                    $arrRequests[$request['id_request_id']] = 0;
                }
            }
        }
        if ($arrcount > 1) {
            $arrRequests['avg'] = $average_count / $arrcount;
            return $arrRequests['avg'];
        }
        return 0;
    }

    public function getAllRequestAverageWithPeriodAndRequestId($requestid, $requeststatus, $period)
    {
        $day = $this->_getFromdateTodate($period);
        $fromdate = $day['fromdate'];
        $todate = $day['todate'];
        if (count($requeststatus) > 1) {
            $requeststatus = implode(',', $requeststatus);
            $forAndrequeststatus = str_replace(",", " AND `id_requeststatus_id`>", $requeststatus);
        } else {
            $requeststatus = $requeststatus[0];
            $forAndrequeststatus = $requeststatus;
        }
        $query = "SELECT *, DATEDIFF(T02.drs, T01.date_request_requeststatus) as datedifference FROM (SELECT id_request_id, date_request_requeststatus, login_request_requeststatus,id_requeststatus_id FROM `data_request_requeststatus` WHERE id_requeststatus_id in('$requeststatus') AND id_request_id=$requestid) as T01 INNER JOIN (SELECT id_request_id as id_req, date_request_requeststatus as drs, login_request_requeststatus as lrs,id_requeststatus_id as irs FROM `data_request_requeststatus` t04 WHERE id_requeststatus_id not in('$requeststatus') and date_request_requeststatus > (SELECT date_request_requeststatus FROM `data_request_requeststatus` t03 WHERE id_requeststatus_id >$forAndrequeststatus and t03.id_request_id = t04.id_request_id LIMIT 1) order by date_request_requeststatus asc) as T02 on T01.id_request_id = T02.id_req ORDER BY T01.id_request_id ASC, T02.drs ASC";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $requests = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $requests = $connection->executeQuery($query)->fetchAll();
        // return $requests;
        $arrRequests = array();
        $average_count = 0;
        $arrcount = 0;
        foreach ($requests as $request) {
            if (!array_key_exists($request['id_request_id'], $arrRequests)) {
                if ($request['date_request_requeststatus'] > $fromdate && $request['date_request_requeststatus'] < $todate) {
                    return (int)$request['datedifference'];
                }
                // return $arrRequests;
            }
        }
        // $count = count($arrRequests);
        return 0;
    }

    public function _getActivityRequestByStatusWithPeriodBasedv1($period)
    {
        $day = $this->_getFromdateTodate($period);
        $fromdate = $day['fromdate'];
        $todate = $day['todate'];
        // return "'37','19'";
        $objperiod = $this->getCountofall($fromdate, $todate);
        return $objperiod;
    }

    public function getCountofall($fromdate, $todate)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();


        $normalizers = array($normalizer, new DateTimeNormalizer());
        $serializer    =   new Serializer($normalizers, $encoders);
        $arrFinalObj = array();
        if ($fromdate == null && $todate == null) {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`=1  group by`dateupd_request`";
        } else {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`=1 AND `dateupd_request` BETWEEN '$fromdate' AND '$todate' group by`dateupd_request`";
        }
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $prospectCounts = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $prospectCounts = $connection->executeQuery($query)->fetchAll();
        $totalcount = 0;
        foreach ($prospectCounts as $prospectCount) {
            $arrprospectsample = array();
            $newdemandCount = $this->getQueryData(4, $prospectCount['dateupd_request']);
            if (!$newdemandCount) {
                $newdemandCount['req_count'] = 0;
            }
            $acceptCount = $this->getQueryData("'37','19'", $prospectCount['dateupd_request']);
            if (!$acceptCount) {
                $acceptCount['req_count'] = 0;
            }
            $requestcount = new RequestCount;
            $requestcount->setdate($prospectCount['dateupd_request']);
            $requestcount->setprospect($prospectCount['req_count']);
            $requestcount->setnewdemand($newdemandCount['req_count']);
            $requestcount->setaccept($acceptCount['req_count']);
            array_push($arrprospectsample, $requestcount);
            $arrFinalObj[$prospectCount['dateupd_request']] = $arrprospectsample;
            $totalcount = $totalcount + $prospectCount['req_count'] + $newdemandCount['req_count'] + $acceptCount['req_count'];
        }
        if ($fromdate == null && $todate == null) {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`=4  group by`dateupd_request`";
        } else {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`=4 AND `dateupd_request` BETWEEN '$fromdate' AND '$todate' group by`dateupd_request`";
        }
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $newdemandCounts = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $newdemandCounts = $connection->executeQuery($query)->fetchAll();
        foreach ($newdemandCounts as $newdemandCount) {
            if (!array_key_exists($newdemandCount['dateupd_request'], $arrFinalObj)) {
                $arrnewdemandsample = array();
                $prospectCount = $this->getQueryData(0, $newdemandCount['dateupd_request']);
                if (!$prospectCount) {
                    $prospectCount['req_count'] = 0;
                }
                $acceptCount = $this->getQueryData("'37','19'", $newdemandCount['dateupd_request']);
                if (!$acceptCount) {
                    $acceptCount['req_count'] = 0;
                }
                $requestcount = new RequestCount;
                $requestcount->setdate($newdemandCount['dateupd_request']);
                $requestcount->setprospect($prospectCount['req_count']);
                $requestcount->setnewdemand($newdemandCount['req_count']);
                $requestcount->setaccept($acceptCount['req_count']);
                array_push($arrnewdemandsample, $requestcount);
                $arrFinalObj[$newdemandCount['dateupd_request']] = $arrnewdemandsample;
                $totalcount = $totalcount + $prospectCount['req_count'] + $newdemandCount['req_count'] + $acceptCount['req_count'];
            }
        }
        if ($fromdate == null && $todate == null) {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`in('37','19') group by`dateupd_request`";
        } else {
            $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id`in('37','19') AND `dateupd_request` BETWEEN '$fromdate' AND '$todate' group by`dateupd_request`";
        }
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $acceptCounts = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $acceptCounts = $connection->executeQuery($query)->fetchAll();
        foreach ($acceptCounts as $acceptCount) {
            if (!array_key_exists($acceptCount['dateupd_request'], $arrFinalObj)) {
                $arracceptsample = array();
                $prospectCount = $this->getQueryData(0, $acceptCount['dateupd_request']);
                if (!$prospectCount) {
                    $prospectCount['req_count'] = 0;
                }
                $newdemandCount = $this->getQueryData(4, $acceptCount['dateupd_request']);
                if (!$newdemandCount) {
                    $newdemandCount['req_count'] = 0;
                }
                $requestcount = new RequestCount;
                $requestcount->setdate($acceptCount['dateupd_request']);
                $requestcount->setprospect($prospectCount['req_count']);
                $requestcount->setnewdemand($newdemandCount['req_count']);
                $requestcount->setaccept($acceptCount['req_count']);
                array_push($arracceptsample, $requestcount);
                $arrFinalObj[$acceptCount['dateupd_request']] = $arracceptsample;
                $totalcount = $totalcount + $prospectCount['req_count'] + $newdemandCount['req_count'] + $acceptCount['req_count'];
            }
        }
        ksort($arrFinalObj);
        $arrFinalObjValue = array();
        foreach ($arrFinalObj as $key => $finalObj) {
            array_push($arrFinalObjValue, $finalObj[0]);
        }
        return $arrFinalObjValue;
    }

    public function getQueryData($requeststatus_id, $date)
    {
        $query = "SELECT * ,count(dateupd_request) as req_count FROM `data_request`where `requeststatus_id` in ($requeststatus_id) AND `dateupd_request`='$date'";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $result = $stmt->fetch();
        $connection = $this->EM->getconnection();
        $result = $connection->executeQuery($query)->fetch();
        return $result;
    }

    public function _getCountOfRequeststatus($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        $listofdates = $this->getDateList($date[0], $date[1]);
        foreach ($listofdates as $listdate) {
            $date = date_create("$listdate");
            $format = date_format($date, "D, d M y H:i:s O");
            $dates["$listdate"] = $format;
        }
        $arroverall = array();
        $arrprospect = array();
        $arrnewdemand = array();
        $arraccept = array();
        $arrrefuese = array();
        $arrbandonnes = array();
        foreach ($dates as $key => $date) {
            $querydata = $this->getQueryDataforGivenDateCount([$key]);
            $Prospect = $this->getQueryForRequeststatust([1, 2], $querydata);
            $newdemand = $this->getQueryForRequeststatust([4], $querydata);
            $accept = $this->getQueryForRequeststatust([19, 37], $querydata);
            $Refuese = $this->getQueryForRequeststatust([16, 35, 36], $querydata);
            $Abandonnes = $this->getQueryForRequeststatust([100], $querydata);
            array_push($arrprospect, ['t' => $date, 'y' => $Prospect]);
            array_push($arrnewdemand, ['t' => $date, 'y' => $newdemand]);
            array_push($arraccept, ['t' => $date, 'y' => $accept]);
            array_push($arrrefuese, ['t' => $date, 'y' => $Refuese]);
            array_push($arrbandonnes, ['t' => $date, 'y' => $Abandonnes]);
            // $totalcount =$Prospect+ $newdemand+$accept+$Refuese+$Abandonnes;
            // array_push($arroverall,['date'=>$date,'prospect'=>$Prospect,'newdemand'=>$newdemand,'accept'=>$accept,'refuese'=>$Refuese,'abandonnes'=>$Abandonnes,'totalcount'=>$totalcount]);
        }
        $arroverall = ["prospect" => $arrprospect, "newdemand" => $arrnewdemand, "accept" => $arraccept, "refuese" => $arrrefuese, "Abandonnes" => $arrbandonnes];
        return $arroverall;
    }

    public function _getCountOfRequeststatusChart($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        // return $date;
        $listofdates = $this->getDateList($date[0], $date[1]);
        foreach ($listofdates as $listdate) {
            $date = date_create("$listdate");
            $format = date_format($date, "d/m/Y");
            $dates["$listdate"] = $format;
        }
        $arrdate = array();
        $arroverall = array();
        $arrprospect = array();
        $arrnewdemand = array();
        $arraccept = array();
        $arrrefuese = array();
        $arrbandonnes = array();
        foreach ($dates as $key => $date) {
            $querydata = $this->getQueryDataforGivenDateCount([$key]);
            $Prospect = $this->getQueryForRequeststatust([1, 2], $querydata);
            $newdemand = $this->getQueryForRequeststatust([4], $querydata);
            $accept = $this->getQueryForRequeststatust([19, 37], $querydata);
            $Refuese = $this->getQueryForRequeststatust([16, 35, 36], $querydata);
            $Abandonnes = $this->getQueryForRequeststatust([100], $querydata);
            array_push($arrdate, $date);
            array_push($arrprospect, $Prospect);
            array_push($arrnewdemand, $newdemand);
            array_push($arraccept, $accept);
            array_push($arrrefuese, $Refuese);
            array_push($arrbandonnes, $Abandonnes);
            // $totalcount =$Prospect+ $newdemand+$accept+$Refuese+$Abandonnes;
            // array_push($arroverall,['date'=>$date,'prospect'=>$Prospect,'newdemand'=>$newdemand,'accept'=>$accept,'refuese'=>$Refuese,'abandonnes'=>$Abandonnes,'totalcount'=>$totalcount]);
        }
        $arroverall = ["date" => $arrdate, "prospect" => $arrprospect, "newdemand" => $arrnewdemand, "accept" => $arraccept, "refuese" => $arrrefuese, "Abandonnes" => $arrbandonnes];
        return $arroverall;
    }
    public function getQueryDataforCount($date)
    {
        //$query = "SELECT count(id_requeststatus_id) as id_count, id_requeststatus_id as req_status from data_request_requeststatus t01, data_request t03 WHERE T01.id_request_id = T03.id and DATE_FORMAT(T03.date_request, '%Y-%m-%d') = '$date' and t01.id = (SELECT max(id) FROM `data_request_requeststatus` T02 WHERE T02.id_request_id = T01.id_request_id and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') <= '$date') group by id_requeststatus_id";    
        $query = "SELECT count(id_requeststatus_id) as id_count, id_requeststatus_id as req_status FROM data_request_requeststatus t01, (SELECT max(id) as maxid, T02.id_request_id FROM `data_request_requeststatus` T02 WHERE DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') <= '$date' GROUP BY T02.id_request_id) as t04 where t01.id= maxid and DATE_FORMAT(T01.date_request_requeststatus, '%Y-%m-%d') = '$date' group by id_requeststatus_id";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrcountwithStatus = array();
        foreach ($results as $result) {
            $arrcountwithStatus[$result['req_status']] = $result['id_count'];
        }
        return $arrcountwithStatus;
    }

    public function getQueryDataforGivenDateCount($date)
    {
        //$query = "SELECT count(id_requeststatus_id) as id_count, id_requeststatus_id as req_status from data_request_requeststatus t01, data_request t03 WHERE T01.id_request_id = T03.id and DATE_FORMAT(T03.date_request, '%Y-%m-%d') <= '$date' and t01.id =(SELECT max(id) FROM `data_request_requeststatus` T02 WHERE T02.id_request_id = T01.id_request_id and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') = '$date') group by id_requeststatus_id";    
        if (count($date) == 1) {
            $query = "SELECT count(*)as id_count, id_requeststatus_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[0]' group by id_requeststatus_id";
        }
        if (count($date) == 2) {
            $query = "SELECT count(*)as id_count, id_requeststatus_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[1]' group by id_requeststatus_id";
        }
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        // return $results;
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrcountwithStatus = array();
        foreach ($results as $result) {
            $arrcountwithStatus[$result['req_status']] = $result['id_count'];
        }
        return $arrcountwithStatus;
    }

    public function getQueryDataforGivenUptoDateCount($date)
    {
        //$query = "SELECT count(id_requeststatus_id) as id_count, id_requeststatus_id as req_status from data_request_requeststatus t01, data_request t03 WHERE T01.id_request_id = T03.id and DATE_FORMAT(T03.date_request, '%Y-%m-%d') <= '$date' and t01.id =(SELECT max(id) FROM `data_request_requeststatus` T02 WHERE T02.id_request_id = T01.id_request_id and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') <= '$date') group by id_requeststatus_id";
        $query = "SELECT count(*) as id_count, id_requeststatus_id as req_status from data_request_requeststatus T01,
		(SELECT max(id) as maxid, id_request_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') <= '$date'
		group by id_request_id) as T02
		WHERE T01.id = maxid
		group by id_requeststatus_id";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        // return $results;
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrcountwithStatus = array();
        foreach ($results as $result) {
            $arrcountwithStatus[$result['req_status']] = $result['id_count'];
        }
        return $arrcountwithStatus;
    }

    public function getCountOfRequestStatus($date)
    {
        /*$query = "SELECT count(id_requeststatus_id) as id_count , id_requeststatus_id as req_status from data_request_requeststatus t01, data_request t03 WHERE T01.id_request_id = T03.id and DATE_FORMAT(T03.date_request, '%Y-%m-%d') <= '$date'
        and t01.id =
        (SELECT max(id) FROM `data_request_requeststatus` T02 WHERE T02.id_request_id = T01.id_request_id and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') <= '$date')
        group by id_requeststatus_id";*/
        $query = "SELECT count(*) as id_count, id_requeststatus_id as req_status from data_request_requeststatus T01,
		(SELECT max(id) as maxid, id_request_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') <= '$date'
		group by id_request_id) as T02
		WHERE T01.id = maxid
		group by id_requeststatus_id";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrcountwithStatus = array();
        foreach ($results as $result) {
            $arrcountwithStatus[$result['req_status']] = $result['id_count'];
        }
        return $arrcountwithStatus;
    }


    public function _getCountOfRequeststatusForAll($period)
    {
        if ($period == "day") {
            $date = date("Y-m-d");
        } else {
            if ((date('Y-m-d', strtotime($period)) == $period)) {
                $date = $period;
            }
        }
        $querydata = $this->getQueryDataforCount($period);
        $result = $this->forAllRequestStatusDetail($querydata);
        return $result;
    }

    public function forAllRequestStatusDetail($querydata)
    {

        $Nouvelles_demandes = $this->getQueryForRequeststatust([4], $querydata);
        $Acceptes = $this->getQueryForRequeststatust([19, 37], $querydata);
        $Refuses = $this->getQueryForRequeststatust([16, 35, 36], $querydata);
        $Analyse_de_la_demande = $this->getQueryForRequeststatust([4, 6], $querydata);
        $En_attente_davis_CTO = $this->getQueryForRequeststatust([7, 8, 9, 20], $querydata);
        $Abandonnes = $this->getQueryForRequeststatust([100], $querydata);
        $En_attente_de_double_validation = $this->getQueryForRequeststatust([10, 28, 24, 22, 21, 11, 12, 25, 26, 27, 29, 30, 39, 40], $querydata);
        $En_attente_repartition_capital = $this->getQueryForRequeststatust([34, 14, 33], $querydata);
        $En_attente_correction = $this->getQueryForRequeststatust([23, 38, 5, 43, 42, 44, 41], $querydata);
        $En_attente_de_virements = $this->getQueryForRequeststatust([13, 31, 32], $querydata);
        // $En_attente_virement_initial = $this->getQueryForRequeststatust("150,151,152,158",$querydata);
        $En_attente_virement_depot_de_capital = $this->getQueryForRequeststatust([99], $querydata);
        $En_attente_de_Kbis_and_statuts = $this->getQueryForRequeststatust([15], $querydata);
        $En_attente_Kbis = $this->getQueryForRequeststatust([45], $querydata);
        $totalcount = $Nouvelles_demandes + $Acceptes + $Refuses + $Analyse_de_la_demande + $En_attente_davis_CTO + $Abandonnes + $En_attente_de_double_validation + $En_attente_repartition_capital + $En_attente_Kbis + $En_attente_de_Kbis_and_statuts + $En_attente_correction + $En_attente_de_virements + $En_attente_virement_depot_de_capital;
        return ['Nouvelles_demandes' => $Nouvelles_demandes, 'Acceptes' => $Acceptes, 'Refuses' => $Refuses, 'Analyse_de_la_demande' => $Analyse_de_la_demande, 'En_attente_davis_CTO' => $En_attente_davis_CTO, 'Abandonnes' => $Abandonnes, 'En_attente_de_double_validation' => $En_attente_de_double_validation, 'En_attente_repartition_capital' => $En_attente_repartition_capital, 'En_attente_correction' => $En_attente_correction, 'En_attente_de_virements' => $En_attente_de_virements, 'En_attente_virement_depot_de_capital' => $En_attente_virement_depot_de_capital, 'En_attente_de_Kbis_and_statuts' => $En_attente_de_Kbis_and_statuts, 'En_attente_Kbis' => $En_attente_Kbis, 'totalcount' => $totalcount];
    }

    public function getFromTodateFromRequest($request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();


        $normalizers = array($normalizer, new DateTimeNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $content = $request->getContent();
        $averagecalculation = $serializer->deserialize($content, AverageCalculation::class, 'json');
        if ($averagecalculation->getPeriod()) {
            $day = $this->_getFromdateTodate($averagecalculation->getPeriod());
            $fromdate = $day['fromdate'];
            $todate = $day['todate'];
        }
        if ($averagecalculation->getDate()) {
            if (count($averagecalculation->getDate()) == 1) {
                $fromdate = $averagecalculation->getDate()[0];
                $todate = $averagecalculation->getDate()[0];
            }
            if (count($averagecalculation->getDate()) == 2) {
                $fromdate = $averagecalculation->getDate()[0];
                $todate = $averagecalculation->getDate()[1];
            }
        }
        return [$fromdate, $todate];
    }
    public function _getCountOfRequeststatusGivenDateForAll($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        // return [$fromdate,$todate];
        $querydata = $this->getQueryDataforGivenDateCount($date);
        $result = $this->forAllRequestStatusDetail($querydata);
        return $result;
    }

    public function _getCountOfRequeststatusGivenUptoDateForAll($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        $querydata = $this->getQueryDataforGivenUptoDateCount($date[1]);
        // return $querydata;
        $result = $this->forAllRequestStatusDetail($querydata);
        $virRec = $this->getVirementRecu($date[1], 0);
        $result['Virements_recus']            = $virRec[1];
        $result['En_attente_de_virements'] = $virRec[0] - $virRec[1];
        return $result;
    }

    public function getQueryForRequeststatust($id, $querydata)
    {
        // $query = "SELECT id FROM `ref_requeststatus` where `status_requeststatus` in ($id)";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        // $requeststatusid = array();
        // foreach($results as $result){
        //     array_push($requeststatusid,(int)$result['id']);
        // }
        $count = 0;
        foreach ($id as $requeststatus) {
            if (array_key_exists("$requeststatus", $querydata)) {
                $count = $count + $querydata["$requeststatus"];
            }
        }
        return $count;
    }

    public function _getFromdateTodate($period)
    {

        $chkperiod = null;
        if ($period == "week") {
            $chkperiod = "-1 week";
        }
        if ($period == "month") {
            $chkperiod = "last month";
        }
        if ($period == "year") {
            $chkperiod = "last year";
        }
        if ($chkperiod != null) {
            $d = strtotime("today");
            $todate = date("Y-m-d", $d);
            $d = strtotime($chkperiod);
            $fromdate = date("Y-m-d", $d);
        }
        if ($period == "day") {
            $fromdate = date("Y-m-d");
            $todate = date("Y-m-d");
        }
        if ($period == "all") {
            $fromdate = null;
            $todate = null;
        }
        if ($period != "all" && $period != "day" && $period != "month" && $period != "week" && $period != "year" && $period != "today") {
            if ((date('Y-m-d', strtotime($period)) == $period)) {
                $fromdate = $period;
                $todate = $period;
            } else {
                return "invalid";
            }
        }
        return ["fromdate" => $fromdate, "todate" => $todate];
    }

    public function getDateList($fromdate, $todate)
    {
        $dateList = array();
        $date_from = strtotime($fromdate); // Convert date to a UNIX timestamp   
        $date_to = strtotime($todate); // Convert date to a UNIX timestamp    
        for ($i = $date_from; $i <= $date_to; $i += 86400) {
            array_push($dateList, date("Y-m-d", $i));
        }
        return $dateList;
    }

    public function _getRefuses($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        //$query = "select t01.comment,t01.client_id,CONCAT(t03.name_attorney,'_',t03.surname_attorney) as name from (select * from `data_comment` where id in(SELECT max(id) FROM `data_comment` WHERE DATE_FORMAT(time, '%Y-%m-%d') = '$date' GROUP BY `client_id`))as t01 inner join (select * from data_attorney where ismandatory_attorney = true)as t03 on t01.client_id = t03.client_id";
        $query = "select data_client.id as client_id, data_client.companyname_client, t03.comment, t03.login_id from data_request_requeststatus as T01, (select max(id) as maxid from data_request_requeststatus where DATE_FORMAT(data_request_requeststatus.date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[1]' group by id_request_id) as T02, data_comment as t03 inner join (select max(id) as maxcomment, client_id from data_comment group by client_id) as T04 on t03.id = T04.maxcomment and t03.client_id = T04.client_id, data_client where T01.id = T02.maxid and id_requeststatus_id in (16, 35, 36) and t03.client_id = T01.id_request_id and t01.id_request_id = data_client.id";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        return $results;
    }

    public function _getNextstatusCount($request)
    {
        $date = $this->getFromTodateFromRequest($request);
        //$Nouvelles_demandes = $this->getQueryInfoForNextStatus("100",$date);
        // return $Nouvelles_demandes;
        $Analyse_de_la_demande = $this->getQueryInfoForNextStatus("100,120", $date);
        $Acceptes = $this->getQueryInfoForNextStatus("190,191", $date);
        $Refuses = $this->getQueryInfoForNextStatus("170,171,172", $date);
        $En_attente_davis_CTO = $this->getQueryInfoForNextStatus("130,133,135,137", $date);
        $Abandonnes = $this->getQueryInfoForNextStatus("999", $date);
        $En_attente_repartition_capital = $this->getQueryInfoForNextStatus("155,156,157", $date);
        $En_attente_correction = $this->getQueryInfoForNextStatus("110,105,106,111,112,107,108", $date);
        $En_attente_de_Kbis_and_statuts = $this->getQueryInfoForNextStatus("160", $date);
        $En_attente_Kbis = $this->getQueryInfoForNextStatus("161", $date);
        $En_attente_de_double_validation = $this->getQueryInfoForNextStatus("140, 142,145,147,149,141,144,146,148,240, 241, 242, 243, 244", $date);
        $Prospect = $this->getQueryInfoForNextStatus("0", $date);
        $result = [
            "Prospect" => ["a" => $Prospect["a"], "b" => $Prospect["b"], "percentage" => $Prospect["percentage"]],
            "Nouvelles_demandes" => ["a" => $Nouvelles_demandes["a"], "b" => $Nouvelles_demandes["b"], "percentage" => $Nouvelles_demandes["percentage"]],
            "Acceptes" => ["a" => $Acceptes["a"], "b" => $Acceptes["b"], "percentage" => $Acceptes["percentage"]],
            "Refuses" => ["a" => $Refuses["a"], "b" => $Refuses["b"], "percentage" => $Refuses["percentage"]],
            "Analyse_de_la_demande" => ["a" => $Analyse_de_la_demande["a"], "b" => $Analyse_de_la_demande["b"], "percentage" => $Analyse_de_la_demande["percentage"]],
            "En_attente_davis_CTO" => ["a" => $En_attente_davis_CTO["a"], "b" => $En_attente_davis_CTO["b"], "percentage" => $En_attente_davis_CTO["percentage"]],
            "Abandonnes" => ["a" => $Abandonnes["a"], "b" => $Abandonnes["b"], "percentage" => $Abandonnes["percentage"]],
            "En_attente_repartition_capital" => ["a" => $En_attente_repartition_capital["a"], "b" => $En_attente_repartition_capital["b"], "percentage" => $En_attente_repartition_capital["percentage"]],
            "En_attente_correction" => ["a" => $En_attente_correction["a"], "b" => $En_attente_correction["b"], "percentage" => $En_attente_correction["percentage"]],
            "En_attente_de_Kbis_and_statuts" => ["a" => $En_attente_de_Kbis_and_statuts["a"], "b" => $En_attente_de_Kbis_and_statuts["b"], "percentage" => $En_attente_de_Kbis_and_statuts["percentage"]],
            "En_attente_Kbis" => ["a" => $En_attente_Kbis["a"], "b" => $En_attente_Kbis["b"], "percentage" => $En_attente_Kbis["percentage"]],
            "En_attente_de_double_validation" => ["a" => $En_attente_de_double_validation["a"], "b" => $En_attente_de_double_validation["b"], "percentage" => $En_attente_de_double_validation["percentage"]]
        ];
        //dump($result);
        return $result;
    }

    public function getQueryInfoForNextStatus($status, $date)
    {
        /*$queryA = "SELECT count(id_requeststatus_id) as req_status from data_request_requeststatus T01,  ref_requeststatus T06,
		(SELECT max(id) as maxid, id_request_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[1]'
		group by id_request_id) as T02
		WHERE T01.id = maxid
        AND T01.id_requeststatus_id = T06.id
        AND status_requeststatus in ($status)		
        ORDER BY `req_status` ASC";*/

        $queryA = "Select count(*) as req_status from ((SELECT id_request_id as req_status  from data_request_requeststatus T01, ref_requeststatus T06, 
		(SELECT data_request_requeststatus.id as maxid, id_request_id as req_status from data_request_requeststatus, ref_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') 
		between '$date[0]' and '$date[1]' AND id_requeststatus_id = ref_requeststatus.id AND status_requeststatus in ($status) ) 
		as T02 
		WHERE T01.id = maxid 
		AND T01.id_requeststatus_id = T06.id 
		AND status_requeststatus in ($status) 
		ORDER BY `req_status` ASC)
		union all
		(SELECT (id_request_id) as req_status  from data_request_requeststatus T01, ref_requeststatus T06, 
		(SELECT max(data_request_requeststatus.id) as maxid, id_request_id as req_status from data_request_requeststatus, ref_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') 
		< '$date[0]' AND id_requeststatus_id = ref_requeststatus.id group by id_request_id) 
		as T02 
		WHERE T01.id = maxid 
		AND T01.id_requeststatus_id = T06.id 
		AND status_requeststatus in ($status) 
		ORDER BY `req_status` ASC)) as T010";
        //echo $queryA;
        //die;

        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($queryA);
        // $stmt->execute();
        // $a_result = $stmt->fetch();
        $connection = $this->EM->getconnection();
        $a_result = $connection->executeQuery($queryA)->fetch();
        if ($a_result != false) {
            $a_count = $a_result["req_status"];
        } else {
            $a_count = 0;
        }

        //return ["b"=>0,"a"=>$a_count,"percentage"=>0];
        /*
        $queryB = "SELECT count(minid) from (
        SELECT min(T02.id) as minid, T02.id_request_id 
        FROM `data_request_requeststatus` T02, ref_requeststatus as T05 
        WHERE T02.id_requeststatus_id = T05.id 
        and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[1]' 
        and (status_requeststatus in (select next_status_code_exist from ref_request_status_order where initial_status_code in ($status)) 
        OR status_requeststatus in (select next_status_code_new from ref_request_status_order where initial_status_code in ($status))) 
        group by T02.id_request_id) 
        as t07";
		
		$queryB = "SELECT count(minid) from ( 
		SELECT min(T02.id) as minid, T02.id_request_id 
		FROM `data_request_requeststatus` T02, ref_requeststatus as T05 WHERE T02.id_requeststatus_id = T05.id and DATE_FORMAT(T02.date_request_requeststatus, '%Y-%m-%d') between '$date[0]' and '$date[1]'  
		and (status_requeststatus in (select next_status_code_exist from ref_request_status_order where initial_status_code in ($status)) 
		OR status_requeststatus in (select next_status_code_new from ref_request_status_order where initial_status_code in ($status))) 
		group by T02.id_request_id, T02.date_request_requeststatus) 
		as t07 ORDER BY `t07`.`id_request_id` ASC ";*/

        $queryB = "SELECT count(*) as id_count from data_request_requeststatus T01, ref_requeststatus,
		(SELECT max(id) as maxid, id_request_id as req_status from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') <= '$date[1]'
		group by id_request_id) as T02
		WHERE T01.id = maxid
		and id_requeststatus_id = ref_requeststatus.id
		and status_requeststatus in ($status)";


        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($queryB);
        // $stmt->execute();
        // $b_result = $stmt->fetch();
        $connection = $this->EM->getconnection();
        $b_result = $connection->executeQuery($queryB)->fetch();
        if ($b_result != false) {
            //$b_count = $b_result["count(minid)"];
            $b_count = $b_result["id_count"];
        } else {
            $b_count = 0;
        }

        if ($a_count == 0) {
            //return ["b"=>$a_count+$b_count,"a"=>$b_count,"percentage"=>0];
            return ["b" => $a_count, "a" => $a_count - $b_count, "percentage" => $percentage];
        }

        //$percentage = $b_count/($a_count+$b_count)*100;
        $percentage = ($a_count - $b_count) / ($a_count) * 100;
        //return ["b"=>$a_count+$b_count,"a"=>$b_count,"percentage"=>$percentage];
        return ["b" => $a_count, "a" => $a_count - $b_count, "percentage" => $percentage];
    }


    public function getVirementRecu($date, $type)
    {

        $query = "SELECT T01.id_request_id from data_request_requeststatus T01,
		(SELECT max(id) as maxid, id_request_id from data_request_requeststatus WHERE DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') <= '$date'
		group by id_request_id) as T02
		WHERE T01.id = maxid  
        AND T01.id_requeststatus_id in (13,31,32)
		ORDER BY `T01`.`id_request_id` ASC";
        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
        // return $results;
        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();
        $arrcountwithStatus = array();
        $nbvir = 0;
        foreach ($results as $result) {
            $arrcountwithStatus[] = $result['id_request_id'];
            $nbvir++;
        }
        $query = "SELECT nbshare, cli, nbtrans, typeclient_id FROM 
		(SELECT count(id) as nbshare, client_id as cli, sum(isshareholder_attorney) as isshare FROM data_attorney WHERE client_id IN (" . implode(",", $arrcountwithStatus) . ") group by client_id) As T02 
		LEFT JOIN 
		(SELECT max(nbtransaction) as nbtrans, client_id FROM data_transaction where date_received <= '$date' group by client_id) as T01 
		ON T01.client_id = T02.cli LEFT JOIN data_client AS T03 ON T02.cli = T03.id ";

        // $conn = $this->EM->getconnection();
        // $stmt = $conn->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll();

        $connection = $this->EM->getconnection();
        $results = $connection->executeQuery($query)->fetchAll();

        $nbrec = 0;
        foreach ($results as $result) {
            if ($result['nbtrans'] >= 1) {
                $nbrec++;
            }
        }
        return array($nbvir, $nbrec);
    }

    public function _tempsMoyen($status, $datedebut, $datefin)
    {
        /*
		set_time_limit(500) ;
		$query = "SELECT * FROM data_request_requeststatus where id_request_id >= 0 ORDER BY id_request_id, date_request_requeststatus";
		
		$conn = $this->EM->getconnection();
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$results = $stmt->fetchAll();
			
		$from 		= "";
		$datefrom 	= "";
		$to   		= "";
		$diff 		= "";
		$dateto 	= "";
		$login 		= "";
		$id			= "";
		$val 		= [];
		foreach ($results as $result) {
			if ($id == "" || $id != $result['id_request_id']) {
				$id 	  = $result['id_request_id'];				
				$from 	  = $result['id_requeststatus_id'];
				$datefrom = $result['date_request_requeststatus'];
			} else {
				$to 	  = $result['id_requeststatus_id'];
				$login 	  = $result['login_request_requeststatus'];
				$interval = date_diff(date_create($result['date_request_requeststatus']), date_create($datefrom));
				$diff 	  = $interval->format('%a');
				$val[] 	  = array($id, $from, $to, $login, $diff);
				
				
				$requeststatusrepo  = $this->EM->getRepository(RefRequeststatus::class);
				$datarequestrepo    = $this->EM->getRepository(DataRequest::class);
		
				$from_requeststatus = $requeststatusrepo->findOneBy(['id'=>$from]);
				$to_requeststatus = $requeststatusrepo->findOneBy(['id'=>$to]);
				$datarequestchk = $datarequestrepo->findOneBy(['id'=>$id]);
				$datatreatment = new DataTreatment();
				$datatreatment->setRequest($datarequestchk);
				$datatreatment->setFromStatus($from_requeststatus);
				$datatreatment->setToStatus($to_requeststatus);
				$datatreatment->setDate(date_create($result['date_request_requeststatus']));
				$datatreatment->setDelay($diff);
				$datatreatment->setLogin($login);
				$this->EM->persist($datatreatment);
				$this->EM->flush();
				
				echo $result['id']."<br>";
				$from 	  = $result['id_requeststatus_id'];
				$datefrom = $result['date_request_requeststatus'];
			}
		}
		dump($val);
		die;
		*/
        $requestarray = [];
        $tempstotal = [];
        foreach ($status as $statut) {
            /*
			$query = "SELECT T010.id_request_id, min(datedifference) as diffdate, statutdepart, T020.login_request_requeststatus from ( 

            SELECT distinct T02.id as minid, T01.id_request_id, T01. id_requeststatus_id as statutdepart, T02.date_request_requeststatus, T02.login_request_requeststatus, T02.id_requeststatus_id as statutfin, DATEDIFF(T02.date_request_requeststatus, T01.date_request_requeststatus) as datedifference
            FROM 
            (SELECT data_request_requeststatus.id, id_request_id, date_request_requeststatus, login_request_requeststatus,id_requeststatus_id 
            FROM `data_request_requeststatus`, data_request WHERE data_request_requeststatus.id_request_id = data_request.id and id_requeststatus_id = $statut
            and DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') BETWEEN '$datedebut' AND '$datefin') as T01

            INNER JOIN 

            (SELECT id, id_request_id, date_request_requeststatus, login_request_requeststatus,id_requeststatus_id 
            FROM `data_request_requeststatus` t04 
            WHERE id_requeststatus_id <> $statut and date_request_requeststatus > (SELECT date_request_requeststatus FROM `data_request_requeststatus` t03 
            WHERE id_requeststatus_id = $statut and t03.id_request_id = t04.id_request_id LIMIT 1)
            and (DATE_FORMAT(date_request_requeststatus, '%Y-%m-%d') <= '$datefin')

            order by date_request_requeststatus asc) as T02 on T01.id_request_id = T02.id_request_id

            ORDER BY T01.id_request_id ASC, T02.date_request_requeststatus ASC) as T010, data_request_requeststatus as T020
            where datedifference > 0
            and T020.id = minid
            group by T010.id_request_id, statutdepart, T020.login_request_requeststatus";*/

            $query = "SELECT sum(delay) as delai, count(login) as nb, login FROM data_treatment 
			WHERE from_status_id in ($statut)
			AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '$datedebut' AND '$datefin'
			GROUP BY login
			ORDER BY login";

            // $conn = $this->EM->getconnection();
            // $stmt = $conn->prepare($query);
            // $stmt->execute();
            // $results = $stmt->fetchAll();
            $connection = $this->EM->getconnection();
            $results = $connection->executeQuery($query)->fetchAll();

            foreach ($results as $key => $value) {
                $tempstotal[$value['login']]['nb']       += $value['nb'];
                $tempstotal[$value['login']]['total'] += $value['delai'];
            }
        }

        $total = 0;
        $count = 0;
        foreach ($tempstotal as $key => $value) {
            $tempstotal[$key]['moyenne'] = round($value['total'] / $value['nb'], 2);
            $total += $value['total'];
            $count += $value['nb'];
        }

        $tempstotal['BANQUE']['moyenne'] = round($total / $count, 2);
        $tempstotal['BANQUE']['nb'] = $count;

        return $tempstotal;
    }

    public function forAllRequestMoyenneDetail($request)
    {

        $date = $this->getFromTodateFromRequest($request);
        $datedebut = $date[0];
        $datefin = $date[1];

        $Analyse_de_la_demande = $this->_tempsMoyen([4, 6], $datedebut, $datefin);
        $En_attente_davis_CTO = $this->_tempsMoyen([7, 8, 9, 20], $datedebut, $datefin);
        $En_attente_de_double_validation = $this->_tempsMoyen([10, 28, 24, 22, 21, 11, 12, 25, 26, 27, 29, 30, 39, 40], $datedebut, $datefin);
        $En_attente_repartition_capital = $this->_tempsMoyen([34, 14, 33], $datedebut, $datefin);
        $En_attente_correction = $this->_tempsMoyen([23, 38, 5, 43, 42, 44, 41], $datedebut, $datefin);
        $En_attente_de_virements = $this->_tempsMoyen([13, 31, 32], $datedebut, $datefin);
        // $En_attente_virement_initial = $this->getQueryForRequeststatust("150,151,152,158",$querydata);
        $En_attente_virement_depot_de_capital = $this->_tempsMoyen([99], $datedebut, $datefin);
        $En_attente_de_Kbis_and_statuts = $this->_tempsMoyen([15], $datedebut, $datefin);
        $En_attente_Kbis = $this->_tempsMoyen([45], $datedebut, $datefin);
        $retour = [
            '1 - Analyse_de_la_demande' => $Analyse_de_la_demande,
            '2 - En_attente_davis_CTO' => $En_attente_davis_CTO,
            //'10 - Abandonnes'=>$Abandonnes,
            '3 - En_attente_de_double_validation' => $En_attente_de_double_validation,
            '4 - En_attente_repartition_capital' => $En_attente_repartition_capital,
            '9 - En_attente_correction' => $En_attente_correction,
            '6 - En_attente_de_virements' => $En_attente_de_virements,
            '7 - En_attente_virement_depot_de_capital' => $En_attente_virement_depot_de_capital,
            '8 - En_attente_de_Kbis_and_statuts' => $En_attente_de_Kbis_and_statuts,
            '5 - En_attente_Kbis' => $En_attente_Kbis
        ];

        $table = [];
        foreach ($retour as $key => $value) {
            foreach ($value as $user => $avr) {
                $table[$user][$key] = $avr;
            }
        }

        foreach ($table as $key => $value) {
            foreach ($retour as $libel => $usr) {
                if (!isset($value[$libel])) {
                    $table[$key][$libel]['nb'] = 0;
                    $table[$key][$libel]['total'] = 0;
                    $table[$key][$libel]['moyenne'] = 0;
                }
            }
        }

        foreach ($table as $key => $value) {
            ksort($value);
            foreach ($value as $keyx => $valx) {
                if ($valx == 0) {
                    $value[$keyx] = "";
                }
            }
            $table[$key] = $value;
        }

        $res = [];
        $res[] = [
            'BANQUE', $table['BANQUE']['1 - Analyse_de_la_demande']['nb'], $table['BANQUE']['1 - Analyse_de_la_demande']['moyenne'],
            $table['BANQUE']['2 - En_attente_davis_CTO']['nb'], $table['BANQUE']['2 - En_attente_davis_CTO']['moyenne'],
            $table['BANQUE']['3 - En_attente_de_double_validation']['nb'],  $table['BANQUE']['3 - En_attente_de_double_validation']['moyenne'],
            $table['BANQUE']['4 - En_attente_repartition_capital']['nb'], $table['BANQUE']['4 - En_attente_repartition_capital']['moyenne'],
            $table['BANQUE']['5 - En_attente_Kbis']['nb'], $table['BANQUE']['5 - En_attente_Kbis']['moyenne']
        ];
        foreach ($table as $key => $value) {
            if ($key != "" && $key != "BANQUE") {
                $res[] = [
                    $key, $value['1 - Analyse_de_la_demande']['nb'], $value['1 - Analyse_de_la_demande']['moyenne'],
                    $value['2 - En_attente_davis_CTO']['nb'], $value['2 - En_attente_davis_CTO']['moyenne'],
                    $value['3 - En_attente_de_double_validation']['nb'], $value['3 - En_attente_de_double_validation']['moyenne'],
                    $value['4 - En_attente_repartition_capital']['nb'], $value['4 - En_attente_repartition_capital']['moyenne'],
                    $value['5 - En_attente_Kbis']['nb'], $value['5 - En_attente_Kbis']['moyenne']
                ];
            }
        }
        return $res;
    }
}
