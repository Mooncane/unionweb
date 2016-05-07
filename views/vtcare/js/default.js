
window.onload = function () {

  if (document.getElementById('cdetails')) {
    document.getElementById('cdetails').onsubmit = function () {
      var url = this.getAttribute("action");
      xhrPost(url, serialize(this), function () {
        if (http_request.readyState === 4 && http_request.status === 200) {
          var response = http_request.responseText;
          if (response === "1") {
            //addMessage.success('Ændringerne blev gemt', 3000);
            document.location.reload();
          } else {
            //addMessage.error('Ændringerne blev ikke gemt');
          }
        }
      });
      return false;
    };
  }

  if (document.getElementById('deleteservice')) {
    document.getElementById('deleteservice').onclick = function () {
      if (confirm('Er du sikker på du vil slette ydelsen?')) {
        serviceOps.delete(this);
      }
    };
  }

  var cb = document.getElementsByClassName('selectcall');
  for (var i = 0; i < cb.length; i++) {
    cb[i].addEventListener('click', function () {
      document.getElementById('toggleall').checked = false;
    }, false);
  }
};

var addMessage = {};
addMessage.success = function (txt, timeout) {
  document.getElementById('message').innerHTML = txt;
  document.getElementById('message').className = 'text-success align-right';
  if (timeout > 0) {
    window.setTimeout(function () {
      document.getElementById('message').innerHTML = '';
    }, timeout);
  }
};

addMessage.error = function (txt, timeout) {
  document.getElementById('message').innerHTML = txt;
  document.getElementById('message').className = 'text-error align-right';
  if (timeout > 0) {
    window.setTimeout(function () {
      document.getElementById('message').innerHTML = '';
    }, timeout);
  }
};

var serviceOps = {};
serviceOps.add = function (elm) {
  var url = elm.getAttribute("action");
  xhrPost(url, serialize(elm), function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      if (response === "1") {
        modal.hide();
        document.location.reload();
        //update ydelser liste "AJAX style"
        // TODO NEXT!!!!

        //addMessage.success('Ændringerne blev gemt', 3000);
      } else {
        //addMessage.error('Ændringerne blev ikke gemt');
      }
    }
  });
  return false;
};

serviceOps.delete = function (elm) {
  var u = location.href.split('/');
  var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/deleteservice/' + elm.getAttribute('rel');

  if (confirm('Er du sikker på du vil slette ydelsen?')) {
    xhrPost(url, u, function () {
      if (http_request.readyState === 4 && http_request.status === 200) {
        var response = http_request.responseText;
        console.log(response);
        if (response === "1") {
          document.location.reload();
        } else {
          //addMessage.error('Ændringerne blev ikke gemt');
        }
      }
    });
  }
};

serviceOps.edit = function (elm) {
  modal.show('modal2');

  var u = location.href.split('/');
  var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/getservice/' + elm.getAttribute('rel');
  xhrPost(url, u, function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      var service = JSON.parse(response);

      // Fill form
      serviceOps.fillEditForm(elm, service);
      return false;
    }
  });
};

serviceOps.update = function (elm) {
  var url = elm.getAttribute("action");
  xhrPost(url, serialize(elm), function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      if (response === "FALSE") {
        //addMessage.error('Ændringerne blev ikke gemt');        
      } else {
        modal.hide();
        document.location.reload();
        //addMessage.success('Ændringerne blev gemt', 3000);
      }
    }
  });
  return false;
};

