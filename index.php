<?php
/**
 * Created by PhpStorm.
 * User: hrvoje
 * Date: 02.09.16
 * Time: 18:34
 */

/* Load configuration */
require_once('config.inc.php');

/* connect to the db */
$mysqli = mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
if (mysqli_connect_errno()) {
    die( "Failed to connect to MySQL: " . mysqli_connect_error() );
}

$res = mysqli_query($mysqli, "SET NAMES utf8");
if (!$res) {
    die( "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error );
}

$res = mysqli_query($mysqli, "SELECT * FROM ".MYSQL_TABLE." WHERE kind_id=1 AND schuljahr_id=20162");
if (!$res) {
    die( "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error );
}

header('Content-Type: text/html; charset=utf-8');

?>
<html>
<head>
    <meta name="viewport" content="width=800">
    <link rel="stylesheet" href="default.css">
</head>
<body>
<?php

echo '<h3>Ruri, Schuljahr 2016/2, Klasse 1a</h3>';

echo '<table cellpadding="0" cellspacing="0" class="db-table">';
echo '<thead><tr><th></th><th></th><th>Montag</th><th>Dienstag</th><th>Mittwoch<th>Donnerstag</th><th>Freitag</th></tr></thead><tbody>';

$rowCount=0;
while($row = mysqli_fetch_assoc($res)) {
    $stunde     = $row['stunde'];
    $montag     = $row['Montag'];
    $dienstag   = $row['Dienstag'];
    $mittwoch   = $row['Mittwoch'];
    $donnerstag = $row['Donnerstag'];
    $freitag    = $row['Freitag'];

    echo '<tr>';

    if(strlen($montag) > 0 && is_null($dienstag) && is_null($mittwoch) && is_null($donnerstag) && is_null($freitag) ) {
        // Nur Montag is definiert, dann wird es eine durchgehende Zeile (z.B. Pause)
        echo "<tr class='intermediate'><td></td><td>{$stunde}</td><td colspan='5'>{$montag}</td></tr>\n";
    } else {
        $rowCount++;
        echo "<td>{$rowCount}</td><td>{$stunde}</td><td>{$montag}</td><td>{$dienstag}</td><td>{$mittwoch}</td><td>{$donnerstag}</td><td>{$freitag}</td>\n";
    }

    echo '</tr>';
}
echo '</tbody></table><br />';

?>


</body>

</html>

