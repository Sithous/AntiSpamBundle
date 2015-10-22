<?php

namespace Sithous\AntiSpamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 */
class SithousAntiSpam
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $userObject;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var SithousAntiSpamType
     */
    private $type;
    
    /**
     * Set id
     * 
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get IP
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set IP
     *
     * @param $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get UserObject
     *
     * @return string
     */
    public function getUserObject()
    {
        return $this->userObject;
    }

    /**
     * Set UserObject
     *
     * @param $userObject
     * @return $this
     */
    public function setUserObject($userObject)
    {
        $this->userObject = $userObject;

        return $this;
    }

    /**
     * Get User ID
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set User ID
     *
     * @param $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get DateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set DateTime
     *
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Set SithousAntiSpamType
     *
     * @param SithousAntiSpamType $type
     * @return $this
     */
    public function setType(SithousAntiSpamType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get SithousAntiSpamType
     *
     * @return SithousAntiSpamType
     */
    public function getType()
    {
        return $this->type;
    }
}