serviceOps.fillEditForm = function (elm, service) {
  // Select right service
  var chooseServiceElements = document.getElementsByName('vtccalltemplateserviceid')[1].options;
  for (var i = 0; i < chooseServiceElements.length; i++) {
    if (service.vtccalltemplateserviceid === chooseServiceElements[i].value) {
      chooseServiceElements[i].selected = 'selected';
    }
  }

  // Insert duration
  document.getElementsByName('vtccalltemplateduration')[1].value = service.vtccalltemplateduration;

  // Fill in weekdays
  var weekdaybin = window.parseInt(service.vtccalltemplateweekdays, 10).toString(2);
  while (weekdaybin.length < 7) {
    weekdaybin = '0' + weekdaybin;
  }
  if (weekdaybin[6] === '1') {
    document.getElementsByName('monday')[1].checked = true;
  }
  if (weekdaybin[5] === '1') {
    document.getElementsByName('tuesday')[1].checked = true;
  }
  if (weekdaybin[4] === '1') {
    document.getElementsByName('wednesday')[1].checked = true;
  }
  if (weekdaybin[3] === '1') {
    document.getElementsByName('thursday')[1].checked = true;
  }
  if (weekdaybin[2] === '1') {
    document.getElementsByName('friday')[1].checked = true;
  }
  if (weekdaybin[1] === '1') {
    document.getElementsByName('saturday')[1].checked = true;
  }
  if (weekdaybin[0] === '1') {
    document.getElementsByName('sunday')[1].checked = true;
  }

  // Fill in interval - VERY VERY VTCare specific!!! - new service has array indices 0-7, edit service has array indices 8-15
  var interval = document.getElementsByName('vtccalltemplateinterval');
  for (var i = 8; i < interval.length; i++) {
    if (interval[i].value === service.vtccalltemplateinterval) {
      interval[i].checked = true;
      //console.log ("interval: "+i);
    }
  }

  // Fill in # plejere - VERY VERY VTCare specific!!! - new service has array indices 0-1, edit service has array indices 2-3
  var nocts = document.getElementsByName('vtccalltemplatenoct');
  for (var i = 2; i < nocts.length; i++) {
    if (nocts[i].value === service.vtccalltemplatenoct) {
      nocts[i].checked = true;
      //console.log ("noplejere: "+i);
    }
  }

  // Fill in offweeks - VERY VERY VTCare specific!!! - new service has array indices 0-7, edit service has array indices 8-15
  var offweeks = document.getElementsByName('vtccalltemplateoffweekinterval');
  for (var i = 8; i < offweeks.length; i++) {
    if (offweeks[i].value === service.vtccalltemplateoffweekinterval) {
      offweeks[i].checked = true;
      //console.log ("offweek: "+i);
    }
  }


  document.getElementsByName('vtccalltemplatetimeframebegin')[1].value = service.vtccalltemplatetimeframebegin.substr(0, 5);
  document.getElementsByName('vtccalltemplatetimeframeend')[1].value = service.vtccalltemplatetimeframeend.substr(0, 5);
  document.getElementsByName('vtccalltemplatebegindate')[1].value = db2dkdate(service.vtccalltemplatebegindate);
  document.getElementsByName('vtccalltemplateenddate')[1].value = (service.vtccalltemplateenddate === "0000-00-00") ? "" : db2dkdate(service.vtccalltemplateenddate);
  document.getElementsByName('vtccalltemplateremark')[1].value = service.vtccalltemplateremark;
  document.getElementsByName('vtccalltemplateinfo')[1].value = service.vtccalltemplateinfo;

  // select forbidden cts
  var forbiddenct = service.vtccalltemplateforbiddenct.split(",");
  var forbiddenoptions = document.getElementsByName('vtccalltemplateforbiddenct[]')[1].options;
  for (var i = 0; i < forbiddenoptions.length; i++) {
    if (forbiddenct.indexOf(forbiddenoptions[i].value) > -1) {
      forbiddenoptions[i].selected = true;
    }
  }

  // insert templateid
  document.getElementById('vtccalltemplateid').value = service.vtccalltemplateid;

};

var citizenOps = {};
citizenOps.add = function (elm) {
  var url = elm.getAttribute('action');
  xhrPost(url, serialize(elm), function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      console.log(response);
      if (response === "1") {
        document.location.reload();
      } else {
        //addMessage.error('Ændringerne blev ikke gemt');
      }
    }
  });
  return false;
};

citizenOps.delete = function (elm) {
  if (confirm("Er du sikker på du vil slette denne borger? Dette kan ikke fortrydes!")) {
    var u = location.href.split('/');
    var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/deletecitizen/' + elm.getAttribute('rel');
    xhrPost(url, u, function () {
      if (http_request.readyState === 4 && http_request.status === 200) {
        var response = http_request.responseText;
        console.log(response);
        if (response === "1") {
          document.location.href = 'http://' + location.hostname + '/' + u[3] + '/vtcare/citizens';
        } else {
          //addMessage.error('Ændringerne blev ikke gemt');
        }
      }
    });
    return false;
  }
};

