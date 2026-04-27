<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

$page = 'dashboard';
$id_user = $user['id_user'];

// --- 1. AMBIL STATISTIK PETANI (MURNI DATA PRIBADI) ---
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
$total_petani = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_aktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Active'");
$total_aktif = mysqli_fetch_assoc($q_aktif)['total'] ?? 0;

$page = 'dashboard';

// --- 1. AMBIL STATISTIK PETANI (MURNI DATA PRIBADI) ---
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
$total_petani = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_aktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Active'");
$total_aktif = mysqli_fetch_assoc($q_aktif)['total'] ?? 0;

$q_nonaktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Inactive'");
$total_nonaktif = mysqli_fetch_assoc($q_nonaktif)['total'] ?? 0;

// --- 2. DATA PENDAFTAR TERBARU (LIST) ---
$query_recent = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC LIMIT 8");
