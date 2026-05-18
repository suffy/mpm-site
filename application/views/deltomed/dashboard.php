<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- <?php $this->load->view('management_claim/css/style') ?> -->

 <style>
    #map {
        height: 500px;
        width: 100%;
    }
    #map-error {
        display: none;
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        text-align: center;
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
        
        <!-- <form action="<?= $url ?>">     -->
        <form action="<?= base_url().$url ?>">    

        <div class="row mt-5">
            <div class="col-md-2">
                <label for="from">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" min="2023-12-01" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" id="to" min="2023-12-01" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp"></label> 
            </div>
            <div class="col-md-10">
                <input type="submit" class="btn btn-submit-red" name="submit" value="search" style="height: 45px;">    
                <input type="submit" class="btn btn-submit" name="submit" value="export" style="height: 45px;">    
                <input type="submit" class="btn btn-submit" name="submit" value="export_by_products" style="height: 45px;">    
                <!-- <a href="<?= base_url().'deltomed/export_spreading' ?>" class="btn btn-submit-black" style="height: 45px; padding-top: 10px;">download raw data</a>         -->
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>

    <div class="card-body">    
        <div id="map"></div>
        <div id="map-error">
            <p>Gagal memuat peta. Pastikan koneksi internet Anda stabil.</p>
            <button onclick="retryLoadMap()">Coba Muat Ulang</button>
        </div>
    </div>


    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">
                <!-- <table id="tabel-ajuan" class="display table-striped table-bordered" style="display: inline-block; overflow-y: scroll; width: 100%;"> -->
                <!-- <table id="tabel-registrasi-new"> -->
                <table id="tabel-ajuan-claim" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">no</th>         
                            <th class="text-center">username</th>         
                            <th class="text-center">nama toko</th>         
                            <!-- <th class="text-center">latitude</th>          -->
                            <!-- <th class="text-center">longitude</th>          -->
                            <th class="text-center">city</th>         
                            <th class="text-center">district</th>         
                            <th class="text-center">address</th>         
                            <th class="text-center">name address</th>         
                            <th class="text-center">postal code</th>         
                            <th class="text-center">region</th>      
                            <th class="text-center">Total Value</th>   
                            <th class="text-center">created at</th>         
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_data->result() as $a) : ?>        
                        <tr>
                            <td><?= $no++ ?></td>   
                            <td><?= $a->name ?></td>   
                            <td><?= $a->nama_toko ?></td>   
                            <!-- <td><?= $a->latitude ?></td>    -->
                            <!-- <td><?= $a->longitude ?></td>    -->
                            <td><?= $a->city ?></td>   
                            <td><?= $a->district ?></td>   
                            <td><?= $a->formatted_address ?></td>   
                            <td><?= $a->name_address ?></td>   
                            <td><?= $a->postal_code ?></td>   
                            <td><?= $a->region ?></td>   
                            <td><?= $a->total_value ?></td>   
                            <td><?= $a->created_at ?></td>   
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    
<?php 
    foreach ($get_data->result() as $a) {
        $nama_toko[] = $a->nama_toko;
        $latitude[] = $a->latitude;   
        $longitude[] = $a->longitude;
    }
    // echo json_encode($latitude);
    // echo json_encode($longitude);
?>


</div>

