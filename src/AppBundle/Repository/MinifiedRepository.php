<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Minified;
use Doctrine\ORM\NoResultException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class MinifiedRepository extends EntityRepository
{

	public function getAllMinifiedUrls()
	{
		$em = $this->getEntityManager();
		try {
			return $em->createQuery('
				SELECT 
					m.id AS id, 
					m.longUrl AS longUrl, 
					m.shortCode AS shortCode,
					COUNT(s) AS hits,
					m.createdAt as createdAt
				FROM AppBundle:Minified m 
				LEFT JOIN m.statistics s
				GROUP BY m.id
			')->getArrayResult();
		} catch (NoResultException $e) {
			return ['status' => 'fail', 'error' => $e->getMessage(), 'message' => 'No results'];
		} catch (\Exception $e) {
			return ['status' => 'fail', 'error' => $e->getMessage()];
		}
	}

	public function addNewUrl($url, $minLength = 10, $maxLength = 20)
	{
		$em = $this->getEntityManager();
		$faker = \Faker\Factory::create('en_US');
		$codeExists = true;
		$result = null;
        while ($codeExists) {
            $shortCode = $faker->regexify(sprintf('^[A-Za-z0-9]{%s,%s}$', $minLength, $maxLength));
            $minified = $this->findOneBy(['shortCode' => $shortCode]);
            if ($minified == null) {
            	$minified = new Minified();
            	$minified->setLongUrl($url);
            	$minified->setShortCode($shortCode);
            	$em->persist($minified);
            	$em->flush();
            	$result = [
            		'id' => $minified->getId(),
            		'longUrl' => $url,
            		'shortCode' => $shortCode,
            		'hits' => 0,
            	];
            	$codeExists = false;
            }
        }
        return $result;
	}

        public function updateUrlShortCode($id, $shortCode)
        {
		$em = $this->getEntityManager();
		try {
			$em->createQuery('
				UPDATE AppBundle:Minified m
				SET m.shortCode = :shortCode
				WHERE m.id = :id
			')
			->setParameters(['id' => $id, 'shortCode' => $shortCode])
			->execute();
			return ['status' => 'ok'];
		} catch (UniqueConstraintViolationException $e) {
                        return ['status' => 'fail', 'error' => $e->getMessage(), 'message' => 'This URL already exists.'];
                } catch (\Exception $e) {
                        return ['status' => 'fail', 'error' => $e->getMessage()];
                }
        }

	public function getStatisticForUrl($id)
	{
		$em = $this->getEntityManager();
		try {
			return $em->createQuery('
				SELECT DISTINCT
					s.country AS label,
					COUNT(s.country) AS value
				FROM AppBundle:Minified m 
				LEFT JOIN m.statistics s
				WHERE m.id = :id
				GROUP BY s.country
			')
			->setParameter('id', $id)
			->getArrayResult();
		} catch (NoResultException $e) {
			return ['status' => 'fail', 'error' => $e->getMessage(), 'message' => 'No results'];
		} catch (\Exception $e) {
			return ['status' => 'fail', 'error' => $e->getMessage()];
		}
	}

	public function deleteUrl($id)
	{
		$em = $this->getEntityManager();
		try {
			$em->createQuery(
				'DELETE FROM AppBundle:Minified m WHERE m.id = :id'
			)
			->setParameter('id', $id)
			->execute();
			return ['status' => 'ok'];
		} catch (\Exception $e) {
			return ['status' => 'fail', 'message' => $e->getMessage()];
		}
	}

}
