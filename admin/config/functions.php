<?php 

function fwithwhere($db_stat, $tname, $cond, $colname) {
    global $data;
    if (!$data) {
        require_once __DIR__ . '/../assets/config-data.php';
    }

    $fdb = "SELECT " . $db_stat . " FROM " . $tname . " WHERE " . $cond . " LIMIT 1";
    $qdb = mysqli_query($data, $fdb);

    if ($qdb && mysqli_num_rows($qdb) > 0) {
        $fetch = mysqli_fetch_assoc($qdb);
        echo html_entity_decode($fetch[$colname] ?? '');
    }
}

function ftab($db_stat, $tname, $colname) {
    global $data;
    if (!$data) {
        require_once __DIR__ . '/../assets/config-data.php';
    }

    $fdb = "SELECT " . $db_stat . " FROM " . $tname;
    $qdb = mysqli_query($data, $fdb);

    if ($qdb && mysqli_num_rows($qdb) > 0) {
        $fetch = mysqli_fetch_assoc($qdb);
        echo html_entity_decode($fetch[$colname] ?? '');
    }
}

?>