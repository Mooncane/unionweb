// This is the main shared JavaScript functions


// Find min-height of window and adjust page-wrapper
function getMinHeight() {

}

function xhrGet(url, action, ajaxerror) {
  if (!window.XMLHttpRequest)
    return;
  try {
    http_request = new XMLHttpRequest();
    http_request.onreadystatechange = action;
    http_request.open('GET', url, true);
    http_request.send(null);
  } catch (error) {
    ajaxerror(error);
  }
}


function xhrPost(url, data, action, ajaxerror) {
  try {
    http_request = new XMLHttpRequest();
    http_request.onreadystatechange = action;
    http_request.open('POST', url, true);
    http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http_request.setRequestHeader("Content-length", data.length);
    http_request.setRequestHeader("Connection", "close");
    http_request.send(data);
  } catch (error) {
    ajaxerror(error);
  }
}

function serialize(form) {
  if (!form || form.nodeName !== "FORM") {
    return;
  }
  var i, j, q = [];
  for (i = form.elements.length - 1; i >= 0; i = i - 1) {
    if (form.elements[i].name === "") {
      continue;
    }
    switch (form.elements[i].nodeName) {
      case 'INPUT':
        switch (form.elements[i].type) {
          case 'text':
          case 'hidden':
          case 'password':
          case 'button':
          case 'reset':
          case 'submit':
            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            break;
          case 'checkbox':
          case 'radio':
            if (form.elements[i].checked) {
              q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            }
            break;
          case 'file':
            break;
        }
        break;
      case 'TEXTAREA':
        q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
        break;
      case 'SELECT':
        switch (form.elements[i].type) {
          case 'select-one':
            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            break;
          case 'select-multiple':
            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
              if (form.elements[i].options[j].selected) {
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
              }
            }
            break;
        }
        break;
      case 'BUTTON':
        switch (form.elements[i].type) {
          case 'reset':
          case 'submit':
          case 'button':
            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            break;
        }
        break;
    }
  }
  return q.join("&");
}

/**
 * Convert a db date format to danish standard format
 * 
 * @param {type} d Input Date from DB (yyyy-mm-dd)
 * @returns String Date d formatted as danish format (dd-mm-yyyy)
 */
function db2dkdate(d) {
  var patt = /[^0-9]/g;
  d = d.replace(patt, "");
  // yyyymmdd
  return d.substr(6, 2) + "-" + d.substr(4, 2) + "-" + d.substr(0, 4);

}

/**
 * Convert a danish standard format to db format
 * 
 * @param {type} d Input Date in danish format (dd-mm-yyyy)
 * @returns String Date d formatted as db format (yyyy-mm-dd)
 */
function dkdate2db(d) {
  var patt = /[^0-9]/g;
  d = d.replace(patt, "");
  // ddmmyyyy
  return d.substr(4, 4) + "-" + d.substr(2, 2) + "-" + d.substr(0, 2);
}

function extractJSdateDK(d) {
  var day = d.getDate();
  var month = d.getMonth() + 1;
  var year = d.getFullYear();

  if (day < 10) {
    day = "0" + day;
  }
  if (month < 10) {
    month = "0" + month;
  }
  return day + "-" + month + "-" + year;


}

function extractJSdateDB(d) {
  var day = d.getDate();
  var month = d.getMonth() + 1;
  var year = d.getFullYear();

  if (day < 10) {
    day = "0" + day;
  }
  if (month < 10) {
    month = "0" + month;
  }
  return year + "-" + month + "-" + day;
}

var popover = {};
popover.top = function (elm) {
  var coords = elm.getBoundingClientRect();
  var message = elm.getAttribute('data-content');

  console.log(elm.getBoundingClientRect());
  
  var ycoord = coords.y+22;
  var xcoord = coords.x;


  var popoverid = Math.floor(Math.random() * (100 - 1)) + 1; // Number bewteen 1 - 99
  popoverid = "pop-"+popoverid;

  var tooltip = document.createElement('div');
  tooltip.className = "popover fade";
  tooltip.id = popoverid;
  
  
  
  tooltip.style.cssText = "position:absolute;top:" + ycoord + "px;left:" + xcoord + "px;opacity:1;";
  document.body.appendChild(tooltip);
  
  /**
  var arrow = document.createElement('div');
  arrow.className = "arrow_box";
  tooltip.appendChild(arrow);
  */

  var content = document.createElement('div');
  content.className = "popover-content";
  content.innerHTML = message;
  tooltip.appendChild(content);

  elm.setAttribute("onclick", 'popover.hide(this, "' + popoverid + '")');
};

popover.hide = function (elm, popid) {
  document.getElementById(popid).parentNode.removeChild(document.getElementById(popid));
  elm.setAttribute("onclick", 'popover.top(this);');
};
