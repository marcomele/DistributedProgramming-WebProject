<?php session_start();
                //qui inizia lo script lato server
                // Controllo accesso
                if (!isset($_SESSION['Username'])) {
                    die("<CENTER>ACCESS DENIED - USER NOT AUTHENTICATED</CENTER>");
                    exit;
                }
                //$id=mysql_connect("bbcasavenezia.it.mysql","bbcasavenezia_i","sqlpswd") 
                  //  or die ("Error 1: connection failed - authentication denied"); //nel caso di connessione fallita

                //MySQL_select_db("bbcasavenezia_i",$id) //apre il DataBase
                  //  or die ("Error 2: DB not fuond.");

                //$sql="SELECT * FROM users";
                //$rs=MySql_query($sql) //la query viene inviata al server ed eseguita
                  //  or die("Query failed");
                //$NumCampi=mysql_num_fields($rs);
                //$Campi=mysql_list_fields("bbcasavenezia_i","users",$id);
                echo"<center>";
                echo"<h2>Hello ".$_SESSION['Username'].". Welcome in your restricted area home page.</h2>";
                echo"<a href=logout.php>Logout</a>";
                echo"\n";
                echo" ";
                //echo"Insert query:";
            ?>
<HTML>
    <HEAD>
        <TITLE>Restricted area</TITLE>
    </HEAD>
    <BODY onLoad="document.form1.user.focus()">
        <CENTER>
            <form name="form2" method="POST" action="result.php">
                <table border="0" cellspacing="10" cellpadding="10">
                    <tr>
                        <input name="query" type="textarea" value="">
                    </tr>
                    <tr rowspan="10">
                        <input name="Submit" type="submit" value="Submit">
                    </tr>
                </table>
            </form>
        </CENTER>
    </BODY>
</HTML>