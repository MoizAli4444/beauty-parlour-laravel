<?php

// if (!function_exists('render_status_badge')) {
//     function render_status_badge($status): string
//     {
//         $statuses = [
//             'active'        => ['Active', 'success'],
//             'inactive'      => ['Inactive', 'secondary'],
//         ];

//         [$text, $class] = $statuses[$status] ?? ['Unknown', 'dark'];

//         return "<span class='badge bg-{$class}'>{$text}</span>";
//     }
// }

if (!function_exists('render_status_badge')) {
    function render_status_badge($status, $id = null, $route = null): string
    {
        $statuses = [
            'active'        => ['Active', 'success'],
            'inactive'      => ['Inactive', 'secondary'],
        ];

        [$text, $class] = $statuses[$status] ?? ['Unknown', 'dark'];

        // If ID and route are passed, make it clickable
        if ($id && $route) {
            return "<a href='javascript:void(0)' data-id='{$id}' data-route='{$route}' class='toggle-status badge bg-{$class}'>{$text}</a>";
        }

        return "<span class='badge bg-{$class}'>{$text}</span>";
    }
}

