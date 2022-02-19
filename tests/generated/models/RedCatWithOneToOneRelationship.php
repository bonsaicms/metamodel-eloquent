<?php

namespace TestApp\Models;

use Some\Namespace\ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedCat extends ParentModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_gen_red_cat_table_suf_gen';

    /**
     * Get the associated BlueDog
     */
    public function blueDog(): BelongsTo
    {
        return $this->belongsTo(BlueDog::class, 'blue_dog_id');
    }
}
