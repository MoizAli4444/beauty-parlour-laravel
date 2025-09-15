<?php

use App\GenderType;
use Illuminate\Support\Facades\Storage;

if (!function_exists('render_index_button')) {
    function render_index_button($route, $label = 'All Records', $isOutline = true): string
    {
        if ($route) {
            $btnClass = $isOutline ? 'btn-sm btn-outline-warning' : 'btn-warning';
            return "<a href='{$route}' class='btn {$btnClass} me-2'>
                        <i class='fas fa-list'></i> {$label}
                    </a>";
        }

        return '';
    }
}


if (!function_exists('render_view_button')) {
    function render_view_button($slug, $isOutline = true): string
    {
        if ($slug) {
            $btnClass = $isOutline ? 'btn-sm btn-outline-info' : 'btn-info';
            return "<a href='{$slug}' class='btn {$btnClass} me-2'>
                        <i class='fas fa-eye'></i> View
                    </a>";
        }

        return '';
    }
}

if (!function_exists('render_edit_button')) {
    function render_edit_button($slug, $isOutline = true): string
    {
        if ($slug) {
            $btnClass = $isOutline ? 'btn-sm btn-outline-warning' : 'btn-warning';
            return "<a href='{$slug}' class='btn {$btnClass} me-2'>
                        <i class='fas fa-edit'></i> Edit
                    </a>";
        }

        return '';
    }
}


if (!function_exists('render_delete_button')) {
    function render_delete_button($id, $route, $isOutline = true, $extraClass = ''): string
    {
        if ($id && $route) {
            $btnClass = $isOutline ? 'btn-sm btn-outline-danger' : 'btn-danger';
            return "<a href='javascript:void(0)' data-id='{$id}' data-route='{$route}' class='delete-record btn  {$btnClass} {$extraClass}'>
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


if (!function_exists('render_gender_badge')) {
    function render_gender_badge(GenderType $gender): string
    {
        $text = $gender->label();
        $color = match ($gender) {
            GenderType::Female => 'info',
            GenderType::Male => 'primary',
            GenderType::Both => 'dark',
        };

        return "<span class='badge rounded-pill text-bg-{$color}'>{$text}</span>";
    }
}


if (!function_exists('getImage')) {
    /**
     * Get image URL or full <img> tag
     *
     * @param string|null $path
     * @param string $default
     * @param bool $asTag
     * @param array $attributes
     * @return string
     */
    function getImage(?string $path, bool $asTag = false, string $default = 'storage/default.png',  array $attributes = []): string
    {
        $url = $path && Storage::disk('public')->exists($path)
            ? asset('storage/' . $path)
            : asset($default);

        if (! $asTag) {
            return $url; // return only URL
        }

        // Default attributes
        $defaultAttributes = [
            'class' => 'img-thumbnail js-media-preview',
            'style' => 'max-width: 60px; cursor:pointer;',
            'data-url' => $url,
            'data-type' => 'image',
        ];

        // Merge user-supplied attributes (they override defaults)
        $finalAttributes = array_merge($defaultAttributes, $attributes);

        // Convert attributes array to HTML string
        $attrString = collect($finalAttributes)->map(function ($value, $key) {
            return $key . '="' . e($value) . '"';
        })->implode(' ');

        return '<img src="' . $url . '" ' . $attrString . '>';
    }
}
