<?php

namespace Newestapps\Uploads\Strategies;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemNotFoundException;
use Newestapps\Uploads\Models\File;
use Newestapps\Uploads\Models\FileOwner;
use Ramsey\Uuid\Uuid;

class DefaultUploadStrategy extends UploadStrategy
{

    private $storageFacade;

    /**
     * @return string
     */
    public function getName()
    {
        return 'default';
    }

    /**
     * @param array $config
     * @return String|void
     */
    public function init()
    {
        $allowedFilesystems = config('filesystems.disks');
        $allowedFilesystems = array_keys($allowedFilesystems);

        $config = $this->getConfigs();

        if (!empty($config['driver']) && in_array($config['driver'], $allowedFilesystems)) {
            $this->storageFacade = Storage::disk($config['driver']);
        } else {
            throw new FilesystemNotFoundException($config['driver']);
        }
    }

    public function preProcessor(Request $request, UploadedFile $uploadedFile)
    {
        return $uploadedFile;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param FileOwner $fileOwner
     * @return UploadedFile|null
     * @throws \Throwable
     */
    public function persistFile(UploadedFile $uploadedFile, FileOwner $fileOwner)
    {
        $file = $uploadedFile->storePublicly($this->getConfig('path'));

        $f = new File();
        $f->stored_name = $uploadedFile->hashName();
        $f->real_name = $uploadedFile->getClientOriginalName();
        $f->owner_type = $fileOwner->getOwnerType();
        $f->owner_id = $fileOwner->getOwnerId();
        $f->uploaded_by_type = $fileOwner->getUploadedByType();
        $f->uploaded_by_id = $fileOwner->getUploadedById();
        $f->size = $uploadedFile->getSize();
        $f->strategy = $this->getName();
        $f->path = $file;
        $f->mimes = $uploadedFile->getMimeType();

        $f->saveOrFail();

        return $uploadedFile;
    }

    public function postProcessor(File $file)
    {

    }
}