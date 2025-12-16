<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::livewire('/', 'pages::landing.home')->name('home');


Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');
});
Route::get('logout', function () {
    Auth::logout();
    Session::invalidate();
    Session::flush();

    return redirect()->route('login');
});
Route::middleware(['auth', 'role:admin'])->prefix('dashboard')->name('dashboard')->group(function () {
    Route::livewire('/', 'pages::dashboard.index');
    // Master Data
    Route::livewire('/master-data/account', 'pages::dashboard.master-data.account')->name('.master-data.account');
    Route::livewire('/master-data/account/add', 'pages::dashboard.master-data.account-add')->name('.master-data.account-add');
    Route::livewire('/master-data/account/{user}', 'pages::dashboard.master-data.account-detail')->name('.master-data.account-detail');
    Route::livewire('/master-data/account/{user}/edit', 'pages::dashboard.master-data.account-edit')->name('.master-data.account-edit');
    Route::livewire('/master-data/seminar', 'pages::dashboard.master-data.seminar')->name('.master-data.seminar');
    Route::livewire('/master-data/seminar/create', 'pages::dashboard.master-data.seminar-create')->name('.master-data.seminar-create');
    Route::livewire('/master-data/seminar/{seminar}', 'pages::dashboard.master-data.seminar-detail')->name('.master-data.seminar-detail');
    Route::livewire('/master-data/seminar/{seminar}/edit', 'pages::dashboard.master-data.seminar-edit')->name('.master-data.seminar-edit');
    // Transaksi
    Route::livewire('/transaksi/data-pendaftar', 'pages::dashboard.transaksi.data-pendaftar')->name('.transaksi.data-pendaftar');
    Route::livewire('/transaksi/data-pendaftar/{registration}', 'pages::dashboard.transaksi.data-pendaftar-detail')->name('.transaksi.data-pendaftar.detail');
    Route::livewire('/transaksi/data-pembayaran', 'pages::dashboard.transaksi.data-pembayaran')->name('.transaksi.data-pembayaran');
    Route::livewire('/transaksi/data-pembayaran/{payment}', 'pages::dashboard.transaksi.data-pembayaran-detail')->name('.transaksi.data-pembayaran.detail');
    // Laporan
    Route::livewire('/laporan/riwayat-absensi', 'pages::dashboard.laporan.riwayat-absensi')->name('.laporan.riwayat-absensi');
    Route::livewire('/laporan/riwayat-transaksi', 'pages::dashboard.laporan.riwayat-transaksi')->name('.laporan.riwayat-transaksi');
    // Settings
    Route::livewire('/settings/account', 'pages::dashboard.settings.account')->name('.settings.account');
});