<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Statistic;
use AppBundle\Entity\Minified;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\EntityRepository;

class StatisticRepository extends EntityRepository
{

	public function registerHitForUrl(Minified $url, $geodata)
        {
                $em = $this->getEntityManager();
		$stat = new Statistic();
		$stat->setCountry($geodata->getCountry());
		$em->persist($stat);
		$url->addStatistics($stat);
		$em->persist($url);
		$em->flush();
		return $url->getLongUrl();
        }

}
