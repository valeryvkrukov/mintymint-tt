<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Statistic;

/**
 * @ORM\Table(name="minified_urls", indexes={@ORM\Index(name="short_code_idx", columns={"short_code"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MinifiedRepository")
 */
class Minified
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
	 * @ORM\Column(name="long_url", type="string", unique=true)
	 */
	private $longUrl;

	/**
	 * @ORM\Column(name="short_code", type="string", unique=true)
	 */
	private $shortCode;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Statistic")
	 * @ORM\JoinTable(name="url_statistics",
	 * 		joinColumns={ @ORM\JoinColumn(name="url_id", referencedColumnName="id", onDelete="cascade") },
	 * 		inverseJoinColumns={ @ORM\JoinColumn(name="statistics_id", referencedColumnName="id", unique=true, onDelete="cascade") }
	 * )
	 */
	private $statistics;

	/**
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	private $createdAt;

	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
		$this->statistics = new ArrayCollection();
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
	 * Get longUrl
	 */
	public function getLongUrl()
	{
		return $this->longUrl;
	}

	/**
	 * Set longUrl
	 *
	 * @param string $longUrl
	 *
	 * @return Minified
	 */
	public function setLongUrl($longUrl)
	{
		$this->longUrl = $longUrl;
		return $this;
	}

	/**
	 * Get shortCode
	 */
	public function getShortCode()
	{
		return $this->shortCode;
	}

	/**
	 * Set shortCode
	 *
	 * @param string $shortCode
	 *
	 * @return Minified
	 */
	public function setShortCode($shortCode)
	{
		$this->shortCode = $shortCode;
		return $this;
	}

	/**
	 * Add statistic item
	 *
	 * @param Statistic
	 *
	 * @return Minified
	 */
	public function addStatistics(Statistic $statistic)
	{
		$this->statistics[] = $statistic;
		return $this;
	}

	/**
	 * Get statistics
	 *
	 * @return Statistic[]
	 */
	public function getStatistics()
	{
		return $this->statistics;
	}

	/**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

	/**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Minified
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
