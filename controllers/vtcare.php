<?php

class vtcare extends Controller {

  function __construct() {
    parent::__construct();
    require 'models/vtcare_model.php';

    $this->model = new vtcare_model();
    $this->view->js = array('vtcare/js/default.js');
  }

  public function index() {

    //$this->view->userlist = $model->db->select('SELECT * FROM posts where id = :id', array(':id' => 2));

    $this->view->render('vtcare/index');
  }

  public function citizens($citizenid = false) {

    $this->view->teamlist = $this->model->getTeamList();

    if (!$citizenid) {
      // fetch all citizens
      $this->view->citizenlist = $this->model->getList();
    } else {
      $this->view->citizendetails = $this->model->getDetails($citizenid);
      $this->view->citizenid = $citizenid;
      $this->view->services = $this->model->getServices($citizenid);
      $this->view->servicelist = $this->model->getServiceList();
      $this->view->employeelist = $this->model->getEmployeeList();
      $this->view->teammembers = array();
      foreach ($this->view->employeelist as $emp) {
        $this->view->teammembers[$emp['TEAM_NR']][] = $emp;
      }
      //$this->view->calllist = $this->model->getCallList($citizenid);
      $this->view->calllist = $this->model->getCallListByDate(Date::dbDate(Session::get('citizenshowdate')), $citizenid);
      $tomorrow = new DateTime(Date::dbDate(Session::get('citizenshowdate')));
      $tomorrow->modify('+1 day');
      $yesterday = new DateTime(Date::dbDate(Session::get('citizenshowdate')));
      $yesterday->modify('-1 day');
      $this->view->tomorrow = $tomorrow->format('d-m-Y');
      $this->view->yesterday = $yesterday->format('d-m-Y');
    }

    $this->view->render('vtcare/citizens');
  }

  public function services() {
    $this->view->render('vtcare/services');
  }

  public function planning() {
    $this->view->citizenlist = $this->model->getList();
    $this->view->citizenarray = array();
    foreach ($this->view->citizenlist as $cit) {
      $this->view->citizenarray[$cit['vtccitizenid']] = $cit;
    }
    $this->view->servicelist = $this->model->getServiceList();
    $this->view->calllist = $this->model->getCallListByDate(Date::dbDate(Session::get('planningshowdate')));

    $tomorrow = new DateTime(Date::dbDate(Session::get('planningshowdate')));
    $tomorrow->modify('+1 day');
    $yesterday = new DateTime(Date::dbDate(Session::get('planningshowdate')));
    $yesterday->modify('-1 day');
    $this->view->tomorrow = $tomorrow->format('d-m-Y');
    $this->view->yesterday = $yesterday->format('d-m-Y');

    // KaldID, ServiceID, ServiceNavn, CitizenNavn, Dato, KaldTidsrum, VTID, VTStatus


    $this->view->render('vtcare/planning');
  }

  public function planninghistory() {
    $this->view->render('vtcare/planninghistory');
  }

  // internal functions
  public function getservicelist($citizenid) {
    echo $this->model->getDetails($citizenid);
  }

  /**
   * Retrieves a list of calls created for a particualr date.
   * @param type $date A date formatted in danish local format (dd-mm-yyyy)
   */
  public function getcallsbydate($date, $cid = false) {

    if (!$cid) {
      $calllist = $this->model->getCallListByDate(Date::dbDate($date));
    } else {
      $calllist = $this->model->getCallListByDate(Date::dbDate($date), $cid);
    }

    $citizenlist = $this->model->getList();
    $citizenarray = array();
    foreach ($citizenlist as $cit) {
      $citizenarray[$cit['vtccitizenid']] = $cit;
    }
    $servicelist = $this->model->getServiceList();


    $cl = array();
    foreach ($calllist as $call) {
      foreach ($call as $key => $value) {
        $cl[$call['vtccallid']][$key] = utf8_encode($value);
        $cl[$call['vtccallid']]['citizenname'] = utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenname']);
        $cl[$call['vtccallid']]['servicename'] = utf8_encode($servicelist[$call['vtccallserviceid']]['NAME']);
      }
      $cl[$call['vtccallid']]['vtccalldate'] = Date::dkDate($cl[$call['vtccallid']]['vtccalldate']);
    }
    echo json_encode($cl);
  }

  public function updatecitizen($cid = false) {

    $data = array();
    foreach ($_POST as $key => $value) {
      if ($key == 'vtccitizencpr') {
        $data[$key] = utf8_decode(str_replace('-', '', filter_input(INPUT_POST, 'vtccitizencpr', FILTER_SANITIZE_STRING)));
      } else {
        $data[$key] = utf8_decode(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING));
      }
    }

