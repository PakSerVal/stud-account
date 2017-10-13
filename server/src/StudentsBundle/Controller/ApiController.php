<?php

namespace StudentsBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use PhpOffice\PhpWord\PhpWord;
use StudentsBundle\Entity\Student;
use StudentsBundle\Service\Cube;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function getStudentsByFilterAction($page, $sortOption, Cube $cube)
    {
        $filter  = json_decode(file_get_contents('php://input'), true);
        $em = $this->getDoctrine()->getManager();
        $limit = 100;
        if (!empty($filter)) {
            $factKey = "__fact_key__";
            $studentFacts = $cube->getFacts($filter);
            $studentFactIds = [];
            foreach ($studentFacts as $studentFact) {
                $studentFactIds[] = $studentFact->$factKey;
            }
            $query = $em->getRepository(Student::class)->createQueryBuilder('x')
                ->select()
                ->andWhere('x.factId IN (:studentFactIds)')
                ->setFirstResult($limit * ($page - 1))
                ->setMaxResults($limit)
                ->orderBy("x.$sortOption", 'ASC')
                ->setParameter('studentFactIds', $studentFactIds)
                ->getQuery();

        } else {
            $query = $em->getRepository(Student::class)->createQueryBuilder('x')
                ->select()
                ->setFirstResult($limit * ($page - 1))
                ->setMaxResults($limit)
                ->orderBy("x.$sortOption", 'ASC')
                ->getQuery();
        }
        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $response = new Response();
        $students = [];
        foreach ($paginator as $studentObject) {
            $students[] = [
                "id"        => $studentObject->getId(),
                "fio"       => $studentObject->getFio(),
                "email"     => $studentObject->getEmail(),
                "phone"     => $studentObject->getPhone(),
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

    public function getStudentsTotalCountAction(Cube $cube)
    {
        $filter  = json_decode(file_get_contents('php://input'), true);
        $studentAggregates = $cube->getAggregates($filter);
        $totalNameProp = "Sum";
        $studentsAgSummary = $studentAggregates->summary;
        $studentsTotalCount = $studentsAgSummary->$totalNameProp;
        $response = new Response(json_encode([compact("studentsTotalCount")]));
        return $response;
    }

    public function exportDocAction($page, $sortOption, Cube $cube) {
        $filter  = $_POST;
        $factKey = "__fact_key__";
        $studentFacts = $cube->getFacts($filter);
        $studentFactIds = [];
        foreach ($studentFacts as $studentFact) {
            $studentFactIds[] = $studentFact->$factKey;
        }
        $limit = 100;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Student::class)->createQueryBuilder('x')
            ->select()
            ->andWhere('x.factId IN (:studentFactIds)')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->orderBy("x.$sortOption", 'ASC')
            ->setParameter('studentFactIds', $studentFactIds)
            ->getQuery();

        $students = new Paginator($query, $fetchJoinCollection = false);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = array('size' => 16, 'bold' => true);
        $section->addTextBreak(1);
        $section->addText('Список студентов', $header);
        $fancyTableStyleName = 'Fancy Table';
        $fancyTableStyle = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 10, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
        $fancyTableFirstRowStyle = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
        $fancyTableCellStyle = array('valign' => 'center');
        $fancyTableFontStyle = array('bold' => false, 'size' => 10);
        $phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
        $table = $section->addTable($fancyTableStyleName);

        $headers = $filter["column"];
        $studentFields = [];
        $studentFieldsMap = [
            "ФИО"            => "fio",
            "Email"          => "email",
            "Телефон"        => "phone",
            "Статус"         => "status",
            "Курс"           => "course",
            "Приказ"         => "orderNum",
            "Форма обучения" => "studyType",
            "Баллы ЕГЭ"      => "score"
        ];
        $studentCellWidthMap = [
            "ФИО"            => 1500,
            "Email"          => 1500,
            "Телефон"        => 1400,
            "Статус"         => 1400,
            "Курс"           => 500,
            "Приказ"         => 1300,
            "Форма обучения" => 1300,
            "Баллы ЕГЭ"      => 700
        ];
        $table->addRow(300);
        foreach ($headers as $header) {
            $table->addCell($studentCellWidthMap[$header], $fancyTableCellStyle)->addText($header, $fancyTableFontStyle);
        }
//        $table->addCell(1500, $fancyTableCellStyle)->addText('ФИО', $fancyTableFontStyle);
//        $table->addCell(1500, $fancyTableCellStyle)->addText('Email', $fancyTableFontStyle);
//        $table->addCell(1400, $fancyTableCellStyle)->addText('Телефон', $fancyTableFontStyle);
//        $table->addCell(1400, $fancyTableCellStyle)->addText('Статус', $fancyTableFontStyle);
//        $table->addCell(500, $fancyTableCellStyle)->addText('Курс', $fancyTableFontStyle);
//        $table->addCell(1300, $fancyTableCellStyle)->addText('Приказ', $fancyTableFontStyle);
//        $table->addCell(1300, $fancyTableCellStyle)->addText('Форма обучения', $fancyTableFontStyle);
//        $table->addCell(700, $fancyTableCellStyle)->addText('Баллы ЕГЭ', $fancyTableFontStyle);
        foreach ($students as $student) {
            $table->addRow(300);
            foreach ($headers as $header) {
                $table->addCell($studentCellWidthMap[$header], $fancyTableCellStyle)->addText(
                    call_user_func([$student, "get" . ucfirst($studentFieldsMap[$header])]),
                    $fancyTableFontStyle
                );
            }
//            $table->addCell(1500, $fancyTableCellStyle)->addText($student->getFio(), $fancyTableFontStyle);
//            $table->addCell(1500, $fancyTableCellStyle)->addText($student->getEmail(), $fancyTableFontStyle);
//            $table->addCell(1400, $fancyTableCellStyle)->addText($student->getPhone(), $fancyTableFontStyle);
//            $table->addCell(1400, $fancyTableCellStyle)->addText($student->getStatus(), $fancyTableFontStyle);
//            $table->addCell(500,  $fancyTableCellStyle)->addText($student->getCourse(), $fancyTableFontStyle);
//            $table->addCell(1300, $fancyTableCellStyle)->addText($student->getOrderNum(), $fancyTableFontStyle);
//            $table->addCell(1300, $fancyTableCellStyle)->addText($student->getStudyType(), $fancyTableFontStyle);
//            $table->addCell(700,  $fancyTableCellStyle)->addText($student->getScore(), $fancyTableFontStyle);
        }
        // Saving the document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename="students.docx";
        $objWriter->save($filename, 'Word2007', true);
        $path = $this->get('kernel')->getRootDir(). "/../web/" . $filename;
        $content = file_get_contents($path);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
        $response->setContent($content);
        return $response;
    }

    public function exportXlsAction($page, $sortOption, Cube $cube)
    {
        $filter  = $_POST;
        $factKey = "__fact_key__";
        $studentFacts = $cube->getFacts($filter);
        $studentFactIds = [];
        foreach ($studentFacts as $studentFact) {
            $studentFactIds[] = $studentFact->$factKey;
        }
        $limit = 100;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Student::class)->createQueryBuilder('x')
            ->select()
            ->andWhere('x.factId IN (:studentFactIds)')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->orderBy("x.$sortOption", 'ASC')
            ->setParameter('studentFactIds', $studentFactIds)
            ->getQuery();

        $students = new Paginator($query, $fetchJoinCollection = false);

        //excel
        $xls = new \PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(12);

        $sheet->setTitle('Список студентов');
        $sheet->setCellValue("A1", 'Список студентов');
        $sheet->getStyle('A1')->getFill()->setFillType(
            \PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->mergeCells('A1:H1');

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(
            \PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $headers = $filter["column"];
        $studentFieldsMap = [
            "ФИО"            => "fio",
            "Email"          => "email",
            "Телефон"        => "phone",
            "Статус"         => "status",
            "Курс"           => "course",
            "Приказ"         => "orderNum",
            "Форма обучения" => "studyType",
            "Баллы ЕГЭ"      => "score"
        ];
        foreach ($headers as $header) {
            $studentFields[] = $studentFieldsMap[$header];
        }

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValueByColumnAndRow(
                $i,
                2,
                $headers[$i]
            );
        }
        $row = 3;
        foreach ($students as $student) {
            for($i = 0; $i < count($studentFields); $i++) {
                $sheet->setCellValueByColumnAndRow(
                    $i,
                    $row,
                    call_user_func([$student, "get" . ucfirst($studentFields[$i])])
                );
            }
            $row++;
        }
        // Выводим содержимое файла
        $objWriter =  $objWriter = \PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
        $filename = "students.xlsx";
        $objWriter->save($filename);
        $path = $this->get('kernel')->getRootDir(). "/../web/" . $filename;
        $content = file_get_contents($path);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
        $response->setContent($content);
        return $response;
    }

    public function getMinAndMaxDateAction (Cube $cube)
    {
        return new Response(json_encode([$cube->getMinAndMaxDate()]));
    }
}