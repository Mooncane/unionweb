<?php
/**
 * PHP section for main menu
 */

$activepage = $_REQUEST['url'];


?>

<nav class="navbar sidebar" role="navigation">
  <ul id="sidenav" class="nav">
    <li id="sidenav-header" >
      <!--<img src="public/images/navheader-bluelines.png" />-->
    </li>
    
    <li id="clients" <?= ($activepage == 'member') ? 'class="active"' : ''; ?> >
      <a href="<?=URL?>member">
        <i class="fa fa-clone"></i>
        <span class="sidenav-label">Medlemmer</span>
      </a>
    </li>
    
    <li <?= ($activepage == 'index') ? 'class="active"' : ''; ?>>
      <a href="<?=URL?>index">
        <i class="fa fa-bookmark-o"></i>
        <span class="sidenav-label">Økonomi</span>
      </a>
    </li>
    
    <li <?= ($activepage == 'mailflow') ? 'class="active"' : ''; ?>>
      <a href="<?=URL?>mailflow">
        <i class="fa fa-envelope-o"></i>
        <span class="sidenav-label">Mailflow</span>
      </a>
    </li>
    
    <li id="reports" <?= ($activepage == 'reports') ? 'class="active"' : ''; ?> >
      <a href="<?=URL?>reports">
        <i class="fa fa-th-large"></i>
        <span class="sidenav-label">Rapporter</span>
        <span class="fa fa-angle-down float-right"></span>
      </a>
      <ul class="nav nav-second-level">
        <li>
          <a href="<?=URL?>reports">Udsigt</a>
        </li>
        <li>
          <a href="<?=URL?>reports">Regional workload</a>
        </li>
      </ul>
    </li>
  </ul>
</nav>