    $res = $this->model->updateCitizen($cid, $data);
    echo $res;
  }

  public function addservice($cid) {
    $postdata = array();

    $sumofweekdays = 0;
    foreach ($_POST as $key => $value) {
      switch ($key) {
        case 'monday':
        case 'tuesday':
        case 'wednesday':
        case 'thursday':
        case 'friday':
        case 'saturday':
        case 'sunday':
          $sumofweekdays += $value;
          break;

        case 'vtccalltemplateserviceid':
        case 'vtccalltemplatetimeframebegin':
        case 'vtccalltemplatetimeframeend':
        case 'vtccalltemplateremark':
        case 'vtccalltemplateinfo':
        case 'vtccalltemplateinterval':
        case 'vtccalltemplateduration':
        case 'vtccalltemplatenoct':
        case 'vtccalltemplateoffweekinterval':
          $postdata[$key] = utf8_decode(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING));
          break;
        case 'vtccalltemplateforbiddenct':
          $strbld = "";
          foreach ($value as $idx => $ctid) {
            $strbld .= $ctid . ",";
          }
          $postdata[$key] = rtrim($strbld, ',');
          break;

        case 'vtccalltemplatebegindate':
        case 'vtccalltemplateenddate':
          $datedata = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
          $datedatearray = explode("-", $datedata);
          $postdata[$key] = $datedatearray[2] . "-" . $datedatearray[1] . "-" . $datedatearray[0];
          break;
        default:
          break;
      }
    }
    $postdata['vtccalltemplateweekdays'] = $sumofweekdays;
    $postdata['vtccalltemplatecitizenid'] = $cid;
    echo $this->model->addService(DB_PREFIX . "_vtccalltemplate", $postdata);
  }

  public function deleteservice($vtcid) {
    echo $this->model->deleteService(DB_PREFIX . "_vtccalltemplate", array("vtccalltemplateid" => $vtcid));
  }

  public function addcitizen() {
    $postdata = array();
    foreach ($_POST as $key => $value) {
      switch ($key) {
        case 'vtccitizencpr':
          $postdata[$key] = preg_replace("/[^0-9]/", "", $value);
          break;
        case 'vtccitizenname':
        case 'vtccitizenstreet':
        case 'vtccitizencity':
        case 'vtccitizenteam':
          $postdata[$key] = utf8_decode(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING));
          break;
        case 'vtccitizenphone':
          $postdata[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
          break;
        default:
          $postdata[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
          break;
      }
    }

    echo $this->model->addCitizen(DB_PREFIX . "_vtccitizen", $postdata);
  }

  public function deletecitizen($cid) {
    echo $this->model->deleteCitizen(DB_PREFIX . "_vtccitizen", array("vtccitizenid" => $cid));
  }

  public function searchcitizens($searchString = '') {
    $res = $this->model->searchCitizens($searchString);
    $conv = array();
    $count = 0;
    foreach ($res as $reskey) {
      foreach ($reskey as $key => $value) {
        $conv[$count][$key] = utf8_encode($value);
      }
      $count++;
    }
    echo json_encode($conv);
  }

  public function changestatus($cid) {
    $details = $this->model->getDetails($cid);
    $currentStatus = $details['vtccitizenstatus'];
    if ($currentStatus == '1') {
      $res = $this->model->changeStatus($cid, 0);
      echo "2";
    } else {
      $res = $this->model->changeStatus($cid, 1);
      echo "1";
    }
  }

  public function generatecalls($cid = false) {
    // POST input params
    $unit = filter_input(INPUT_POST, 'unit', FILTER_SANITIZE_STRING);
    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);


    $this->model->generateCalls($unit, $count, $cid);
  }

  public function deletecall($callid) {
    echo $this->model->deleteCall(DB_PREFIX . "_vtccall", array("vtccallid" => $callid));
  }

  public function deleteselected($calllist) {
    $callarray = explode(',', $calllist);
    foreach ($callarray as $callid) {
      $this->model->deleteCall(DB_PREFIX . "_vtccall", array("vtccallid" => $callid));
    }
    echo "1";
  }

  public function getservice($templateid) {
    $res = $this->model->getService($templateid);
    $conv = array();
    foreach ($res as $key => $value) {
      $conv[$key] = utf8_encode($value);
    }
    echo json_encode($conv);
  }

  public function updateservice($cid = false) {
    $postdata = array();

    $sumofweekdays = 0;
    foreach ($_POST as $key => $value) {
      switch ($key) {
        case 'monday':
        case 'tuesday':
        case 'wednesday':
        case 'thursday':
        case 'friday':
        case 'saturday':
        case 'sunday':
          $sumofweekdays += $value;
          break;

        case 'vtccalltemplateserviceid':
        case 'vtccalltemplatetimeframebegin':
        case 'vtccalltemplatetimeframeend':
        case 'vtccalltemplateremark':
        case 'vtccalltemplateinfo':
        case 'vtccalltemplateinterval':
        case 'vtccalltemplateduration':
        case 'vtccalltemplatenoct':
        case 'vtccalltemplateoffweekinterval':
          $postdata[$key] = utf8_decode(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING));
          break;
        case 'vtccalltemplateforbiddenct':
          $strbld = "";
          foreach ($value as $idx => $ctid) {
            $strbld .= $ctid . ",";
          }
          $postdata[$key] = rtrim($strbld, ',');
          break;

        case 'vtccalltemplatebegindate':
        case 'vtccalltemplateenddate':
          $datedata = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
          if ($datedata == '') {
            $datedata = '00-00-0000';
          }
          $datedatearray = explode("-", $datedata);
          $postdata[$key] = $datedatearray[2] . "-" . $datedatearray[1] . "-" . $datedatearray[0];
          break;
        default:
          break;
      }
    }
    $postdata['vtccalltemplateweekdays'] = $sumofweekdays;
    $postdata['vtccalltemplatecitizenid'] = $cid;
    $postdata['vtccalltemplateid'] = filter_input(INPUT_POST, 'vtccalltemplateid', FILTER_SANITIZE_STRING);
    echo $this->model->updateService($postdata['vtccalltemplateid'], $postdata);
  }

  public function plancalls($cid = false) {
    // POST input params
    $unit = filter_input(INPUT_POST, 'unit', FILTER_SANITIZE_STRING);
    $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);


    $this->model->planCalls($unit, $count, $cid);
  }

  public function setsessionvariable($svar, $value) {
    Session::set($svar, $value);
    echo "updated session";
  }

}
