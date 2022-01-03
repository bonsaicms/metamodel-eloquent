<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;

class Page extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_table_name_pages';
}
