<?php

namespace StudentsBundle\Entity;

/**
 * Student
 */
class Student
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $fio;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $course;

    /**
     * @var string
     */
    private $orderNum;

    /**
     * @var string
     */
    private $studyType;

    /**
     * @var string
     */
    private $score;

    /**
     * @return string
     */

    /**
     * @var int
     */
    private $factId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fio
     *
     * @param string $fio
     *
     * @return Student
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Student
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Student
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set course
     *
     * @param string $course
     *
     * @return Student
     */
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Set orderNum
     *
     * @param string $orderNum
     *
     * @return Student
     */
    public function setOrderNum($orderNum)
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    /**
     * Get orderNum
     *
     * @return string
     */
    public function getOrderNum()
    {
        return $this->orderNum;
    }

    /**
     * Set studyType
     *
     * @param string $studyType
     *
     * @return Student
     */
    public function setStudyType($studyType)
    {
        $this->studyType = $studyType;

        return $this;
    }

    /**
     * Get studyType
     *
     * @return string
     */
    public function getStudyType()
    {
        return $this->studyType;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return Student
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set factId
     *
     * @param integer $factId
     *
     * @return Student
     */
    public function setFactId($factId)
    {
        $this->factId = $factId;

        return $this;
    }

    /**
     * Get factId
     *
     * @return int
     */
    public function getFactId()
    {
        return $this->factId;
    }
}

