<?php

#####################################################################
#Script written by Chrissyx                                         #
#You may use and edit this script, if you don't remove this comment!#
#http://www.chrissyx.de(.vu)/                                       #
#####################################################################

 function tausche($x)
 {
  $temp_array = file("liste.dat");
  for ($j=0; $j<$x; $j++)
  {
   $temp_array2 = explode("\t", $temp_array[$j]);
   $temp_towrite .= implode("\t", $temp_array2);
  }
  $temp_array2 = explode("\t", $temp_array[$x+1]);
  $temp_array2[2]++;
  setcookie("vote", "true", time()+60*60*24*7); //eine woche!
  if (!file_exists("ip.dat"))
  {
   $temp = fopen("ip.dat", "w");
   fwrite($temp, $_SERVER['REMOTE_ADDR'] . "\n");
   fclose($temp);
  }
  else
  {
   $temp = fopen("ip.dat", "r");
   $iparray = fread($temp, filesize("ip.dat"));
   fclose($temp);
   $iparray = explode("\n", $iparray);
   foreach ($iparray as $temp) if ($temp == "") array_pop($iparray);
   $iparray[count($iparray)] = $_SERVER['REMOTE_ADDR'];
   $ip_towrite = implode("\n", $iparray);
   $temp = fopen("ip.dat", "w");
   fwrite($temp, $ip_towrite);
   fclose($temp);
  }
  $temp_towrite .= implode("\t", $temp_array2);
  $temp_array2 = explode("\t", $temp_array[$x]);
  $temp_towrite .= implode("\t", $temp_array2);
  for ($j=$x+2; $j<count($temp_array); $j++)
  {
   $temp_array2 = explode("\t", $temp_array[$j]);
   $temp_towrite .= implode("\t", $temp_array2);
  }
  $temp_temp = fopen("liste.dat", "w");
  fwrite($temp_temp, $temp_towrite);
  fclose($temp_temp);
 }

#
###---PLÄTZE CHECK---###
#
 $array = file("liste.dat");
 for ($i=0; $i<count($array); $i++)
 {
  $array2 = explode("\t", $array[$i]);
  $davor = $array2[2];
  if (($array2[2] > $votes_davor) and (($votes_davor) or ($votes_davor == "0")))
  {
   $x = $i-1;
   $temp_array = file("liste.dat");
   for ($j=0; $j<$x; $j++)
   {
    $temp_array2 = explode("\t", $temp_array[$j]);
    $temp_towrite .= implode("\t", $temp_array2);
   }
   $temp_array2 = explode("\t", $temp_array[$x+1]);
   $temp_towrite .= implode("\t", $temp_array2);
   $temp_array2 = explode("\t", $temp_array[$x]);
   $temp_towrite .= implode("\t", $temp_array2);
   for ($j=$x+2; $j<count($temp_array); $j++)
   {
    $temp_array2 = explode("\t", $temp_array[$j]);
    $temp_towrite .= implode("\t", $temp_array2);
   }
   $temp_temp = fopen("liste.dat", "w");
   fwrite($temp_temp, $temp_towrite);
   fclose($temp_temp);
   $i = count($array);
  }
  $votes_davor = $davor;
 }
 unset($votes_davor);

#
###---AB GEHT'S!---###
#
 $action = $_POST['action'];
 if (!$action) $action = $_GET['action'];
 $mode = $_POST['mode'];
 $id = $_GET['id'];
 if (file_exists("ip.dat"))
 {
  $temp = fopen("ip.dat", "r");
  $iparray = fread($temp, filesize("ip.dat"));
  fclose($temp);
  $iparray = explode("\n", $iparray);
  foreach ($iparray as $temp) if ($temp == "") array_pop($iparray);
 }
 else $iparray = array("");
 global $iparray;
 clearstatcache();

