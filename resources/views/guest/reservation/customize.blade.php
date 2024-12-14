@extends('layouts.guestlayout')

@section('content')
<div class="container mt-5" style="padding-top: 50px;">
    <h2>Customize Menu</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('menu.update', $packageId) }}" method="POST">
                @csrf
                <input type="hidden" name="menu_name" value="{{ $selectedMenu['menu_name'] }}">
                <input type="hidden" name="package_name" value="{{ $package['package_name'] }}">

                @foreach($selectedMenu['foods'] as $food)
                    <div class="mb-4">
                        <label class="form-label">{{ $food['category'] }}</label>
                        <select name="foods[{{ $food['category'] }}]" class="form-select">
                            <option value="{{ $food['food'] }}">{{ $food['food'] }} (Current)</option>
                            @foreach($availableFoods[$food['category']] as $availableFood)
                                @if($availableFood !== $food['food'])
                                    <option value="{{ $availableFood }}">{{ $availableFood }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Customized Menu</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection