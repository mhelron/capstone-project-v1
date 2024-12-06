@extends('layouts.guestlayout')

@section('content')

@vite('resources/css/guestcalendar.css')

<style>
    .reservation-count {
        font-size: 0.8em;
        color: #666;
        margin-left: 5px;
    }

    .fully-booked {
        background-color: #ffebee !important;
        cursor: not-allowed !important;
    }

    
</style>

<div class="container" style="padding-top: 100px;">
    <div class="row">
        <div class="col-md-12">
            <div id='calendar'></div>
        </div>
    </div>
</div>

@vite('resources/js/guestcalendar.js')

<script>
    // Define the events array
    var events = [];

    <?php 
        foreach($reservations as $reservation){
            echo 'events.push({"Event": "Reserved", 
            "Date": "'. addslashes($reservation['event_date']) .'",
            "Status": "'. addslashes($reservation['status']) .'"});';
        }
    ?>

    var packages = [];

    <?php 
        foreach($packages as $package) {
            echo 'packages.push({
                "package_name": "'. addslashes($package['package_name']) .'",
                "package_type": "'. addslashes($package['package_type']) .'",
                "menus": [';        
            foreach($package['menus'] as $menu) {
                echo '{
                    "menu_name": "'. addslashes($menu['menu_name']) .'",
                    "foods": [';                
                foreach($menu['foods'] ?? [] as $food) {
                    if (is_array($food)) {
                        echo '{
                            "category": "'. addslashes($food['category'] ?? '') .'", 
                            "food": "'. addslashes($food['food'] ?? '') .'"
                        },';
                    }
                }           
                echo ']
                },';
            }
            echo ']});';
        }
    ?>
    
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy.all.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@endsection