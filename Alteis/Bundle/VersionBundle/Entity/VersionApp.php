<?php

namespace Alteis\Bundle\VersionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gestion des versions de la base
 *
 * @ORM\Table(name="version_app")
 * @ORM\Entity(repositoryClass="Alteis\Bundle\VersionBundle\Repository\VersionAppRepository")
 */
class VersionApp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_initialisation", type="date", nullable = true)
     */
    private $dateInitialisation;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_mise_en_production", type="date", nullable = true)
     */
    private $dateMiseEnProduction;
    
    /**
     * @var string
     *
     * @ORM\Column(name="release_note", type="text", length=800, nullable = true)
     */
    private $releaseNote;

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
     * Get id
     *
     * @param integer $id
     *
     * @return VersionApp
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return VersionApp
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return \DateTime
     */
    public function getDateInitialisation()
    {
        return $this->dateInitialisation;
    }

    /**
     * @param \DateTime $dateInitialisation
     * @return $this
     */
    public function setDateInitialisation($dateInitialisation)
    {
        $this->dateInitialisation = $dateInitialisation;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getDateMiseEnProduction()
    {
        return $this->dateMiseEnProduction;
    }

    /**
     * @param \DateTime $dateMiseEnProduction
     * @return $this
     */
    public function setDateMiseEnProduction($dateMiseEnProduction)
    {
        $this->dateMiseEnProduction = $dateMiseEnProduction;
        return $this;
    }
    
    /**
     * Set releaseNote
     *
     * @param string $releaseNote
     * @return VersionApp
     */
    public function setReleaseNote($releaseNote)
    {
        $this->releaseNote = $releaseNote;

        return $this;
    }

    /**
     * Get releaseNote
     *
     * @return string
     */
    public function getReleaseNote()
    {
        return $this->releaseNote;
    }
}
