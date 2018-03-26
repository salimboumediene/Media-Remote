<?php

namespace MediaRemoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaRemoteFile
 *
 * @ORM\Table(name="media_remote_file", indexes={@ORM\Index(name="media _remote_id", columns={"media _remote_id"})})
 * @ORM\Entity
 */
class MediaRemoteFile
{
    /**
     * @var string
     *
     * @ORM\Column(name="media_remote_file_title", type="string", length=255, nullable=false)
     */
    private $mediaRemoteFileTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="media_remote_file_descr", type="string", length=255, nullable=false)
     */
    private $mediaRemoteFileDescr;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_remote_file_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mediaRemoteFileId;

    /**
     * @var \MediaRemoteBundle\Entity\MediaRemote
     *
     * @ORM\ManyToOne(targetEntity="MediaRemoteBundle\Entity\MediaRemote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media _remote_id", referencedColumnName="media_remote_id")
     * })
     */
    private $mediaRemote;



    /**
     * Set mediaRemoteFileTitle
     *
     * @param string $mediaRemoteFileTitle
     *
     * @return MediaRemoteFile
     */
    public function setMediaRemoteFileTitle($mediaRemoteFileTitle)
    {
        $this->mediaRemoteFileTitle = $mediaRemoteFileTitle;

        return $this;
    }

    /**
     * Get mediaRemoteFileTitle
     *
     * @return string
     */
    public function getMediaRemoteFileTitle()
    {
        return $this->mediaRemoteFileTitle;
    }

    /**
     * Set mediaRemoteFileDescr
     *
     * @param string $mediaRemoteFileDescr
     *
     * @return MediaRemoteFile
     */
    public function setMediaRemoteFileDescr($mediaRemoteFileDescr)
    {
        $this->mediaRemoteFileDescr = $mediaRemoteFileDescr;

        return $this;
    }

    /**
     * Get mediaRemoteFileDescr
     *
     * @return string
     */
    public function getMediaRemoteFileDescr()
    {
        return $this->mediaRemoteFileDescr;
    }

    /**
     * Get mediaRemoteFileId
     *
     * @return integer
     */
    public function getMediaRemoteFileId()
    {
        return $this->mediaRemoteFileId;
    }

    /**
     * Set mediaRemote
     *
     * @param \MediaRemoteBundle\Entity\MediaRemote $mediaRemote
     *
     * @return MediaRemoteFile
     */
    public function setMediaRemote(\MediaRemoteBundle\Entity\MediaRemote $mediaRemote = null)
    {
        $this->mediaRemote = $mediaRemote;

        return $this;
    }

    /**
     * Get mediaRemote
     *
     * @return \MediaRemoteBundle\Entity\MediaRemote
     */
    public function getMediaRemote()
    {
        return $this->mediaRemote;
    }
}
