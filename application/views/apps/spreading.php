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

</div>
<div class="container-fluid">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <form action="<?= $url ?>" method="get">

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="from">Periode 1</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from_1" id="from" min="2025-05-01" class="form-control" value="<?= $this->input->get('from_1') ?>" required>
                    <input type="date" name="to_1" id="to" min="2025-05-01" class="form-control" value="<?= $this->input->get('to_1') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-2">
                <label for="from">Periode 2</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from_2" id="from" min="2025-05-01" class="form-control" value="<?= $this->input->get('from_2') ?>" required>
                    <input type="date" name="to_2" id="to" min="2025-05-01" class="form-control" value="<?= $this->input->get('to_2') ?>" required>
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
                        <option value="<?= $user->username ?>"><?= $user->username ?></option>
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
                <!-- export -->
                <input type="submit" class="btn btn-submit-black" name="submit" value="export_1" style="height: 45px;">
                <input type="submit" class="btn btn-submit-black" name="submit" value="export_2" style="height: 45px;">
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>

    <?php if (is_object($get_data_1)) { ?>

    <div class="card-block mt-1 mb-5">
        <div class="row">

            <div class="col-md-6">
                <table id="tabel-periode-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="6">Periode 1</th>        
                        </tr>
                        <tr>       
                            <th class="text-center">username</th>         
                            <th class="text-center">toko</th>         
                            <th class="text-center">alamat</th>         
                            <th class="text-center">Avaibility</th>         
                            <th class="text-center">Transaksi</th>         
                            <th class="text-center">Image</th>         
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                            foreach ($get_data_1->result() as $a) : ?>        
                            <tr> 
                                <td><?= $a->username ?></td>   
                                <td><?= $a->nama_toko ?></td>   
                                <td><?= $a->city ?></td>   
                                <td></td>   
                                <td></td>   
                                <td>
                                    <?php 
                                    if ($a->image_before == null) 
                                    {
                                        echo "No Image";
                                    }else{ ?>
                                        <img src=<?= $a->image_before ?> alt<?= $a->nama_toko ?> style="width: 100px; height: 100px;">
                                    <?php
                                    } ?>
                                </td>  
                            </tr>
                            <?php 
                            endforeach; 
                        ?>   
                    </tbody>
                </table>

            </div>

            <div class="col-md-6">
                <table id="tabel-periode-2" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="6">Periode 2</th>        
                        </tr>
                        <tr>       
                            <th class="text-center">username</th>         
                            <th class="text-center">toko</th>         
                            <th class="text-center">alamat</th>         
                            <th class="text-center">Avaibility</th>         
                            <th class="text-center">Transaksi</th>    
                            <th class="text-center">Image</th>        
                        </tr>
                    </thead>
                    <tbody>     
                        <?php
                            foreach ($get_data_2->result() as $a) : ?>        
                            <tr> 
                                <td><?= $a->username ?></td>   
                                <td><?= $a->nama_toko ?></td>   
                                <td><?= $a->city ?></td>   
                                <td></td>   
                                <td></td>   
                                <td>
                                    <?php 
                                    if ($a->image_before == null) 
                                    {
                                        echo "No Image";
                                    }else{ ?>
                                        <img src=<?= $a->image_before ?> alt<?= $a->nama_toko ?> style="width: 100px; height: 100px;">
                                    <?php
                                    } ?>
                                </td>  
                            </tr>
                            <?php endforeach; 
                        ?>   
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
                foreach ($get_data_1->result() as $a) {
                    if (!in_array($a->city, $cities)) {
                        $cities[] = $a->city;
                    }
                }
                foreach ($get_data_2->result() as $a) {
                    if (!in_array($a->city, $cities)) {
                        $cities[] = $a->city;
                    }
                }
                sort($cities);
                foreach ($cities as $city) {
                    echo '<option value="' . $city . '">' . $city . '</option>';
                }
                ?>
            </select>
        </div>
        
        <!-- Maps container -->
        <div class="map-container">
            <div class="map-wrapper">
                <div class="map-title">Periode 1</div>
                <div id="map1" class="map"></div>
                <div id="map1-error" class="map-error">
                    <p>Gagal memuat peta. Pastikan koneksi internet Anda stabil.</p>
                    <button onclick="retryLoadMap(1)">Coba Muat Ulang</button>
                </div>
            </div>
            <div class="map-wrapper">
                <div class="map-title">Periode 2</div>
                <div id="map2" class="map"></div>
                <div id="map2-error" class="map-error">
                    <p>Gagal memuat peta. Pastikan koneksi internet Anda stabil.</p>
                    <button onclick="retryLoadMap(2)">Coba Muat Ulang</button>
                </div>
            </div>
        </div>
    </div>

    
    <?php } ?>

<?php 
// Prepare data for Period 1
$nama_toko_1 = array();
$latitude_1 = array();
$longitude_1 = array();
$city_1 = array();
if (is_object($get_data_1)) {
    foreach ($get_data_1->result() as $a) {
        $nama_toko_1[] = $a->nama_toko;
        $latitude_1[] = $a->latitude;   
        $longitude_1[] = $a->longitude;
        $city_1[] = $a->city;
    }
}

