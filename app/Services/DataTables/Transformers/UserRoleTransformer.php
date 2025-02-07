<?php

namespace App\Services\DataTables\Transformers;

use App\Models\User;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

class UserRoleTransformer extends TransformerAbstract
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

        return [
            'id' => '<span class="fw-bold float-start">'.$user->id.'</span>',
            'username' => $user->username,
            'email' => '<a class="text-primary" href="mailto:'.$user->email.'">'.$user->email.'</a>',
            'role' => $role,
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
        return '
            '.ActionWidget::renderUpdateBtn(route('app.user.roleManage.edit', ['id' => $user->id])).'
        ';
    }
}