citizenOps.changestatus = function (elm) {
  var u = location.href.split('/');
  var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/changestatus/' + elm.getAttribute('rel');
  xhrPost(url, u, function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      //console.log(response);
      if (response === "1") {
        elm.className = 'btn btn-xs btn-primary';
        elm.innerHTML = 'Aktiv';
      } else {
        elm.className = 'btn btn-xs btn-danger';
        elm.innerHTML = 'Inaktiv';
      }
    }
  });
  return false;
};

citizenOps.search = function (elm) {
  var u = location.href.split('/');
  var baseURL = 'http://' + location.hostname + '/' + u[3];

  if (elm.value.length > -1) {
    var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/searchcitizens/' + elm.value;
    xhrPost(url, u, function () {
      if (http_request.readyState === 4 && http_request.status === 200) {
        var response = http_request.responseText;
        //console.log(JSON.parse(response));
        var citizens = JSON.parse(response);
        citizenOps.updateCitizenList(citizens, baseURL);
      }
    });
    return false;
  } else {
    return false;
  }
};

citizenOps.updateCitizenList = function (citizens, baseURL) {
  // Get clientlist and reset content
  var clientlist = document.getElementById('client-list');
  // fill clientlist
  var cbuilder = '';
  cbuilder = '<table class="table table-striped table-hover"><tbody>';

  for (var i = 0; i < citizens.length; i++) {
    cbuilder += '<tr>';
    cbuilder += '<td class="client-avatar"><i class="fa fa-' + ((parseInt(citizens[i].vtccitizencpr) % 2 === 0) ? 'venus' : 'mars') + '"> </i></td>';
    cbuilder += '<td><a data-toggle="tab" href="' + baseURL + '/vtcare/citizens/' + citizens[i].vtccitizenid + '" class="client-link">' + citizens[i].vtccitizenname + '</a></td>';
    cbuilder += '<td>' + citizens[i].vtccitizenstreet + '</td>';
    cbuilder += '<td>' + citizens[i].vtccitizenplz + ' ' + citizens[i].vtccitizencity + '</td>';
    cbuilder += '<td><i class="fa fa-phone"> </i> +45 ' + citizens[i].vtccitizenphone.substr(0, 4) + ' ' + citizens[i].vtccitizenphone.substr(4, 4) + '</td>';
    if (citizens[i].vtccitizenstatus === '1') {
      cbuilder += '<td class="client-status"><button rel="' + citizens[i].vtccitizenid + '" class="btn btn-xs btn-primary" onclick="citizenOps.changestatus(this);">Aktiv</button></td>';
    } else {
      cbuilder += '<td class="client-status"><button rel="' + citizens[i].vtccitizenid + '" class="btn btn-xs btn-danger" onclick="citizenOps.changestatus(this);">Inaktiv</button></td>';
    }
    cbuilder += '</tr>';
  }
  // end new clientlist
  clientlist.innerHTML = cbuilder + '</tbody></table>';
  return true;
};

var validateInput = {};
validateInput.time = function (elm) {
  var val = elm.value;
  var patt = /[^0-9]/g;
  val = val.replace(patt, "");
  val = val.substr(0, 4);
  if (val.length === 1) {
    // case h
    val = "0" + val + ":00";
  }

  if (val.length === 2) {
    // case hh
    val = val.substr(0, 2) + ':00';
  }
  if (val.length === 3) {
    // case h:mm
    // case h.mm
    if (val[0] === '0') {
      val = val.substr(0, 2) + ':' + val[2] + '0';
    } else {
      val = '0' + val[0] + ':' + val.substr(1, 2);
    }
  }
  if (val.length === 4) {
    // case hh:mm
    // case hh.mm
    val = val.substr(0, 2) + ':' + val.substr(2, 2);
  }

  // final check and adjustments
  var hours = val.substr(0, 2);
  var minutes = val.substr(3, 2);
  if (hours > 23) {
    hours = '23';
  }
  if (minutes > 59) {
    minutes = '59';
  }
  //console.log(val);
  elm.value = hours + ":" + minutes;

  // check if startime is earlier than endtime and adjust accordingly
  var start = document.getElementsByName('vtccalltemplatetimeframebegin')[0].value;
  var end = document.getElementsByName('vtccalltemplatetimeframeend')[0].value;
  start = start.replace(patt, "");
  end = end.replace(patt, "");
  if (start > end) {
    switch (elm.getAttribute('name')) {
      case "vtccalltemplatetimeframebegin":
        document.getElementsByName('vtccalltemplatetimeframeend')[0].value = elm.value;
        break;
      case "vtccalltemplatetimeframeend":
        document.getElementsByName('vtccalltemplatetimeframebegin')[0].value = elm.value;
        break;
      default:
        break;
    }
  }
  return true;
};