// Prepare data for Period 2
$nama_toko_2 = array();
$latitude_2 = array();
$longitude_2 = array();
$city_2 = array();
if (is_object($get_data_2)) {
    foreach ($get_data_2->result() as $a) {
        $nama_toko_2[] = $a->nama_toko;
        $latitude_2[] = $a->latitude;   
        $longitude_2[] = $a->longitude;
        $city_2[] = $a->city;
    }
}
?>

<script>
    // Global variables for map management
    let map1, map2;
    let markers1 = [], markers2 = [];
    let mapLoadAttempts = 0;
    const MAX_ATTEMPTS = 3;

    // Initialize maps after Google Maps API loads
    function initMaps() {
        // Initialize data for Period 1
        initMap(
            'map1', 
            <?php echo json_encode($latitude_1); ?>, 
            <?php echo json_encode($longitude_1); ?>, 
            <?php echo json_encode($nama_toko_1); ?>, 
            <?php echo json_encode($city_1); ?>,
            1
        );
        
        // Initialize data for Period 2
        initMap(
            'map2', 
            <?php echo json_encode($latitude_2); ?>, 
            <?php echo json_encode($longitude_2); ?>, 
            <?php echo json_encode($nama_toko_2); ?>, 
            <?php echo json_encode($city_2); ?>,
            2
        );
        
        // Sync maps (optional - for better comparison)
        syncMaps();
    }

    // Initialize a single map
    function initMap(mapId, latitudes, longitudes, names, cities, periodNum) {
        // Validate data
        if (!latitudes || !longitudes || latitudes.length === 0) {
            showMapError(new Error('No location data available for ' + mapId), periodNum);
            return;
        }

        // Calculate center of map (average of all coordinates)
        const centerLat = latitudes.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / latitudes.length;
        const centerLng = longitudes.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / longitudes.length;

        // Create map centered on average coordinates
        const mapObj = new google.maps.Map(document.getElementById(mapId), {
            zoom: 15,
            center: { lat: centerLat, lng: centerLng }
        });
        
        // Store map reference
        if (periodNum === 1) {
            map1 = mapObj;
        } else {
            map2 = mapObj;
        }

        // Create markers for each coordinate pair - keep original marker styling
        const markers = latitudes.map((lat, index) => {
            // Ensure we have a corresponding longitude
            if (longitudes[index] !== undefined) {
                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(lat), 
                        lng: parseFloat(longitudes[index]) 
                    },
                    map: mapObj,
                    title: names[index],
                    city: cities[index],
                    // Match original marker appearance from your code
                    animation: google.maps.Animation.DROP
                });

                // Add info window with additional details - matching original format
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div>
                            <strong>Location Details</strong><br>
                            Name: ${names[index]}<br>
                            City: ${cities[index]}<br>
                            Latitude: ${lat}<br>
                            Longitude: ${longitudes[index]}
                        </div>
                    `
                });

                // Add click event to show info window
                marker.addListener('click', () => {
                    infoWindow.open(mapObj, marker);
                });

                return marker;
            }
        }).filter(marker => marker !== undefined);

        // Store markers reference
        if (periodNum === 1) {
            markers1 = markers;
        } else {
            markers2 = markers;
        }

        // Adjust map to fit all markers
        const bounds = new google.maps.LatLngBounds();
        markers.forEach(marker => {
            bounds.extend(marker.getPosition());
        });
        mapObj.fitBounds(bounds);
        
        return { map: mapObj, markers: markers };
    }

    // Function to sync the two maps (optional - for better comparison)
    function syncMaps() {
        if (!map1 || !map2) return;
        
        // Each map fits its own bounds independently
        const adjustMap1Bounds = function() {
            if (markers1.length > 0) {
                const bounds1 = new google.maps.LatLngBounds();
                markers1.forEach(marker => {
                    if (marker.getMap()) { // Only include visible markers
                        bounds1.extend(marker.getPosition());
                    }
                });
                if (!bounds1.isEmpty()) {
                    map1.fitBounds(bounds1);
                }
            }
        };
        
        const adjustMap2Bounds = function() {
            if (markers2.length > 0) {
                const bounds2 = new google.maps.LatLngBounds();
                markers2.forEach(marker => {
                    if (marker.getMap()) { // Only include visible markers
                        bounds2.extend(marker.getPosition());
                    }
                });
                if (!bounds2.isEmpty()) {
                    map2.fitBounds(bounds2);
                }
            }
        };
        
        // Initial adjustment
        adjustMap1Bounds();
        adjustMap2Bounds();
    }

    // Function to filter markers by city
    function filterMarkersByCity(selectedCity) {
        if (!map1 || !map2 || !markers1 || !markers2) return;

        // Function to filter markers for a specific map
        function filterMapMarkers(markers, map) {
            // If no city selected, show all markers
            if (!selectedCity) {
                markers.forEach(marker => marker.setMap(map));
                return markers;
            }

            // Filter and show/hide markers
            return markers.filter(marker => {
                const isVisible = marker.city.toLowerCase() === selectedCity.toLowerCase();
                marker.setMap(isVisible ? map : null);
                return isVisible;
            });
        }

        // Filter markers for both maps
        const visibleMarkers1 = filterMapMarkers(markers1, map1);
        const visibleMarkers2 = filterMapMarkers(markers2, map2);
        
        // If markers found in either map, adjust bounds
        if (visibleMarkers1.length > 0 || visibleMarkers2.length > 0) {
            // Process map 1 bounds if it has visible markers
            if (visibleMarkers1.length > 0) {
                const bounds1 = new google.maps.LatLngBounds();
                visibleMarkers1.forEach(marker => {
                    bounds1.extend(marker.getPosition());
                });
                if (!bounds1.isEmpty()) {
                    map1.fitBounds(bounds1);
                }
            }
            
            // Process map 2 bounds if it has visible markers
            if (visibleMarkers2.length > 0) {
                const bounds2 = new google.maps.LatLngBounds();
                visibleMarkers2.forEach(marker => {
                    bounds2.extend(marker.getPosition());
                });
                if (!bounds2.isEmpty()) {
                    map2.fitBounds(bounds2);
                }
            }
        }
    }

    // Add event listener for city filter
    document.addEventListener('DOMContentLoaded', function() {
        // Get unique cities from the tables
        const cities = [];
        
        // Process period 1 cities
        document.querySelectorAll('#tabel-periode-1 tbody tr').forEach(function(row) {
            const city = row.cells[2].textContent.trim();
            if (city && !cities.includes(city)) {
                cities.push(city);
            }
        });
        
        // Process period 2 cities
        document.querySelectorAll('#tabel-periode-2 tbody tr').forEach(function(row) {
            const city = row.cells[2].textContent.trim();
            if (city && !cities.includes(city)) {
                cities.push(city);
            }
        });
        
        // Sort cities alphabetically
        cities.sort();
        
        // Create city filter dropdown
        const cityFilterContainer = document.getElementById('city-filter-container');
        
        // Create label
        const cityLabel = document.createElement('label');
        cityLabel.setAttribute('for', 'city-filter');
        cityLabel.className = 'city-filter-label';
        cityLabel.textContent = 'Filter by City:';
        
        // Create select element
        const citySelect = document.createElement('select');
        citySelect.id = 'city-filter';
        citySelect.className = 'form-control';
        
        // Add "All Cities" option
        const allOption = document.createElement('option');
        allOption.value = '';
        allOption.textContent = 'All Cities';
        citySelect.appendChild(allOption);
        
        // Add city options
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
        
        // Assemble the filter
        cityFilterContainer.appendChild(cityLabel);
        cityFilterContainer.appendChild(citySelect);
        
        // Add change event listener
        citySelect.addEventListener('change', function() {
            const selectedCity = this.value;
            filterMarkersByCity(selectedCity);
        });
    });

    // Load Google Maps API script
    function loadMapScript() {
        if (mapLoadAttempts >= MAX_ATTEMPTS) {
            showMapError(new Error('Maksimum percobaan muat script telah tercapai'), 1);
            showMapError(new Error('Maksimum percobaan muat script telah tercapai'), 2);
            return;
        }

        mapLoadAttempts++;
        
        // Remove old script if exists
        const oldScript = document.querySelector('script[src^="https://maps.googleapis.com"]');
        if (oldScript) {
            oldScript.remove();
        }

        // Create new script
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDfhFof3DuTaNIj_GcMySd4VzosG_agK1U&callback=initMaps`;
        script.async = true;
        script.defer = true;
        
        // Handle script error
        script.onerror = () => {
            showMapError(new Error('Gagal memuat script Google Maps'), 1);
            showMapError(new Error('Gagal memuat script Google Maps'), 2);
        };

        document.body.appendChild(script);
    }

    // Show error message for specific map
    function showMapError(error, mapNum) {
        console.error(`Map ${mapNum} Error:`, error);
        document.getElementById(`map${mapNum}`).style.display = 'none';
        document.getElementById(`map${mapNum}-error`).style.display = 'block';
    }

    // Retry loading specific map
    function retryLoadMap(mapNum) {
        document.getElementById(`map${mapNum}-error`).style.display = 'none';
        document.getElementById(`map${mapNum}`).style.display = 'block';
        
        // If both maps need to reload, load the script
        if ((mapNum === 1 && document.getElementById('map2-error').style.display === 'none') ||
            (mapNum === 2 && document.getElementById('map1-error').style.display === 'none')) {
            loadMapScript();
        }
    }

    // Load maps on page load
    window.addEventListener('DOMContentLoaded', loadMapScript);
    
    // Add event listener for reload if connection is lost
    window.addEventListener('online', function() {
        document.getElementById('map1-error').style.display = 'none';
        document.getElementById('map1').style.display = 'block';
        document.getElementById('map2-error').style.display = 'none';
        document.getElementById('map2').style.display = 'block';
        loadMapScript();
    });
</script>

</body>
</html>