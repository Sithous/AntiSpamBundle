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
     * @var string
     */
    private $identifier;

    /**
     * @var integer
     */
    private $maxCalls;

    /**
     * @var integer
     */
    private $maxTime;

    /**
     * @var \DateTime
     */
    private $dateTime;

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
     * Get Identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set Identifier
     *
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get Max Calls
     *
     * @return int
     */
    public function getMaxCalls()
    {
        return $this->maxCalls;
    }

    /**
     * Set Max Calls
     *
     * @param $maxCalls
     * @return $this
     */
    public function setMaxCalls($maxCalls)
    {
        $this->maxCalls = $maxCalls;

        return $this;
    }

    /**
     * Get Max Time
     *
     * @return int
     */
    public function getMaxTime()
    {
        return $this->maxTime;
    }

    /**
     * Set Max Time
     *
     * @param $maxTime
     * @return $this
     */
    public function setMaxTime($maxTime)
    {
        $this->maxTime = $maxTime;

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
}