validateInput.integer = function (elm) {
  var res = parseInt(elm.value);
  //console.log(res);
  if (isNaN(res)) {
    elm.value = 1;
  } else {
    elm.value = res;
  }
  return true;
};

validateInput.date = function (elm) {
  var today = new Date();
  var day = today.getDate();
  if (day.length < 10) {
    day = '0' + day;
  }
  var month = today.getMonth() + 1;
  if (month < 10) {
    month = '0' + month;
  }
  var year = today.getFullYear();
  var todayDK = [day, month, year].join("-");

  var val = elm.value;
  var patt = /[^0-9]/g;
  val = val.replace(patt, "");
  var newval;

  switch (val.length) {
    case 1:
      // case d -> add current month and year and add 0 before d (i.e. 0d-mm-Y)
      newval = "0" + val + "-" + month + "-" + year;
      elm.value = newval;
      break;
    case 2:
      // case dd -> add current month and year
      newval = val + "-" + month + "-" + year;
      elm.value = newval;
      break;
    case 4:
      // case ddmm -> split into dd/mm and add year
      newval = val.substr(0, 2) + "-" + val.substr(2, 2) + "-" + year;
      elm.value = newval;
      break;
    case 6:
      // case ddmmyy -> split into dd/mm/Y (yy -> Y)
      newval = val.substr(0, 2) + "-" + val.substr(2, 2) + "-20" + val.substr(4, 2);
      elm.value = newval;
      break;
    case 8:
      // case ddmmyyyy -> split into dd-mm-yyyy (yy -> Y)
      newval = val.substr(0, 2) + "-" + val.substr(2, 2) + "-" + val.substr(4, 4);
      elm.value = newval;
      break;

    case 3:
    case 5:
    case 7:
    case 9:
      elm.value = todayDK;
      break;
    default:
      if (elm.getAttribute('name') === "vtccalltemplatebegindate") {
        elm.value = todayDK;
      } else {
        elm.value = '';
      }
      break;
  }

  // validate final date
  var cdate = new Date(elm.value.substr(6, 4) + "-" + elm.value.substr(3, 2) + "-" + elm.value.substr(0, 2));
  var cday = '' + cdate.getDate();
  if (cday.length < 2) {
    cday = '0' + cday;
  }
  var cmonth = '' + (cdate.getMonth() + 1);
  if (cmonth.length < 2) {
    cmonth = '0' + cmonth;
  }
  var cyear = cdate.getFullYear();
  var checkdate = [cday, cmonth, cyear].join('-');
  // Check if startdate is before enddate if exists
  if (document.getElementsByName('vtccalltemplatebegindate')[0]) {
    var begindate = document.getElementsByName('vtccalltemplatebegindate')[0].value;
    var enddate = document.getElementsByName('vtccalltemplateenddate')[0].value;
    var bd = new Date(begindate.substr(6, 4) + "-" + begindate.substr(3, 2) + "-" + begindate.substr(0, 2));
    var ed = new Date(enddate.substr(6, 4) + "-" + enddate.substr(3, 2) + "-" + enddate.substr(0, 2));
    if (elm.value !== checkdate || bd > ed) {
      if (elm.getAttribute('name') === "vtccalltemplatebegindate") {
        elm.value = todayDK;
      } else {
        elm.value = '';
      }
    }
  }

};

validateInput.postalcode = function (elm) {
  var val = elm.value;
  var patt = /[^0-9]/g;
  val = val.replace(patt, "");
  val = val.substr(0, 4);
  if (val.length !== 4) {
    elm.value = '';
    elm.setAttribute('placeholder', 'Fejl i postnummer!');
    return false;
  }
  return true;
};


