<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 *
 * This class is responsible for transforming user data for DataTables.
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(User $user): array
    {
        $status = match ($user->status) {
            User::STATUS_INACTIVE => '<span class="badge badge-soft-secondary">'.User::STATUSES[$user->status].'</span>',
            User::STATUS_BLOCKED => '<span class="badge badge-soft-danger">'.User::STATUSES[$user->status].'</span>',
            default => '<span class="badge badge-soft-success">'.User::STATUSES[$user->status].'</span>',
        };

        $role = match ($user->role) {
            User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$user->role].'</span>',
            default => '<span class="badge badge-soft-primary">'.User::ROLES[$user->role].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.$user->id.'</span>',
            'username' => $user->username,
            'email' => '<a class="text-primary" href="mailto:'.$user->email.'">'.$user->email.'</a>',
            'role' => $role,
            'registration_ip' => $user->registration_ip,
            'status' => $status,
            'last_login_at' => ! empty($user->last_login_at) ? $user->last_login_at->format(config('app.date_format')) : '-',
            'blocked_at' => ! empty($user->blocked_at) ? $user->blocked_at->format(config('app.date_format')) : '-',
            'created_at' => $user->created_at->format(config('app.date_format')),
            'updated_at' => $user->updated_at->format(config('app.date_format')),
            'action' => $this->renderActions($user),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(User $user): string
    {
        $modify = match ($user->status) {
            User::STATUS_ACTIVE => '<a href="#" class="btn btn-sm btn-soft-secondary" title="Ban"><i class="fa fa-ban"></i></a>',
            User::STATUS_INACTIVE, User::STATUS_BLOCKED => '<a href="#" class="btn btn-sm btn-soft-success" title="Active"><i class="fa fa-check"></i></a>',
        };

        return '
            <a href="" class="btn btn-sm btn-soft-primary" title="View"><i class="fa fa-eye"></i></a>
            <a href="#" class="btn btn-sm btn-soft-warning" title="Edit"><i class="fa fa-pen"></i></a>
            '.$modify.'
            <a href="#" class="btn btn-sm btn-soft-danger" title="Delete"><i class="fa fa-trash"></i></a>
        ';
    }
}
