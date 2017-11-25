<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

use AppBundle\Entity\Minified;
use AppBundle\Entity\Statistic;

class LoadFixtures implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$faker = \Faker\Factory::create('en_US');
		$countries = [];
		for ($i = 0; $i < 10; $i++) {
			$countries[] = $faker->country;
		}
		for ($i = 0; $i < 15; $i++) {
			$minified = new Minified();
			$minified->setLongUrl($faker->url);
			$minified->setShortCode($faker->regexify('^[A-Za-z0-9]{10,20}$'));
			$minified->setCreatedAt($faker->dateTime());
			var_dump($minified->getLongUrl());
			for ($k = 0; $k < $faker->numberBetween(100, 500); $k++) {
				$stat = new Statistic();
				$stat->setCountry($countries[array_rand($countries)]);
				$manager->persist($stat);
				$minified->addStatistics($stat);
				var_dump($stat->getCountry());
			}
			$manager->persist($minified);
		}
		$manager->flush();
	}
}