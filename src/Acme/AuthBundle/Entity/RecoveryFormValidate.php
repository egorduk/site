<?php

namespace Acme\AuthBundle\Entity;

class RecoveryFormValidate
{
    public $fieldSurname;
    public $fieldName;
    public $fieldPatronymic;
    public $fieldEmail;

    public function __construct()
    {
    }

    /**
     * @param mixed $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @param mixed $fieldEmail
     */
    public function setFieldEmail($fieldEmail)
    {
        $this->fieldEmail = $fieldEmail;
    }

    /**
     * @return mixed
     */
    public function getFieldEmail()
    {
        return $this->fieldEmail;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param mixed $fieldPatronymic
     */
    public function setFieldPatronymic($fieldPatronymic)
    {
        $this->fieldPatronymic = $fieldPatronymic;
    }

    /**
     * @return mixed
     */
    public function getFieldPatronymic()
    {
        return $this->fieldPatronymic;
    }

    /**
     * @param mixed $fieldSurname
     */
    public function setFieldSurname($fieldSurname)
    {
        $this->fieldSurname = $fieldSurname;
    }

    /**
     * @return mixed
     */
    public function getFieldSurname()
    {
        return $this->fieldSurname;
    }
}