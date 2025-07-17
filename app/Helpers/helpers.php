<?php

if (!function_exists('render_view_button')) {
    function render_view_button($slug): string
    {
        if ($slug) {
            return "<a href='{$slug}' class='btn btn-sm btn-outline-info me-2'>
                        <i class='fas fa-eye'></i> View
                    </a>";
        }

        return '';
    }
}

if (!function_exists('render_edit_button')) {
    function render_edit_button($slug): string
    {
        if ($slug) {
            return "<a href='{$slug}' class='btn btn-sm btn-outline-warning me-2'>
                        <i class='fas fa-edit'></i> Edit
                    </a>";
        }

        return '';
    }
}


if (!function_exists('render_delete_button')) {
    function render_delete_button($id, $route): string
    {
        if ($id && $route) {
            return "<a href='javascript:void(0)' data-id='{$id}' data-route='{$route}' class='btn btn-sm btn-outline-danger me-2'>
                        <i class='fas fa-trash-alt'></i> Delete
                    </a>";
        }

        return '';
    }
}


if (!function_exists('render_status_badge')) {
    function render_status_badge($status, $id = null, $route = null): string
    {
        $statuses = [
            'active'        => ['Active', 'success'],
            'inactive'      => ['Inactive', 'secondary'],
        ];

        [$text, $class] = $statuses[$status] ?? ['Unknown', 'dark'];

        if ($id && $route) {
            return "<a href='javascript:void(0)' data-id='{$id}' data-route='{$route}' class='toggle-status badge rounded-pill text-bg-{$class}'>{$text}</a>";
        }

        return "<span class='badge rounded-pill text-bg-{$class}'>{$text}</span>";
    }
}
