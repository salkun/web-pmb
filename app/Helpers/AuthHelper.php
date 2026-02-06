<?php

if (!function_exists('auth_user')) {
    function auth_user() {
        return session('user');
    }
}

if (!function_exists('auth_check')) {
    function auth_check() {
        return session()->has('user');
    }
}

if (!function_exists('auth_id')) {
    function auth_id() {
        return session('user.id');
    }
}

if (!function_exists('auth_role')) {
    function auth_role() {
        return session('user.role');
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return auth_role() === 'admin';
    }
}

if (!function_exists('is_user')) {
    function is_user() {
        return auth_role() === 'user';
    }
}
