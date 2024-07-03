<?php

namespace App\Helper;

use App\Models\RolePermission;


class Menue
{

    public static function getList($roleId)
    {
        $menuJson = file_get_contents(base_path('resources/json/menu.json'));
        $menus = json_decode($menuJson)->menu;
        $menusList = [];
        foreach ($menus as $menu) {
            $menu->can_view = 0;
            $menu->can_create = 0;
            $menu->can_update = 0;
            $menu->can_delete = 0;
            $rolePermission = RolePermission::where('module', $menu->name)->where('role_id', $roleId)->first();
            if ($rolePermission) {
                $menu->can_view = $rolePermission->can_view;
                $menu->can_create = $rolePermission->can_create;
                $menu->can_update = $rolePermission->can_update;
                $menu->can_delete = $rolePermission->can_delete;
            }
            if ($menu->hasSubMenu) {
                $subMenuesList = [];
                foreach ($menu->sub as $subMenu) {
                    $subMenu->can_view = 0;
                    $subMenu->can_create = 0;
                    $subMenu->can_update = 0;
                    $subMenu->can_delete = 0;
                    $rolePermission = RolePermission::where('module', $subMenu->name)->where('role_id', $roleId)->first();
                    if ($rolePermission) {
                        $subMenu->can_view = $rolePermission->can_view;
                        $subMenu->can_create = $rolePermission->can_create;
                        $subMenu->can_update = $rolePermission->can_update;
                        $subMenu->can_delete = $rolePermission->can_delete;
                    }
                    $subMenuesList[] = $subMenu;
                }
                $menu->sub = $subMenuesList;
            }
            $menusList[] = $menu;
        }

        return $menusList;
    }
}
