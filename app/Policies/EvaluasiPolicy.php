<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Evaluasi;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluasiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Evaluasi');
    }

    public function view(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('View:Evaluasi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Evaluasi');
    }

    public function update(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('Update:Evaluasi');
    }

    public function delete(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('Delete:Evaluasi');
    }

    public function restore(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('Restore:Evaluasi');
    }

    public function forceDelete(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('ForceDelete:Evaluasi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Evaluasi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Evaluasi');
    }

    public function replicate(AuthUser $authUser, Evaluasi $evaluasi): bool
    {
        return $authUser->can('Replicate:Evaluasi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Evaluasi');
    }

}