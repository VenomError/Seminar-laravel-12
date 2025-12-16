<?php

if (!function_exists('addNav')) {
    function addNav($href, $title, $icon, $sub = [])
    {
        return [
            'href' => $href,
            'title' => $title,
            'icon' => $icon,
            'sub' => $sub
        ];
    }
}

if (!function_exists('addSub')) {
    function addSub($href, $title)
    {
        return [
            'href' => $href,
            'title' => $title,
        ];
    }
}

return [
    'Main Menu' => [
        addNav('dashboard', 'Dashboard', 'ti ti-layout-dashboard'),
    ],

    'Manajemen Acara' => [
        addNav('dashboard', 'Seminar Saya', 'ti ti-presentation'),
    ],

    'Transaksi' => [
        addNav('dashboard', 'Pendaftar Masuk', 'ti ti-ticket'),
        addNav('dashboard', 'Verifikasi Pembayaran', 'ti ti-credit-card'),
    ],

    'Event Desk' => [
        addNav('dashboard', 'Scan Tiket (QR)', 'ti ti-scan'),
    ],

    'Laporan' => [
        addNav('dashboard', 'Riwayat Absensi', 'ti ti-clipboard-check'),
    ],
    'Settings' => [
        addNav('dashboard', 'Account', 'ti ti-user-circle'),
    ],
];