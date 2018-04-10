<?php

namespace Newestapps\Uploads\Http\Middlewares;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Newestapps\Uploads\Exceptions\FileOwnerNotSpecifiedException;
use Newestapps\Uploads\Exceptions\InvalidFileOwnerException;
use Newestapps\Uploads\Exceptions\UploadStrategyNotFoundException;
use Newestapps\Uploads\Models\FileOwner;
use Newestapps\Uploads\Strategies\UploadStrategy;

class UploadsMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     *
     * @throws UploadStrategyNotFoundException
     * @throws FileOwnerNotSpecifiedException
     * @throws InvalidFileOwnerException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->route()->hasParameter('strategy')) {
            $availableStrategiesConfig = config('nw-uploads.strategies');
            $availableStrategies = array_keys($availableStrategiesConfig);

            $strategy = $request->route('strategy');
            if (!empty($strategy) && in_array($strategy, $availableStrategies)) {

                $strategyClass = $availableStrategiesConfig[$strategy]['class'];

                $instance = new $strategyClass();

                if ($instance instanceof UploadStrategy) {
                    $instance->setConfigs($availableStrategiesConfig[$strategy]);

                    // can be null, add the reference for the user who is trying to upload some file.
                    $uploadedBy = Auth::user();

                    $fileOwner = null;

                    $ownerType = $request->get('owner_type', null);
                    $ownerId = $request->get('owner_id', null);

                    if (empty($ownerType) || empty($ownerId)) {
                        if ($uploadedBy === null) {
                            throw new FileOwnerNotSpecifiedException();
                        }
                    } else {
                        if (empty($ownerType) || empty($ownerId) || !is_numeric($ownerId) || empty($uploadedBy) || !($uploadedBy instanceof Model)) {
                            throw new InvalidFileOwnerException();
                        }
                    }

                    $fileOwner = new FileOwner();
                    $fileOwner->setUploadedByType(get_class($uploadedBy));
                    $fileOwner->setUploadedById($uploadedBy->id);
                    $fileOwner->setOwnerType($ownerType);
                    $fileOwner->setOwnerId($ownerId);

                    app()->instance(UploadStrategy::class, $instance);
                    app()->instance(FileOwner::class, $fileOwner);

                    $request->route()->forgetParameter('strategy');
                }

                return $next($request);
            }
        }

        throw new UploadStrategyNotFoundException();
    }

}
