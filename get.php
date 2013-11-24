<?php

$dblocation = "127.0.0.1";
$dbname = "fias";
$dbuser = "root";
$dbpasswd = "password";

$dbconn = @mysql_connect($dblocation, $dbuser, $dbpasswd);
if (!$dbconn)
{
echo "Can not connect to mysql!";
exit();
}
if (!@mysql_select_db($dbname,$dbconn) )
{
echo "Can not connect to db!";
exit();
}

mysql_query("SET NAMES 'utf8'");

header("HTTP/1.0 200 OK");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');

$parentguid = isset($_GET['parentguid']) ? $_GET['parentguid'] : '';

if ($parentguid > '')
{
    $query = "SELECT  aoid,formalname, offname, regioncode, code,  shortname, aolevel, okato, postalcode, parentguid, aoguid
    FROM d_fias_addrobj fa
    WHERE fa.parentguid='$parentguid' AND fa.actstatus=1 AND fa.aolevel <=4
    ORDER BY fa.formalname";
}
else
{
    $query = "SELECT aoid,formalname, offname, regioncode, code,  shortname, aolevel, okato, postalcode, parentguid, aoguid
    FROM d_fias_addrobj fa
    WHERE fa.aolevel=1 AND fa.actstatus=1
    ORDER BY fa.formalname";
}
$result = mysql_query($query, $dbconn);
$data = array();
if($result)
{
    while ($row = mysql_fetch_assoc($result)) {
        $data[] = array(
            'formalname' => $row['formalname'],
            'offname' => $row['offname'],
            'code' => $row['code'],
            'regioncode' => $row['regioncode'],
            'shortname' => $row['shortname'],
            'aoid' => $row['aoid'],
            'aolevel' => $row['aolevel'],
            'okato' => $row['okato'],
            'parentguid' => $row['parentguid'],
            'postalcode' => $row['postalcode'],
            'aoguid' => $row['aoguid']
        );
    }

}

$json_data = '{"data": '. json_encode($data) .'}';

echo $json_data;


?>
