@extends('layouts.master')
@section('title')
    {{ __('admin.control_panel') }}
@endsection
@section('css')
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 300px);
            min-height: 500px;
            border-radius: 8px;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.control_panel') }}
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.map') }} !
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('admin.map') }}</h5>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let map;
        let markers = [];
        const icons = {
            0: "{{ URL::asset('assets/img/order-marker.png') }}",
            1: "{{ URL::asset('assets/img/order-marker.png') }}",
            2: "{{ URL::asset('assets/img/worker-marker.png') }}"
        };

        function initMap() {
            const makkah = {
                lat: 21.38069,
                lng: 39.82235
            };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 11.4,
                center: makkah,
            });

            fetchLocations();
            setInterval(fetchLocations, 30000);
        }

        function fetchLocations() {
            fetch("{{ route('services_app.admin.maps.locations') }}")
                .then(res => res.json())
                .then(data => {
                    clearMarkers();

                    data.forEach(location => {
                        const marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(location.latitude),
                                lng: parseFloat(location.longitude)
                            },
                            map: map,
                            icon: {
                                url: icons[location.type],
                                scaledSize: new google.maps.Size(40, 40),
                            }
                        });

                        let typeLabel = location.type == 0 ?
                            "{{ __('admin.order_start_point') }}" :
                            location.type == 1 ?
                            "{{ __('admin.order_end_point') }}" :
                            "{{ __('admin.worker_location') }}";

                        let showOrderUrl = "{{ env('APP_URL') . '/dashboard/orders/show/' }}";
                        let nameContainer = (location.type < 2) ? `
                                <p style="margin:0;"><a href="${showOrderUrl + location.id}" target="_blanck">${location.name ?? ''}</a></p>
                            ` : `
                                <p style="margin:0;">${location.name ?? ''}</p>
                            `;

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="min-width:180px; padding:5px;">
                                    <h6 style="margin:0;"><strong>${typeLabel}</strong></h6>
                            ` + nameContainer + `
                                </div>
                            `
                        });

                        marker.addListener("click", () => {
                            infoWindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                });
        }

        function clearMarkers() {
            markers.forEach(m => m.setMap(null));
            markers = [];
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=geometry,places&callback=initMap"
        async defer></script>
@endsection
