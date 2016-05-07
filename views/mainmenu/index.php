<?php
/**
 * PHP section for main menu
 */

$activepage = $_REQUEST['url'];


?>

<nav class="navbar sidebar" role="navigation">
  <ul id="sidenav" class="nav">
    <li id="sidenav-header" style="background-color:white;">
      <img src="<?=URL?>public/images/privatsygeplejerske.png" />
      
      <!--<img src="public/images/navheader-bluelines.png" />-->
    </li>
    <!--<li id="reports" <?= ($activepage == 'reports') ? 'class="active"' : ''; ?> >
      <a href="<?=URL?>reports">
        <i class="fa fa-th-large"></i>
        <span class="sidenav-label">Reports</span>
        <span class="fa fa-angle-down float-right"></span>
      </a>
      <ul class="nav nav-second-level">
        <li>
          <a href="<?=URL?>reports">Forecast</a>
        </li>
        <li>
          <a href="<?=URL?>reports">Regional workload</a>
        </li>
      </ul>
    </li>
    <li id="clients" <?= ($activepage == 'clients') ? 'class="active"' : ''; ?> >
      <a href="<?=URL?>clients">
        <i class="fa fa-clone"></i>
        <span class="sidenav-label">Clients</span>
      </a>
    </li>
    <li <?= ($activepage == 'index') ? 'class="active"' : ''; ?>>
      <a href="<?=URL?>index">
        <i class="fa fa-bookmark-o"></i>
        <span class="sidenav-label">Economy</span>
      </a>
    </li>
    <li <?= ($activepage == 'vtour') ? 'class="active"' : ''; ?>>
      <a href="<?=URL?>vtour">
        <i class="fa fa-flag-checkered"></i>
        <span class="sidenav-label">Visitour</span>
      </a>
    </li>-->
    <li <?= ($activepage == 'vtcare') ? 'class="active"' : ''; ?>>
      <a href="<?=URL?>vtcare">
        <i class="fa fa-wheelchair"></i>
        <span class="sidenav-label">VT care</span>
        <span class="fa fa-angle-down float-right"></span>
      </a>
      <ul class="nav nav-second-level">
        <li <?= (substr($activepage,0,15) == 'vtcare/citizens') ? 'class="active"' : ''; ?>>
          <a href="<?=URL?>vtcare/citizens">Borgere</a>
        </li>
        <!--
        <li <?= ($activepage == 'vtcare/services') ? 'class="active"' : ''; ?>>
          <a href="<?=URL?>vtcare/services">Ydelser</a>
        </li>-->
        <li <?= (substr($activepage,0,15) == 'vtcare/planning') ? 'class="active"' : ''; ?>>
          <a href="<?=URL?>vtcare/planning">Planlægning</a>
        </li>
      </ul>
    </li>
  </ul>
</nav>

