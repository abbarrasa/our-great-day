<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table(name="ogd_post")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    const COVER_PICTURE_WEB_DIR = 'uploads/post';    

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_picture", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Image(maxWidth=180, maxHeight=180, maxSize="2M")
     */
    private $coverPicture;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer", nullable=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="post")
     */
    private $comments;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->setPublished(false);
        $this->setLikes(0);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set coverPicture.
     *
     * @param string $coverPicture
     *
     * @return Post
     */
    public function setCoverPicture($coverPicture)
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    /**
     * Get coverPicture.
     *
     * @return string
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * Set content.
     *
     * @param string|null $content
     *
     * @return Post
     */
    public function setContent($content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set publishedAt.
     *
     * @param \DateTime|null $publishedAt
     *
     * @return Post
     */
    public function setPublishedAt($publishedAt = null)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt.
     *
     * @return \DateTime|null
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set published.
     *
     * @param bool|null $published
     *
     * @return Post
     */
    public function setPublished($published = null)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published.
     *
     * @return bool|null
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Check if is published.
     *
     * @return bool|null
     */
    public function isPublished()
    {
        return $this->getPublished();
    }

    /**
     * Set likes.
     *
     * @param int|null $likes
     *
     * @return Post
     */
    public function setLikes($likes = null)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes.
     *
     * @return int|null
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add comment.
     *
     * @param \AdminBundle\Entity\PostComment $comment
     *
     * @return Post
     */
    public function addComment(\AdminBundle\Entity\PostComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param \AdminBundle\Entity\PostComment $comment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComment(\AdminBundle\Entity\PostComment $comment)
    {
        return $this->comments->removeElement($comment);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set createdAt value before persist
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get absolute directory path for picture
     *
     * @return string
     */
    static public function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.self::COVER_PICTURE_WEB_DIR;        
    }
    
    /**
     * Get absolute picture path
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->coverPicture
            ? null
            : $this->getUploadRootDir().'/'.$this->coverPicture
        ;        
    }
}
