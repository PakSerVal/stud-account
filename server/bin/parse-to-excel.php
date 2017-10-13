#!/usr/bin/env php
<?php
$headers = [
    "ФИО", "Приказ", "E-mail", "Телефон", "Форма обучения", "Баллы ЕГЭ", "Статус", "Курс"
];

$f = fopen("students.csv", "w");
fputcsv($f, $headers);
$fioHandle = fopen("fio.txt", "r");
$phoneHandle = fopen("phone.txt", "r");
$emailHandle = fopen("email.txt", "r");


for ($i = 0; $i<20000; $i++) {
//    дата, курс, статус, баллы, форма обучения
    $randMonthArr = [1,2,3,4,5,6,7,8,9,10,11,12];
    $randMonthKey = array_rand($randMonthArr, 1);
    $randMonth = $randMonthArr[$randMonthKey];
    $randDay = rand(1, 30);
    $arrYear = [2016, 2017];
    $randYearKey = array_rand($arrYear, 1);
    $randYear = $arrYear[$randYearKey];
    $arrOrder = [2500,2501,2538,2701];
    $randOrderKey = array_rand($arrOrder, 1);
    $date = "от $randDay.$randMonth.$randYear № $arrOrder[$randOrderKey]-к";
    $arrType = ["бюджет", "внебюджет"];
    $randTypeKey = array_rand($arrType, 1);
    $randEge = rand(150,250);
    $randCourse = rand(2,4);
    $arrStatus = ["Зачислен", "Отчислен", "Выпустился", "Переведён"];
    $randStatusKey = array_rand($arrStatus, 1);
    if($randStatusKey == 0){
        $randCourse = 1;
    } else if ($randStatusKey == 2) {
        $randCourse = 5;
    }

    $fio = fgets($fioHandle);
    $email = fgets($emailHandle);
    $phone = fgets($phoneHandle);


    $row = [
        trim($fio),
        $date,
        trim($email),
        trim($phone),
        $arrType[$randTypeKey],
        $randEge,
        $arrStatus[$randStatusKey],
        $randCourse
    ];

    fputcsv($f, $row);
}
fclose($f);
fclose($fioHandle);
fclose($emailHandle);
fclose($phoneHandle);

