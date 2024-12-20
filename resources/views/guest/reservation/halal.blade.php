@extends('layouts.guestlayout')

@section('content')
<div class="container mt-5" style="padding-top: 50px;">
    <div class="row mb-4">
        <div class="col">
            <h2>Customize Halal Menu</h2>
            <p class="text-muted">All selections are guaranteed pork-free</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card custom-card">
        <div class="card-body">
            <form action="{{ route('menu.update.halal', $packageId) }}" method="POST" id="customizeForm">
                @csrf
                <input type="hidden" name="menu_name" value="{{ $halalMenu['menu_name'] ?? 'Halal Menu' }}">
                <input type="hidden" name="package_name" value="{{ $package['package_name'] }}">

                @foreach($halalMenu['foods'] as $index => $food)
                    <div class="mb-4">
                        <label class="form-label text-darkorange">{{ $food['category'] }}</label>
                        @if($food['category'] === 'Extra Main Course (Beef, Chicken, or Fish)')
                            <select name="foods[Extra Main Course]" class="form-select custom-select extra-select" required>
                                <option value="">Select Extra Item</option>
                                @foreach(['Chicken', 'Beef', 'Fish'] as $mainCategory)
                                    @if(isset($availableFoods[$mainCategory]) && !empty($availableFoods[$mainCategory]))
                                        <optgroup label="{{ $mainCategory }}">
                                            @foreach($availableFoods[$mainCategory] as $item)
                                                @if(!in_array($item, $selectedFoods))
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <select name="foods[{{ $food['category'] }}]" class="form-select custom-select main-select" 
                                    data-category="{{ $food['category'] }}">
                                <option value="{{ $food['food'] }}">{{ $food['food'] }} (Current)</option>
                                @php
                                    $categoryType = '';
                                    if (stripos($food['category'], 'Chicken') !== false) {
                                        $categoryType = 'Chicken';
                                    } elseif (stripos($food['category'], 'Beef') !== false) {
                                        $categoryType = 'Beef';
                                    } elseif (stripos($food['category'], 'Fish') !== false) {
                                        $categoryType = 'Fish';
                                    } elseif (stripos($food['category'], 'Side Dish') !== false) {
                                        $categoryType = 'Side Dish';
                                    } elseif (stripos($food['category'], 'Pasta') !== false) {
                                        $categoryType = 'Pasta';
                                    } elseif (stripos($food['category'], 'Rice') !== false) {
                                        $categoryType = 'Rice';
                                    } elseif (stripos($food['category'], 'Dessert') !== false) {
                                        $categoryType = 'Dessert';
                                    } elseif (stripos($food['category'], 'Drinks') !== false) {
                                        $categoryType = 'Drinks';
                                    }
                                @endphp
                                @if($categoryType && isset($availableFoods[$categoryType]))
                                    @foreach($availableFoods[$categoryType] as $availableFood)
                                        @if($availableFood !== $food['food'])
                                            <option value="{{ $availableFood }}">{{ $availableFood }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </div>
                @endforeach

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary" id="cancelBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Cancel</span>
                    </a>
                    <button type="submit" class="btn btn-darkorange" id="saveBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Save and Select Menu</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
/* Card Styles */
.custom-card {
    border: 2px solid darkorange;
    background-color: #f5f5dc;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.custom-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* Form Select Styles */
.custom-select {
    border: 2px solid darkorange;
    border-radius: 5px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.custom-select:focus {
    border-color: darkorange;
    box-shadow: 0 0 5px rgba(255, 140, 0, 0.3);
    outline: none;
}

/* Button Styles */
.btn-darkorange {
    background-color: darkorange;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-darkorange:hover {
    background-color: #ff8c00;
    color: white;
    transform: translateY(-1px);
}

.btn-darkorange:active {
    transform: translateY(1px);
}

/* Text Colors */
.text-darkorange {
    color: darkorange;
}

/* Form Label Styles */
.form-label {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

/* Button Loading State */
.btn[disabled] {
    cursor: not-allowed;
    opacity: 0.7;
}

/* Spinner Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.spinner-border {
    animation: spin 1s linear infinite;
}

/* Modal Styles */
#selectedItemsList .selected-item {
    padding: 10px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
}

#selectedItemsList .selected-item:last-child {
    border-bottom: none;
}

.selected-item-category {
    font-weight: bold;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customizeForm');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const selectedItemsList = document.getElementById('selectedItemsList');
    const mainSelects = document.querySelectorAll('.main-select');
    const extraSelect = document.querySelector('.extra-select');
    let isSubmitting = false;

    function updateExtraSelect() {
        if (!extraSelect) return;

        // Get all currently selected values from main selects
        const selectedValues = Array.from(mainSelects).map(select => select.value);

        // Get all options from extra select
        const extraOptions = extraSelect.querySelectorAll('option');
        
        // Hide or show options based on selections
        extraOptions.forEach(option => {
            if (option.value === '') return; // Skip the placeholder option
            if (selectedValues.includes(option.value)) {
                option.disabled = true;
                option.style.display = 'none';
            } else {
                option.disabled = false;
                option.style.display = '';
            }
        });
    }

    // Add change event listeners to main course selects
    mainSelects.forEach(select => {
        select.addEventListener('change', updateExtraSelect);
    });

    // Initial update
    updateExtraSelect();

    // Initialize Bootstrap modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

    // Handle form submission to show modal
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting immediately
        
        if (isSubmitting) {
            return;
        }

        // Clear and populate the selected items list
        selectedItemsList.innerHTML = '';
        document.querySelectorAll('.custom-select').forEach(select => {
            const category = select.name.match(/\[(.*?)\]/)[1];
            const item = document.createElement('div');
            item.className = 'selected-item';
            item.innerHTML = `
                <span class="selected-item-category">${category}:</span>
                <span>${select.value}</span>
            `;
            selectedItemsList.appendChild(item);
        });

        // Show the modal
        modal.show();
    });

    // Handle modal close - reset form state
    document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function () {
        isSubmitting = false;
        saveBtn.disabled = false;
        cancelBtn.disabled = false;
        
        // Reset button states
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.disabled = false;
        confirmBtn.querySelector('.spinner-border').classList.add('d-none');
        confirmBtn.querySelector('.btn-text').textContent = 'Confirm and Proceed';
        
        saveBtn.querySelector('.spinner-border').classList.add('d-none');
        saveBtn.querySelector('.btn-text').textContent = 'Save and Select Menu';
    });

    // Handle confirm button click
    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (isSubmitting) return;

        isSubmitting = true;
        this.disabled = true;
        
        // Show spinner, hide text
        this.querySelector('.spinner-border').classList.remove('d-none');
        this.querySelector('.btn-text').textContent = 'Processing...';
        
        // Submit the form
        form.submit();
    });

    // Handle cancel button
    cancelBtn.addEventListener('click', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        cancelBtn.disabled = true;
        
        // Show spinner, hide text
        cancelBtn.querySelector('.spinner-border').classList.remove('d-none');
        cancelBtn.querySelector('.btn-text').textContent = 'Cancelling...';
    });
});
</script>
@endsection