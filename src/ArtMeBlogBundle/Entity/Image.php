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
 * Product
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

    // ...

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PNG file.")
     * @Assert\File(mimeTypes={ "image/png" })
     */
    private $imageName;

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