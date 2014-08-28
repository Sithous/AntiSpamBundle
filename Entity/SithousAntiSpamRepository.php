<?php

namespace Sithous\AntiSpamBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Sithous\AntiSpamBundle\Entity\SithousAntiSpamType;

class SithousAntiSpamRepository extends EntityRepository
{
    public function getUserActionCount(SithousAntiSpamType $type, $user, $ip)
    {
        $query = $this->createQueryBuilder('a')
            ->setMaxResults(1)
            ->andWhere('a.type = :type')
            ->setParameter('type', $type)
            ->andWhere('a.dateTime >= :before')
            ->setParameter('before', date('Y-m-d H:i:s', time() - $type->getMaxTime()))
            ->orderBy('a.dateTime', 'ASC');

        if($type->getTrackUser() && $type->getTrackIp())
        {
            $query
                ->andWhere('(a.ip = :ip OR a.userId = :userId AND a.userObject = :userObject)')
                ->setParameter('ip', $ip)
                ->setParameter('userId', $user->getId())
                ->setParameter('userObject', get_class($user));
        }
        elseif($type->getTrackIp())
        {
            $query
                ->andWhere('a.ip = :ip')
                ->setParameter('ip', $ip);
        }
        elseif($type->getTrackUser())
        {
            $query
                ->andWhere('a.userId = :userId')
                ->setParameter('userId', $user->getId())
                ->andWhere('a.userObject = :userObject')
                ->setParameter('userObject', get_class($user));
        }

        return array(
            'oldest' => $query->getQuery()->getOneOrNullResult(),
            'count'  => $query
                    ->setMaxResults(null)
                    ->select('COUNT(a.id)')
                    ->getQuery()->getsinglescalarresult(),
        );
    }

    public function purgeOldRecords($identifiers)
    {
//        $em = $this->getEntityManager();
//
//        foreach($identifiers as $identifier => $identifierData)
//        {
//            $results = $this->createQueryBuilder('a')
//                ->where('a.identifier = :identifier')
//                ->setParameter('identifier', $identifier)
//                ->andWhere('a.dateTime < :dateTime')
//                ->setParameter('dateTime', date('Y-m-d H:i:s', time() - $identifierData['max_time']))
//                ->getQuery()->getResult();
//
//            foreach($results as $result)
//            {
//                $em->remove($result);
//            }
//        }
//
//        $em->flush();
    }
}