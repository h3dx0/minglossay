<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translate
 *
 * @ORM\Table(name="translate")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TranslateRepository")
 */
class Translate
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
     * @ORM\ManyToOne(targetEntity="Term", inversedBy="translates")
     * @ORM\JoinColumn(name="term_id", referencedColumnName="id")
     */
    private $term;
    /**
     * @ORM\ManyToOne(targetEntity="Idiom")
     * @ORM\JoinColumn(name="idiom_id", referencedColumnName="id")
     */
    private $idiom;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * @return Translate
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
     * Set term
     *
     * @param \AppBundle\Entity\Term $term
     *
     * @return Translate
     */
    public function setTerm(\AppBundle\Entity\Term $term = null)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return \AppBundle\Entity\Term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set idiom
     *
     * @param \AppBundle\Entity\Idiom $idiom
     *
     * @return Translate
     */
    public function setIdiom(\AppBundle\Entity\Idiom $idiom = null)
    {
        $this->idiom = $idiom;

        return $this;
    }

    /**
     * Get idiom
     *
     * @return \AppBundle\Entity\Idiom
     */
    public function getIdiom()
    {
        return $this->idiom;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Translate
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
