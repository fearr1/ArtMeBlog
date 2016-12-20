<?php
/**
 * Created by PhpStorm.
 * User: Cecko
 * Date: 12/18/2016
 * Time: 3:45 AM
 */

namespace ArtMeBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Song
 *
 * @ORM\Table(name="songs")
 * @ORM\Entity(repositoryClass="ArtMeBlogBundle\Repository\SongRepository")
 */
class Song
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
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the song as a MP3, WAV, AIFF file.")
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes={"audio/mpeg", "audio/x-aiff", "audio/x-wav"})
     */
    private $songName;

    /**
     * @var int
     *
     * @@ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ArtMeBlogBundle\Entity\User", inversedBy="songs")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    /**
     * @var string;
     *
     * @ORM\Column(name="description", type="string", length=20)
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param User $author
     *
     * @return Song
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $authorId
     *
     * @return Song
     */
    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setSongName($songName)
    {
        $this->songName = $songName;

        return $this;
    }

    public function getSongName()
    {
        return $this->songName;
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
}