<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlueDog extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_blue_dog_table_suf_gen';

    /**
     * Get the associated RedCat models
     */
    public function redCats(): HasMany
    {
        return $this->hasMany(RedCat::class, 'blue_dog_id');
    }
}
