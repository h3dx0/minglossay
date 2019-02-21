<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TermRepository")
 */
class Term
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
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
   /**
     * @ORM\ManyToOne(targetEntity="MiniGlossary", inversedBy="terms")
     * @ORM\JoinColumn(name="miniglossary_id", referencedColumnName="id")
     */
   private $miniglossary;
    /**
     * @ORM\OneToMany(targetEntity="Translate", mappedBy="term")
     */
    private $translates;

    
    public function __toString()
    {
        return $this->text;
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
     * Set text
     *
     * @param string $text
     *
     * @return Term
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set miniglossary
     *
     * @param \AppBundle\Entity\MiniGlossary $miniglossary
     *
     * @return Term
     */
    public function setMiniglossary(\AppBundle\Entity\MiniGlossary $miniglossary = null)
    {
        $this->miniglossary = $miniglossary;

        return $this;
    }

    /**
     * Get miniglossary
     *
     * @return \AppBundle\Entity\MiniGlossary
     */
    public function getMiniglossary()
    {
        return $this->miniglossary;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translates = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add translate
     *
     * @param \AppBundle\Entity\Translate $translate
     *
     * @return Term
     */
    public function addTranslate(\AppBundle\Entity\Translate $translate)
    {
        $this->translates[] = $translate;

        return $this;
    }

    /**
     * Remove translate
     *
     * @param \AppBundle\Entity\Translate $translate
     */
    public function removeTranslate(\AppBundle\Entity\Translate $translate)
    {
        $this->translates->removeElement($translate);
    }

    /**
     * Get translates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslates()
    {
        return $this->translates;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Term
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
