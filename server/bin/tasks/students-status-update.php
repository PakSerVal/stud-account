#!/usr/bin/php
<?php
// 0 23 * * * * root /home/sergey/www/stud-account/server/bin/tasks/students-status-update.php
class MyDB extends SQLite3 {
    function __construct() {
        $this->open('students');
    }
}

function statusUpdate() {
    $date = date("d.m");
    if ($date == "01.09") {
        $db = new MyDB();
        $sql = "UPDATE student SET status = 'Выпустился' WHERE course = 5;";
        @$db->exec($sql);
        $sql = "UPDATE student SET status = 'Переведён', course = course + 1 WHERE course <> 5;";
        @$db->exec($sql);
        $db->close();
    }
}

statusUpdate();