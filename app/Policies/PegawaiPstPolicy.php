<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PegawaiPst;
use Illuminate\Auth\Access\HandlesAuthorization;

class PegawaiPstPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PegawaiPst');
    }

    public function view(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('View:PegawaiPst');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PegawaiPst');
    }

    public function update(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('Update:PegawaiPst');
    }

    public function delete(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('Delete:PegawaiPst');
    }

    public function restore(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('Restore:PegawaiPst');
    }

    public function forceDelete(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('ForceDelete:PegawaiPst');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PegawaiPst');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PegawaiPst');
    }

    public function replicate(AuthUser $authUser, PegawaiPst $pegawaiPst): bool
    {
        return $authUser->can('Replicate:PegawaiPst');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PegawaiPst');
    }

}