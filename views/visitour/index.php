<?php ?>
  <div class="wrapper wrapper-content">
   
    
    <?php if (!empty($this->calls) > 0) : ?>
    <div class="row">
      <div class="col-6">
        <div class="infobox">
          <div class="infobox-title">
            <h5># of calls / day</h5>
          </div>
          <div class="infobox-content">
            <div>
              <canvas id="callsperday" width="792" height="400"></canvas>
              <script>
                <?php foreach ($this->calls as $key => $val){ ?>
                    callsLabelData.push("<?=$key?>");
                    callsCallData.push("<?=$val?>");
                <?php } ?>
              </script>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="infobox">
          <div class="infobox-title">
            <h5># of kms / day</h5>
          </div>
          <div class="infobox-content">
            <div>
              <canvas id="kmsperday" width="792" height="400"></canvas>
              <script>
                <?php foreach ($this->kms as $key => $val){ ?>
                    kmsLabelData.push("<?=$key?>");
                    kmsCallData.push("<?=$val?>");
                <?php } ?>
              </script>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    
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
