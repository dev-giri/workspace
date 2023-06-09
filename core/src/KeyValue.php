<?php

namespace Workspace;

use Illuminate\Database\Eloquent\Model;


class KeyValue extends Model
{
	protected $table = 'workspace_key_values';

 	public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'keyvalue_id',
        'keyvalue_type',
        'key',
        'value',
    ];

    public function keyvalue()
    {
        return $this->morphTo();
    }
}
