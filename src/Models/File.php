<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    public $table = 'file_uploads';

    public function owner()
    {
        return $this->morphTo('owner');
    }

}