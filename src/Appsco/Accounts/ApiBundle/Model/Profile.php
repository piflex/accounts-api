<?php

namespace Appsco\Accounts\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Profile implements \Serializable
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $email;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $firstName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $lastName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $locale;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $timezone;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $gender;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $country;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $province;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $city;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $phone;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $pictureUrl;

    /**
     * @var Directory
     * @JMS\Type("Appsco\Accounts\ApiBundle\Model\Directory")
     */
    protected $directory;




    /**
     * @param string $city
     * @return $this|Profile
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country
     * @return $this|Profile
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \Appsco\Accounts\ApiBundle\Model\Directory $directory
     * @return $this|Profile
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return \Appsco\Accounts\ApiBundle\Model\Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $email
     * @return $this|Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $firstName
     * @return $this|Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $gender
     * @return $this|Profile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $id
     * @return $this|Profile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $lastName
     * @return $this|Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $locale
     * @return $this|Profile
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $phone
     * @return $this|Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $pictureUrl
     * @return $this|Profile
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $province
     * @return $this|Profile
     */
    public function setProvince($province)
    {
        $this->province = $province;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $timezone
     * @return $this|Profile
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }




    public function __toString()
    {
        return $this->getEmail();
    }


    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->locale,
            $this->timezone,
            $this->gender,
            $this->country,
            $this->province,
            $this->city,
            $this->phone,
            $this->pictureUrl,
            $this->directory
        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->locale,
            $this->timezone,
            $this->gender,
            $this->country,
            $this->province,
            $this->city,
            $this->phone,
            $this->pictureUrl,
            $this->directory
        ) = unserialize($serialized);
    }


} 