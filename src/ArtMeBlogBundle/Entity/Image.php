<?php
/**
 * Created by PhpStorm.
 * User: Cecko
 * Date: 12/17/2016
 * Time: 4:33 PM
 */

namespace ArtMeBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="ArtMeBlogBundle\Repository\ImageRepository")
 */
class Image
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    // "image/png", "image/jpg", "image/jpeg", "image/gif"

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PNG file.")
     * @Assert\File(
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg", "image/gif"}
     *     )
     */
    private $imageName;

    /**
     * @var int
     *
     * @@ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ArtMeBlogBundle\Entity\User", inversedBy="images")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    /**
     * * @Assert\Length(
     *     max = 15,
     *     maxMessage="Max length of description (15 chars)"
     * )
     * @var string;
     * @ORM\Column(name="description", type="string", length = 15)
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
     * @return Image
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
     * @return Image
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

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
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