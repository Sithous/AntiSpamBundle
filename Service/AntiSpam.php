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
         * make sure identifier was defined
         */
        if(!$this->getIdentifier())
        {
            throw new \Exception('Identifier was never set.');
        }

        /**
         * verify identifier config can be found
         */
        if(!$config = $this->getIdentifierConfig())
        {
            throw new \Exception('Identifier "'.$this->getIdentifier().'" is not defined in the config.yml');
        }

        /**
         * make sure track_ip and track_user are not both false.
         */
        if(!$config['track_ip'] && !$config['track_user'])
        {
            throw new \Exception('Both config options track_ip and track_user cannot be false (identifier: "'.$this->getIdentifier().'").');
        }

        $user = $config['track_user'] ? ($this->getUser() ?: $this->getSecurityContextUser()) : null;
        $ip = $config['track_ip'] ? ($this->getIp() ?: $_SERVER['REMOTE_ADDR']) : null;
        $this->_results = $repository->getUserActionCount($this->getIdentifier(), $config, $user, $ip);

        if($this->_results['count'] >= $config['max_calls'])
        {
            return false;
        }

        if($track)
        {
            $entity = new SithousAntiSpam();
            $entity
                ->setIdentifier($this->getIdentifier())
                ->setDateTime(new \DateTime())
                ->setIp($ip)
                ->setUserId($user ? $user->getId() : null)
                ->setUserObject($user ? get_class($user) : null);

            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }

    public function getErrorMessage($string = null)
    {
        $config = $this->getIdentifierConfig();

        $replace = array(
            '[max_calls]' => $config['max_calls'],
            '[max_time]'  => $config['max_time'],
            '[time_left]' => $this->getWaitTime()
        );

        return $config ? str_replace(array_keys($replace), array_values($replace), $string ?: "You can only do this [max_calls] time(s) in [max_time] seconds. \nYou must wait another [time_left] second(s).") : '';
    }

    public function getWaitTime()
    {
        if(!$this->getIdentifierConfig() || !isset($this->_results['oldest']) || !is_object($this->_results['oldest']))
        {
            return 0;
        }

        return $this->getIdentifierConfig()['max_time'] - (time() - $this->_results['oldest']->getDateTime()->getTimestamp());
    }

    /**
     * Set Identifier string
     *
     * @param $identifier
     * @throws \Exception
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
     * Get config for identifier
     *
     * @param null $identifier
     * @return mixed
     * @throws \Exception
     */
    public function getIdentifierConfig($identifier = null)
    {
        if(!$identifier)
        {
            $identifier = $this->getIdentifier();
        }

        return isset($this->config['identifiers'][$identifier]) ? $this->config['identifiers'][$identifier] : null;
    }

    private function getSecurityContextUser()
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