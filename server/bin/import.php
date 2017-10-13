<?php
class MyDB extends SQLite3 {
    function __construct()
    {
        $this->open('students');
    }
}

function createTables() {
    $db = new MyDB();
    $sql = "
            drop table if exists `stud-events`; drop table if exists `courses`; drop table if exists `dates`; drop table if exists `statuses`;drop table if exists `student`;
            
            CREATE TABLE `stud-events` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `dateId` INTEGER, `statusId` INTEGER, `courseId` INTEGER, `studentsQuantity` INTEGER );
            CREATE TABLE `courses` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `courseName` TEXT );
            CREATE TABLE `dates` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `year` INTEGER, `quarter` INTEGER, `month` INTEGER, `day` INTEGER );
            CREATE TABLE `statuses` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `statusName` TEXT );
            CREATE TABLE `student` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT, `fio` TEXT, `email` TEXT, `phone` TEXT, `status` TEXT, `course` TEXT, `order_num` TEXT, `study_type` TEXT, `score` INTEGER, `fact_id` INTEGER );
    ";
    @$db->exec($sql);
    $db->close();
}

function insertFact($dateId, $statusId, $courseId) {
    $db = new MyDB();
    $sqlSelect = "SELECT `id`, `studentsQuantity` FROM `stud-events` WHERE `dateId` = $dateId AND `statusId` = $statusId AND `courseId` = $courseId";
    $select = $db->query($sqlSelect)->fetchArray();
    if ($select) {
        $studentsQuantity = $select[1] + 1;
        $sqlUpdate = "UPDATE `stud-events` SET `studentsQuantity` = '$studentsQuantity' WHERE `id` = '$select[0]'";
        $db->exec($sqlUpdate);
        $lastId = $select[0];
    } else {
        $sql = "INSERT OR IGNORE INTO `stud-events` (`dateId`, `statusId`, `courseId`, `studentsQuantity`) VALUES ($dateId, $statusId, $courseId, 1)";
        $db->exec($sql);
        $lastId = $db->query("SELECT last_insert_rowid()")->fetchArray()[0];
    }
    return $lastId;
}

function getOrCreate($table, $row) {
    $db = new MyDB();
    $wherePar = [];
    foreach ($row as $key => $value) {
        $wherePar[] = "`$key` = '$value'";
    }
    $wherePar = implode(" AND ", $wherePar);
    $sqlSelect = "SELECT id FROM $table WHERE $wherePar";
    $select = $db->query($sqlSelect)->fetchArray()[0];

    if($select) {
        $lastId = $select;
    }
    else {
        $insertKeys   = [];
        $insertValues = [];
        foreach ($row as $key => $value) {
            $insertKeys[] = "`$key`";
            $insertValues[] = "'$value'";
        }
        $insertKeys = implode(",", $insertKeys);
        $insertValues = implode(",", $insertValues);

        $sql = "INSERT OR IGNORE INTO $table($insertKeys) VALUES ($insertValues)";
        $db->exec($sql);
        $lastId = $db->query("SELECT last_insert_rowid()")->fetchArray()[0];
    }
    $db->close();
    return $lastId;
}

function insertStudents(){
    $db = new SQLite3("students");
    if (($handle = fopen("students.csv", "r")) !== FALSE) {
        echo "Импорт студентов...";
        fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $order_num   = $data[1];
            $date        = explode(".", explode(" ", $order_num)[1]);
            $dateDay     = $date[0];
            $dateMonth   = $date[1];
            $dateYear    = $date[2];
            $dateQuarter = intval(($dateMonth + 2) / 3);
            $dateId      = getOrCreate("dates", ["year" => $dateYear, "quarter" => $dateQuarter, "month" => $dateMonth, "day" => $dateDay]);
            $status      = $data[6];
            $statusId    = getOrCreate("statuses", ["statusName" => $status]);
            $course      = $data[7];
            $courseId    = getOrCreate("courses", ["courseName" => $course]);
            $fio         = $data[0];
            $email       = $data[2];
            $phone       = $data[3];
            $study_type  = $data[4];
            $score       = $data[5];
            $fact_id     = insertFact($dateId, $statusId, $courseId);
            getOrCreate("student", compact("fio","order_num", "email", "phone","status", "course", "study_type", "score", "fact_id"));
        }
    }
}
createTables();
insertStudents();
