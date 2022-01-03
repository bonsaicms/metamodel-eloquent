<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;

class Person extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_people_suf_gen';
}
