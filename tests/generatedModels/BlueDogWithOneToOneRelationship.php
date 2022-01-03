<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlueDog extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_blue_dog_table_suf_gen';

    /**
     * Get the associated RedCat
     */
    public function redCat(): HasOne
    {
        return $this->hasOne(RedCat::class, 'blue_dog_id');
    }
}
