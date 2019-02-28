<?php

namespace AppBundle\Entity;

use Component\Media\MediasAwareInterface;
use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * Filesystem.
 *
 * @ORM\Table(name="file_system")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileSystemRepository")
 * @Cloner\Exclude()
 */
class FileSystem implements ProjectAwareInterface, MediasAwareInterface, ResourceInterface, CloneableInterface
{
    const LOCAL_ADAPTER = 'local_adapter';
    const DROPBOX_ADAPTER = 'dropbox_adapter';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="fileSystems", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="driver", type="string", length=255, nullable=false)
     */
    private $driver;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="config", type="json_array", nullable=false)
     */
    private $config;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var ArrayCollection|Media[]
     *
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="fileSystem")
     */
    private $medias;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false, options={"default"=0})
     */
    private $isDefault = false;

    /**
     * FileSystem constructor.
     */
    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * Set driver.
     *
     * @param string $driver
     *
     * @return FileSystem
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver.
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return FileSystem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return str_replace(' ', '_', $this->name);
    }

    /**
     * Set config.
     *
     * @param array $config
     *
     * @return FileSystem
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return FileSystem
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return FileSystem
     */
    public function setProject(ProjectInterface $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add media.
     *
     * @param Media $media
     *
     * @return FileSystem
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media.
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias.
     *
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set isDefault.
     *
     * @param bool $isDefault
     *
     * @return FileSystem
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param Media[]|ArrayCollection $medias
     */
    public function setMedias($medias)
    {
        foreach ($medias as $media) {
            $media->setFileSystem($this);
        }

        $this->medias = $medias;
    }
}
