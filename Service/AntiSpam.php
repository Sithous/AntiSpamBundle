<?php
namespace Sithous\AntiSpamBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpam;
use Symfony\Component\Security\Core\SecurityContext;

class AntiSpam
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var string
     */
    private $_identifier;

    /**
     * @var string
     */
    private $_ip;

    /**
     * @var string
     */
    private $_user;

    /**
     * @var int
     */
    private $_maxCalls = 1;

    /**
     * @var int
     */
    private $_maxTime = 60;

    public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
    {
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
    }

    /**
     * Verify an action can occur
     *
     * @return bool
     */
    public function verify()
    {
        return get_class($this->getUser());
    }

    /**
     * Set Identifier string
     *
     * @param $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->_identifier = $identifier;

        return $this;
    }

    /**
     * Get Identifier string
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Set IP
     *
     * @param $ip
     */
    public function setIp($ip)
    {
        $this->_ip = $ip;
    }

    /**
     * Get IP
     *
     * @return mixed
     */
    public function getIp()
    {
        return $this->_ip;
    }

    /**
     * Set User
     *
     * @param $user
     * @throws \Exception
     */
    public function setUser($user)
    {
        if(!is_object($user))
        {
            throw new \Exception('User must be object');
        }

        $this->_user = $user;
    }

    /**
     * Get User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Set Max calls that can be executed in $maxTime second(s)
     *
     * @param int $maxCalls
     * @return $this
     */
    public function setMaxCalls($maxCalls = 1)
    {
        $this->_maxCalls = $maxCalls;

        return $this;
    }

    /**
     * Get Max calls that can be executed in $maxTime second(s)
     *
     * @return int
     */
    public function getMaxCalls()
    {
        return $this->_maxCalls;
    }

    /**
     * Set Time Frame in seconds to limit the calls
     *
     * @param int $maxTime
     * @return $this
     */
    public function setMaxTime($maxTime = 60)
    {
        $this->_maxTime = $maxTime;

        return $this;
    }

    /**
     * Get Time Frame in seconds to limit the calls
     *
     * @return int
     */
    public function getMaxTime()
    {
        return $this->_maxTime;
    }
}