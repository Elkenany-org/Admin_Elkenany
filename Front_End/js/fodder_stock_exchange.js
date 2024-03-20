$(document).ready(function () {
    let searchCompaniesInput = document.getElementById("searchCompany");
    let searchTypesInput = document.getElementById("searchTypes");
    let childrenCompanies = document.querySelectorAll('.company__filter__label');
    let childrenTypes = document.querySelectorAll('.type__filter__label');
    searchCompaniesInput.addEventListener('input', function ($event) {
        const value = $event.target.value;
        if (value && value !== '' && value !== ' ' && value.length >= 3) {
            for (let i = 0; i < childrenCompanies.length; i++) {
                let element = childrenCompanies[i];
                if (!element.innerText.includes(value)) {
                    element.classList.add('d-none');
                }
            }
        } else if (value === '' || value === ' ' || (value.length < 3 && value.length > 0)) {
            for (let i = 0; i < childrenCompanies.length; i++) {
                childrenCompanies[i].classList.remove('d-none');
            }
        }
    })
    searchTypesInput.addEventListener('input', function ($event) {
        const value = $event.target.value;
        if (value && value !== '' && value !== ' ' && value.length >= 3) {
            for (let i = 0; i < childrenTypes.length; i++) {
                let element = childrenTypes[i];
                if (!element.innerText.includes(value)) {
                    element.classList.add('d-none');
                }
            }
        } else if (value === '' || value === ' ' || (value.length < 3 && value.length > 0)) {
            for (let i = 0; i < childrenTypes.length; i++) {
                childrenTypes[i].classList.remove('d-none');
            }
        }
    })
});
