<?php

namespace App\Policies\BankAccount;

use App\Models\BankAccount\Goal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class GoalPolicy
 *
 * @package App\Policies\BankAccount
 */
class GoalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasTeamPermission($user->currentTeam, 'view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User              $user
     * @param  \App\Models\BankAccount\Goal  $bankAccountGoal
     *
     * @return mixed
     */
    public function view(User $user, Goal $bankAccountGoal)
    {
        return $user->hasTeamPermission($user->currentTeam, 'view')
            && $bankAccountGoal->bankAccount()->teamId === $user->currentTeam->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasTeamPermission($user->currentTeam, 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User              $user
     * @param  \App\Models\BankAccount\Goal  $bankAccountGoal
     *
     * @return mixed
     */
    public function update(User $user, Goal $bankAccountGoal)
    {
        return $user->hasTeamPermission($user->currentTeam, 'update');
            // && $bankAccountGoal->bankAccount()->first()->teamId === $user->currentTeam->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User              $user
     * @param  \App\Models\BankAccount\Goal  $bankAccountGoal
     *
     * @return mixed
     */
    public function delete(User $user, Goal $bankAccountGoal)
    {
        return $user->hasTeamPermission($user->currentTeam, 'delete');
            // && $bankAccountGoal->bankAccount()->first()->teamId === $user->currentTeam->id;
    }
}