<!-- <script>
    let mapLoadAttempts = 0;
    const MAX_ATTEMPTS = 3;

    function initMap() {
    // Parse latitude and longitude from PHP-generated JSON
    const latitudes = <?php echo json_encode($latitude); ?>;
    const longitudes = <?php echo json_encode($longitude); ?>;
    const names = <?php echo json_encode($nama_toko); ?>;

    // Validate data
    if (!latitudes || !longitudes || latitudes.length === 0) {
        showMapError(new Error('No location data available'));
        return;
    }

    // Calculate center of map (average of all coordinates)
    const centerLat = latitudes.reduce((a, b) => a + b, 0) / latitudes.length;
    const centerLng = longitudes.reduce((a, b) => a + b, 0) / longitudes.length;

    // Create map centered on average coordinates
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15, // Adjusted for multiple markers
        center: { lat: centerLat, lng: centerLng }
    });

    // Create markers for each coordinate pair
    latitudes.forEach((lat, index) => {
        // Ensure we have a corresponding longitude
        if (longitudes[index] !== undefined) {
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(lat), 
                    lng: parseFloat(longitudes[index]) 
                },
                map: map,
                // title: `Location ${index + 1}` // Optional: you can customize this
                title: names[index]
            });

            // Optional: Add info window with additional details
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <strong>Location Details</strong><br>
                        Latitude: ${lat}<br>
                        Longitude: ${longitudes[index]}
                    </div>
                `
            });

            // Add click event to show info window
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }
    });

    // Adjust map to fit all markers
    const bounds = new google.maps.LatLngBounds();
    latitudes.forEach((lat, index) => {
        bounds.extend(new google.maps.LatLng(
            parseFloat(lat), 
            parseFloat(longitudes[index])
        ));
    });
    map.fitBounds(bounds);
}

    function loadMapScript() {
        if (mapLoadAttempts >= MAX_ATTEMPTS) {
            showMapError(new Error('Maksimum percobaan muat script telah tercapai'));
            return;
        }

        mapLoadAttempts++;
        
        // Hapus script lama jika ada
        const oldScript = document.querySelector('script[src^="https://maps.googleapis.com"]');
        if (oldScript) {
            oldScript.remove();
        }

        // Buat script baru
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDfhFof3DuTaNIj_GcMySd4VzosG_agK1U&callback=initMap`;
        script.async = true;
        script.defer = true;
        
        // Tangani error script
        script.onerror = () => {
            showMapError(new Error('Gagal memuat script Google Maps'));
        };

        document.body.appendChild(script);
    }

    function showMapError(error) {
        console.error('Map Error:', error);
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-error').style.display = 'block';
    }

    function retryLoadMap() {
        document.getElementById('map-error').style.display = 'none';
        document.getElementById('map').style.display = 'block';
        loadMapScript();
    }

    // Mulai proses pemuatan
    loadMapScript();

    // Tambahkan event listener untuk reload ulang jika koneksi terputus
    window.addEventListener('online', retryLoadMap);
</script> -->

