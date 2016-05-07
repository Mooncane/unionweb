<?php

/**
 * Class for db logic on VTCare pages
 */
class vtcare_model extends Model {

  /**
   * Class for db logic on VTCare pages
   */
  function __construct() {
    parent::__construct();

    $this->visitour = new Visitour(VTUSER, VTPASSWORD, VTLOC, VTURI);
  }

  public function getList() {

    $list = $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccitizen", array());
    return $list;
  }

  public function getDetails($cid) {
    $details = $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccitizen WHERE vtccitizenid = :id", array("id" => $cid));
    return $details[0];
  }

  public function getServices($cid = false) {
    if ($cid) {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccalltemplate WHERE vtccalltemplatecitizenid = :id", ["id" => $cid]);
    } else {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccalltemplate", []);
    }
  }
  

  public function updateService($tid, $postdata) {
    return $this->db->update("" . DB_PREFIX . "_vtccalltemplate", "vtccalltemplateid = $tid", $postdata);
  }

  public function getServiceList() {
    // From VT
    $returnServiceArray = array();
    $result = $this->visitour->webservice("ExecuteSQL", ["SQL" => "select * from vtsaart"]);
    $resArray = $result->diffgram->VTSData->Table;
    foreach ($resArray as $service) {
      $serv = (array) $service;
      $returnServiceArray[$serv['NR']] = $serv;
    }
    return $returnServiceArray;

    // From DB
    //return $this->db->select("SELECT * FROM ".DB_PREFIX."_vtcservice");
  }

  public function getTeamList() {
    // From VT
    $returnTeamArray = array();
    $result = $this->visitour->webservice("ExecuteSQL", ["SQL" => "select * from vtsteam"]);
    $resArray = $result->diffgram->VTSData->Table;
    foreach ($resArray as $team) {
      $returnTeamArray[] = (array) $team;
    }
    return $returnTeamArray;
  }

  public function getEmployeeList() {
    // From VT
    $returnEmpArray = array();
    $result = $this->visitour->webservice("ExecuteSQL", ["SQL" => "select * from vtsadm"]);
    $resArray = $result->diffgram->VTSData->Table;
    foreach ($resArray as $emp) {
      $returnEmpArray[] = (array) $emp;
    }
    return $returnEmpArray;
  }

  public function getCallList($cid = false) {
    if ($cid) {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccallcitizenid = :cid", ["cid" => $cid]);
    } else {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall", []);
    }
  }
  
  public function getCallListByDate($d, $cid = false) {
    $date = $d;
    if ($date == '0000-00-00' || $date == '') {
      $date = date('Y-m-d');
    }
    if ($cid) {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccalldate = :date AND vtccallcitizenid = :cid", ["date" => $date, "cid" => $cid]);
    } else {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccalldate = :date", ["date" => $date]);
    }
  }

  public function getCall($callid) {
    return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccallid = :callid", ["callid" => $callid]);
  }

  public function addService($table, $data) {
    return $this->db->insert($table, $data);
  }

  public function deleteService($table, $where) {
    return $this->db->delete($table, $where);
  }

  public function getService($templateid) {
    $res = $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccalltemplate WHERE vtccalltemplateid = :tid", ["tid" => $templateid]);
    return $res[0];
  }
  
  

  public function deleteCall($table, $where) {
    // Get call
    $invt = false;
    $call = $this->getCall($where['vtccallid']);
    if (!empty($call) && $call[0]["vtccallstatus"] > 1) {
      $invt = true;
      // If already planned in VT - delete there also
      $result = $this->visitour->webservice("Call", [
          "FunctionCode" => "4",
          "VTID" => $call[0]["vtccallvtid"]
      ]);

      switch ($result["CallResult"]) {
        case 0:
        case 30:
          $invt = false;
          break;
        case 40:
          $this->updateCall($call[0]['vtccallcitizenid'], $call[0]['vtccallid'], array("vtccallstatus" => 4));
          break;
        default:
          break;
      }
    }
    if (!$invt) {
      return $this->db->delete($table, $where);
    }
    return 0;
  }

