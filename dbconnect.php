<?php
error_reporting(~E_DEPRECATED & ~E_NOTICE);
//error_reporting(E_ALL);
ini_set('display_errors', TRUE);
// but I strongly suggest you to use PDO or MySQLi.
/* Live setup */
//  define('DBHOST', 'mysql45.unoeuro.com');
//  define('DBUSER', 'contrasoft_dk');
//  define('DBPASS', 'ContraSoft786');
//  define('DBNAME', 'contrasoft_dk_db4');
//  define('HOME_URL','http://contrasoft.dk/firsttransport');
/* local setup */
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'first_transport');
define('HOME_URL', 'http://localhost/FirstTransport');
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS);
$dbcon = mysqli_select_db($conn, DBNAME);

if (!$conn) {
    die("Connection failed : " . mysqli_error($conn));
}

if (!$dbcon) {
    die("Database Connection failed : " . mysqli_error($conn));
}

function qry_insert($table, $data, $ignore = false)
{
    $qry = array();
    if (is_array($qry) === true) {
        $qry['query'] = 'INSERT ';
        if ($ignore === true) {
            $qry['query'] .= 'IGNORE ';
        }
        foreach ($data as $key => $value) {
            $value          = "'" . $value . "'";
            $data['key'][]     = $key;
            $data['value'][]   = $value;
        }
        $qry['query'] .= 'INTO ' . $table . ' (' . implode(', ', $data['key']) . ')  VALUES (' . implode(', ', $data['value']) . ')';
    }
    return implode('', $qry);
}

function qry_update($table, $data, $condition = '')
{
    $qry = array();
    if (is_array($qry) === true) {
        $qry['query'] = 'UPDATE ';
        foreach ($data as $key => $value) {
            $value          = "'" . $value . "'";
            $data[$key] = $key . ' = ' . $value;
        }
        $qry['query'] .= $table . ' SET ' . implode(', ', $data);
        $qry['query'] .= ' ' . $condition;
    }
    return implode('', $qry);
}

function custom_redirect($location)
{
    $locations = explode(':', $location);
    $locations = end($locations);
    $locations = trim($locations);
    ?>
    <script>
        window.location.href = "http:<?php echo $locations; ?>";
    </script>
    <?php
    die();
}