<?php

namespace App\Services\DataTables\Transformers;

use App\Models\Permission;
use App\Models\User;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(Permission $permission): array
    {
        $routeString = "";
        if ($permission->routes) {
            foreach ($permission->routes as $route) {
                $routeString .= '<span class="badge badge-soft-info">'.parse_url(route($route, ['id' => 'ID'], false), PHP_URL_PATH).'</span> ';
            }
        }

        $role = match ($permission->role) {
            User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$permission->role].'</span>',
            User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$permission->role].'</span>',
            User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$permission->role].'</span>',
            User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$permission->role].'</span>',
            default => '<span class="badge badge-soft-primary">'.User::ROLES[$permission->role].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.$permission->id.'</span>',
            'role' => $role,
            'routes' => $routeString,
            'action' => $this->renderActions($permission),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(Permission $permission): string
    {
        return '
            '.ActionWidget::renderUpdateBtn(route('app.user.permission.edit', ['id' => $permission->id])).'
            '.ActionWidget::renderDeleteBtn($permission->id, route('app.user.permission.delete', ['id' => $permission->id])).'
        ';
    }
}
