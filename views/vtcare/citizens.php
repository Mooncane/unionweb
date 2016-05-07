
<?php if ($this->citizenid) : ?>
  <div class="wrapper wrapper-content">
    <div class="col-4">
      <div class="infobox">
        <div class="infobox-title">
          <h5>Borger - <?= $this->citizendetails['vtccitizenid'] ?></h5>
          <div class="align-right"><button class="btn btn-sm btn-danger" rel="<?= $this->citizendetails['vtccitizenid'] ?>" onclick="citizenOps.delete(this);">Slet borger</button></div>
        </div>
        <div class="infobox-content">
          <form id="cdetails" action="<?= URL ?>vtcare/updatecitizen/<?= $this->citizendetails['vtccitizenid'] ?>" method="post" >
            <table class="table table-striped">
              <tbody>
                <tr><td class="vtctablecell">Navn</td><td><input name="vtccitizenname" class="input input-cell" type="text" value="<?= utf8_encode($this->citizendetails['vtccitizenname']) ?>" /></td></tr>
                <tr><td class="vtctablecell">CPR</td><td><input name="vtccitizencpr" class="input input-cell" type="text" value="<?= substr($this->citizendetails['vtccitizencpr'], 0, 6) . '-' . substr($this->citizendetails['vtccitizencpr'], 6, 4) ?>" /></td></tr>
                <tr><td class="vtctablecell">Gade</td><td><input name="vtccitizenstreet" class="input input-cell" type="text" value="<?= utf8_encode($this->citizendetails['vtccitizenstreet']) ?>" /></td></tr>
                <tr><td class="vtctablecell">Postnummer</td><td><input name="vtccitizenplz" class="input input-cell" type="text" value="<?= $this->citizendetails['vtccitizenplz'] ?>" /></td></tr>
                <tr><td class="vtctablecell">By</td><td><input name="vtccitizencity" class="input input-cell" type="text" value="<?= utf8_encode($this->citizendetails['vtccitizencity']) ?>" /></td></tr>
                <tr><td class="vtctablecell">Telefon</td><td><input name="vtccitizenphone" class="input input-cell" type="text" value="<?= $this->citizendetails['vtccitizenphone'] ?>" /></td></tr>
                <tr>
                  <td class="vtctablecell">Tildelt team</td>
                  <td>
                    <select name="vtccitizenteam" class="input">
                      <option value="">Ingen</option>
                      <?php foreach ($this->teamlist as $team) { ?>
                        <option value="<?= $team['ID'] ?>" <?php if ($this->citizendetails['vtccitizenteam'] == $team['ID']) { echo 'selected';} ?>><?= $team['NAME'] ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr><td class="vtctablecell align-top">Bemærkninger</td><td><textarea name="vtccitizenremark" class="input input-cell" rows="2" noresi ><?= utf8_encode($this->citizendetails['vtccitizenremark']) ?></textarea></td></tr>
              </tbody>
            </table>
            <div class="align-right"><span id="message" style="margin-right: 15px;"></span><input type="submit" class="btn btn-primary" value="Gem ændringer"/></div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="infobox">
        <div class="infobox-title">
          <h5>Ydelser</h5>
          <div class="float-right">
            <button class="btn btn-xs btn-primary" onclick="modal.show('modal1');">Tilføj ydelse</button>
          </div>
        </div>
        <div class="infobox-content">
          <?php if (!empty($this->services)) : ?>
            <table class="table table-striped">
              <tbody>
                <tr class="align-left">
                  <th>Ydelse ID</th>
                  <th>Navn</th>
                  <th>Ugedage</th>
                  <th>Start</th>
                  <th>Slut</th>
                  <th>&nbsp;</th>
                </tr>
                <?php
                foreach ($this->services as $idx => $service) {
                  $wds = str_pad(decbin($service['vtccalltemplateweekdays']), 7, '0', STR_PAD_LEFT);
                  echo "<tr>";
                  echo "<td>" . $service['vtccalltemplateserviceid'] . "</td>";
                  echo "<td>" . $this->servicelist[$service['vtccalltemplateserviceid']]['NAME'] . "</td>";
                  echo "<td>";
                  echo "<div class='weekdaybox ". ((substr($wds, 6, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>M</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 5, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>T</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 4, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>O</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 3, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>T</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 2, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>F</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 1, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>L</div>";
                  echo "<div class='weekdaybox ". ((substr($wds, 0, 1) == '1') ? 'btn-primary' : 'btn-outline-danger') ."'>S</div>";
                  echo "</td>";
                  echo "<td>" . substr($service['vtccalltemplatetimeframebegin'], 0, 5) . "</td>";
                  echo "<td>" . substr($service['vtccalltemplatetimeframeend'], 0, 5) . "</td>";
                  echo "<td>";
                  echo "<button rel='" . $service['vtccalltemplateid'] . "' class='btn btn-xs btn-danger' onclick='serviceOps.delete(this);'><i class='fa fa-trash-o'> </i></button>&nbsp;";
                  echo "<button rel='" . $service['vtccalltemplateid'] . "' class='btn btn-xs btn-default' onclick='serviceOps.edit(this);'><i class='fa fa-pencil'> </i></button>&nbsp;";
                  if ($service['vtccalltemplateremark'] != '') {
                    echo "<button rel='" . $service['vtccalltemplateid'] . "' class='btn btn-xs btn-info' data-content='".utf8_encode($service['vtccalltemplateremark'])."' onclick='popover.top(this);'><i class='fa fa-exclamation'> </i></button>&nbsp;";
                  }
                  if ($service['vtccalltemplateinfo'] != '') {
                    echo "<button rel='" . $service['vtccalltemplateid'] . "' class='btn btn-xs btn-info' data-content='".utf8_encode($service['vtccalltemplateinfo'])."' onclick='popover.top(this);'><i class='fa fa-info'> </i></button>&nbsp;";
                  }
                  if ($service['vtccalltemplateoffweekinterval'] != '0') {
                    echo "<button class='btn btn-xs btn-warning'>". $service['vtccalltemplateoffweekinterval'] .".</button>";
                  }
                  echo "</td>";
                  echo "</tr>";
                }
                ?>

              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="infobox">
        <div class="infobox-title">
          <h5>Genererede besøg &nbsp; 
          <button id="plan-prevday" class="btn btn-xs btn-white" rel="<?=$this->yesterday;?>" onclick="planOps.getCallsByDate(this);"><i class="fa fa-angle-left"></i></button>
          <input id="plan-dateselector" type="text" class="input inline" value="<?= Session::get('citizenshowdate'); ?>" onblur="validateInput.date(this);planOps.getCallsByDate(this);"/>
          <button id="plan-nextday" class="btn btn-xs btn-white" rel="<?=$this->tomorrow;?>" onclick="planOps.getCallsByDate(this);"><i class="fa fa-angle-right"></i></button>
        </h5>
          <div class="align-right">
            <button class="btn btn-sm btn-default" onclick="modal.show('modal-gc');">Generer besøg</button>
            <button class="btn btn-sm btn-primary" onclick="modal.show('modal-pc');">Planlæg besøg</button>
            <button class="btn btn-sm btn-danger" onclick="callOps.deleteSelected(this);">Slet valgte besøg</button>
          </div>
        </div>
        <div id="calltable" class="infobox-content">
          <?php if (!empty($this->calllist)) : ?>
            <table class="table table-striped">
              <tbody>
                <tr class="align-left">
                  <th><input type="checkbox" id="toggleall" onchange="fieldOps.toggleAllCheckboxes(this, 'selectcall');"/></th>
                  <th>ID</th>
                  <th>Navn</th>
                  <th>Dato</th>
                  <th>Tidsrum</th>
                  <th>VTID</th>
                  <th>VT status</th>
                  <th>&nbsp;</th>
                </tr>
                  <?php foreach ($this->calllist as $call) { ?>
                  <tr>
                    <td>
                    <?php if ($call['vtccallstatus'] < 3) : ?>
                        <input class="selectcall" type="checkbox" rel="<?= $call['vtccallid'] ?>" /></td>
                    <?php else : ?>
                      &nbsp;
                    <?php endif; ?>
                    <td><?= $call['vtccallid'] ?></td>
                    <td><?= $this->servicelist[$call['vtccallserviceid']]['NAME'] ?></td>
                    <td><?= Date::dkDate($call['vtccalldate']) ?></td>
                    <td><?= substr($call['vtccalltimestart'], 0, 5) ?>-<?= substr($call['vtccalltimeend'], 0, 5) ?></td>
                    <td><?= $call['vtccallvtid'] ?></td>
                    <td><span class="label label-info"><?= $call['vtccallstatus'] ?></span></td>
                    <td>
                      <?php if ($call['vtccallstatus'] > 2) : ?>
                        <button class="btn btn-xs btn-danger"><i class='fa fa-lock'> </i></button>
                      <?php else : ?>
                        <button rel="<?= $call['vtccallid'] ?>" class="btn btn-xs btn-danger" onclick="callOps.delete(this);"><i class='fa fa-trash-o'> </i></button>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php } ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- New service modal -->
  <div id="modal1" class="modal" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tilføj ydelse</h4>
        </div>
        <form name="addservice" method="post" action="<?= URL ?>vtcare/addservice/<?= $this->citizendetails['vtccitizenid'] ?>">
          <div class="modal-body">
            <table class="table table-striped">
              <tr>
                <td>Ydelse</td>
                <td>
                  <select name="vtccalltemplateserviceid" onchange="formOps.selectService(this);">
                    <option id="no-selection" value="" selected >Vælg ydelse</option>
                    <?php foreach ($this->servicelist as $key => $service) { ?>
                      <option value="<?= $service['NR'] ?>"><?= $service['NAME'] ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td>Varighed</td>
                <td><input type="text" class="input" name="vtccalltemplateduration" value=""/></td>
              </tr>
              <tr>
                <td>Ugedage</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>Man</td><td>Tir</td><td>Ons</td><td>Tor</td><td>Fre</td><td>Lør</td><td>Søn</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" type="checkbox" value="1" name="monday" /></td>
                      <td><input class="weekdays" type="checkbox" value="2" name="tuesday" /></td>
                      <td><input class="weekdays" type="checkbox" value="4" name="wednesday" /></td>
                      <td><input class="weekdays" type="checkbox" value="8" name="thursday" /></td>
                      <td><input class="weekdays" type="checkbox" value="16" name="friday" /></td>
                      <td><input class="weekdays" type="checkbox" value="32" name="saturday" /></td>
                      <td><input class="weekdays" type="checkbox" value="64" name="sunday" /></td>
                    </tr>
                  </table>
                </td>
                <td>Interval</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>1.</td><td>2.</td><td>3.</td><td>4.</td><td>5.</td><td>6.</td><td>7.</td><td>8.</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="1" checked /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="2" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="3" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="4" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="5" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="6" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="7" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="8" /></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td># plejere</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>1</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td><input name="vtccalltemplatenoct" type="radio" value="1" checked ></td>
                      <td><input name="vtccalltemplatenoct" type="radio" value="2" ></td>
                    </tr>
                  </table>
                </td>
                <td>Overspring</td>
                <td>
                  <table class="vt-selectoffweeks">
                    <tr>
                      <td>0.</td><td>1.</td><td>2.</td><td>3.</td><td>4.</td><td>5.</td><td>6.</td><td>7.</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="0" checked /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="1" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="2" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="3" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="4" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="5" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="6" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="7" /></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>Starttid</td>
                <td><input class="input" type="text" name="vtccalltemplatetimeframebegin" value="07:30" onblur="validateInput.time(this);"/></td>
                <td>Sluttid</td>
                <td><input class="input" type="text" name="vtccalltemplatetimeframeend" value="17:00" onblur="validateInput.time(this);" /></td>
              </tr>
              <tr>
                <td>Startdato</td>
                <td><input class="input" type="text" name="vtccalltemplatebegindate" value="<?= date('d-m-Y'); ?>" onblur="validateInput.date(this);"/></td>
                <td>Slutdato</td>
                <td><input class="input" type="text" name="vtccalltemplateenddate" onblur="validateInput.date(this);" /></td>
              </tr>
              <tr>
                <td>Bemærkning</td>
                <td colspan="3"><input class="input" type="text" name="vtccalltemplateremark" /></td>
              </tr>
              <tr>
                <td>Info</td>
                <td colspan="3"><textarea class="input textarea" name="vtccalltemplateinfo" rows="3" cols="max" style="resize:none;"></textarea></td>
              </tr>
              <tr>
                <td>Forbyd plejer<br><span class="text-8">(CTRL+klik for at vælge)</span></td>
                <td colspan="3">
                  <select class="input" name="vtccalltemplateforbiddenct[]" multiple size="<?= count($this->teammembers[$this->citizendetails['vtccitizenteam']]); ?>">
                    <?php foreach ($this->employeelist as $employee) { ?>
                      <?php if ($employee['TEAM_NR'] == $this->citizendetails['vtccitizenteam']) : ?>
                        <option value="<?= $employee['ID'] ?>"><?= $employee['VORNAME'] . " " . $employee['NAME'] ?></option>
                      <?php endif; ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-white" onclick="modal.hide();return false;">Annuller</button>
            <button class="btn btn-primary" onclick="serviceOps.add(document.forms['addservice']);return false;">Gem</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit service -->
  <div id="modal2" class="modal" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Rediger ydelse</h4>
        </div>
        <form name="editservice" method="post" action="<?= URL ?>vtcare/updateservice/<?= $this->citizendetails['vtccitizenid'] ?>">
          <div class="modal-body">
            <table class="table table-striped">
              <tr>
                <td>Ydelse</td>
                <td>
                  <select name="vtccalltemplateserviceid" onchange="formOps.selectService(this);">
                    <?php foreach ($this->servicelist as $key => $service) { ?>
                      <option value="<?= $service['NR'] ?>"><?= $service['NAME'] ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td>Varighed</td>
                <td><input type="text" class="input" name="vtccalltemplateduration" value=""/></td>
              </tr>
              <tr>
                <td>Ugedage</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>Man</td><td>Tir</td><td>Ons</td><td>Tor</td><td>Fre</td><td>Lør</td><td>Søn</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" type="checkbox" value="1" name="monday" /></td>
                      <td><input class="weekdays" type="checkbox" value="2" name="tuesday" /></td>
                      <td><input class="weekdays" type="checkbox" value="4" name="wednesday" /></td>
                      <td><input class="weekdays" type="checkbox" value="8" name="thursday" /></td>
                      <td><input class="weekdays" type="checkbox" value="16" name="friday" /></td>
                      <td><input class="weekdays" type="checkbox" value="32" name="saturday" /></td>
                      <td><input class="weekdays" type="checkbox" value="64" name="sunday" /></td>
                    </tr>
                  </table>
                </td>
                <td>Interval</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>1.</td><td>2.</td><td>3.</td><td>4.</td><td>5.</td><td>6.</td><td>7.</td><td>8.</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="1" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="2" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="3" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="4" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="5" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="6" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="7" /></td>
                      <td><input class="weekdays" name="vtccalltemplateinterval" type="radio" value="8" /></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td># plejere</td>
                <td>
                  <table class="vt-selectweekdays">
                    <tr>
                      <td>1</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td><input name="vtccalltemplatenoct" type="radio" value="1" checked ></td>
                      <td><input name="vtccalltemplatenoct" type="radio" value="2" ></td>
                    </tr>
                  </table>
                </td>
                <td>Overspring</td>
                <td>
                  <table class="vt-selectoffweeks">
                    <tr>
                      <td>0.</td><td>1.</td><td>2.</td><td>3.</td><td>4.</td><td>5.</td><td>6.</td><td>7.</td>
                    </tr>
                    <tr>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="0" checked /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="1" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="2" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="3" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="4" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="5" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="6" /></td>
                      <td><input class="weekdays" name="vtccalltemplateoffweekinterval" type="radio" value="7" /></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>Starttid</td>
                <td><input class="input" type="text" name="vtccalltemplatetimeframebegin" value="07:30" onblur="validateInput.time(this);"/></td>
                <td>Sluttid</td>
                <td><input class="input" type="text" name="vtccalltemplatetimeframeend" value="17:00" onblur="validateInput.time(this);" /></td>
              </tr>
              <tr>
                <td>Startdato</td>
                <td><input class="input" type="text" name="vtccalltemplatebegindate" value="<?= date('d-m-Y'); ?>" onblur="validateInput.date(this);"/></td>
                <td>Slutdato</td>
                <td><input class="input" type="text" name="vtccalltemplateenddate" onblur="validateInput.date(this);" /></td>
              </tr>
              <tr>
                <td>Bemærkning</td>
                <td colspan="3"><input class="input" type="text" name="vtccalltemplateremark" /></td>
              </tr>
              <tr>
                <td>Info</td>
                <td colspan="3"><textarea class="input textarea" name="vtccalltemplateinfo" rows="3" cols="max" style="resize:none;"></textarea></td>
              </tr>
              <tr>
                <td>Forbyd plejer<br><span class="text-8">(CTRL+klik for at vælge)</span></td>
                <td colspan="3">
                  <select class="input" name="vtccalltemplateforbiddenct[]" multiple size="<?= count($this->teammembers[$this->citizendetails['vtccitizenteam']]); ?>">
                    <?php foreach ($this->employeelist as $employee) { ?>
                      <?php if ($employee['TEAM_NR'] == $this->citizendetails['vtccitizenteam']) : ?>
                        <option value="<?= $employee['ID'] ?>"><?= $employee['VORNAME'] . " " . $employee['NAME'] ?></option>
                      <?php endif; ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="vtccalltemplateid" name="vtccalltemplateid" value="" />
            <button class="btn btn-white" onclick="modal.hide();return false;">Annuller</button>
            <button class="btn btn-primary" onclick="serviceOps.update(document.forms['editservice']);return false;">Gem</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modal-gc" class="modal" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Generer besøg</h4>
        </div>
        <form name="generatecalls" method="post" action="<?= URL ?>vtcare/generatecalls/<?= $this->citizendetails['vtccitizenid'] ?>">
          <div class="modal-body" id="modal-body-gc">
            <table class="table table-striped">
              <tr>
                <td>Vælg periode</td>
                <td>
                  <input type="text" class="input" width="4" name="count" value="1" onblur="validateInput.integer(this);" />
                </td>
                <td>
                  <select name="unit">
                    <option value="D" selected="selected" >Dag(e)</option>
                    <option value="W">Uge(r)</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-white" onclick="modal.hide();return false;">Annuller</button>
            <button class="btn btn-primary" onclick="callOps.generate(document.forms['generatecalls']);return false;">Generer besøg</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modal-pc" class="modal" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Planlæg besøg</h4>
        </div>
        <form name="plancalls" method="post" action="<?= URL ?>vtcare/plancalls/<?= $this->citizendetails['vtccitizenid'] ?>">
          <div class="modal-body" id="modal-body-pc">
            <table class="table table-striped">
              <tr>
                <td>Vælg periode</td>
                <td>
                  <input type="text" class="input" width="4" name="count" value="1" onblur="validateInput.integer(this);" />
                </td>
                <td>
                  <select name="unit">
                    <option value="D" selected="selected" >Dag(e)</option>
                    <option value="W">Uge(r)</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-white" onclick="modal.hide();return false;">Annuller</button>
            <button class="btn btn-primary" onclick="callOps.plan(document.forms['plancalls']);return false;">Planlæg besøg</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php else : ?>
  <div class="wrapper wrapper-content">
    <div class="row">
      <div class="col-12">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Borgere</h5>
            <div class="align-right"><button class="btn btn-sm btn-primary" onclick="modal.show('modal3');">Tilføj borger</button></div>
          </div>
          <div class="infobox-content">
            <div class="table">
              <div class="table-row">
                <div class="table-cell">
                  <input id="search-citizen" class="input input-cell" onkeyup="citizenOps.search(this)" type="text" placeholder="Indtast navn eller cpr" />
                </div>
                <div class="table-cell input-group-button">
                  <button type="button" class="btn btn-primary">
                    <i class="fa fa-search">&nbsp;</i>
                    Søg borger
                  </button>
                </div>
              </div>
            </div>
            <div id="client-list" class="clients-list">
              <table class="table table-striped table-hover">
                <tbody>
                  <?php
                  foreach ($this->citizenlist as $key => $citizen) {
                    echo "<tr>";
                    echo "<td class='client-avatar'><i class='fa fa-" . ((intval($citizen['vtccitizencpr']) % 2 == 0) ? 'venus' : 'mars') . "'> </i></td>";
                    echo "<td><a data-toggle='tab' href='" . URL . "vtcare/citizens/" . $citizen['vtccitizenid'] . "' class='client-link'>" . utf8_encode($citizen['vtccitizenname']) . "</a></td>";
                    echo "<td>" . utf8_encode($citizen['vtccitizenstreet']) . "</td>";
                    echo "<td>" . utf8_encode($citizen['vtccitizenplz']) . " " . utf8_encode($citizen['vtccitizencity']) . "</td>";
                    echo "<td><i class='fa fa-phone'> </i> +45 " . substr($citizen['vtccitizenphone'], 0, 4) . " " . substr($citizen['vtccitizenphone'], 4, 4) . "</td>";
                    echo ($citizen['vtccitizenstatus'] == '1') ? "<td class='client-status align-right'><button rel='" . $citizen['vtccitizenid'] . "' class='btn btn-xs btn-primary' onclick='citizenOps.changestatus(this);'>Aktiv</button></td>" : "<td class='client-status align-right'><button rel='" . $citizen['vtccitizenid'] . "' class='btn btn-xs btn-danger' onclick='citizenOps.changestatus(this);'>Inaktiv</button></td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!--
      <div class="col-4">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Detaljer</h5>
          </div>
          <div class="infobox-content">
            Indhold

          </div>
        </div>
      </div>
      -->
    </div>
  </div>

  <div id="modal3" class="modal" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tilføj borger</h4>
        </div>
        <form name="addcitizen" method="post" action="<?= URL ?>vtcare/addcitizen">
          <div class="modal-body">
            <table class="table table-striped">
              <tr>
                <td>Navn</td>
                <td><input class="input" type="text" name="vtccitizenname" req="required" /></td>
              </tr>
              <tr>
                <td>CPR</td>
                <td><input class="input" type="text" name="vtccitizencpr" /></td>
              </tr>
              <tr>
                <td>Gade</td>
                <td><input class="input" type="text" name="vtccitizenstreet" /></td>
              </tr>
              <tr>
                <td>Postnummer</td>
                <td><input class="input" type="text" name="vtccitizenplz" onblur="fieldOps.fillCity(this, 'newcitizencity');" req="required" /></td>
              </tr>
              <tr>
                <td>By</td>
                <td><input id="newcitizencity" class="input" type="text" name="vtccitizencity" readonly req="required" /></td>
              </tr>
              <tr>
                <td>Telefon</td>
                <td><input class="input" type="text" name="vtccitizenphone" /></td>
              </tr>
              <tr>
                <td>Team</td>
                <td>
                  <select name="vtccitizenteam">
                    <option value="">Ingen</option>
                    <?php foreach ($this->teamlist as $team) { ?>
                      <option value="<?= $team['ID'] ?>"><?= $team['NAME'] ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-white" onclick="modal.hide();return false;">Annuller</button>
            <button class="btn btn-primary" onclick="if (formOps.validate(document.forms['addcitizen'])) {
                  citizenOps.add(document.forms['addcitizen'])
                }
                ;
                return false;">Gem</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php endif; ?>



