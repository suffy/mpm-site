<style>
    .map-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }
    .map-wrapper {
        flex: 1;
        min-width: 300px;
    }
    .map {
        height: 500px;
        width: 100%;
    }
    .map-error {
        display: none;
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        text-align: center;
    }
    .map-title {
        font-weight: 600;
        text-align: center;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    .city-filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .city-filter-container .form-control {
        max-width: 300px;
        margin-right: 10px;
    }
    .city-filter-label {
        margin-right: 10px;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .city-filter-container {
            flex-direction: column;
            align-items: stretch;
        }
        .city-filter-container .form-control {
            margin-bottom: 10px;
        }
    }
</style>

<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <form action="<?= $url ?>" method="get">

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="from">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" min="2025-05-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" id="to" min="2025-05-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="user">User</label> 
            </div>
            <div class="col-md-4">
                <select name="user" id="user" class="form-control">
                    <option value="all">All</option>
                    <?php foreach ($users->result() as $user) { ?>
                        <option value="<?= $user->username ?>" <?= ($this->input->get('user') == $user->username) ? 'selected' : '' ?>><?= $user->username ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp"></label> 
            </div>
            <div class="col-md-10">
                <input type="submit" class="btn btn-submit-red" name="submit" value="search" style="height: 45px;">  
                <input type="submit" class="btn btn-submit-black" name="submit" value="export" style="height: 45px;">          
                <input type="submit" class="btn btn-submit-black" name="submit" value="export_pdf" style="height: 45px;">
            </div>
        </div>
        </form>

    </div>

    <?php if (is_object($get_data)) { ?>

    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">
                <table id="tabel" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>       
                            <th class="text-center">Username</th>         
                            <th class="text-center">Pasar</th>         
                            <th class="text-center">Kecamatan</th>         
                            <th class="text-center">Toko</th>         
                            <th class="text-center">Posisi</th>         
                            <th class="text-center">Class</th>         
                            <th class="text-center">Tipe</th>         
                            <th class="text-center">StatusOB</th>         
                            <th class="text-center">availability_obat_masuk_angin</th>         
                            <th class="text-center">availability_obat_batuk_sachet</th>         
                            <th class="text-center">availability_obat_masuk_angin_anak</th>         
                            <th class="text-center">availability_obat_pegel_linu</th>         
                            <th class="text-center">availability_permen</th>         
                            <th class="text-center">Foto Toko</th>         
                            <th class="text-center">Foto Produk</th>         
                            <th class="text-center">Foto Branding</th>         
                        </tr>
                    </thead>
                    <tbody>     
                        <?php foreach ($get_data->result() as $a) : ?>        
                            <tr> 
                                <td><?= $a->username ?></td>   
                                <td><?= $a->nama_pasar ?></td>   
                                <td><?= $a->nama_kecamatan ?></td>   
                                <td><?= $a->nama_toko ?></td>   
                                <td><?= $a->posisi_toko ?></td>   
                                <td><?= $a->class_toko ?></td>   
                                <td><?= $a->tipe_toko ?></td>   
                                <td><?= $a->status_ob ?></td>   
                                <td>
                                    <?php
                                        $produk = json_decode($a->availability_obat_masuk_angin);
                                        if(empty($produk)) {
                                            $produk = [];
                                        }
                                        echo implode(", ", $produk);
                                    ?>
                                </td>   
                                <td>
                                    <?php
                                        $produk = json_decode($a->availability_obat_batuk_sachet);
                                        if(empty($produk)) {
                                            $produk = [];
                                        }
                                        echo implode(", ", $produk);
                                    ?>
                                </td>   
                                <td>
                                    <?php
                                        $produk = json_decode($a->availability_obat_masuk_angin_anak);
                                        if(empty($produk)) {
                                            $produk = [];
                                        }
                                        echo implode(", ", $produk);
                                    ?>
                                </td>   
                                <td>
                                    <?php
                                        $produk = json_decode($a->availability_obat_pegel_linu);
                                        if(empty($produk)) {
                                            $produk = [];
                                        }
                                        echo implode(", ", $produk);
                                    ?>                                
                                </td> 
                                <td>
                                    <?php
                                        $produk = json_decode($a->availability_permen);
                                        if(empty($produk)) {
                                            $produk = [];
                                        }
                                        echo implode(", ", $produk);
                                    ?>                                
                                </td>   
                                <td>
                                    <span id="no-image-toko-<?= $a->id ?>">No Image</span>
                                    <!-- Gunakan data-src instead of src -->
                                    <img data-src="<?= $a->foto_toko ?>" alt="<?= $a->nama_toko ?>" 
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; display: none" 
                                        id="image-toko-<?= $a->id ?>">
                                    <button onclick="loadAndShowImage('toko', <?= $a->id ?>)" id="show-image-toko-<?= $a->id ?>">Tampilkan Gambar Toko</button>
                                </td>
                                <td>
                                    <span id="no-image-produk-<?= $a->id ?>">No Image</span>
                                    <img data-src="<?= $a->foto_display_produk ?>" alt="<?= $a->nama_toko ?>" 
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; display: none" 
                                        id="image-produk-<?= $a->id ?>">
                                    <button onclick="loadAndShowImage('produk', <?= $a->id ?>)" id="show-image-produk-<?= $a->id ?>">Tampilkan Gambar Produk</button>
                                </td>
                                <td>
                                    <span id="no-image-branding-<?= $a->id ?>">No Image</span>
                                    <img data-src="<?= $a->foto_branding ?>" alt="<?= $a->nama_toko ?>" 
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; display: none" 
                                        id="image-branding-<?= $a->id ?>">
                                    <button onclick="loadAndShowImage('branding', <?= $a->id ?>)" id="show-image-branding-<?= $a->id ?>">Tampilkan Gambar Branding</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- City filter container -->
        <div class="city-filter-container">
            <label for="city-filter" class="city-filter-label">Filter by City:</label>
            <select id="city-filter" class="form-control">
                <option value="">All Cities</option>
                <?php
                $cities = array();
                
                // Collect cities from data
                if (is_object($get_data)) {
                    foreach ($get_data->result() as $a) {
                        if (isset($a->city) && !in_array($a->city, $cities)) {
                            $cities[] = $a->city;
                        }
                    }
                }
                
                sort($cities);
                foreach ($cities as $city) {
                    echo '<option value="' . htmlspecialchars($city) . '">' . htmlspecialchars($city) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <!-- Map container -->
        <div class="map-container">
            <div class="map-wrapper">
                <div class="map-title">Peta Lokasi</div>
                <div id="map" class="map"></div>
                <div id="map-error" class="map-error">
                    <p>Gagal memuat peta. Pastikan koneksi internet Anda stabil.</p>
                    <button onclick="retryLoadMap()" class="btn btn-primary">Coba Muat Ulang</button>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>
</div>

<?php 
// Prepare data for the map
$nama_toko = array();
$latitude = array();
$longitude = array();
$city = array();

if (is_object($get_data)) {
    foreach ($get_data->result() as $a) {
        if (isset($a->nama_toko) && isset($a->latitude) && isset($a->longitude)) {
            $nama_toko[] = $a->nama_toko;
            $latitude[] = floatval($a->latitude);   
            $longitude[] = floatval($a->longitude);
            $city[] = isset($a->city) ? $a->city : 'Unknown';
        }
    }
}
?>

<script>
function loadAndShowImage(type, id) {
    console.log('Memuat gambar:', type, 'dengan ID:', id);
    
    var imageElement = document.getElementById('image-' + type + '-' + id);
    var noImageElement = document.getElementById('no-image-' + type + '-' + id);
    var buttonElement = document.getElementById('show-image-' + type + '-' + id);
    
    if (imageElement && noImageElement && buttonElement) {
        // Sembunyikan teks "No Image"
        noImageElement.style.display = 'none';
        
        // Sembunyikan tombol sementara
        buttonElement.textContent = 'Loading...';
        buttonElement.disabled = true;
        
        // Load gambar hanya ketika tombol diklik
        var imageUrl = imageElement.getAttribute('data-src');
        var img = new Image();
        
        img.onload = function() {
            // Set src setelah gambar selesai dimuat
            imageElement.src = imageUrl;
            imageElement.style.display = 'block';
            
            // Sembunyikan tombol setelah berhasil
            buttonElement.style.display = 'none';
        };
        
        img.onerror = function() {
            // Jika gagal load, tampilkan pesan error
            noImageElement.textContent = 'Error loading image';
            noImageElement.style.display = 'inline';
            noImageElement.style.color = 'red';
            
            // Reset tombol
            buttonElement.textContent = 'Tampilkan Gambar';
            buttonElement.disabled = false;
        };
        
        // Mulai memuat gambar
        img.src = imageUrl;
    }
}
</script>

<script>
    // Global variables for map management
    let map;
    let markers = [];
    let mapLoadAttempts = 0;
    const MAX_ATTEMPTS = 3;

    // Initialize map after Google Maps API loads
    function initMaps() {
        try {
            // Initialize map data
            const mapData = {
                latitudes: <?php echo json_encode($latitude); ?>,
                longitudes: <?php echo json_encode($longitude); ?>,
                names: <?php echo json_encode($nama_toko); ?>,
                cities: <?php echo json_encode($city); ?>
            };

            if (mapData.latitudes && mapData.latitudes.length > 0) {
                initMap('map', mapData);
            } else {
                showMapError(new Error('No data available for map'));
            }

        } catch (error) {
            console.error('Error initializing map:', error);
            showMapError(error);
        }
    }

    // Initialize the map
    function initMap(mapId, data) {
        try {
            const { latitudes, longitudes, names, cities } = data;

            // Validate data
            if (!latitudes || !longitudes || latitudes.length === 0) {
                throw new Error(`No location data available for ${mapId}`);
            }

            // Filter out invalid coordinates
            const validCoords = [];
            for (let i = 0; i < latitudes.length; i++) {
                const lat = parseFloat(latitudes[i]);
                const lng = parseFloat(longitudes[i]);
                
                if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                    validCoords.push({
                        lat: lat,
                        lng: lng,
                        name: names[i] || 'Unknown Location',
                        city: cities[i] || 'Unknown City'
                    });
                }
            }

            if (validCoords.length === 0) {
                throw new Error('No valid coordinates found');
            }

            // Calculate center of map
            const centerLat = validCoords.reduce((sum, coord) => sum + coord.lat, 0) / validCoords.length;
            const centerLng = validCoords.reduce((sum, coord) => sum + coord.lng, 0) / validCoords.length;

            // Create map
            map = new google.maps.Map(document.getElementById(mapId), {
                zoom: 10,
                center: { lat: centerLat, lng: centerLng },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            // Create markers
            markers = validCoords.map((coord, index) => {
                const marker = new google.maps.Marker({
                    position: { lat: coord.lat, lng: coord.lng },
                    map: map,
                    title: coord.name,
                    city: coord.city,
                    animation: google.maps.Animation.DROP
                });

                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px;">
                            <h6><strong>${coord.name}</strong></h6>
                            <p><strong>City:</strong> ${coord.city}</p>
                            <p><strong>Coordinates:</strong> ${coord.lat.toFixed(6)}, ${coord.lng.toFixed(6)}</p>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    // Close other info windows
                    markers.forEach(m => {
                        if (m.infoWindow) m.infoWindow.close();
                    });
                    
                    infoWindow.open(map, marker);
                });

                marker.infoWindow = infoWindow;
                return marker;
            });

            // Fit map to show all markers
            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(marker => {
                    bounds.extend(marker.getPosition());
                });
                map.fitBounds(bounds);
                
                // Ensure minimum zoom level
                google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                    if (map.getZoom() > 15) {
                        map.setZoom(15);
                    }
                });
            }

            console.log(`Map initialized successfully with ${markers.length} markers`);

        } catch (error) {
            console.error('Error initializing map:', error);
            showMapError(error);
        }
    }

    // Function to filter markers by city
    function filterMarkersByCity(selectedCity) {
        if (!markers || !map) return;

        const visibleMarkers = [];
        
        markers.forEach(marker => {
            const isVisible = !selectedCity || marker.city.toLowerCase().includes(selectedCity.toLowerCase());
            marker.setMap(isVisible ? map : null);
            if (isVisible) {
                visibleMarkers.push(marker);
            }
        });

        // Adjust bounds if there are visible markers
        if (visibleMarkers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            visibleMarkers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            map.fitBounds(bounds);
            
            // Ensure minimum zoom level
            google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                if (map.getZoom() > 15) {
                    map.setZoom(15);
                }
            });
        }
    }

    // Show error message
    function showMapError(error) {
        console.error('Map Error:', error);
        const mapElement = document.getElementById('map');
        const errorElement = document.getElementById('map-error');
        
        if (mapElement) mapElement.style.display = 'none';
        if (errorElement) errorElement.style.display = 'block';
    }

    // Retry loading map
    function retryLoadMap() {
        const mapElement = document.getElementById('map');
        const errorElement = document.getElementById('map-error');
        
        if (mapElement) mapElement.style.display = 'block';
        if (errorElement) errorElement.style.display = 'none';
        
        // Reload the entire script
        loadMapScript();
    }

    // Load Google Maps API script
    function loadMapScript() {
        if (mapLoadAttempts >= MAX_ATTEMPTS) {
            showMapError(new Error('Maksimum percobaan muat script telah tercapai'));
            return;
        }

        mapLoadAttempts++;

        // Remove old script if exists
        const oldScript = document.querySelector('script[src*="maps.googleapis.com"]');
        if (oldScript) {
            oldScript.remove();
        }

        // Create new script
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDfhFof3DuTaNIj_GcMySd4VzosG_agK1U&callback=initMaps&libraries=geometry`;
        script.async = true;
        script.defer = true;

        script.onerror = () => {
            console.error('Failed to load Google Maps script');
            showMapError(new Error('Gagal memuat script Google Maps'));
        };

        script.onload = () => {
            console.log('Google Maps script loaded successfully');
        };

        document.head.appendChild(script);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add city filter event listener
        const cityFilter = document.getElementById('city-filter');
        if (cityFilter) {
            cityFilter.addEventListener('change', function() {
                filterMarkersByCity(this.value);
            });
        }

        // Load map
        loadMapScript();
    });

    // Reload map when connection is restored
    window.addEventListener('online', function() {
        location.reload();
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });
</script>