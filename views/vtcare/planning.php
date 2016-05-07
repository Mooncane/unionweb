
<div class="wrapper wrapper-content">
  <div class="col-12">
    <div class="infobox">
      <div class="infobox-title">
        <h5>Genererede besøg &nbsp; 
          <button id="plan-prevday" class="btn btn-xs btn-white" rel="<?=$this->yesterday;?>" onclick="planOps.getCallsByDate(this);"><i class="fa fa-angle-left"></i></button>
          <input id="plan-dateselector" type="text" class="input inline" value="<?= Session::get('planningshowdate'); ?>" onblur="validateInput.date(this);planOps.getCallsByDate(this);"/>
          <button id="plan-nextday" class="btn btn-xs btn-white" rel="<?=$this->tomorrow;?>" onclick="planOps.getCallsByDate(this);"><i class="fa fa-angle-right"></i></button>
        </h5>
        
        <div class="align-right">
          <button class="btn btn-sm btn-default" onclick="modal.show('modal-gc');">Generer besøg</button>
          <button class="btn btn-sm btn-primary" onclick="modal.show('modal-pc');">Planlæg kald</button>
          <button class="btn btn-sm btn-danger" onclick="callOps.deleteSelected(this);">Slet valgte kald</button>
        </div>
      </div>
      <div id="calltable" class="infobox-content">
        <?php if (!empty($this->calllist)) : ?>
          <table class="table table-striped">
            <tbody>
              <tr class="align-left">
                <th><input type="checkbox" id="toggleall" onchange="fieldOps.toggleAllCheckboxes(this, 'selectcall');"/></th>
                <th>Kald ID</th>
                <th>Borger</th>
                <th>Ydelsenavn</th>
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
                  <td><?= $this->citizenarray[$call['vtccallcitizenid']]['vtccitizenname'] ?></td>
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

<div id="modal-gc" class="modal" role="dialog" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Generer kald</h4>
      </div>
      <form name="generatecalls" method="post" action="<?= URL ?>vtcare/generatecalls">
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
          <button class="btn btn-primary" onclick="callOps.generate(document.forms['generatecalls']);return false;">Generer kald</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="modal-pc" class="modal" role="dialog" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Planlæg kald</h4>
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
          <button class="btn btn-primary" onclick="callOps.plan(document.forms['plancalls']);return false;">Planlæg kald</button>
        </div>
      </form>
    </div>
  </div>
</div>