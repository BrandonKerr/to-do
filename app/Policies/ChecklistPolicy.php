<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChecklistPolicy {
    use HandlesAuthorization;

    /**
     * Allow site admin to do whatever they want
     */
    public function before($user) {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Checklist $checklist) {
        return $checklist->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Checklist $checklist) {
        return $checklist->user->is($user);
    }
}
