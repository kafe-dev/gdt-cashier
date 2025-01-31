<?php

declare(strict_types=1);

namespace App\Services\DataTables\Transformers;

use App\Models\User;
use App\Utils\ActionWidget;
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
        $role = match ($user->role) {
            User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$user->role].'</span>',
            User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$user->role].'</span>',
            User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$user->role].'</span>',
            User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$user->role].'</span>',
            default => '<span class="badge badge-soft-primary">'.User::ROLES[$user->role].'</span>',
        };

        $status = match ($user->status) {
            User::STATUS_INACTIVE => '<span class="badge badge-soft-secondary">'.User::STATUSES[$user->status].'</span>',
            User::STATUS_BLOCKED => '<span class="badge badge-soft-danger">'.User::STATUSES[$user->status].'</span>',
            default => '<span class="badge badge-soft-success">'.User::STATUSES[$user->status].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.$user->id.'</span>',
            'username' => $user->username,
            'email' => '<a class="text-primary" href="mailto:'.$user->email.'">'.$user->email.'</a>',
            'role' => $role,
            'registration_ip' => $user->registration_ip,
            'status' => $status,
            'last_login_at' => ! empty($user->last_login_at) ? '<span class="x-has-time-converter">'.$user->last_login_at->format(config('app.date_format')).'</span>' : '-',
            'blocked_at' => ! empty($user->blocked_at) ? '<span class="x-has-time-converter">'.$user->blocked_at->format(config('app.date_format')).'</span>' : '-',
            'created_at' => ! empty($user->created_at) ? '<span class="x-has-time-converter">'.$user->created_at->format(config('app.date_format')).'</span>' : '-',
            'updated_at' => ! empty($user->updated_at) ? '<span class="x-has-time-converter">'.$user->updated_at->format(config('app.date_format')).'</span>' : '-',
            'action' => $this->renderActions($user),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(User $user): string
    {
        $modify = match ($user->status) {
            User::STATUS_ACTIVE => '<a href="'.route('app.user.changeStatus', ['id' => $user->id]).'" class="btn btn-sm btn-secondary" title="Ban"><i class="fa fa-ban"></i></a>',
            User::STATUS_INACTIVE, User::STATUS_BLOCKED => '<a href="'.route('app.user.changeStatus', ['id' => $user->id]).'" class="btn btn-sm btn-success" title="Active"><i class="fa fa-check"></i></a>',
        };

        return '
            '.$modify.'
            '.ActionWidget::renderShowBtn(route('app.user.show', ['id' => $user->id])).'
            '.ActionWidget::renderUpdateBtn(route('app.user.edit', ['id' => $user->id])).'
            '.ActionWidget::renderDeleteBtn($user->id, route('app.user.delete', ['id' => $user->id])).'
        ';
    }
}
