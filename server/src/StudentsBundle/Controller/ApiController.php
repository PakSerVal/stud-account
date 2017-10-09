<?php

namespace StudentsBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use StudentsBundle\Entity\Student;
use StudentsBundle\Service\Cube;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function getAllStudentsAction($page)
    {
        $limit = 100;
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(Student::class)->createQueryBuilder('x')
            ->select()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->orderBy('x.fio', 'ASC')
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        $response = new Response();
        $students = [];
        foreach ($paginator as $studentObject) {
            $students[] = [
                "id"        => $studentObject->getId(),
                "fio"       => $studentObject->getFio(),
                "email"     => $studentObject->getEmail(),
                "phone"     => $studentObject->getPhone(),
                "address"   => $studentObject->getAddress(),
                "status"    => $studentObject->getStatus(),
                "course"    => $studentObject->getCourse(),
                "orderNum"  => $studentObject->getOrderNum(),
                "studyType" => $studentObject->getStudyType(),
                "score"     => $studentObject->getScore(),
                "factId"    => $studentObject->getFactId()
            ];
        }
        $response->setContent(json_encode($students));
        return $response;
    }

    public function getSortedStudentsAction($page, $sortOption)
    {
        $limit = 100;
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(Student::class)->createQueryBuilder('x')
            ->select()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->orderBy("x.$sortOption", 'ASC')
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        $response = new Response();
        $students = [];
        foreach ($paginator as $studentObject) {
            $students[] = [
                "id"        => $studentObject->getId(),
                "fio"       => $studentObject->getFio(),
                "email"     => $studentObject->getEmail(),
                "phone"     => $studentObject->getPhone(),
                "address"   => $studentObject->getAddress(),
                "status"    => $studentObject->getStatus(),
                "course"    => $studentObject->getCourse(),
                "orderNum"  => $studentObject->getOrderNum(),
                "studyType" => $studentObject->getStudyType(),
                "score"     => $studentObject->getScore(),
                "factId"    => $studentObject->getFactId()
            ];
        }
        $response->setContent(json_encode($students));
        return $response;
    }

    public function getStudentsByFilterAction(Cube $cube)
    {
        $filter = json_decode(file_get_contents('php://input'), true);
        
    }
}