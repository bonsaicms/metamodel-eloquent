<?php

namespace TestApp\Policies;

use Some\Another\Class;
use Test\App\Models\User;
use TestApp\Models\BlueDog;
use Test\Illuminate\Auth\Access\HandlesAuthorization;
use TestApp\Some\Custom\Namespace\SomeParentPolicyClass;

class BlueDogPolicy extends SomeParentPolicyClass
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \TestApp\Models\BlueDog  $blueDog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BlueDog $blueDog)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \TestApp\Models\BlueDog  $blueDog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BlueDog $blueDog)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \TestApp\Models\BlueDog  $blueDog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BlueDog $blueDog)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \TestApp\Models\BlueDog  $blueDog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BlueDog $blueDog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \TestApp\Models\BlueDog  $blueDog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BlueDog $blueDog)
    {
        //
    }
}
