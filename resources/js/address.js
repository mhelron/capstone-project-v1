// Get old values 
const oldRegion = document.querySelector('[name="region"]').value;
const oldProvince = document.querySelector('[name="province"]').value;
const oldCity = document.querySelector('[name="city"]').value;
const oldBarangay = document.querySelector('[name="barangay"]').value;

// Get references to the select elements
const regionSelect = document.getElementById('region');
const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');
const barangaySelect = document.getElementById('barangay');

// Function to reset dependent fields
function resetDependentFields(startingField) {
   switch(startingField) {
       case 'region':
           provinceSelect.innerHTML = '<option value="" disabled selected>Select a Province</option>';
           provinceSelect.disabled = true;
       case 'province':
           citySelect.innerHTML = '<option value="" disabled selected>Select a City</option>';
           citySelect.disabled = true;
       case 'city':
           barangaySelect.innerHTML = '<option value="" disabled selected>Select a Barangay</option>';
           barangaySelect.disabled = true;
           break;
   }
}

// Handle Region Change
regionSelect.addEventListener('change', function() {
   resetDependentFields('region');
   
   const selectedRegion = addressData.find(region => region.id === regionSelect.value);
   if (selectedRegion) {
       provinceSelect.disabled = false;
       selectedRegion.province.forEach(province => {
           const option = document.createElement('option');
           option.value = province.name;
           option.textContent = province.name;
           option.selected = province.name === oldProvince;
           provinceSelect.appendChild(option);
       });
       if (oldProvince) provinceSelect.dispatchEvent(new Event('change'));
   }
});

// Handle Province Change
provinceSelect.addEventListener('change', function() {
   resetDependentFields('province');
   
   const selectedRegion = addressData.find(region => region.id === regionSelect.value);
   const selectedProvince = selectedRegion.province.find(province => province.name === provinceSelect.value);
   if (selectedProvince) {
       citySelect.disabled = false;
       selectedProvince.city.forEach(city => {
           const option = document.createElement('option');
           option.value = city.name;
           option.textContent = city.name;
           option.selected = city.name === oldCity;
           citySelect.appendChild(option);
       });
       if (oldCity) citySelect.dispatchEvent(new Event('change'));
   }
});

// Handle City Change
citySelect.addEventListener('change', function() {
   resetDependentFields('city');
   
   const selectedRegion = addressData.find(region => region.id === regionSelect.value);
   const selectedProvince = selectedRegion.province.find(province => province.name === provinceSelect.value);
   const selectedCity = selectedProvince.city.find(city => city.name === citySelect.value);
   if (selectedCity) {
       barangaySelect.disabled = false;
       selectedCity.barangay.forEach(barangay => {
           const option = document.createElement('option');
           option.value = barangay;
           option.textContent = barangay;
           option.selected = barangay === oldBarangay;
           barangaySelect.appendChild(option);
       });
   }
});

// Trigger initial load if old region exists
if (oldRegion) {
   regionSelect.dispatchEvent(new Event('change'));
}