<?php 
  $activesubpage = $_REQUEST['url'];
?>

<div class="row">
  <nav class="navbar-submenu">
    <ul class="submenu">
      <li <?= (($activesubpage == "configuration/users") ? 'class="active"' : ''); ?> ><a href="<?=URL?>configuration/users" >Brugere</a></li>
      <li <?= (($activesubpage == "configuration/standards") ? 'class="active"' : ''); ?> ><a href="<?=URL?>configuration/standards" >Standarder</a></li>
      <li <?= (($activesubpage == "configuration/other") ? 'class="active"' : ''); ?> ><a href="<?=URL?>configuration/other" >Andet</a></li>
    </ul>
  </nav>
</div>