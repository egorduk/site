<?php

namespace Acme\SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Helper\Helper;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_ps")
 */
class UserPs extends EntityRepository
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $num;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_edit;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\AuthBundle\Entity\User", inversedBy="link_user_ps", cascade={"merge"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="TypePs", inversedBy="link_type_ps", cascade={"merge"})
     * @ORM\JoinColumn(name="type_ps_id", referencedColumnName="id")
     **/
    private $type_ps;


    public function __construct(){
        $this->date_create = new \DateTime();
        $this->date_edit = Helper::getFormatDateForInsert("0000-00-00 00:00:00", "Y-m-d H:i:s");
    }

    public function setName($val) {
        $this->name = $val;
    }

    public function getName() {
        return $this->name;
    }

    public function setNum($val) {
        $this->num = $val;
    }

    public function getNum() {
        return $this->num;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser($val) {
        $this->user = $val;
    }

    public function getUser() {
        return $this->user;
    }

    public function setTypePs($val) {
        $this->type_ps = $val;
    }

    public function getTypePs() {
        return $this->type_ps;
    }

    public function getDateEdit() {
        return $this->date_edit;
    }

    public function setDateEdit($val) {
        $this->date_edit = $val;
    }
}