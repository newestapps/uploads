<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Models;

use Illuminate\Database\Eloquent\Model;
use Newestapps\Uploads\Scopes\VersionedFileScope;

class File extends Model
{

    public $table = 'file_uploads';

    protected static function boot()
    {
        static::addGlobalScope(new VersionedFileScope());
        parent::boot();
    }

    public function owner()
    {
        return $this->morphTo('owner');
    }

    public function getExtensionAttribute()
    {
        if (empty(trim($this->attributes['extension']))) {
            return 'unknown';
        }

        return $this->attributes['extension'];
    }

}