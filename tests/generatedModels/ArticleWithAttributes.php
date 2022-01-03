<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;

class Article extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_articles_suf_gen';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'some_boolean_attribute' => 'boolean',
        'some_date_attribute' => 'date',
        'some_time_attribute' => 'time',
        'some_datetime_attribute' => 'datetime',
        'some_json_attribute' => 'array',
    ];
}