  public function updateCall($cid, $callid, $data) {
    return $this->db->update(DB_PREFIX . '_vtccall', "vtccallcitizenid = $cid AND vtccallid = '$callid'", $data);
  }

  public function addCitizen($table, $data) {
    return $this->db->insert($table, $data);
  }

  public function deleteCitizen($table, $where) {
    return $this->db->delete($table, $where);
  }

  public function updateCitizen($cid, $postdata) {
    return $this->db->update('' . DB_PREFIX . '_vtccitizen', "vtccitizenid = $cid", $postdata);
  }

  public function searchCitizens($searchString) {
    if ($searchString == '') {
      return $this->getList();
    } else {
      return $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccitizen WHERE vtccitizenname LIKE :searchstring OR vtccitizencpr LIKE :searchstring", [":searchstring" => "%" . $searchString . "%"]);
    }
  }

  public function changeStatus($cid, $state) {
    return $this->db->update(DB_PREFIX . "_vtccitizen", "vtccitizenid = $cid", array("vtccitizenstatus" => $state));
  }

  /**
   * Create a string or array representing the weekdays in binary code, that calls are active for
   * 
   * @param string $weekstring A string representation of an integer, representing weekdays the call should be done
   * @param boolean $reverse Function returns the string in reverse order
   * @param boolean $as_array Function converts string to array before returning data
   * @return mixed The function either returns a string or array representing the weekdays that a call is active for. (f.ex. 01001 or array(0, 1, 0, 0, 1))
   */
  public function getWeekString($weekstring, $reverse = false, $as_array = false) {
    $return = ($reverse) ? strrev(str_pad(decbin($weekstring), 5, '0', STR_PAD_LEFT)) : str_pad(decbin($weekstring), 5, '0', STR_PAD_LEFT);
    return ($as_array) ? $return : str_split($return);
  }

  public function generateCalls($unit, $count, $cid) {
    // Create date data
    $begin = new DateTime(date("Y-m-d"));
    $dt = new DateTime(date("Y-m-d"));
    $tdi = new DateInterval("P" . $count . $unit);
    $end = $dt->add($tdi);
    $dateslist = array();
    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($begin, $interval, $end);
    foreach ($daterange as $dt) {
      $dateslist[] = $dt->format("Y-m-d");
    }

    $citizenlist = array();
    if ($cid) {
      // Single citizen
      $citizenlist[0] = $this->getDetails($cid);
    } else {
      // All citizens
      $citizenlist = $this->getList();
    }

    foreach ($citizenlist as $citizen) {
      if ($citizen['vtccitizenstatus']) {
        $services = $this->getServices($citizen['vtccitizenid']);
        foreach ($dateslist as $d) {
          $daytoexamine = new DateTime($d);
          $daytoexamineWeekday = date("N", strtotime($daytoexamine->format("Y-m-d")));

          foreach ($services as $service) {
            $weekarray = $this->getWeekString($service['vtccalltemplateweekdays'], true, true);
            $startdate = new DateTime($service['vtccalltemplatebegindate']);
            $weekdiff = floor($startdate->diff($daytoexamine, true)->days / 7);
            $offweekinterval = true;

            $testdiff = $weekdiff + 1;

            if ($service['vtccalltemplateoffweekinterval'] == 0 || $weekdiff == 0) {
              $offweekinterval = true;
            } elseif ($service['vtccalltemplateoffweekinterval'] - 1 > $weekdiff) {
              $offweekinterval = true;
            } elseif ($service['vtccalltemplateoffweekinterval'] - 1 == $weekdiff) {
              $offweekinterval = false;
            } elseif ($testdiff % $service['vtccalltemplateoffweekinterval'] == 0) {
              $offweekinterval = false;
            }

            if (($weekdiff == 0 || $weekdiff % $service['vtccalltemplateinterval'] == 0) && $offweekinterval && $weekarray[$daytoexamineWeekday - 1] == '1' && $daytoexamine->format("Y-m-d") >= $service['vtccalltemplatebegindate'] && ($daytoexamine->format("Y-m-d") <= $service['vtccalltemplateenddate'] || $service['vtccalltemplateenddate'] == '0000-00-00' )) {
              // Create call ID
              $callid = $citizen['vtccitizenid'] . "-" . $service['vtccalltemplateserviceid'] . "-" . $daytoexamine->format("ymd") . "-" . substr($service['vtccalltemplatetimeframebegin'], 0, 2) . substr($service['vtccalltemplatetimeframeend'], 0, 2);
              if (empty($this->getCallDetails($callid))) {
                // If call ID !exist in DB => create call
                echo $this->createCall($callid, $service, $citizen, $daytoexamine->format("Y-m-d"));

                //echo "I have created a call with ID of: $callid\n";
              } else {
                /*
                  echo "Call ID exists!\n";

                 */
              }
            }
          } // end foreach service
        } // end foreach date        
      } // Citizen status = inactive
    }
  }

