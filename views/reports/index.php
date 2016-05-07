<?php ?>
  <div class="wrapper wrapper-content">
    <div class="row">
      <div class="col-3">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Income</h5>
            <span class="label label-success float-right">Monthly</span>
          </div>
          <div class="infobox-content">
            <h1 class="no-margin">40.890,00</h1>
            <div class="float-right text-bold text-success">
              98%
              <i class="fa fa-bolt"></i>
            </div>
            <small>Total income</small>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Orders</h5>
            <span class="label label-info float-right">Weekly</span>
          </div>
          <div class="infobox-content">
            <h1 class="no-margin">12.890,00</h1>
            <div class="float-right text-bold text-info">
              20%
              <i class="fa fa-level-up"></i>
            </div>
            <small>New orders</small>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Visits</h5>
            <span class="label label-primary float-right">Daily</span>
          </div>
          <div class="infobox-content">
            <h1 class="no-margin">1.798,00</h1>
            <div class="float-right text-bold text-primary">
              44%
              <i class="fa fa-level-up"></i>
            </div>
            <small>New visits</small>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="infobox">
          <div class="infobox-title">
            <h5>User activity</h5>
            <span class="label label-danger float-right">Hourly</span>
          </div>
          <div class="infobox-content">
            <h1 class="no-margin">590,00</h1>
            <div class="float-right text-bold text-danger">
              38%
              <i class="fa fa-level-down"></i>
            </div>
            <small>In first month</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="infobox">
          <div class="infobox-title">
            <h5>Buttons</h5>
          </div>
          <div class="infobox-content">
            <button class="btn btn-primary" onclick="modal.show('modal1');">Click here</button>
          </div>
        </div>
      </div>
    </div>
    
    
  </div>


  <div id="footer">
    <div>
      <b>Copyright</b> Example Company © 2014-2015
    </div>
  </div>


<div id="right-sidebar"></div>
</div>

<div id="modal1" class="modal" role="dialog" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal title</h4>
        <small class="text-bold">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
      </div>
      <div class="modal-body">Body</div>
      <div class="modal-footer">
        <button class="btn btn-white" onclick="modal.hide();">Annuller</button>
        <button class="btn btn-primary" onclick="modal.hide();">Gem</button>
      </div>
    </div>
  </div>
</div>
