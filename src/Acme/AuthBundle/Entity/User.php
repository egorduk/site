<?php

namespace Acme\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NoResultException;
use Helper\Helper;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
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
    protected $surname;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $patronymic;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $info;

    /**
     * @ORM\Column(type="string")
     */
    protected $work;

    /**
     * @ORM\Column(type="string")
     */
    protected $dscribe;

    /**
     * @ORM\Column(type="string")
     */
    protected $institute;

    /**
     * @ORM\Column(type="string")
     */
    protected $chair;

    /**
     * @ORM\Column(type="string")
     */
    protected $speciality;

    /**
     * @ORM\Column(type="string")
     */
    protected $gp;

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @ORM\Column(type="string")
     */
    protected $photo;

    /**
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }



    /**
     * @ORM\Column(type="integer")
     */
    protected $course;

    /**
     * @ORM\Column(type="integer")
     */
    protected $is_show_email;

    /**
     * @ORM\Column(type="string")
     */
    protected $about;

    /**
     * @ORM\Column(type="date")
     */
    protected $date_birthday;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\Message", mappedBy="writerId")
     **/
    protected $link_message_writer;

    /**
     * @ORM\OneToMany(targetEntity="Acme\SecureBundle\Entity\Message", mappedBy="responseId")
     **/
    protected $link_message_response;


    /**
     * @param mixed $is_show_email
     */
    public function setIsShowEmail($is_show_email)
    {
        $this->is_show_email = $is_show_email;
    }

    /**
     * @return mixed
     */
    public function getIsShowEmail()
    {
        return $this->is_show_email;
    }

    /**
     * @param mixed $date_birthday
     */
    public function setDateBirthday($date_birthday)
    {
        $this->date_birthday = $date_birthday;
    }

    /**
     * @return mixed
     */
    public function getDateBirthday()
    {
        return $this->date_birthday;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $chair
     */
    public function setChair($chair)
    {
        $this->chair = $chair;
    }

    /**
     * @return mixed
     */
    public function getChair()
    {
        return $this->chair;
    }

    public function setDescribe($describe)
    {
        $this->dscribe = $describe;
    }

    /**
     * @return mixed
     */
    public function getDescribe()
    {
        return $this->dscribe;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->gp = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->gp;
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
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $institute
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;
    }

    /**
     * @return mixed
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $patronymic
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return mixed
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param mixed $speciality
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;
    }

    /**
     * @return mixed
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    public function getUserRole() {
        return $this->role;
    }

    public function setUserRole($role) {
        $this->role = $role;
    }

    /**
     * @param mixed $work
     */
    public function setWork($work)
    {
        $this->work = $work;
    }

    /**
     * @return mixed
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @ORM\ManyToOne(targetEntity="UserRole", inversedBy="link_role", cascade={"refresh"})
     * @ORM\JoinColumn(name="user_role_id", referencedColumnName="id")
     **/
    protected $role;


    public function __construct() {
        $this->link_message_response = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_message_writer = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getUsername()
    {
    }


    public function getRoles() {
        return array('');
    }

    public function eraseCredentials() {
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function getSalt() {
        return true;
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
            $this->id
            ) = unserialize($serialized);
    }

    public function setUnEncodePass($pass) {
        $this->unEncodePass = $pass;
    }

    public function getUnEncodePass() {
        return $this->unEncodePass;
    }
}