/** FORM OPS **/
var formOps = {};
formOps.selectService = function (elm) {
  var noselection = document.getElementById('no-selection');
  elm.removeChild(noselection);
};

formOps.clearField = function (elm) {
  elm.value = "";
};

formOps.validate = function (form) {
  var errors = false;
  var formFields = form.elements;
  for (var i = 0; i < formFields.length; i++) {
    if (formFields[i].nodeName === 'INPUT' && formFields[i].getAttribute('req') === 'required' && formFields[i].value === '') {
      formFields[i].className = "input input-danger";
      errors = true;
    }
  }
  if (errors) {
    alert('Der er fejl i indtastningen!');
    return false;
  } else {
    return true;
  }
  //console.log(formFields);
};
/** FORM OPS ^^^^^^^^^^^^^^^ */

/** CALL OPS **/
var callOps = {};
callOps.generate = function (elm) {
  var serialelm = serialize(elm);
  var url = elm.getAttribute('action');

  // Add loading.gif
  var u = location.href.split('/');
  document.getElementById('modal-body-gc').innerHTML = "<center><img class='loading' src='http://" + location.hostname + "/" + u[3] + "/public/images/loading.gif' /></center>";

  xhrPost(url, serialelm, function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      console.log(response);
      if (response) {
        document.location.reload();
      } else {
        //addMessage.error('Ændringerne blev ikke gemt');
      }
    }
  });
  return false;
};

callOps.delete = function (elm) {
  var u = location.href.split('/');
  var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/deletecall/' + elm.getAttribute('rel');

  if (confirm('Er du sikker på du vil slette besøget? \n(Handlingen kan ikke fortrydes!)')) {
    xhrPost(url, u, function () {
      if (http_request.readyState === 4 && http_request.status === 200) {
        var response = http_request.responseText;
        //console.log(response);
        if (response === "1") {
          document.location.reload();
        } else {
          //addMessage.error('Ændringerne blev ikke gemt');
        }
      }
    });
  }
};

callOps.plan = function (elm) {
  var serialelm = serialize(elm);
  var url = elm.getAttribute('action');

  // Add loading gif
  var u = location.href.split('/');
  document.getElementById('modal-body-pc').innerHTML = "<center><img class='loading' src='http://" + location.hostname + "/" + u[3] + "/public/images/loading.gif' /></center>";

  xhrPost(url, serialelm, function () {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = http_request.responseText;
      console.log(response);
      if (response) {
        document.location.reload();
      } else {
        //addMessage.error('Ændringerne blev ikke gemt');
      }
    }
  });
  return false;

};

callOps.deleteSelected = function (elm) {
  var checks = document.getElementsByClassName('selectcall');
  var checkedCalls = [];
  for (var i = 0; i < checks.length; i++) {
    if (checks[i].checked) {
      checkedCalls.push(checks[i].getAttribute('rel'));
    }
  }
  var calllist = checkedCalls.join();

  var u = location.href.split('/');
  var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/deleteselected/' + calllist;

  if (confirm('Er du sikker på du vil slette de valgte besøg? \n(Handlingen kan ikke fortrydes!)')) {
    xhrPost(url, u, function () {
      if (http_request.readyState === 4 && http_request.status === 200) {
        var response = http_request.responseText;
        //console.log(response);
        if (response === "1") {
          document.location.reload();
        } else {
          //addMessage.error('Ændringerne blev ikke gemt');
        }
      }
    });
  }
};


/** FIELD OPS **/
var fieldOps = {};
fieldOps.fillCity = function (elm, target) {
  if (elm.value.length === 0)
    return false;
  var targetelm = document.getElementById(target);
  if (!validateInput.postalcode(elm)) {
    elm.className = "input input-danger";
    targetelm.className = "input input-danger";
    return false;
  } else {
    elm.className = "input input-success";
    targetelm.className = "input input-success";
  }
  var plz = elm.value;
  xhrGet('http://dawa.aws.dk/postnumre?q=' + plz, function (e) {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = JSON.parse(http_request.responseText);
      targetelm.value = response[0].navn;
      //console.log(response);
      return false;
    }
  });
};


fieldOps.toggleAllCheckboxes = function (elm, cname) {
  var checkBoxes = document.getElementsByClassName(cname);
  for (var i = 0; i < checkBoxes.length; i++) {
    if (elm.checked) {
      checkBoxes[i].checked = true;
    } else {
      checkBoxes[i].checked = false;
    }
  }
};


