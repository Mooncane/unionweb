<?php ?>
<!DOCTYPE html>
<html>
  <head>
    <title>UnionWEB&reg;</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="<?=URL?>public/css/normalize.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?=URL?>public/css/modal.css">
    <link rel="stylesheet" type="text/css" href="<?=URL?>public/css/main.css">
    <script type="text/javascript" src="<?=URL?>public/js/main.js"></script>
    <script type="text/javascript" src="<?=URL?>public/js/modal.js"></script>
    <script type="text/javascript" src="<?=URL?>public/js/Chart.js"></script>
    
    <?php
      if (isset($this->js)){
        echo '<script type="text/javascript"></script>';
        foreach ($this->js as $js){
          echo '<script type="text/javascript" src="'.URL.'views/'.$js.'"></script>';
        }
      }
    ?>
    
  </head>
  <body>
    <div id="wrapper">
      