<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorizationPolicy
{
    use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function sales($user){
        return ($user->auth_admin || $user->auth_super_user || $user->auth_sales || $user->auth_operations);
    }

    public function operations($user){
        return ($user->auth_admin || $user->auth_super_user || $user->auth_operations);
    }

    public function admin($user){
        return ($user->auth_admin || $user->auth_super_user);
    }
    
    public function superUser($user){
        return $user->auth_super_user;
    }
}