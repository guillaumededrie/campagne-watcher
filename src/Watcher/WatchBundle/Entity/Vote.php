<?php
// src/Watcher/WatchBundle/Entity/Vote.php
namespace Watcher\WatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vote")
 */
class Vote
{
  const WALL_LISTE = 'wall-liste';
  const GUILIGUILIST = 'guiliguilist';

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=20)
   */
  protected $ip;

  /**
   * @ORM\Column(type="string", length=20)
   */
  protected $trigramme;

  /**
   * @ORM\Column(type="string")
   */
  protected $liste;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $datevote;


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
   * Set ip
   *
   * @param string $ip
   * @return Vote
   */
  public function setIp($ip)
  {
    $this->ip = $ip;
    
    return $this;
  }

  /**
   * Get ip
   *
   * @return string 
   */
  public function getIp()
  {
    return $this->ip;
  }

  /**
   * Set datevote
   *
   * @param \DateTime $datevote
   * @return Vote
   */
  public function setDatevote($datevote)
  {
    $this->datevote = $datevote;
    
    return $this;
  }

  /**
   * Get datevote
   *
   * @return \DateTime 
   */
  public function getDatevote()
  {
    return $this->datevote;
  }

    /**
     * Set liste
     *
     * @param string $liste
     * @return Vote
     */
    public function setListe($liste)
    {
      if (!in_array($liste, array(self::WALL_LISTE, self::GUILIGUILIST))) {
	throw new \InvalidArgumentException("Invalid status");
      }
      
      $this->liste = $liste;
    }

    /**
     * Get liste
     *
     * @return string 
     */
    public function getListe()
    {
        return $this->liste;
    }

    /**
     * Set trigramme
     *
     * @param string $trigramme
     * @return Vote
     */
    public function setTrigramme($trigramme)
    {
        $this->trigramme = $trigramme;
    
        return $this;
    }

    /**
     * Get trigramme
     *
     * @return string 
     */
    public function getTrigramme()
    {
        return $this->trigramme;
    }
}