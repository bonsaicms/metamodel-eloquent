<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RedCat extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_red_cat_table_suf_gen';

    /**
     * Get the associated BlueDog models
     */
    public function blueDogs(): BelongsToMany
    {
        return $this->belongsToMany(BlueDog::class, 'pre_gen_blue_dog_red_cat_pivot_table_suf_gen', 'red_cat_id', 'blue_dog_id');
    }
}
