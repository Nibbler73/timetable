<?php
/**
 * Created by PhpStorm.
 * User: hrvoje
 * Date: 02.09.16
 * Time: 18:34
 */

/* Load configuration */
require_once('config.inc.php');


/* Parse input */
$kidId = $_REQUEST['kind'] ?: 1;
$schuljahr = $_REQUEST['schuljahr'] ?: -1;

if(! is_numeric($kidId) && !is_null($kidId)) {
    die('Ungültiges kind');
}
if(! is_numeric($schuljahr) && !is_null($schuljahr)) {
    die('Ungültiges schuljahr');
}

/* connect to the db */
$mysqli = mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
if (mysqli_connect_errno()) {
    die( "Failed to connect to MySQL: " . mysqli_connect_error() );
}

$res = mysqli_query($mysqli, "SET NAMES utf8");
if (!$res) {
    die( "Failed enable UTF-8: (" . $mysqli->errno . ") " . $mysqli->error );
}

/* get default schuljahr from database */
if(is_numeric($schuljahr) && $schuljahr === -1) {
    $res = mysqli_query($mysqli, "SELECT MAX(id) AS schuljahr FROM schuljahre");
    if (!$res) {
        die("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
    }
    $row = mysqli_fetch_assoc($res);
    $schuljahr = $row['schuljahr'];
}


if(is_numeric($schuljahr)) {
    $res = mysqli_query($mysqli, "SELECT description FROM schuljahre WHERE id=" . $schuljahr);
    if (!$res) {
        die("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
    }
    $row = mysqli_fetch_assoc($res);
    $schuljahrDescription = $row['description'];
} else {
    die('?!');
}
if(is_numeric($kidId)) {
    $res = mysqli_query($mysqli, "SELECT name FROM kinder WHERE id=" . $kidId);
    if (!$res) {
        die("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
    }
    $row = mysqli_fetch_assoc($res);
    $kindName = $row['name'];
} else {
    die('?!');
}

if(is_numeric($kidId) && is_numeric($schuljahr)) {
    $res = mysqli_query($mysqli, "SELECT * FROM " . MYSQL_TABLE . " WHERE kind_id={$kidId} AND schuljahr_id={$schuljahr} ORDER BY stunde ASC");
    if (!$res) {
        die("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
    }
} else {
    die('?!');
}

$currentDay = intval( date('N') );
// skip Weekend
if($currentDay > 5) {
	$currentDay = 1;
}

$highlightElement = $currentDay + 2;
// <700px: Show three days of week only
$tableSelectorList = array();
for ($i = 1; $i <= 5; $i++) {
	if ($currentDay != $i && $currentDay != $i-1 && $currentDay != $i+1) {
		$hideElement = $i + 2;
		$tableSelectorList[] = "table.db-table > tbody > tr.regular > td:nth-child($hideElement)";
		$tableSelectorList[] = "table > thead > tr > th:nth-child($hideElement)";
	}
}
// <500px: Show current day of week only
$tableSelectorListTiny = array();
for ($i = 1; $i <= 5; $i++) {
	if ($currentDay == $i-1 || $currentDay == $i+1) {
		$hideElement = $i + 2;
		$tableSelectorListTiny[] = "table.db-table > tbody > tr.regular > td:nth-child($hideElement)";
		$tableSelectorListTiny[] = "table > thead > tr > th:nth-child($hideElement)";
	}
}

header('Content-Type: text/html; charset=utf-8');

?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Stundenplan - <?php echo $schuljahrDescription; ?> - <?php echo $kidId==1?"Das eine Kind":"Das andere Kind"; ?></title>
    <link rel="stylesheet" href="default.css">
    <link rel="icon" href="layout/image/favicon.ico">
	<style type="text/css" media="screen">
		table.db-table > tbody > tr.regular > td:nth-child(<?php echo $highlightElement; ?>) {
			background-color: aliceblue;
		}
		/* hide other days */
		@media only screen and (max-width: 700px) {
			<?php echo implode(", ", $tableSelectorList); ?>
			{
				display: none;
			}
		}
		/* hide other days */
		@media only screen and (max-width: 500px) {
			<?php echo implode(", ", $tableSelectorListTiny); ?>
			{
				display: none;
			}
		}
    </style>
</head>
<body>
<?php

echo "<h3>{$schuljahrDescription}</h3>";

echo '<table cellpadding="0" cellspacing="0" class="db-table">';
echo "\n<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Montag</th><th>Dienstag</th><th>Mittwoch<th>Donnerstag</th><th>Freitag</th></tr></thead>\n<tbody>\n";

$rowCount=0;

while($row = mysqli_fetch_assoc($res)) {
    $stunde     = $row['stunde'];
    $montag     = $row['Montag'];
    $dienstag   = $row['Dienstag'];
    $mittwoch   = $row['Mittwoch'];
    $donnerstag = $row['Donnerstag'];
    $freitag    = $row['Freitag'];

    if(strlen($montag) > 0 && is_null($dienstag) && is_null($mittwoch) && is_null($donnerstag) && is_null($freitag) ) {
        // Nur Montag is definiert, dann wird es eine durchgehende Zeile (z.B. Pause)
        echo "<tr class='intermediate'><td>&nbsp;</td><td>{$stunde}</td><td colspan='5'>{$montag}</td></tr>\n";
    } else {
        $rowCount++;
        echo "<tr class='regular'><td>{$rowCount}</td><td>{$stunde}</td><td>{$montag}</td><td>{$dienstag}</td><td>{$mittwoch}</td><td>{$donnerstag}</td><td>{$freitag}</td></tr>\n";
    }
}
echo "</tbody>\n</table>\n<br />";

// Footnotes
$res = mysqli_query($mysqli, "SELECT * FROM fussnoten WHERE schuljahr_id={$schuljahr}");
if (!$res) {
    die("Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error);
}
while($row = mysqli_fetch_assoc($res)) {
    $note = $row['note'];
    echo "<p>{$note}</p>";
}
?>

<p>
<a href="?kind=1">Das eine Kind</a>
|
<a href="?kind=2">Das andere Kind</a>
</p<
</body>

</html>

