function openEditModal(button) {
    // 1. Ambil data dari atribut tombol yang diklik
    const id = button.getAttribute('data-id');
    const prov = button.getAttribute('data-prov');
    const luas = button.getAttribute('data-luas');
    const prod = button.getAttribute('data-prod');
    const hasil = button.getAttribute('data-hasil');

    // 2. Masukkan data tersebut ke dalam input form di Pop-up
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_provinsi').value = prov;
    document.getElementById('edit_luas').value = luas;
    document.getElementById('edit_produktivitas').value = prod;
    document.getElementById('edit_produksi').value = hasil;

    // 3. Tampilkan Pop-up
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    // Sembunyikan Pop-up
    document.getElementById('editModal').classList.remove('active');
}

// ==========================================
// FITUR BUKA/TUTUP POP-UP TAMBAH DATA
// ==========================================
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
}

// ==========================================
// FITUR PENCARIAN PROVINSI (LIVE SEARCH)
// ==========================================
function searchTable() {
    let input = document.getElementById('searchInput').value.toUpperCase();
    let tbody = document.querySelector('.admin-data-table tbody');
    let tr = tbody.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        // Mengambil kolom pertama (indeks 0) yang berisi nama provinsi
        let tdProvinsi = tr[i].getElementsByTagName('td')[0];

        if (tdProvinsi) {
            let txtValue = tdProvinsi.textContent || tdProvinsi.innerText;
            // Jika teks cocok dengan ketikan, tampilkan. Jika tidak, sembunyikan.
            if (txtValue.toUpperCase().indexOf(input) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// ==========================================
// FITUR FILTER/URUT PRODUKSI TERTINGGI
// ==========================================
let sortDescending = true; // Status awal urutan

function sortProduction() {
    let tbody = document.querySelector('.admin-data-table tbody');
    // Ambil semua baris tabel di dalam tbody dan ubah jadi array
    let rows = Array.from(tbody.querySelectorAll('tr'));
    let btnIcon = document.querySelector('#btnFilter i');

    rows.sort(function (a, b) {
        // Mengambil nilai dari kolom ke-4 (indeks 3) yaitu Produksi (ton)
        let valA = a.getElementsByTagName('td')[3].innerText;
        let valB = b.getElementsByTagName('td')[3].innerText;

        // Membersihkan format angka Indonesia (1.000.000,50 menjadi 1000000.50) agar bisa dihitung komputer
        let numA = parseFloat(valA.replace(/\./g, '').replace(',', '.'));
        let numB = parseFloat(valB.replace(/\./g, '').replace(',', '.'));

        // Logika pengurutan
        if (sortDescending) {
            return numB - numA; // Besar ke kecil
        } else {
            return numA - numB; // Kecil ke besar
        }
    });

    // Ganti arah urutan untuk klik selanjutnya
    sortDescending = !sortDescending;

    // Ganti ikon pada tombol agar lebih interaktif
    if (sortDescending) {
        btnIcon.className = 'fa-solid fa-arrow-up-wide-short'; // Ikon besar ke kecil
    } else {
        btnIcon.className = 'fa-solid fa-arrow-down-short-wide'; // Ikon kecil ke besar
    }

    // Masukkan kembali baris yang sudah diurutkan ke dalam tabel
    rows.forEach((row) => tbody.appendChild(row));
}
