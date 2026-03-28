@extends('layouts.master')

@section('title')
    {{ __('admin.region_coordinates') }}
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('admin.region_coordinates_change') }}
        @endslot
        @slot('title')
            {{ __('admin.region_coordinates') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ __('admin.region_coordinates_change') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('services_app.admin.settings.regions.update') }}">
                        @csrf
                        @include('layouts.session')
                        @method('PUT')
                        <div id="map" style="height:450px"></div>
                        <input type="hidden" name="coordinates" id="coordinates">
                        <button class="btn btn-primary mt-3">{{ __('admin.change') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

    <script>
        const map = L.map('map').setView([24.7136, 46.6753], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        const drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                rectangle: true,
                circle: false,
                marker: false,
                polyline: false
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        // ===== Load existing region (EDIT MODE) =====
        @isset($region)
            const existingPolygon = L.polygon(@json($region->coordinates));
            drawnItems.addLayer(existingPolygon);
            map.fitBounds(existingPolygon.getBounds());

            document.getElementById('coordinates').value =
                JSON.stringify(@json($region->coordinates));
        @endisset

        // ===== CREATE polygon =====
        map.on(L.Draw.Event.CREATED, function(e) {
            drawnItems.clearLayers();
            drawnItems.addLayer(e.layer);

            updateCoordinates(e.layer);
        });

        // ===== EDIT polygon =====
        map.on(L.Draw.Event.EDITED, function(e) {
            e.layers.eachLayer(function(layer) {
                updateCoordinates(layer);
            });
        });

        // ===== Helper function =====
        function updateCoordinates(layer) {
            const latLngs = layer.getLatLngs()[0].map(p => [
                p.lat,
                p.lng
            ]);

            document.getElementById('coordinates').value =
                JSON.stringify(latLngs);
        }
    </script>
@endsection
