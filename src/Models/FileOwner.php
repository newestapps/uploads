<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Models;

class FileOwner
{

    private $ownerType;
    private $ownerId;

    private $uploadedByType;
    private $uploadedById;

    /**
     * @return mixed
     */
    public function getOwnerType()
    {
        return (empty($this->ownerType)) ? ($this->uploadedByType) : ($this->ownerType);
    }

    /**
     * @param mixed $ownerType
     */
    public function setOwnerType($ownerType): void
    {
        $this->ownerType = $ownerType;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return (empty($this->ownerId)) ? ($this->uploadedById) : ($this->ownerId);
    }

    /**
     * @param mixed $ownerId
     */
    public function setOwnerId($ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return mixed
     */
    public function getUploadedByType()
    {
        return $this->uploadedByType;
    }

    /**
     * @param mixed $uploadedByType
     */
    public function setUploadedByType($uploadedByType): void
    {
        $this->uploadedByType = $uploadedByType;
    }

    /**
     * @return mixed
     */
    public function getUploadedById()
    {
        return $this->uploadedById;
    }

    /**
     * @param mixed $uploadedById
     */
    public function setUploadedById($uploadedById): void
    {
        $this->uploadedById = $uploadedById;
    }

}