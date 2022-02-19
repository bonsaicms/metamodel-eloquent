<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function redCats(): BelongsToMany
    {
        return $this->belongsToMany(RedCat::class, 'pre_gen_blue_dog_red_cat_pivot_table_suf_gen', 'blue_dog_id', 'red_cat_id');
    }
}
