<?php

namespace Newestapps\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Newestapps\Core\Facades\Newestapps;
use Newestapps\Uploads\Exceptions\NoFilesToUploadException;
use Newestapps\Uploads\Exceptions\StrategyInitializationException;
use Newestapps\Uploads\Models\File;
use Newestapps\Uploads\Models\FileOwner;
use Newestapps\Uploads\Strategies\UploadStrategy;

class UploadsController extends Controller
{

    /**
     * @param Request $request
     * @param UploadStrategy $strategy
     * @param FileOwner $fileOwner
     * @return \Newestapps\Core\Http\Resources\ApiErrorResponse|\Newestapps\Core\Http\Resources\ApiResponse
     * @throws StrategyInitializationException
     */
    public function upload(Request $request, UploadStrategy $strategy, FileOwner $fileOwner)
    {
        $this->validate();
        $files = $request->allFiles();

        $output = [
            'input-count' => 0,
            'failed' => 0,
            'success' => 0,
            'errors' => [],
        ];

        $initializations = [];

        if (count($files) === 0 && $strategy !== null && $fileOwner !== null) {
            foreach ($files as $file) {
                /** @var UploadedFile $file */

                if (!isset($initializations[get_class($strategy)])) {
                    try {
                        $strategy->init();
                        $initializations[get_class($strategy)] = true;
                    } catch (\Exception $e) {
                        throw new StrategyInitializationException($strategy->getConfig('class', null));
                    }
                }

                $name = $file->getClientOriginalName();
                if (!isset($output['errors'][$name])) {
                    $output['errors'][$name] = [];
                }

                try {
                    $file = $strategy->preProcessor($request, $file);

                    if ($file === null || $file === false || ($file instanceof Validator)) {
                        if ($file === null || $file === false) {
                            $output['errors'][$name][] = [
                                'error' => "O arquivo selecionado não pode ser enviado!",
                            ];
                        } else {
                            $output['errors'][$name] = array_merge($output['errors'][$name], $file->messages()->all());
                        }
                    }
                } catch (ValidationException $validationException) {
                    $output['errors'][$name] = array_merge($output['errors'][$name], $validationException->errors());
                    $output['failed']++;
                } catch (\Exception $e) {
                    $output['failed']++;
                    $output['errors'][$name] = array_merge($output['errors'][$name], ['exception' => $e->getMessage()]);
                }

                $persistedFile = null;

                try {
                    $persistedFile = $strategy->persistFile($file, $fileOwner);
                    $output['success']++;
                } catch (\Exception $e) {
                    $output['failed']++;
                    $output['errors'][$name] = array_merge($output['errors'][$name], ['exception' => $e->getMessage()]);
                }

                if ($persistedFile instanceof File) {
                    $strategy->postProcessor($persistedFile);
//                    $s = ((count($output['success']) === 1) ? ('') : ('s'));
                }

            }
        }

        return Newestapps::apiResponse($output, null, 200);
    }

}