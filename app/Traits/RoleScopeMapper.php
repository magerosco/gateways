<?php

namespace App\Traits;

trait RoleScopeMapper
{
    public function determineScopesBasedOnRole($roles)
    {
        if (in_array('admin', $roles)) {
            return ['view-profile', 'manage-users', 'manage-posts'];
        }

        if (in_array('editor', $roles)) {
            return ['view-profile', 'manage-posts'];
        }

        if (in_array('viewer', $roles)) {
            return ['view-profile'];
        }

        return [];
    }
}
