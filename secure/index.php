<?php
    session_start();
    if(isset($_POST['submit'])) {
        $id=mysql_connect("bbcasavenezia.it.mysql","bbcasavenezia_i","sqlpswd")
            or die ("Error 1: connection failed - database authentication error"); //nel caso di connessione fallita
        MySQL_select_db("bbcasavenezia_i") //apre il DataBase
            or die ("Error 2: DB not found");
        $user=$_POST['user'];$psw=$_POST['psw'];$psw=md5($psw);// echo $user."  ".$psw;
        $query_login="SELECT * FROM users WHERE user='$user' AND psw='$psw'";
        $rslt_login=MySql_query($query_login) //la query viene inviata al server ed eseguita
            or die("Error 3: query failed");
        $nrighe=MySql_num_rows($rslt_login);
        if ($nrighe>0) {
            // Esiste un record con questi username più(firma)password:
            // inserisco i dati nella sessione
            $_SESSION['Username']=MySql_result($rslt_login,0,1);
            header('location:logged.php');
            die();
        }
        else {
            echo '<center><span style="color:#F00;text-align:center;">No match found for such username and password.</span></center>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Restricted area</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>

    <body onLoad="document.form1.user.focus()">
        <center>
            <h1>Restrict Area</h1>
            Access is restricted to allowed personnel only.
            Please authenticate:
            <form name="form1" method="POST" action="index.php">
                <table border="0" cellspacing="10" cellpadding="10">
                    <tr>
                        <td>User:           </td>
                        <td><input name="user" type="text"></td>
                    </tr>
                    <tr>
                        <td>Password:       </td>
                        <td><input name="psw" type="password"></td>
                    </tr>
                    <tr>
                        <center><td colspan="2"><input name="Submit" type="submit" value="Log in"></td></center>
                    </tr>
                </table>
            </form>
        </center>
    </body>
</html>
