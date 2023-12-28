@php
    $user = Auth::user();
    $canViewAccessGroups = $user->hasPermissionTo('viewlist-access-groups', 'user');
    $canViewPermission = $user->hasPermissionTo('viewlist-permissions', 'user');
    $canViewUsers = $user->hasPermissionTo('viewlist-users', 'user');
    $canViewInterfaces = $user->hasPermissionTo('viewlist-interfaces', 'user');
    $canViewIntegrators = $user->hasPermissionTo('viewlist-integrators', 'user');
    $canViewFields = $user->hasPermissionTo('viewlist-fields', 'user');

    $items = [
        [
            'position' => 'center',
            'title' => 'Dashboard',
            'route' => '/admin/dashboard',
            'visible' => true,
            'items' => [],
        ],
        [
            'position' => 'center',
            'title' => 'Interfaces',
            'visible' => $canViewInterfaces || $canViewIntegrators || $canViewFields,
            'items' => [
                [
                    'position' => 'center',
                    'title' => 'Interfaces',
                    'route' => '/admin/interfaces',
                    'visible' => $canViewInterfaces,
                    'items' => [],
                ],
                [
                    'position' => 'center',
                    'title' => 'Integradores',
                    'route' => '/admin/integrators',
                    'visible' => $canViewIntegrators,
                    'items' => [],
                ],
                [
                    'position' => 'center',
                    'title' => 'Campos',
                    'route' => '/admin/fields',
                    'visible' => $canViewFields,
                    'items' => [],
                ],
            ],
        ],
        [
            'position' => 'center',
            'title' => 'Acesso',
            'visible' => $canViewUsers || $canViewAccessGroups,
            'items' => [
                [
                    'title' => 'Usuários',
                    'route' => '/admin/users',
                    'visible' => $canViewUsers,
                ],
                [
                    'title' => 'Grupos de Acesso',
                    'route' => '/admin/access-groups',
                    'visible' => $canViewAccessGroups,
                ],
                [
                    'title' => 'Permissões',
                    'route' => '/admin/permissions',
                    'visible' => $canViewPermission,
                ],
            ],
        ],
        [
            'position' => 'right',
            'title' => $user->name,
            'visible' => true,
            'custom_style' => 'right: 0px;left:unset;',
            'items' => [
                [
                    'title' => 'Sair',
                    'route' => '/login',
                    'visible' => true,
                ],
            ],
        ],
    ];
@endphp
<theme-navbar :items='@json($items)'>
    <theme-switcher></theme-switcher>
</theme-navbar>
