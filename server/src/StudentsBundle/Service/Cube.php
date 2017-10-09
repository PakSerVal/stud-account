<?php

namespace StudentsBundle\Service;


class Cube
{
    public function getStudents() {
        $curl = curl_init("http://localhost:5000/cube/stud-events/facts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}