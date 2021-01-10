<?php
 if (!$_GET['id']) die("document.write('<b>ERROR:</b> Kein id Parameter gefunden!');");
?>
document.write('<table width="100%" bgcolor="black">');
document.write('<tr>');
document.write('<td valign="middle" bgcolor="black"><a href="http://chrissyx.s01.user-portal.com/liste.php"><img src="http://chrissyx.s01.user-portal.com/extern.gif" border="0" alt="Chrissyx Toplist und Webring"></a></td>');
document.write('<td valign="middle" bgcolor="black"><center><font color="white">- | -</center></font></td>');
document.write('<td valign="middle" bgcolor="black"><center><font color="white">Klicke <a href="http://chrissyx.s01.user-portal.com/liste.php?id=<?php echo($_GET['id']) ?>">hier</a>, um für diese Seite zu voten!</font></center></td>');
document.write('<td valign="middle" bgcolor="black"><center><font color="white">- | -</center></font></td>');
document.write('<td valign="middle" bgcolor="black"><p align="right"><form name="leiste"><select name="links" onChange="javascript:if(this.form.links.options[this.form.links.selectedIndex].value.length != 0) window.open(this.form.links.options[this.form.links.selectedIndex].value);"><option>Andere Seiten im Webring, bitte auswählen...</option>');
<?php
 $array = file("liste.dat");
 $size = count($array);
 for ($i=0; $i<$size; $i++)
 {
  $array2 = explode("\t", $array[$i]);
  echo("document.write('<option value=\"" . $array2[3] . "\">" . $array2[0] . "</option>');");
 }
?>
document.write('</select></form></p></td>');
document.write(' </tr>');
document.write('</table>');
