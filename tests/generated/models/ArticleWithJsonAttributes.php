<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Article extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_articles_suf_gen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'some_arraylist_attribute',
        'some_arrayhash_attribute',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'some_arraylist_attribute' => AsCollection::class,
        'some_arrayhash_attribute' => AsArrayObject::class,
    ];
}
