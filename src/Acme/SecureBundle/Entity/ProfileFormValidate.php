<?php

namespace Acme\SecureBundle\Entity;

class ProfileFormValidate
{
    public $fieldSurname;
    public $fieldName;
    public $fieldPatronymic;
    public $fieldPassOld;
    public $fieldPassNew;
    public $fieldPassApprove;
    public $fieldOptions;
    public $fieldAbout;
    public $fieldEmail;
    public $fieldInfo;
    public $fieldWork;
    public $fieldDescribe;
    public $fieldInstitute;
    public $fieldChair;
    public $fieldSpeciality;
    public $fieldGroup;
    public $fieldDateBirthday;
    public $fieldIsShowEmail;
    public $fieldCourse;
    public $fieldPhone;
    public $fieldTypeProfile;
    public $fieldUserId;
    public $userRole;
    public $fieldPhoto;

    /**
     * @param mixed $fieldPhoto
     */
    public function setFieldPhoto($fieldPhoto)
    {
        $this->fieldPhoto = $fieldPhoto;
    }

    /**
     * @return mixed
     */
    public function getFieldPhoto()
    {
        return $this->fieldPhoto;
    }

    /**
     * @param mixed $fieldUserId
     */
    public function setFieldUserId($fieldUserId)
    {
        $this->fieldUserId = $fieldUserId;
    }

    /**
     * @return mixed
     */
    public function getFieldUserId()
    {
        return $this->fieldUserId;
    }

    /**
     * @param mixed $fieldTypeProfile
     */
    public function setFieldTypeProfile($fieldTypeProfile)
    {
        $this->fieldTypeProfile = $fieldTypeProfile;
    }

    /**
     * @return mixed
     */
    public function getFieldTypeProfile()
    {
        return $this->fieldTypeProfile;
    }

    /**
     * @param mixed $fieldPhone
     */
    public function setFieldPhone($fieldPhone)
    {
        $this->fieldPhone = $fieldPhone;
    }

    /**
     * @return mixed
     */
    public function getFieldPhone()
    {
        return $this->fieldPhone;
    }


    /**
     * @param mixed $fieldCourse
     */
    public function setFieldCourse($fieldCourse)
    {
        $this->fieldCourse = $fieldCourse;
    }

    /**
     * @return mixed
     */
    public function getFieldCourse()
    {
        return $this->fieldCourse;
    }


    /**
     * @param mixed $fieldAbout
     */
    public function setFieldAbout($fieldAbout)
    {
        $this->fieldAbout = $fieldAbout;
    }

    /**
     * @return mixed
     */
    public function getFieldAbout()
    {
        return $this->fieldAbout;
    }

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
     * @param mixed $fieldDateBirthday
     */
    public function setFieldDateBirthday($fieldDateBirthday)
    {
        $this->fieldDateBirthday = $fieldDateBirthday;
    }

    /**
     * @return mixed
     */
    public function getFieldDateBirthday()
    {
        return $this->fieldDateBirthday;
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
     * @param mixed $fieldIsShowEmail
     */
    public function setFieldIsShowEmail($fieldIsShowEmail)
    {
        $this->fieldIsShowEmail = $fieldIsShowEmail;
    }

    /**
     * @return mixed
     */
    public function getFieldIsShowEmail()
    {
        return $this->fieldIsShowEmail;
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

    /**
     * @param mixed $fieldPassNew
     */
    public function setFieldPassNew($fieldPassNew)
    {
        $this->fieldPassNew = $fieldPassNew;
    }

    /**
     * @return mixed
     */
    public function getFieldPassNew()
    {
        return $this->fieldPassNew;
    }

    /**
     * @param mixed $fieldPassOld
     */
    public function setFieldPassOld($fieldPassOld)
    {
        $this->fieldPassOld = $fieldPassOld;
    }

    /**
     * @return mixed
     */
    public function getFieldPassOld()
    {
        return $this->fieldPassOld;
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
    //public $fieldPhoto;



    public function __construct($a) {
        $this->userRole = $a;
    }
}