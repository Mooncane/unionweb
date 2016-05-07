<?php 
  $activesubpage = $_REQUEST['url'];
?>

<div class="row">
  <nav class="navbar-submenu">
    <ul class="submenu">
      <li <?= (($activesubpage == "vtcare/planning") ? 'class="active"' : ''); ?> ><a href="<?=URL?>vtcare/planning" >Plan</a></li>
      <li <?= (($activesubpage == "vtcare/planninghistory") ? 'class="active"' : ''); ?> ><a href="<?=URL?>vtcare/planninghistory" >Historik</a></li>
    </ul>
  </nav>
</div>