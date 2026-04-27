document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // 1. SETUP AWAL PETA (LEAFLET)
    // ==========================================

    // Inisialisasi peta
    var map = L.map('map', { maxZoom: 18 }).setView([-2.5489, 118.0149], 5);

    // URUTAN 1: Masukkan Base Map (Satelit) TERLEBIH DAHULU
    L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        {
            attribution:
                'Tiles &copy; Esri &mdash; Source: Esri | &copy; BPS Indonesia',
            maxZoom: 18,
        },
    ).addTo(map);

    // URUTAN 2: Baru inisialisasi Layer Peta & MarkerCluster SETELAH satelit terpasang
    var geojsonLayer;
    var markerLayer = L.markerClusterGroup().addTo(map);

    // Elemen DOM Dropdown
    var filterSubsektor = document.getElementById('filter-subsektor');
    var filterProvinsi = document.getElementById('filter-provinsi');

    // ... (kode di bawahnya biarkan sama persis, tidak perlu diubah) ...
    var filterKabupaten = document.getElementById('filter-kabupaten');
    var filterKecamatan = document.getElementById('filter-kecamatan');
    var filterDesa = document.getElementById('filter-desa');
    var btnReset = document.getElementById('btn-reset-filter');

    // ==========================================
    // 2. FUNGSI INTI (API & PETA)
    // ==========================================

    function muatPetaDariAPI(urlGeoJSON) {
        let finalUrl = urlGeoJSON;
        if (urlGeoJSON.includes('geoserver.bps.go.id')) {
            finalUrl =
                '../controllers/proxy_bps.php?url=' +
                encodeURIComponent(urlGeoJSON);
        }

        fetch(finalUrl)
            .then((res) => res.text())
            .then((text) => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error(
                        'Bukan JSON! Teks asli:',
                        text.substring(0, 300),
                    );
                    throw new Error('Format bukan JSON dari server BPS.');
                }
            })
            .then((data) => {
                // Cek jika BPS mengembalikan JSON tapi isinya 0 koordinat (kosong)
                if (data.features && data.features.length === 0) {
                    console.warn(
                        'Peringatan: Data wilayah ditemukan, tapi belum ada gambar petanya di server BPS.',
                    );
                    return; // Hentikan agar tidak menggambar peta kosong
                }

                if (geojsonLayer) map.removeLayer(geojsonLayer);

                geojsonLayer = L.geoJSON(data, {
                    style: {
                        color: 'white',
                        weight: 1,
                        fillColor: '#d1005d',
                        fillOpacity: 0.7,
                    },
                    onEachFeature: function (feature, layer) {
                        // BPS sering mengubah nama kolom, kita siapkan berbagai kemungkinannya
                        let nama =
                            feature.properties.nama_wilayah ||
                            feature.properties.nmwilayah ||
                            feature.properties.nama ||
                            feature.properties.nmdesa ||
                            feature.properties.nmkec ||
                            feature.properties.nmkab ||
                            feature.properties.nmprov ||
                            'Wilayah';

                        layer.bindTooltip(`<b>${nama}</b>`);
                        layer.on({
                            mouseover: (e) =>
                                e.target.setStyle({
                                    weight: 2,
                                    color: '#ffeb3b',
                                    fillOpacity: 0.9,
                                }),
                            mouseout: (e) => geojsonLayer.resetStyle(e.target),
                        });
                    },
                }).addTo(map);

                let bounds = geojsonLayer.getBounds();
                if (bounds.isValid()) map.fitBounds(bounds);
            })
            .catch((err) => console.warn('Peta dibatalkan:', err.message));
    }

    function isiDropdownWilayah(idElement, urlData, keyData, placeholder) {
        const dropdown = document.getElementById(idElement);
        dropdown.innerHTML = `<option value="">Memuat ${placeholder}...</option>`;
        dropdown.disabled = true;

        const proxyUrl =
            '../controllers/proxy_bps.php?url=' + encodeURIComponent(urlData);

        fetch(proxyUrl)
            .then((res) => res.json())
            .then((res) => {
                if (res.error) throw new Error(res.error);

                const listData = res[keyData];
                let html = `<option value="">Seluruh ${placeholder}</option>`;

                // 🔥 Tambahkan pengecekan aman agar tidak Error "forEach undefined"
                if (listData && Array.isArray(listData)) {
                    listData.forEach((item) => {
                        html += `<option value="${item.id}">${item.nama}</option>`;
                    });
                } else {
                    console.warn(
                        `Data untuk dropdown ${placeholder} kosong atau formatnya salah.`,
                    );
                }

                dropdown.innerHTML = html;
                dropdown.disabled = false;
            })
            .catch((err) => {
                console.error(`Gagal muat ${placeholder}:`, err);
                dropdown.innerHTML = `<option value="">Gagal memuat</option>`;
            });
    }

    // ==========================================
    // 3. LOGIKA ALUR (EVENT LISTENERS)
    // ==========================================

    // Peta Awal
    muatPetaDariAPI('../assets/json/peta_petani_indonesia.json');

    // 1. PROVINSI BERUBAH -> Load Kabupaten
    filterProvinsi.addEventListener('change', function () {
        const idProv = this.value;

        filterKabupaten.innerHTML =
            '<option value="">Seluruh Kabupaten</option>';
        filterKabupaten.disabled = true;
        filterKecamatan.innerHTML =
            '<option value="">Seluruh Kecamatan</option>';
        filterKecamatan.disabled = true;
        filterDesa.innerHTML = '<option value="">Seluruh Desa</option>';
        filterDesa.disabled = true;

        if (idProv) {
            // Dropdown tetap pakai URL API
            isiDropdownWilayah(
                'filter-kabupaten',
                `https://webgis-st2023.web.bps.go.id/kabupaten?id_wilayah=${idProv}`,
                'master_kabupaten',
                'Kabupaten',
            );

            // 🔥 KEMBALIKAN KE URL PETA YANG TERBUKTI BERHASIL (batas_kabupaten & kdprov)
            muatPetaDariAPI(
                `https://geoserver.bps.go.id/st2023/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=st2023:batas_kabupaten&outputFormat=application/json&CQL_FILTER=kdprov='${idProv}'`,
            );
        } else {
            btnReset.click();
        }
    });

    // 2. KABUPATEN BERUBAH -> Load Kecamatan
    filterKabupaten.addEventListener('change', function () {
        const idKab = this.value; // Contoh: "3519"

        filterKecamatan.innerHTML =
            '<option value="">Seluruh Kecamatan</option>';
        filterKecamatan.disabled = true;
        filterDesa.innerHTML = '<option value="">Seluruh Desa</option>';
        filterDesa.disabled = true;

        if (idKab) {
            // Dropdown tetap pakai URL API
            isiDropdownWilayah(
                'filter-kecamatan',
                `https://webgis-st2023.web.bps.go.id/kecamatan?id_wilayah=${idKab}`,
                'master_kecamatan',
                'Kecamatan',
            );

            // 🔥 PECAH KODE BPS: "3519" -> kdprov="35", kdkab="19"
            const kdprov = idKab.substring(0, 2);
            const kdkab = idKab.substring(2, 4);

            // 🔥 RUMUS FILTER BARU: Cari Provinsi 35 DAN Kabupaten 19
            const cqlFilter = `kdprov='${kdprov}' AND kdkab='${kdkab}'`;

            muatPetaDariAPI(
                `https://geoserver.bps.go.id/st2023/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=st2023:batas_kecamatan&outputFormat=application/json&CQL_FILTER=${encodeURIComponent(cqlFilter)}`,
            );
        }
    });

    // 3. KECAMATAN BERUBAH -> Highlight Biru & Zoom (Gaya 100% BPS)
    filterKecamatan.addEventListener('change', function () {
        const idKec = this.value; // Contoh value: "3519110" (Wonoasri)
        filterDesa.innerHTML = '<option value="">Seluruh Desa</option>';
        filterDesa.disabled = true;

        if (idKec) {
            // 1. Tarik daftar nama Desa ke dropdown (Tabular)
            isiDropdownWilayah(
                'filter-desa',
                `https://webgis-st2023.web.bps.go.id/desa?id_wilayah=${idKec}`,
                'master_desa',
                'Desa',
            );

            // 2. LOGIKA HIGHLIGHT BIRU & ZOOM IN (MIMIC BPS)
            if (geojsonLayer) {
                let layerDitemukan = null;

                // Ambil 3 digit terakhir untuk kode kecamatan (contoh dari 3519110 jadi 110)
                const kodeKecamatanAsli = idKec.substring(4, 7);

                // Cari poligon kecamatan yang pas di peta yang sedang tampil
                geojsonLayer.eachLayer(function (layer) {
                    // Reset semua gaya kembali ke awal (warna putih) agar yang biru cuma 1
                    geojsonLayer.resetStyle(layer);

                    let props = layer.feature.properties;

                    // Cek kecocokan berdasarkan kdkec atau id_wilayah
                    if (
                        props.kdkec === kodeKecamatanAsli ||
                        props.id_wilayah === idKec
                    ) {
                        // KETEMU! Ubah garis tepinya jadi biru terang ala BPS
                        layer.setStyle({
                            color: '#007bff', // Warna Garis Tepi Biru BPS
                            weight: 4, // Ketebalan Garis
                            fillOpacity: 0.8, // Sedikit lebih pekat
                        });
                        layerDitemukan = layer;
                    }
                });

                // Jika ketemu poligonnya, suruh peta Zoom-in ke area tersebut
                if (layerDitemukan) {
                    map.fitBounds(layerDitemukan.getBounds());
                }
            }
        } else {
            // JIKA "Seluruh Kecamatan" DIPILIH KEMBALI:
            // Reset garis biru jadi putih lagi dan Zoom-out ke seluruh Kabupaten
            if (geojsonLayer) {
                geojsonLayer.eachLayer(function (layer) {
                    geojsonLayer.resetStyle(layer);
                });
                map.fitBounds(geojsonLayer.getBounds());
            }
        }
    });

    // 4. DESA BERUBAH -> Ambil Link Titik (WFS), Clustering, dan Custom Icon
    filterDesa.addEventListener('change', function () {
        const idDesa = this.value;
        markerLayer.clearLayers();

        if (idDesa) {
            const urlInfoTitik = `https://webgis-st2023.web.bps.go.id/geotagging?id_wilayah=${idDesa}&id_subsektor=1&jenis_service=WFS`;
            const proxyInfo =
                '../controllers/proxy_bps.php?url=' +
                encodeURIComponent(urlInfoTitik);

            fetch(proxyInfo)
                .then((res) => res.json())
                .then((data) => {
                    if (data.error) throw new Error(data.error);

                    let daftarInfo = data.peta || data.features || data;

                    if (Array.isArray(daftarInfo) && daftarInfo.length > 0) {
                        let linkAsliBPS = daftarInfo[0].url;

                        if (linkAsliBPS) {
                            linkAsliBPS = linkAsliBPS.replace(
                                'http://',
                                'https://',
                            );
                            const proxyPetaTitik =
                                '../controllers/proxy_bps.php?url=' +
                                encodeURIComponent(linkAsliBPS);

                            fetch(proxyPetaTitik)
                                .then((res2) => res2.json())
                                .then((geojsonData) => {
                                    if (
                                        geojsonData.features &&
                                        geojsonData.features.length > 0
                                    ) {
                                        // ----------------------------------------------------
                                        // MEMBUAT CUSTOM ICON ALA BPS (Lingkaran Putih, Border Orange, Icon Daun)
                                        // ----------------------------------------------------
                                        const ikonBPS = L.divIcon({
                                            className: 'custom-ikon-tani',
                                            html: `<div style="
                                                background-color: white; 
                                                border: 2px solid #ff5722; 
                                                border-radius: 50%; 
                                                width: 28px; 
                                                height: 28px; 
                                                display: flex; 
                                                justify-content: center; 
                                                align-items: center; 
                                                box-shadow: 0px 3px 6px rgba(0,0,0,0.4);
                                                font-size: 16px;">🌱</div>`,
                                            iconSize: [28, 28],
                                            iconAnchor: [14, 14], // Titik pusat icon
                                            popupAnchor: [0, -14], // Munculnya popup di atas icon
                                        });

                                        let points = L.geoJSON(geojsonData, {
                                            pointToLayer: function (
                                                feature,
                                                latlng,
                                            ) {
                                                // Gunakan L.marker biasa (bukan circleMarker) dan pasangkan ikonBPS
                                                return L.marker(latlng, {
                                                    icon: ikonBPS,
                                                });
                                            },
                                            onEachFeature: function (
                                                feature,
                                                layer,
                                            ) {
                                                let props = feature.properties;
                                                let namaPetani =
                                                    props.responden ||
                                                    props.nama_petani ||
                                                    props.nama ||
                                                    'Lokasi Lahan';
                                                layer.bindPopup(
                                                    `<b>${namaPetani}</b><br>Landmark: Titik Koordinat Sensus`,
                                                );
                                            },
                                        });

                                        markerLayer.addLayer(points);
                                        map.fitBounds(markerLayer.getBounds(), {
                                            padding: [30, 30],
                                            maxZoom: 16,
                                        });
                                    } else {
                                        console.warn(
                                            'Link titik (WFS) berhasil dibuka, tapi datanya kosong (0 petani).',
                                        );
                                    }
                                })
                                .catch((err) =>
                                    console.error(
                                        'Gagal membuka Link Peta Titik:',
                                        err,
                                    ),
                                );
                        }
                    } else {
                        console.warn('Tidak ada info titik di desa ini.');
                    }
                })
                .catch((err) =>
                    console.error('Gagal memuat info geotagging:', err),
                );
        }
    });

    // RESET
    btnReset.addEventListener('click', function () {
        filterProvinsi.value = '';
        filterKabupaten.innerHTML =
            '<option value="">Seluruh Kabupaten</option>';
        filterKabupaten.disabled = true;
        filterKecamatan.innerHTML =
            '<option value="">Seluruh Kecamatan</option>';
        filterKecamatan.disabled = true;
        filterDesa.innerHTML = '<option value="">Seluruh Desa</option>';
        filterDesa.disabled = true;
        muatPetaDariAPI('../assets/json/peta_petani_indonesia.json');
        setTimeout(() => map.setView([-2.5489, 118.0149], 5), 300);
    });
});
