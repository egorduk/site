<?php

namespace Acme\AuthBundle\Entity;

class RegFormValidate
{
    public $fieldSurname;
    public $fieldName;
    public $fieldPatronymic;
    public $fieldPass;
    public $fieldPassApprove;
    public $fieldOptions;

    /**
     * @param mixed $fieldOptions
     */
    public function setFieldOptions($fieldOptions)
    {
        $this->fieldOptions = $fieldOptions;
    }

    /**
     * @return mixed
     */
    public function getFieldOptions()
    {
        return $this->fieldOptions;
    }

    /**
     * @param mixed $fieldPassApprove
     */
    public function setFieldPassApprove($fieldPassApprove)
    {
        $this->fieldPassApprove = $fieldPassApprove;
    }

    /**
     * @return mixed
     */
    public function getFieldPassApprove()
    {
        return $this->fieldPassApprove;
    }
    public $fieldEmail;
    public $fieldInfo;
    public $fieldWork;
    public $fieldDescribe;
    public $fieldInstitute;
    public $fieldChair;
    public $fieldSpeciality;
    public $fieldGroup;

    /**
     * @param mixed $fieldChair
     */
    public function setFieldChair($fieldChair)
    {
        $this->fieldChair = $fieldChair;
    }

    /**
     * @return mixed
     */
    public function getFieldChair()
    {
        return $this->fieldChair;
    }

    /**
     * @param mixed $fieldDescribe
     */
    public function setFieldDescribe($fieldDescribe)
    {
        $this->fieldDescribe = $fieldDescribe;
    }

    /**
     * @return mixed
     */
    public function getFieldDescribe()
    {
        return $this->fieldDescribe;
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
     * @param mixed $fieldGroup
     */
    public function setFieldGroup($fieldGroup)
    {
        $this->fieldGroup = $fieldGroup;
    }

    /**
     * @return mixed
     */
    public function getFieldGroup()
    {
        return $this->fieldGroup;
    }

    /**
     * @param mixed $fieldInfo
     */
    public function setFieldInfo($fieldInfo)
    {
        $this->fieldInfo = $fieldInfo;
    }

    /**
     * @return mixed
     */
    public function getFieldInfo()
    {
        return $this->fieldInfo;
    }

    /**
     * @param mixed $fieldInstitute
     */
    public function setFieldInstitute($fieldInstitute)
    {
        $this->fieldInstitute = $fieldInstitute;
    }

    /**
     * @return mixed
     */
    public function getFieldInstitute()
    {
        return $this->fieldInstitute;
    }

    /**
     * @param mixed $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param mixed $fieldPassword
     */
    public function setFieldPass($fieldPassword)
    {
        $this->fieldPass = $fieldPassword;
    }

    /**
     * @return mixed
     */
    public function getFieldPass()
    {
        return $this->fieldPass;
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
     * @param mixed $fieldSpeciality
     */
    public function setFieldSpeciality($fieldSpeciality)
    {
        $this->fieldSpeciality = $fieldSpeciality;
    }

    /**
     * @return mixed
     */
    public function getFieldSpeciality()
    {
        return $this->fieldSpeciality;
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

    /**
     * @param mixed $fieldWork
     */
    public function setFieldWork($fieldWork)
    {
        $this->fieldWork = $fieldWork;
    }

    /**
     * @return mixed
     */
    public function getFieldWork()
    {
        return $this->fieldWork;
    }

    public function __construct()
    {
    }
}