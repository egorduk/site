<?php

namespace Acme\SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Helper\Helper;

/**
 * @ORM\Entity
 * @ORM\Table(name="additional")
 */
class Additional extends EntityRepository
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(type="string")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="link_subject", cascade={"refresh"})
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     **/
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\AuthBundle\Entity\User", inversedBy="link_user", cascade={"refresh"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="TypeLesson", inversedBy="link_type_lesson", cascade={"refresh"})
     * @ORM\JoinColumn(name="type_lesson_id", referencedColumnName="id")
     **/
    private $type_lesson;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="link_room", cascade={"refresh"})
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     **/
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="GpsAdditional", mappedBy="additional")
     **/
    private $link_gps;


    public function __construct() {
        $this->link_gps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setDateCreate($date) {
        $format = 'd/m/Y';
        $this->date_create = Helper::getFormatDateForInsert($date, $format);
    }

    /**
     * @param mixed $students
     */
    public function setStudents($students)
    {
        $this->students = $students;
    }

    /**
     * @return mixed
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $link_gps
     */
    public function setGps($link_gps)
    {
        $this->link_gps = $link_gps;
    }

    /**
     * @return mixed
     */
    public function getGps()
    {
        return $this->link_gps;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $format = 'Y-m-d H:i';
        $this->start_date = Helper::getFormatDateForInsert1($start_date, $format);
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $format = 'Y-m-d H:i';
        $this->end_date = Helper::getFormatDateForInsert1($end_date, $format);
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $type_lesson
     */
    public function setTypeLesson($type_lesson)
    {
        $this->type_lesson = $type_lesson;
    }

    /**
     * @return mixed
     */
    public function getTypeLesson()
    {
        return $this->type_lesson;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }


}