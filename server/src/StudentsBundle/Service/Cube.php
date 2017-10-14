<?php

namespace StudentsBundle\Service;


class Cube
{
    const HOST = "localhost";
    const PORT = "5000";
    const CUBE = "stud-events";
    //http://localhost:5000/cube/stud-events/facts?cut=status@default:1;2|date@date:2017,4,10|course@default:1;2
    //        $res = json_decode(file_get_contents("http://localhost:5000/cube/events/members/$dimensionName"));

    public function getFacts($filter)
    {
        $url = "http://" . $this::HOST . ":" . $this::PORT . "/cube/" . $this::CUBE . "/facts?cut=";
        $filterCuts = [];
        if (!empty($filter["date"])) {
            $filterCuts[] = $this->getDateCut($filter["date"]);
        }
        if (!empty($filter["status"])) {
            $filterCuts[] = $this->getStatusCut($filter["status"]);
        }
        if (!empty($filter["course"])) {
            $filterCuts[] = $this->getCourseCut($filter["course"]);
        }
        $url .= implode("|", $filterCuts);
        $res = json_decode(file_get_contents($url));
        return $res;
    }

    public function getAggregates($filter)
    {
        $url = "http://" . $this::HOST . ":" . $this::PORT . "/cube/" . $this::CUBE . "/aggregate?cut=";
        $filterCuts = [];
        if (!empty($filter["date"])) {
            $filterCuts[] = $this->getDateCut($filter["date"]);
        }
        if (!empty($filter["status"])) {
            $filterCuts[] = $this->getStatusCut($filter["status"]);
        }
        if (!empty($filter["course"])) {
            $filterCuts[] = $this->getCourseCut($filter["course"]);
        }
        $url .= implode("|", $filterCuts);
        $res = json_decode(file_get_contents($url));
        return $res;
    }

    private function getDateCut($date)
    {
        $dateCut = "date@date:";
        $period = $date["period"];
        if ($period == "year") {
            $dateCut .= $date["year"];
        } else if ($period == "quarter") {
            $dateCut .= $date["year"] . "," . $date["quarter"];
        } else if ($period == "month") {
            $dateCut .= $date["year"] . "," . $date["quarter"] . "," . $date["month"];
        } else if ($period == "day") {
            $year  = $date["year"];
            $month = $date["month"];
            $day   = $date["day"];
            $quarter = intval(($month + 2) / 3);
            $dateCut .= "$year,$quarter,$month,$day";
        }
        return $dateCut;
    }

    private function getStatusCut($status)
    {
        $statusesMap = [
            "admis"  => "Зачислен",
            "depart" => "Отчислен",
            "trans"  => "Переведён",
            "grad"   => "Выпустился"
        ];
        $statusCut = "status@default:";
        $statusIds = [];
        foreach ($status as $statusName) {
            $statusIds[] = $this->getDimensionIdByName("status", $statusesMap[$statusName]);
        }
        $statusCut .= implode(";", $statusIds);
        return $statusCut;
    }

    private function getCourseCut($course)
    {
        $coursesMap = [
            "first"  => "1",
            "second" => "2",
            "third"  => "3",
            "fourth" => "4",
            "fifth"  => "5"
        ];
        $courseCut = "course@default:";
        $courseIds = [];
        foreach ($course as $courseName) {
            $courseIds[] = $this->getDimensionIdByName("course", $coursesMap[$courseName]);
        }
        $courseCut .= implode(";", $courseIds);
        return $courseCut;
    }

    private function getDimensionIdByName($dimension, $name)
    {
        $propId = "$dimension.id";
        $propName = "$dimension.name";
        $url = "http://" . $this::HOST . ":" . $this::PORT . "/cube/" . $this::CUBE . "/members/" . $dimension;
        $dimensionData = json_decode(file_get_contents($url))->data;
        foreach ($dimensionData as $dimensionDatum) {
            $dimName = $dimensionDatum->$propName;
            if ($dimName == $name) {
                return $dimensionDatum->$propId;
            }
        }
    }

    public function getMinAndMaxDate()
    {
        $url = "http://" . $this::HOST . ":" . $this::PORT . "/cube/" . $this::CUBE . "/members/date";
        $dateData = json_decode(file_get_contents($url))->data;
        $dates = [];
        foreach ($dateData as $data) {
            $propMonth   = "date.month";
            $propYear    = "date.year";
            $propDay     = "date.day";
            $dates[] = date(
                'Y-m-d',
                mktime(0,0,0, $data->$propMonth, $data->$propDay, $data->$propYear)
            );
        }
        $max = max($dates);
        $min = min($dates);
        $result = [
            "max" => [
                "year"    => date('Y', strtotime($max)),
                "quarter" => intval((date('n', strtotime($max)) + 2) / 3),
                "month"   => date('n', strtotime($max)),
                "day"     => date('j', strtotime($max))
            ],
            "min" => [
                "year"    => date('Y', strtotime($min)),
                "quarter" => intval((date('n', strtotime($min)) + 2) / 3),
                "month"   => date('n', strtotime($min)),
                "day"     => date('j', strtotime($min))
            ]
        ];
        return $result;
    }

}