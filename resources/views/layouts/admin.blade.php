@props([
    'title' => 'Admin Dashboard | LapkingHub',
    'pageTitle' => 'Dashboard',
    'breadcrumbs' => [],
])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body bg-body-tertiary">
    <div class="admin-shell">
        <div class="admin-sidebar-backdrop" data-admin-sidebar-close></div>

        @include('layouts.admin.sidebar')

        <div class="admin-main">
            @include('layouts.admin.header', ['pageTitle' => $pageTitle])

            <main class="admin-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <x-admin.breadcrumb :items="$breadcrumbs" :current="$pageTitle" />
                    <x-admin.flash-message />

                    {{ $slot }}
                </div>
            </main>

            @include('layouts.admin.footer')
        </div>
    </div>
</body>
</html>