  public function getCallDetails($callid) {
    return $this->db->select("SELECT vtccallid FROM " . DB_PREFIX . "_vtccall WHERE vtccallid = :callid", array("callid" => $callid));
  }

  public function createCall($callid, $service, $citizen, $daytoexamine) {
    $table = DB_PREFIX . "_vtccall";
    $data = [
        "vtccallid" => $callid,
        "vtccallcitizenid" => $service['vtccalltemplatecitizenid'],
        "vtccallserviceid" => $service['vtccalltemplateserviceid'],
        "vtccallduration" => $service['vtccalltemplateduration'],
        "vtccalltimestart" => $service['vtccalltemplatetimeframebegin'],
        "vtccalltimeend" => $service['vtccalltemplatetimeframeend'],
        "vtccallteam" => $citizen['vtccitizenteam'],
        "vtccalldate" => $daytoexamine,
        "vtccallstatus" => 0,
        "vtccallforbiddenct" => $service['vtccalltemplateforbiddenct'],
        "vtccallnoct" => $service['vtccalltemplatenoct'],
        "vtccallremark" => $service['vtccalltemplateremark'],
        "vtccallinfo" => $service['vtccalltemplateinfo'],
    ];

    return $this->db->insert($table, $data);
  }

  public function planCalls($unit, $count, $cid) {
    $begin = new DateTime(date("Y-m-d"));
    $dt = new DateTime(date("Y-m-d"));
    $tdi = new DateInterval("P" . $count . $unit);
    $end = $dt->add($tdi);

    $beginformatted = $begin->format('Y-m-d');
    $endformatted = $end->format('Y-m-d');

    echo "Planning period: " . $begin->format('Y-m-d') . " - " . $end->format('Y-m-d');

    // Get calls from DB
    if ($cid) {
      // Function called from citizen
      $calls = $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccalldate BETWEEN :beginformatted AND :endformatted AND vtccallcitizenid = :cid AND vtccallstatus = :vtstatus", [':beginformatted' => $beginformatted, ':endformatted' => $endformatted, ':cid' => $cid, ':vtstatus' => 0]);
      $citizen = $this->getDetails($cid);
      $citizenarray = array(
          $cid => $citizen
      );

      //print_r($calls);
    } else {
      // Function called from planning list
      $calls = $this->db->select("SELECT * FROM " . DB_PREFIX . "_vtccall WHERE vtccalldate BETWEEN :beginformatted AND :endformatted AND vtccallstatus = :vtstatus", [':beginformatted' => $beginformatted, ':endformatted' => $endformatted, ':vtstatus' => 0]);
      $citizens = $this->getList();
      $citizenarray = array();
      foreach ($citizens as $key => $cit) {
        $citizenarray[$cit['vtccitizenid']] = $cit;
      }
    }

    foreach ($calls as $key => $call) {
      //echo $citizenarray[$call['vtccallcitizenid']]['vtccitizenname'];
      if ($call['vtccallnoct'] > 1) {
        // 2_Mann job
        // Step 1. Create main call
        $result = $this->visitour->webservice("Call", [
            "Functioncode" => "0",
            "Agent" => "VTCare",
            "ExtID" => $call['vtccallid'],
            "VTID" => '-1',
            "Name" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenname']),
            "Phone" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenphone'],
            "CallInfo1" => utf8_encode($call['vtccallremark']),
            "CallInfo2" => utf8_encode($call['vtccallinfo']),
            "Country" => "DK",
            "ZIP" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenplz'],
            "City" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizencity']),
            "Street" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenstreet']),
            "TeamID" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenteam'],
            "TaskTypeID" => $call['vtccallserviceid'],
            "AllowedFieldmanagerIDs" => (($call['vtccallforbiddenct'] != '') ? "-".$call['vtccallforbiddenct'] : ''),
            "DateFrom" => $call['vtccalldate'],
            "DateTo" => $call['vtccalldate'],
            "TimeFrom" => $call['vtccalltimestart'],
            "TimeTo" => $call['vtccalltimeend'],
            "Duration" => $call['vtccallduration'],
        ]);

        $tempVTID = $result['VTID'];

        // Step 2. Create subcalls for more caretakers (1 or 2 extra calls)
        for ($i = 2; $i <= $call['vtccallnoct']; $i++) {
          $result = $this->visitour->webservice("Call", [
              "Functioncode" => "0",
              "Agent" => "VTCare",
              "ExtID" => $call['vtccallid'] . "--$i",
              "VTID" => '0',
              "Name" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenname']),
              "Phone" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenphone'],
              "CallInfo1" => utf8_encode($call['vtccallremark']),
              "CallInfo2" => utf8_encode($call['vtccallinfo']),
              "Country" => "DK",
              "ZIP" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenplz'],
              "City" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizencity']),
              "Street" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenstreet']),
              "TeamID" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenteam'],
              "TaskTypeID" => $call['vtccallserviceid'],
              "AllowedFieldmanagerIDs" => (($call['vtccallforbiddenct'] != '') ? "-".$call['vtccallforbiddenct'] : ''),
              "DateFrom" => $call['vtccalldate'],
              "DateTo" => $call['vtccalldate'],
              "TimeFrom" => $call['vtccalltimestart'],
              "TimeTo" => $call['vtccalltimeend'],
              "Duration" => $call['vtccallduration'],
              "Relation_ExtID" => $call['vtccallid'],
              "Relation_type" => '1',
          ]);
        }

        // Step 3. Plan main call
        $result = $this->visitour->webservice("Call", [
            "Functioncode" => "2",
            "Agent" => "VTCare",
            "VTID" => $tempVTID,
            "FixedDate" => $call['vtccalldate'] . "T00:00:00",
        ]);

        $data = array();
        $data['vtccallstatus'] = 2;
        $data['vtccallvtid'] = $tempVTID;
        $this->updateCall($call['vtccallcitizenid'], $call['vtccallid'], $data);
      } else {
        $result = $this->visitour->webservice("Call", [
            "Functioncode" => "2",
            "Agent" => "VTCare",
            "ExtID" => $call['vtccallid'],
            "VTID" => '-1',
            "Name" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenname']),
            "Phone" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenphone'],
            "CallInfo1" => utf8_encode($call['vtccallremark']),
            "CallInfo2" => utf8_encode($call['vtccallinfo']),
            "Country" => "DK",
            "ZIP" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenplz'],
            "City" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizencity']),
            "Street" => utf8_encode($citizenarray[$call['vtccallcitizenid']]['vtccitizenstreet']),
            "TeamID" => $citizenarray[$call['vtccallcitizenid']]['vtccitizenteam'],
            "TaskTypeID" => $call['vtccallserviceid'],
            "AllowedFieldmanagerIDs" => (($call['vtccallforbiddenct'] != '') ? "-".$call['vtccallforbiddenct'] : ''),
            "DateFrom" => $call['vtccalldate'],
            "DateTo" => $call['vtccalldate'],
            "TimeFrom" => $call['vtccalltimestart'],
            "TimeTo" => $call['vtccalltimeend'],
            "Duration" => $call['vtccallduration'],
            "FixedDate" => $call['vtccalldate'] . "T00:00:00",
        ]);

        $data = array();
        $data['vtccallstatus'] = 2;
        $data['vtccallvtid'] = $result['VTID'];
        $this->updateCall($call['vtccallcitizenid'], $call['vtccallid'], $data);
      }
    }

    return true;
  }

}