<script>
    let allMarkers = []; // Store all markers globally
    let map; // Global map variable

     function initMap() {
        // Parse latitude and longitude from PHP-generated JSON
        const latitudes = <?php echo json_encode($latitude); ?>;
        const longitudes = <?php echo json_encode($longitude); ?>;
        const names = <?php echo json_encode($nama_toko); ?>;
        const cities = <?php echo json_encode(array_column($get_data->result_array(), 'city')); ?>;

        // Validate data
        if (!latitudes || !longitudes || latitudes.length === 0) {
            showMapError(new Error('No location data available'));
            return;
        }

        // Calculate center of map (average of all coordinates)
        const centerLat = latitudes.reduce((a, b) => a + b, 0) / latitudes.length;
        const centerLng = longitudes.reduce((a, b) => a + b, 0) / longitudes.length;

        // Create map centered on average coordinates
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15, // Adjusted for multiple markers
            center: { lat: centerLat, lng: centerLng }
        });

        // Create markers for each coordinate pair
        allMarkers = latitudes.map((lat, index) => {
            // Ensure we have a corresponding longitude
            if (longitudes[index] !== undefined) {
                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(lat), 
                        lng: parseFloat(longitudes[index]) 
                    },
                    map: map,
                    title: names[index],
                    city: cities[index] // Add city information to marker
                });

                // Optional: Add info window with additional details
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
                    infoWindow.open(map, marker);
                });

                return marker;
            }
        }).filter(marker => marker !== undefined); // Remove any undefined markers

        // Adjust map to fit all markers
        const bounds = new google.maps.LatLngBounds();
        allMarkers.forEach(marker => {
            bounds.extend(marker.getPosition());
        });
        map.fitBounds(bounds);
    }

     // Function to filter markers by city
    function filterMarkersByCity(selectedCity) {
        if (!map || !allMarkers) return;

        // If no city selected, show all markers
        if (!selectedCity) {
            allMarkers.forEach(marker => marker.setMap(map));
            
            // Readjust map bounds to all markers
            const bounds = new google.maps.LatLngBounds();
            allMarkers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            map.fitBounds(bounds);
            return;
        }

        // Filter and show/hide markers
        const visibleMarkers = allMarkers.filter(marker => {
            const isVisible = marker.city.toLowerCase() === selectedCity.toLowerCase();
            marker.setMap(isVisible ? map : null);
            return isVisible;
        });

        // If markers found, center and fit bounds to visible markers
        if (visibleMarkers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            visibleMarkers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            map.fitBounds(bounds);
        }
    }

     // Add city filter dropdown to the form
    $(document).ready(function() {
        // Get unique cities from the table
        const cities = [];
        $('#tabel-ajuan-claim tbody tr').each(function() {
            const city = $(this).find('td:nth-child(4)').text().trim();
            if (city && !cities.includes(city)) {
                cities.push(city);
            }
        });

        // Create city filter container
        const cityFilterContainer = $('<div>', {
            class: 'city-filter-container'
        });

        // Create label
        const cityLabel = $('<label>', {
            for: 'city-filter',
            class: 'city-filter-label',
            text: 'Filter by City:'
        });

        // Create city filter dropdown
        const citySelect = $('<select>', {
            id: 'city-filter',
            class: 'form-control'
        }).append($('<option>', {
            value: '',
            text: 'All Cities'
        }));

        cities.sort().forEach(city => {
            citySelect.append($('<option>', {
                value: city,
                text: city
            }));
        });

        // Assemble the container
        cityFilterContainer.append(cityLabel, citySelect);

        // Insert city filter container right before the map
        $('#map').before(cityFilterContainer);

        // Add event listener for city filter
        $('#city-filter').on('change', function() {
            const selectedCity = $(this).val();
            filterMarkersByCity(selectedCity);
        });
    });

    let mapLoadAttempts = 0;
    const MAX_ATTEMPTS = 3;

    function loadMapScript() {
        if (mapLoadAttempts >= MAX_ATTEMPTS) {
            showMapError(new Error('Maksimum percobaan muat script telah tercapai'));
            return;
        }

        mapLoadAttempts++;
        
        // Hapus script lama jika ada
        const oldScript = document.querySelector('script[src^="https://maps.googleapis.com"]');
        if (oldScript) {
            oldScript.remove();
        }

        // Buat script baru
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDfhFof3DuTaNIj_GcMySd4VzosG_agK1U&callback=initMap`;
        script.async = true;
        script.defer = true;
        
        // Tangani error script
        script.onerror = () => {
            showMapError(new Error('Gagal memuat script Google Maps'));
        };

        document.body.appendChild(script);
    }

    function showMapError(error) {
        console.error('Map Error:', error);
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-error').style.display = 'block';
    }

    function retryLoadMap() {
        document.getElementById('map-error').style.display = 'none';
        document.getElementById('map').style.display = 'block';
        loadMapScript();
    }

    // Mulai proses pemuatan
    loadMapScript();

    // Tambahkan event listener untuk reload ulang jika koneksi terputus
    window.addEventListener('online', retryLoadMap);
</script>

<script>
    $(document).ready(function () {
        $("#btnBack").show();
        $("#btnLoading").hide();
        $('#tabel-ajuan-claim').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'asc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            scrollX: true,
        });
    });

    $.ajax({ 
        type: 'POST',
        url: '<?php echo base_url('management_claim/master_kategori') ?>',
        success: function(result) {
            $("select[name = kategori]").html(result);
        }
    });
</script>

</body>
</html>