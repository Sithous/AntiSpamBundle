<?php
namespace Sithous\AntiSpamBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpam;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpamType;
use Symfony\Component\Security\Core\SecurityContext;

class AntiSpamService
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
    private $_results;

    /**
     * @var array
     */
    protected $config;

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

    public function __construct(EntityManager $entityManager, SecurityContext $securityContext, $config)
    {
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
        $this->config = $config;
    }

    /**
     * Verify an action can occur
     *
     * @param $track bool - Track this action
     * @throws \Exception on error
     * @return bool
     */
    public function verify($track = true)
    {
        $repository = $this->em->getRepository('SithousAntiSpamBundle:SithousAntiSpam');

        /**
         * Lets first purge any old rows if config is set to not use cron
         */
        if($this->config['active_gc'])
        {
            $this->garbage_collector();
        }

        /**
         * make sure identifier was defined
         */
        if(!$this->getType())
        {
            throw new \Exception('AntiSpamType was never set.');
        }

        $user = $this->getType()->getTrackUser() ? ($this->getUser() ?: $this->_getSecurityContextUser()) : null;
        $ip = $this->getType()->getTrackIp() ? ($this->getIp() ?: $_SERVER['REMOTE_ADDR']) : null;
        $this->_results = $repository->getUserActionCount($this->getType(), $user, $ip);

        if($this->_results['count'] >= $this->getType()->getMaxCalls())
        {
            return false;
        }

        if($track)
        {
            $entity = new SithousAntiSpam();
            $entity
                ->setType($this->getType())
                ->setDateTime(new \DateTime())
                ->setIp($ip)
                ->setUserId($user ? $user->getId() : null)
                ->setUserObject($user ? get_class($user) : null);

            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }

    /**
     * Get error message.
     *
     * @todo get this using sprintf
     * @param null $string
     * @return mixed|string
     */
    public function getErrorMessage($string = null)
    {
        if(!$this->getType())
        {
            return '';
        }

        $replace = array(
            '{max_calls}'         => $this->getType()->getMaxCalls(),
            '{max_time}'          => $this->getType()->getMaxTime(),
            '{time_left}'         => $this->getWaitTime(),
            '{time_left_hours}'   => gmdate('H', $this->getWaitTime()),
            '{time_left_minutes}' => gmdate('i', $this->getWaitTime()),
            '{time_left_seconds}' => gmdate('s', $this->getWaitTime()),
        );

        return str_replace(array_keys($replace), array_values($replace), $string ?: "You must wait another {time_left} second(s).");
    }

    /**
     * Get wait time in seconds.
     *
     * @return int
     */
    public function getWaitTime()
    {
        if(!$this->getType() || !isset($this->_results['oldest']) || !is_object($this->_results['oldest']))
        {
            return 0;
        }

        return $this->getType()->getMaxTime() - (time() - $this->_results['oldest']->getDateTime()->getTimestamp());
    }

    /**
     * Set SithousAntiSpamType
     *
     * @param $identifier string
     * @throws \Exception
     * @return $this
     */
    public function setType($identifier)
    {
        if(!$this->_identifier = $this->em->getRepository('SithousAntiSpamBundle:SithousAntiSpam')->findOneById($identifier))
        {
            throw new \Exception('Could not find SithousAntiSpamType "'.$identifier.'" in the database.');
        }

        return $this;
    }

    /**
     * Get SithousAntiSpamType
     *
     * @return null|SithousAntiSpamType
     */
    public function getType()
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
     * Run garbage collector
     */
    public function garbage_collector()
    {
//        $repository = $this->em->getRepository('SithousAntiSpamBundle:SithousAntiSpam');
//
//        $results = $repository->createQueryBuilder('a')
//            ->join('SithousAntiSpamBundle:SithousAntiSpamType', 't')
//            ->andWhere('a.dateTime < :dateTime')
//            ->setParameter('dateTime', date('Y-m-d H:i:s', time() - $identifierData['max_time']))
//            ->getQuery()->getResult();
    }

    /**
     * Get the current user logged in
     *
     * @return bool|object
     */
    private function _getSecurityContextUser()
    {
        if (null === $token = $this->securityContext->getToken()) {
            return false;
        }

        if (!is_object($user = $token->getUser())) {
            return false;
        }

        return $user;
    }
}