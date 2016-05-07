<?php
  require 'submenu/index.php';
?>

<div class="wrapper wrapper-content">
    <div class="col-8">
      <div class="infobox">
      <div class="infobox-title">
        <h5>Brugerliste</h5>
        <div class="align-right"><button class="btn btn-sm btn-primary" onclick="userOps.add();">Ny bruger</button></div>
      </div>
      <div class="infobox-content">
        <table class="table table-striped">
          <tbody>
            <tr class="align-left">
              <th>ID</th>
              <th>Navn</th>
              <th>&nbsp;</th>
            </tr>
            <?php 
              foreach ($this->userlist as $user){
                echo "<tr>";
                echo "<td>".$user['userid']."</td>";
                echo "<td>".$user['username']."</td>";
                echo "<td>";
                if ($_SESSION['auth'] != $user['username']){
                  echo "<button class='btn btn-xs btn-danger'><i class='fa fa-trash'> </i></button>";
                }
                echo "</td>";
                echo "</tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
