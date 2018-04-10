<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VersionedFileScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('id', 'desc');
    }

}