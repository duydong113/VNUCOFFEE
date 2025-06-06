// Remove static data
// const provinces = [...];
// const districts = {...};
// const wards = {...};

// Function to remove Vietnamese diacritics
function removeVietnameseDiacritics(str) {
    if (str === null || str === undefined) return "";
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    return str;
}

// Function to fetch data from the API
async function fetchData(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error fetching data: ", error);
        // Optionally display an error message to the user
        return []; // Return empty array on error
    }
}

// Function to populate provinces dropdown
async function populateProvinces() {
    const provinceSelect = document.getElementById('province');
    provinceSelect.innerHTML = '<option value="">-- Select Province/City --</option>'; // Reset dropdown
    provinceSelect.disabled = true; // Disable until data is loaded

    const provinces = await fetchData('https://provinces.open-api.vn/api/p/');

    if (provinces.length > 0) {
        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            
            // Remove prefixes "Tỉnh" and "Thành phố "
            let cleanedName = province.name;
            if (cleanedName.startsWith('Tỉnh ')) {
                cleanedName = cleanedName.substring(5); // Remove "Tỉnh "
            } else if (cleanedName.startsWith('Thành phố ')) {
                cleanedName = cleanedName.substring(10); // Remove "Thành phố "
            }

            option.textContent = removeVietnameseDiacritics(cleanedName); // Apply diacritics removal
            provinceSelect.appendChild(option);
        });
        provinceSelect.disabled = false;
    }
}

// Function to populate districts dropdown based on selected province
async function populateDistricts(provinceCode) {
    const districtSelect = document.getElementById('district');
    districtSelect.innerHTML = '<option value="">-- Select District --</option>'; // Reset dropdown
    districtSelect.disabled = true;

    const wardSelect = document.getElementById('ward');
    wardSelect.innerHTML = '<option value="">-- Select Ward --</option>'; // Reset ward dropdown
    wardSelect.disabled = true;

    if (provinceCode) {
        const districts = await fetchData(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
         if (districts && districts.districts && districts.districts.length > 0) {
            districts.districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = removeVietnameseDiacritics(district.name);
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        }
    }
}

// Function to populate wards dropdown based on selected district
async function populateWards(districtCode) {
    const wardSelect = document.getElementById('ward');
    wardSelect.innerHTML = '<option value="">-- Select Ward --</option>'; // Reset dropdown
    wardSelect.disabled = true;

    if (districtCode) {
        const wards = await fetchData(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        if (wards && wards.wards && wards.wards.length > 0) {
            wards.wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.code;
                option.textContent = removeVietnameseDiacritics(ward.name);
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
        }
    }
}

// Initialize the address dropdowns when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Populate provinces on page load
    populateProvinces().then(() => {
        // Check for pre-filled province
        const provinceSelect = document.getElementById('province');
        const prefilledProvinceName = provinceSelect.getAttribute('data-prefill');
        if (prefilledProvinceName) {
            // Find the option with matching text content (cleaned name)
            const matchingProvinceOption = Array.from(provinceSelect.options).find(option => 
                removeVietnameseDiacritics(option.textContent.toLowerCase()) === removeVietnameseDiacritics(prefilledProvinceName.toLowerCase())
            );

            if (matchingProvinceOption) {
                provinceSelect.value = matchingProvinceOption.value; // Set select value to the found code

                // Trigger population of districts and then wards
                populateDistricts(matchingProvinceOption.value).then(() => {
                    const districtSelect = document.getElementById('district');
                    const prefilledDistrictName = districtSelect.getAttribute('data-prefill');
                    if (prefilledDistrictName) {
                         // Find the option with matching text content
                        const matchingDistrictOption = Array.from(districtSelect.options).find(option => 
                             removeVietnameseDiacritics(option.textContent.toLowerCase()) === removeVietnameseDiacritics(prefilledDistrictName.toLowerCase())
                        );

                        if (matchingDistrictOption) {
                             districtSelect.value = matchingDistrictOption.value; // Set select value to the found code
                            populateWards(matchingDistrictOption.value).then(() => {
                                const wardSelect = document.getElementById('ward');
                                const prefilledWardName = wardSelect.getAttribute('data-prefill');
                                if(prefilledWardName) {
                                    // Find the option with matching text content
                                     const matchingWardOption = Array.from(wardSelect.options).find(option => 
                                         removeVietnameseDiacritics(option.textContent.trim().toLowerCase()) === removeVietnameseDiacritics(prefilledWardName.trim().toLowerCase())
                                    );
                                    if(matchingWardOption) {
                                        wardSelect.value = matchingWardOption.value; // Set select value to the found code
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
    });

    // Add event listener for province selection
    document.getElementById('province').addEventListener('change', function() {
        populateDistricts(this.value);
    });

    // Add event listener for district selection
    document.getElementById('district').addEventListener('change', function() {
        populateWards(this.value);
    });
}); 