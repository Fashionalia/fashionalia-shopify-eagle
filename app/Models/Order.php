<?php declare (strict_types = 1);

namespace Eagle\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eagle\Models\Order
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Eagle\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Eagle\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Eagle\Models\Order query()
 * @mixin \Eloquent
 */
class Order extends Model
{
    public $guarded = ['created_at', 'updated_at'];
}
