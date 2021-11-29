<?php

namespace DummyNamespace;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DummyModelClass extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const DELETED_AT = 'deleted_at';

    protected $table = 'DummyModelVariable';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function getStateAttribute($value = null)
    {
        return strtolower($value);
    }

    public function setStateAttribute($value = null)
    {
        $this->attributes['state'] = strtoupper($value);
    }

    public function getStateAttributes($value = null, $default = '--')
    {
        $attributes = [
            'NORMAL' => '正常',
            'DISABLE' => '禁用',
        ];

        $attributes = array_change_key_case($attributes);

        if (func_num_args()) {
            return array_key_exists(strtolower($value), $attributes) ? $attributes[$value] : $default;
        }

        return $attributes;
    }
}
