<?php

namespace Acme\SecureBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gps_additional")
 */
class GpsAdditional extends EntityRepository
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Additional", inversedBy="link_gps", cascade={"refresh"})
     * @ORM\JoinColumn(name="additional_id", referencedColumnName="id")
     **/
    private $additional;

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
     * @param mixed $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }

    /**
     * @return mixed
     */
    public function getAdditional()
    {
        return $this->additional;
    }
}