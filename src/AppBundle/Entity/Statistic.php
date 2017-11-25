<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="statistics")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatisticRepository")
 */
class Statistic
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="country", type="string")
	 */
	private $country;

	/**
	 * @ORM\Column(name="visited_at", type="datetime")
	 */
	private $visitedAt;
	
	public function __construct()
	{
		$this->visitedAt = new \DateTime();
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set country
	 *
	 * @param $country string
	 *
	 * @return Statistic
	 */
	public function setCountry($country)
	{
		$this->country = $country;
		return $this;
	}

	/**
	 * Get country
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
     * Get visitedAt
     *
     * @return \DateTime
     */
    public function getVisitedAt()
    {
        return $this->visitedAt;
    }
}