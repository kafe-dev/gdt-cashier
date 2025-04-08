<?php
/**
 * @project gdt-cashier
 * @author hoep
 * @email hiepnguyen3624@gmail.com
 * @date 2025-03-31
 * @time 7:32 AM
 */

namespace App\Services\DataTables\Transformers;

use App\Models\RoleHierarchy;
use App\Models\User;
use App\Utils\ActionWidget;
use League\Fractal\TransformerAbstract;

class RoleHierarchyTransformer extends TransformerAbstract
{
    /**
     * Data transformer.
     */
    public function transform(RoleHierarchy $hierarchy): array
    {

        $parent = match ($hierarchy->parent_role) {
            User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$hierarchy->parent_role].'</span>',
            User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$hierarchy->parent_role].'</span>',
            User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$hierarchy->parent_role].'</span>',
            User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$hierarchy->parent_role].'</span>',
            default => '<span class="badge badge-soft-primary">'.User::ROLES[$hierarchy->parent_role].'</span>',
        };

        $child = match ($hierarchy->child_role) {
            User::ROLE_USER => '<span class="badge badge-soft-secondary">'.User::ROLES[$hierarchy->child_role].'</span>',
            User::ROLE_ACCOUNTANT => '<span class="badge badge-soft-success">'.User::ROLES[$hierarchy->child_role].'</span>',
            User::ROLE_SUPPORT => '<span class="badge badge-soft-danger" style="background-color: #e0cffc !important; color: #6610f2 !important;">'.User::ROLES[$hierarchy->child_role].'</span>',
            User::ROLE_SELLER => '<span class="badge badge-soft-info" style="background-color: #ffe5d0 !important; color: #fd7e14 !important;">'.User::ROLES[$hierarchy->child_role].'</span>',
            default => '<span class="badge badge-soft-primary">'.User::ROLES[$hierarchy->child_role].'</span>',
        };

        return [
            'id' => '<span class="fw-bold float-start">'.$hierarchy->id.'</span>',
            'parent_role' => $parent,
            'child_role' => $child,
            'action' => $this->renderActions($hierarchy),
        ];
    }

    /**
     * Render action columns.
     */
    private function renderActions(RoleHierarchy $hierarchy): string
    {
        return '
            '.ActionWidget::renderUpdateBtn(route('app.user.role.hierarchy.edit', ['id' => $hierarchy->id])).'
            '.ActionWidget::renderDeleteBtn($hierarchy->id, route('app.user.role.hierarchy.delete', ['id' => $hierarchy->id])).'
        ';
    }
}
