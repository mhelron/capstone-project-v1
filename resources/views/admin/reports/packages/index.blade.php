@extends('layouts.adminlayout')

@section('content')

<style>
    .leaderboard {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .card-header {
        background-color: #f8f9fa;
        font-size: 1.25rem;
        font-weight: bold;
        padding: 0.75rem;
    }

    .card-body {
        padding: 1rem;
        font-size: 1rem;
    }

    .first-place {
        border: 2px solid gold;
        background-color: #f8e71c;
    }

    .second-place {
        border: 2px solid silver;
        background-color: #dcdcdc;
    }

    .third-place {
        border: 2px solid #cd7f32;
        background-color: #f5e1a4;
    }

    .leaderboard .card {
        width: 100%;
        max-width: 300px;
    }

    /* Adjust for smaller screens */
    @media (max-width: 768px) {
        .leaderboard .card {
            width: 100%;
        }
    }
</style>


<div>
    <h1 style="padding-top: 35px;">Packages</h1>
</div>

<div class="table-responsive pt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Package Name</th>
                <th>Total Reservations</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packageCounts as $packageName => $count)
                <tr @if($loop->index == 0) class="table-success" @elseif($loop->index == 1) class="table-secondary" @elseif($loop->index == 2) class="table-warning" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $packageName }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
