<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Strategies;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Newestapps\Uploads\Models\File;
use Newestapps\Uploads\Models\FileOwner;

abstract class UploadStrategy
{

    private $configs = [];

    /** @return String */
    public abstract function getName();

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        if (isset($this->configs[$key])) {
            return $this->configs[$key];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    /**
     * @param Request $request
     * @param UploadedFile $uploadedFile
     * @return UploadedFile|null
     */
    public abstract function preProcessor(Request $request, UploadedFile $uploadedFile);

    /**
     * @return String
     */
    public abstract function init();


    /**
     * @param UploadedFile $uploadedFile
     * @param FileOwner $fileOwner
     * @return File
     */
    public abstract function persistFile(UploadedFile $uploadedFile, FileOwner $fileOwner);

    /**
     * @param File $file
     * @return mixed
     */
    public abstract function postProcessor(File $file);

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     */
    protected function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        $this->getValidationFactory()
            ->make($request->all(), $rules, $messages, $customAttributes)
            ->validate();

        return $this->extractInputFromRules($request, $rules);
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app(Factory::class);
    }

    /**
     * Get the request input based on the given validation rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @return array
     */
    protected function extractInputFromRules(Request $request, array $rules)
    {
        return $request->only(collect($rules)->keys()->map(function ($rule) {
            return Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
    }

}