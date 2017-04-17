<?php

    include "functions.inc.php";

    if (file_exists("config.inc.php")) {
        include "config.inc.php";
    } else if (file_exists("config.sample.inc.php")) {
        include "config.sample.inc.php";
    } else {
        die();
    }

   // Detecting the users language
   if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
   } else {
      $user_lang = "en";
   }

   if (file_exists('lang/'.$user_lang.'.php')) {
      require_once 'lang/'.$user_lang.'.php';
   } else {
      require_once 'lang/en.php';
   }

   // Push Value with key into array: $data[$key] = $value;
   $downcount = 0;

   foreach ($addresses as $key => $value) {
      $temp = isPingable($value, $ports[$key]);
      $statuses[$key] = $temp;
      if (!$temp) {
         $downcount++;
      }
      unset($temp);
   }

   if ($downcount == 0) {
      $alert_type = "success";
      $alert_title = $lang['all_services_available'];
      $alert_text = $lang['all_services_available_detail'];

   } elseif ($downcount != 0 && $downcount <= 2) {
      $alert_type = "warning";
      $alert_title = $lang['minor_outage'];
      $alert_text = $lang['minor_outage_detail'];

   } else {
      $alert_type = "danger";
      $alert_title = $lang['major_outage'];
      $alert_text = $lang['major_outage_detail'];
   }

require_once "interfaces/bootstrap.php";