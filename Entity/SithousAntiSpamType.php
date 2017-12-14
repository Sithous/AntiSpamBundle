<?php

namespace Sithous\AntiSpamBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 */
class SithousAntiSpamType
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var boolean
     */
    private $trackIp;

    /**
     * @var boolean
     */
    private $trackUser;

    /**
     * @var integer
     */
    private $maxTime;

    /**
     * @var integer
     */
    private $maxCalls;

    /**
     * @var ArrayCollection|SithousAntiSpam[]
     */
    private $sithousAntiSpams;

    public function __construct()
    {
        $this->sithousAntiSpams = new ArrayCollection();
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
     * Set id
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get whether to track IP
     *
     * @return bool
     */
    public function getTrackIp()
    {
        return $this->trackIp;
    }

    /**
     * Set whether to track IP
     *
     * @param $trackIp
     * @return $this
     */
    public function setTrackIp($trackIp)
    {
        $this->trackIp = $trackIp;

        return $this;
    }

    /**
     * Get whether to track User
     *
     * @return bool
     */
    public function getTrackUser()
    {
        return $this->trackUser;
    }

    /**
     * Set whether to track User
     *
     * @param $trackUser
     * @return $this
     */
    public function setTrackUser($trackUser)
    {
        $this->trackUser = $trackUser;

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

    public function getSithousAntiSpams()
    {
        return $this->sithousAntiSpams;
    }

    public function setSithousAntiSpams($sithousAntiSpams)
    {
        $this->sithousAntiSpams = $sithousAntiSpams;
    }

    public function addSithousAntiSpam(SithousAntiSpam $sithousAntiSpam)
    {
        $this->sithousAntiSpams->add($sithousAntiSpam);
        return $this;
    }

    public function removeSithousAntiSpam(SithousAntiSpam $sithousAntiSpam)
    {
        $this->sithousAntiSpams->removeElement($sithousAntiSpam);
        return $this;
    }

    public function clearSithousAntiSpams()
    {
        $this->sithousAntiSpams->clear();
        return $this;
    }
}