#
###---VOTE---###
#
 if ($id)                                 //vote für $id
 {
  if (!$_SERVER['REMOTE_ADDR']) die("<b>IP ERROR!</b> Ohne erkennbare IP kannst Du nicht voten!");
  if (($_COOKIE['vote'] == "true") or (in_array($_SERVER['REMOTE_ADDR'], $iparray)))
  {
   include("head.inc");
   echo("Bitte nur einmal voten! Danke!<br><br>");
   include("tail.inc");
   exit;
  }
  $array = file("liste.dat");             //liste zeilenweise in $array einlesen
  for ($i=0; $i<count($array); $i++)      //für jede zeile tue
  {
   $array2 = explode("\t", $array[$i]);    //die 5 werte auflösen
   $davor = $array2[2];
   if ($id == $array2[1])                 //nach der ID suchen, für die gevotet wurde
   {
    $array2[2]++;                         //ID einen vote höher
    setcookie("vote", "true", time()+60*60*24*7); //eine woche!
    if ($array2[2] > $votes_davor)
    {
     unset($towrite);
     tausche($i-1);                       //plätze ändern!
     include("head.inc");
     echo("Danke für deinen Vote! Klicke <a href=\"liste.php\">hier</a>, um zur Liste zu gelangen.<br><br>");
     include("tail.inc");
     exit;
    }
   }
   $towrite .= implode("\t", $array2);    //kein . "\n" nötig!!!?!
   $votes_davor = $davor;
  }
  $temp = fopen("liste.dat", "w");
  fwrite($temp, $towrite);
  fclose($temp);
  if (!file_exists("ip.dat"))
  {
   $temp = fopen("ip.dat", "w");
   fwrite($temp, $_SERVER['REMOTE_ADDR'] . "\n");
   fclose($temp);
  }
  else
  {
   $iparray[count($iparray)] = $_SERVER['REMOTE_ADDR'];
   $ip_towrite = implode("\n", $iparray);
   $temp = fopen("ip.dat", "w");
   fwrite($temp, $ip_towrite);
   fclose($temp);
  }
  include("head.inc");
  echo("Danke für deinen Vote! Klicke <a href=\"liste.php\">hier</a>, um zur Liste zu gelangen.<br><br>");
  include("tail.inc");
 }
 else
 {

#
###---REGISTRIEREN---###
#
  if ($action == "new")
  {
   if ($mode == "save")
   {
    $array = file("liste.dat");
    $newarray[0] = $_POST['name'];
    $newarray[1] = count($array)+1;
    $newarray[2] = "0";
    $newarray[3] = $_POST['url'];
    $newarray[4] = "0";
    $newarray[5] = md5($_POST['pw']);
    $newarray[6] = $_POST['bild'];
    if ($newarray[6] == "") $newarray[6] = "nobanner.jpg";
    include("head.inc");
    for ($i=0; $i<count($array); $i++)
    {
     $array2 = explode("\t", $array[$i]);
     $towrite .= implode("\t", $array2);
    }
    $towrite .= implode("\t", $newarray) . "\n";
    $temp = fopen("liste.dat", "w");
    fwrite($temp, $towrite);
    fclose($temp);
    ?>

   Registrierung abgeschlossen! <font color="red">Deine ID ist: <?php echo($newarray[1]); ?>!</font><br>
   Um die Webringfrunktion zu nutzen, füge diesen Code in den Code deiner Seite unter dem <code>&lt;body></code> Tag ein:<br><br>
   <code>&lt;script language="JavaScript" src="http://chrissyx.ch.funpic.de/extern.php?id=<?php echo($newarray[1]); ?>">&lt;/script></code><br><br>
   Klicke <a href="liste.php">hier</a>, um zur Liste zu gelangen.<br><br>

    <?php
    include("tail.inc");
   }
   else
   {
    include("head.inc");
    ?>

   <script language="JavaScript" type="text/javascript">
   function chkform (form)
   {
    if (form.name.value == "")
    {
     alert("Bitte Name angeben!");
     form.name.focus();
     return false;
    };

    if (form.name.value.indexOf("\t") != -1)
    {
     alert("Bitte keine Tabs '\t' im Namen!");
     form.name.focus();
     return false;
    }

//    if (form.name.value.length > 27)
//    {
//     alert("Name zu lang!");
//     form.name.focus();
//     return false;
//    }

    if (form.url.value == "")
    {
     alert("Bitte URL eingeben!");
     form.url.focus();
     return false;
    };

    if (form.url.value == "http://")
    {
     alert("Bitte eine gültige URL eingeben!");
     form.url.focus();
     return false;
    };

    if (form.bild.value == "http://")
    {
     alert("Bitte einen gültigen Pfad angeben ODER Feld freilassen!");
     form.bild.focus();
     return false;
    };

    if (form.pw.value == "")
    {
     alert("Bitte ein Passwort eingeben!");
     form.pw.focus();
     return false;
    };

    return true;
   };
   </script>

   <form action="liste.php" method="post" name="form">
   <b><font color="red">Werbung verboten!</font></b><br><br>

   <table>

    <tr>
     <td>Name der Seite:</td>
     <td><input type="text" name="name"></td>
    </tr>
    <tr>
     <td>Adresse der Seite:</td>
     <td><input type="text" name="url" value="http://"></td>
    </tr>
    <tr>
     <td>Pfad zum Banner<br>(Max. 468*60 Pixel!):</td>
     <td><input type="text" name="bild" value="http://"></td>
    </tr>
    <tr>
     <td colspan="2">Wenn Du keinen Banner hast, lasse das Feld bitte ganz frei!</td>
    </tr>
    <tr>
     <td>Dein Passwort:</td>
     <td><input type="password" name="pw"></td>
    </tr>
    <tr>
     <td colspan="2"><center><font color="red">Cookies müssen akzeptiert werden!</font></center></td>
    </tr>

   </table>

   <br>
   <input type="submit" value="Registrieren" onClick="return chkform(form);">
   <input type="hidden" name="action" value="new">
   <input type="hidden" name="mode" value="save">
   </form>

    <?php
    include("tail.inc");
   }
  }

#
###---EINLOGGEN---###
#
  elseif ($action == "login")
  {
   if ($mode == "save")
   {
    include("head.inc");
    $array = file("liste.dat");
    for ($i=0; $i<count($array); $i++)
    {
     $array2 = explode("\t", $array[$i]);
     if ($_POST['login_id'] == $array2[1]) $i = count($array);
    }
//    if ((md5($_POST['login_pw']) != $array2[5]) or ($_POST['login_id'] == "") or ($_POST['login_id'] > count($array)))
    if ((md5($_POST['login_pw']) != $array2[5]) or ($_POST['login_id'] == ""))
    {
     echo("Falsches Passwort oder Cookiefehler!<br><br>");
     include("tail.inc");
     exit;
    }
    ?>

  <u>Deine Daten:</u><br><br>
  <form action="liste.php" method="post">

  <table>

   <tr>
    <td>Nummer/ID:</td>
    <td><?php echo($array2[1]); ?></td>
   </tr>
   <tr>
    <td>Seitenname:</td>
    <td><input type="text" name="name" value="<?php echo($array2[0]); ?>"></td>
   </tr>
   <tr>
    <td>Hits:</td>
    <td><?php echo($array2[4]); ?></td>
   </tr>
   <tr>
    <td>Votes:</td>
    <td><?php echo($array2[2]); ?></td>
   </tr>
   <tr>
    <td>Adresse:</td>
    <td><input type="text" name="url" value="<?php echo($array2[3]); ?>"></td>
   </tr>
   <tr>
    <td>Bannerpfad:</td>
    <td><input type="text" name="bild" value="<?php echo($array2[6]); ?>"></td>
   </tr>
   <tr>
    <td colspan="2"><font size="2">Passwort ändern? Ansonsten Feld frei lassen!</font></td>
   </tr>
   <tr>
    <td>Passwort:</td>
    <td><input type="password" name="pw"></td>
   </tr>

  </table>

  <br>
  <input type="submit" value="Aktualisieren"> <input type="button" value="Abbrechen" onClick="javascript:document.location.href='liste.php';"><br><br>
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="id" value="<?php echo($array2[1]); ?>">
  <input type="hidden" name="votes" value="<?php echo($array2[2]); ?>">
  <input type="hidden" name="hits" value="<?php echo($array2[4]); ?>">
  </form>
  <form action="liste.php" method="post">
  <input type="submit" value="Account löschen" onClick="return confirm('Sicher? Alle Daten werden gelöscht! Auch deine Votes!');"><br>
  <input type="hidden" name="action" value="kill">
  <input type="hidden" name="id" value="<?php echo($array2[1]); ?>">
  </form>

    <?php
    include("tail.inc");
   }
   else
   {
    include("head.inc");
    ?>

  <form action="liste.php" method="post">

  <table>

   <tr>
    <td>Nummer/ID:</td>
    <td><input type="text" name="login_id"></td>
   </tr>
   <tr>
    <td>Passwort:</td>
    <td><input type="password" name="login_pw"> - Vergessen? Pech gehabt! :P</td>
   </tr>

  </table>

  <br>
  <input type="submit" value="Einloggen">
  <input type="hidden" name="action" value="login">
  <input type="hidden" name="mode" value="save">
  </form>

    <?php
    include("tail.inc");
   }
  }

#
###---STATISTIK---###
#
  elseif ($action == "stats")
  {
   include("head.inc");
   $anz_acc = 0;
   $anz_votes = 0;
   $anz_hits = 0;
   $array = file("liste.dat");
   for ($i=0; $i<count($array); $i++)
   {
    $array2 = explode("\t", $array[$i]);
    $anz_votes = $anz_votes + $array2[2];
    $anz_hits = $anz_hits + $array2[4];
    $anz_acc++;
   }
   echo("  Es sind insgesamt " . $anz_acc . " Seiten in der Liste.<br>
  Es wurde insgesamt " . $anz_votes . " Mal abgestimmt.<br>
  Es gibt insgesamt " . $anz_hits . " Hits.<br><br>");
   include("tail.inc");
  }

#
###---UPDATE---###
#
  elseif ($action == "update")
  {
   include("head.inc");
   $array = file("liste.dat");
   for ($i=0; $i<count($array); $i++)
   {
    $array2 = explode("\t", $array[$i]);
    if ($_POST['id'] == $array2[1])
    {
     $array2[0] = $_POST['name'];
     $array2[1] = $_POST['id'];
     $array2[2] = $_POST['votes'];
     $array2[3] = $_POST['url'];
     $array2[4] = $_POST['hits'];
     if ($_POST['pw']) $array2[5] = md5($_POST['pw']);
     $array2[6] = $_POST['bild'] . "\n";
    }
    $towrite = $towrite . implode("\t", $array2);
   }
   $temp = fopen("liste.dat", "w");
   fwrite($temp, $towrite);
   fclose($temp);
   echo("Profil geupdatet! Klicke <a href=\"liste.php\">hier</a>, um zur Liste zu gelangen.<br><br>");
   include("tail.inc");
  }

#
###---ACCOUNT LÖSCHEN---###
#
  elseif ($action == "kill")
  {
   include("head.inc");
   $array = file("liste.dat");
   for ($i=0; $i<count($array); $i++)
   {
    $array2 = explode("\t", $array[$i]);
    if ($_POST['id'] != $array2[1]) $towrite .= implode("\t", $array2);
   }
   $temp = fopen("liste.dat", "w");
   fwrite($temp, $towrite);
   fclose($temp);
   echo("Account gelöscht! Klicke <a href=\"liste.php\">hier</a>, um zur Liste zu gelangen.<br><br>");
   include("tail.inc");
  }

#
###---HITS---###
#
  elseif ($action == "hits")
  {
   $id = $_GET['hit_id'];
   $array = file("liste.dat");
   for ($i=0; $i<count($array); $i++)
   {
    $array2 = explode("\t", $array[$i]);
    if ($id == $array2[1]) $array2[4]++;
    $towrite = $towrite . implode("\t", $array2);
   }
  $temp = fopen("liste.dat", "w");
  fwrite($temp, $towrite);
  fclose($temp);
  echo("<meta http-equiv=\"refresh\" content=\"0; URL=liste.php\">
<a href=\"liste.php\">Weiterleiten...</a>");
  }

#
###---LISTE ZEIGEN---###
#
  else                                    //showlist
  {
   include("head.inc");
   echo(" <table border=\"1\">\n");
   $array = file("liste.dat");
   if ($array[0] == "")
   {
    echo("  Keine Seite eingetragen bzw. gefunden!");
    echo (" </table><br>\n");
    include("tail.inc");
    exit;
   }
   for ($i=0; $i<count($array); $i++)
   {
    $array2 = explode("\t", $array[$i]);
    /*------------------------------------------------------------------*\
    +0 - name, 1 - id, 2 - votes, 3 - URL, 4 - hits, 5 - PW, 6 - bildpfad+
    \*------------------------------------------------------------------*/
    ?>

  <tr>
   <td><?php echo($array2[0] . "\n"); ?></td>
   <td>Nummer/ID: <?php echo($array2[1] . "\n"); ?></td>
   <td>Hits: <?php echo($array2[4]); ?></td>
   <td>Votes: <?php echo($array2[2] . "\n"); ?></td>
  </tr>
  <tr>
   <td colspan="4"><a href="<?php echo($array2[3]); ?>" target="_blank" onClick="javascript:document.location.href('liste.php?action=hits&hit_id=<?php echo($array2[1]); ?>');"><img src="<?php echo($array2[6]); ?>" border="0" width="468" height="60" alt="<?php echo($array2[0]); ?>"></a></td>
  </tr>
  <tr>
   <td height="10" colspan="4"></td>
  </tr>

    <?php
   }
   echo (" </table><br>\n");
   include("tail.inc");
  }
 }
?>