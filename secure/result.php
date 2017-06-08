<HTML>
  <HEAD>
    <TITLE>Query result | Restricted area</TITLE>
  </HEAD>
  <BODY>
    <CENTER>
    
<?php  //qui inizia lo script lato server
  // Controllo accesso
  session_start();
  if (!isset($_SESSION['Username']))
  {die("ACCESS DENIED - USER NOT AUTHENTICATED");
   exit;
   }
  if(empty($_POST['query']))
  {
      header('local:logged.php');
  }
  $id=mysql_connect("bbcasavenezia.it.mysql","bbcasavenezia_i","sqlpswd") 
   or die ("Error 1: connection failed - authentication denied"); //nel caso di connessione fallita

    MySQL_select_db("bbcasavenezia_i",$id) //apre il DataBase
   or die ("Error 2: DB not fuond.");

  $sql="".$_POST['query']."";
  $rs=MySql_query($sql) //la query viene inviata al server ed eseguita
   or die("Error 5: Query failed or empty query box. Go back: <a href='logged.php'>Home</a>.");
  $NumRows=mysql_num_rows($rs);
  if($NumRows<1) 
  {
      echo("Query returned an empty record set. Go back: <a hfre='logged.php'>Home</a>"); exit;
  }
  $NumFields=mysql_num_fields($rs);
  echo"<h2>Query result record set</h2>";
  echo"<table border='1' align='center' valign='middle' cellspacing='3' cellpadding='10'>";
  $Fields=mysql_list_fields("bbcasavenezia_i","users",$id);
  echo"<tr>";
  for($i=0;$i<$NumFields;$i++)
    echo"<td>".$Fields[$i]."</td>";
  echo"</tr>";
  for($i=0;$i<$NumRows;$i++)
    {
        echo"<tr>";
        for($j=0;$j<$NumFields;$j++)
            echo "<td>".MySql_result($rs,$i,$j)."</td>";
        echo"</tr>";
    }
  echo"</table>";  
  echo"<h1></h1>Go back: <a href='logged.php'>Home</a>";
?>
 </CENTER>
 </BODY>
</HTML>