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
    'Master Data' => [
        addNav('dashboard.master-data.account', 'Manajemen Pengguna', 'ti ti-user'),
        addNav('dashboard.master-data.seminar', 'Manajemen Seminar', 'ti ti-presentation'),
    ],
    'Transaksi' => [
        addNav('dashboard.transaksi.data-pendaftar', 'Data Pendaftar', 'ti ti-ticket'),
        addNav('dashboard.transaksi.data-pembayaran', 'Data Pembayaran', 'ti ti-cash'),
    ],
    'Laporan' => [
        addNav('dashboard.laporan.riwayat-absensi', 'Riwayat Absensi', 'ti ti-clipboard-check'),
        addNav('dashboard.laporan.riwayat-transaksi', 'Riwayat Transaksi', 'ti ti-database-dollar'),
    ],
    'settings' => [
        addNav('dashboard.settings.account', 'Account', 'ti ti-user-circle'),
    ],
];