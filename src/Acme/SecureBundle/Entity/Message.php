<?php

namespace Acme\SecureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message extends EntityRepository
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
    private $date_write;

    /**
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @ORM\Column(type="string")
     */
    private $theme;

    /**
     * @ORM\Column(type="integer")
     */
    private $is_read;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\AuthBundle\Entity\User", inversedBy="link_message_writer", cascade={"refresh"})
     * @ORM\JoinColumn(name="writer_id", referencedColumnName="id")
     **/
    protected $writerId;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\AuthBundle\Entity\User", inversedBy="link_message_response", cascade={"refresh"})
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     **/
    protected $responseId;

    /**
     * @param mixed $response_id
     */
    public function setResponse($response_id)
    {
        $this->responseId = $response_id;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->responseId;
    }

    /**
     * @param mixed $writer_id
     */
    public function setWriter($writer_id)
    {
        $this->writerId = $writer_id;
    }

    /**
     * @return mixed
     */
    public function getWriter()
    {
        return $this->writerId;
    }



    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $date_write
     */
    public function setDateWrite($date_write)
    {
        $this->date_write = $date_write;
    }

    /**
     * @return mixed
     */
    public function getDateWrite()
    {
        return $this->date_write;
    }

    /**
     * @param mixed $is_read
     */
    public function setIsRead($is_read)
    {
        $this->is_read = $is_read;
    }

    /**
     * @return mixed
     */
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * @param mixed $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
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

    public function __construct(){
        $this->date_write = new \DateTime();
    }
}