planOps = {};
planOps.getCallsByDate = function (elm) {
  var thedate;
  if (elm.id === 'plan-dateselector') {
    thedate = elm.value;
  } else {
    thedate = elm.getAttribute('rel');
  }

  if (thedate === '') {
    thedate = extractJSdateDK(new Date());
  }

  var u = location.href.split('/');
  if (u[6]) {
    var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/getcallsbydate/' + thedate + "/" + u[6];
  } else {
    var url = 'http://' + location.hostname + '/' + u[3] + '/vtcare/getcallsbydate/' + thedate;
  }


  xhrGet(url, function (e) {
    if (http_request.readyState === 4 && http_request.status === 200) {
      var response = JSON.parse(http_request.responseText);

      // Update the call table
      planOps.updateCallTable(response);

      // Update navigation controls
      var pday = document.getElementById('plan-prevday');
      var cday = document.getElementById('plan-dateselector');
      var nday = document.getElementById('plan-nextday');
      cday.value = thedate;
      var d = new Date(dkdate2db(thedate));
      var dprev = new Date(d.getTime() - 86400000); // The day before
      var dnext = new Date(d.getTime() + 86400000); // The day after

      var thedatebefore = extractJSdateDK(dprev);
      var thedateafter = extractJSdateDK(dnext);
      pday.setAttribute("rel", thedatebefore);
      nday.setAttribute("rel", thedateafter);
      
      if (u[6]) {
        var updsessurl = 'http://' + location.hostname + '/' + u[3] + '/vtcare/setsessionvariable/citizenshowdate/' + thedate;
      } else {
        var updsessurl = 'http://' + location.hostname + '/' + u[3] + '/vtcare/setsessionvariable/planningshowdate/' + thedate;
      }
      xhrGet(updsessurl, function (e) {
        if (http_request.readyState === 4 && http_request.status === 200) {
          var response = http_request.responseText;
          return false;
        }
      });
    }
  });

};

planOps.updateCallTable = function (data) {
  var calltable = document.getElementById('calltable');
  var newcalltable = '';

  newcalltable += "<table class=\"table table-striped\"><tbody><tr class=\"align-left\"><th><input type=\"checkbox\" id=\"toggleall\" onchange=\"fieldOps.toggleAllCheckboxes(this, 'selectcall');\"/></th><th>Kald ID</th><th>Borger</th><th>Ydelsenavn</th><th>Dato</th><th>Tidsrum</th><th>VTID</th><th>VT status</th><th>&nbsp;</th></tr>";

  for (var key in data) {
    var sb = '';
    if (!data.hasOwnProperty(key))
      continue;

    var obj = data[key];
    sb = "<tr><td>";
    newcalltable += sb;
    if (obj.vtccallstatus < 3) {
      sb = "<input class=\"selectcall\" type=\"checkbox\" rel=\"" + obj.vtccallid + "\" />";
    } else {
      sb = "&nbsp;";
    }
    newcalltable += sb + "</td>";
    sb = "<td>" + obj.vtccallid + "</td><td>" + decodeURIComponent(escape(obj.citizenname)) + "</td><td>" + decodeURIComponent(escape(obj.servicename)) + "</td><td>" + obj.vtccalldate + "</td>";
    sb += "<td>" + obj.vtccalltimestart.substr(0, 5) + "-" + obj.vtccalltimeend.substr(0, 5) + "</td><td>" + obj.vtccallvtid + "</td>";
    sb += "<td><span class=\"label label-info\">" + obj.vtccallstatus + "</span></td>";
    newcalltable += sb;

    if (obj.vtccallstatus > 2) {
      sb = "<td><button class=\"btn btn-xs btn-danger\"><i class='fa fa-lock'> </i></button></td>";
    } else {
      sb = "<td><button rel=\"" + obj.vtccallid + "\" class=\"btn btn-xs btn-danger\" onclick=\"callOps.delete(this);\"><i class='fa fa-trash-o'> </i></button></td>";
    }

    sb += "</tr>";
    newcalltable += sb;
  }


  newcalltable += "</tbody></table>";
  calltable.innerHTML = newcalltable;

  return true;
};

