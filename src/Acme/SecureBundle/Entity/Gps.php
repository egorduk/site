<?php

namespace Acme\SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Gps")
 */
class Gps extends EntityRepository
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="link_gps", cascade={"refresh"})
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     **/
    private $schedule;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\AuthBundle\Entity\Gp", inversedBy="link_gps", cascade={"refresh"})
     * @ORM\JoinColumn(name="gp_id", referencedColumnName="id")
     **/
    private $gp;


    public function __construct()
    {
        $this->link_group = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $gp
     */
    public function setGp($gp)
    {
        $this->gp = $gp;
    }

    /**
     * @return mixed
     */
    public function getGp()
    {
        return $this->gp;
    }

    /**
     * @param mixed $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}