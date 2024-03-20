let expandedCompanies = false;
let checkboxesCompanies = document.getElementById("checkboxes-companies");
let searchInput = document.getElementById("search");
let children = document.querySelectorAll('.company__filter__label');
searchInput.addEventListener('input', function ($event) {
    const value = $event.target.value;
    if (value && value !== '' && value !== ' ' && value.length >= 3) {
        for (let i = 0; i < children.length; i++) {
            let element = children[i];
            if (!element.innerText.includes(value)) {
                element.classList.add('d-none');
            }
        }
    } else if (value === '' || value === ' ' || (value.length < 3 && value.length > 0)) {
        for (let i = 0; i < children.length; i++) {
            children[i].classList.remove('d-none');
        }
    }
})

////////////// Start Code for Open And Close Select in Comparison Box //////////////
function showCheckboxes(name) {

    // For Companies
    if (name === "companies") {
        // For Companies
        if (!expandedCompanies) {
            checkboxesCompanies.style.display = "block";
            expandedCompanies = true;
        } else {
            checkboxesCompanies.style.display = "none";
            expandedCompanies = false;
        }
    }
}

////////////// End Code for Open And Close Select in Comparison Box //////////////

////////////// Start Code If Click Away From Multiselect Box Close Select//////////////
window.addEventListener('click', function (e) {
    // For Companies
    if (!document.getElementById('multiselect-companies').contains(e.target)) {
        // Clicked outside the box
        checkboxesCompanies.style.display = "none";
        expandedCompanies = false;
    }
});
////////////// Start Code If Click Away From Multiselect Box Close Select//////////////

////////////// Start Code For Select fixed number of checkboxes//////////////
//For Search Box
let limit = 3;
$('input.checks-company').on('change', function (evt) {
    if ($("input[name='companies']:checked").length >= limit) {
        $("input[name='companies']:not(:checked)").attr("disabled", true);
    } else {
        $("input[name='companies']:not(:checked)").attr("disabled", false);
    }
});
//For Accordion
let limit_2 = 3;
$('input.checks-company-accordion').on('change', function (evt) {
    if ($("input[name='companies-accordion']:checked").length >= limit_2) {
        $("input[name='companies-accordion']:not(:checked)").attr("disabled", true);
    } else {
        $("input[name='companies-accordion']:not(:checked)").attr("disabled", false);
    }
});
////////////// End Code For Select fixed number of checkboxes//////////////

////////////// Start Code for generating new company and items (SEARCH BOX) //////////////
function submit() {
    let totalCompanies = $("input[name='companies']:checked").length;

    //remove all old results
    $("section").remove(".company-card");

    //Validation
    //companies
    if (totalCompanies === 0) {
        $('.companies-error').css("display", "block");
    } else {
        $('.companies-error').css("display", "none");
    }

    //check if user select companies and items
    if (totalCompanies !== 0) {
        //generating companies
        for (let i = 0; i < totalCompanies; i++) {
            //generating company inside comparison holder
            let sectionCard = document.createElement('section');
            sectionCard.className = "company-card";
            if (i === 1) {
                sectionCard.classList.add("center");
            }
            sectionCard.innerHTML =
                `<a class="company-head" href="companies_details.html">
                    <img class="company-logo" src="../images/company_logo.png" alt="logo">
                    <h3 class="company-title">ناسكو ايجيبت</h3>
                </a>`;
            document.getElementById("comparison-holder").appendChild(sectionCard);
        }

        //generating items inside companies
        for (let k = 0; k < 1; k++) {
            //generating items inside companies
            let sectionItems = document.createElement('section');
            sectionItems.className = "item-box";
            sectionItems.innerHTML =
                `<h4 class="item-name">بادي نامي</h4>
            <h4 class="item-number">7500</h4>
            <h5 class="item-date">13-12-2020</h5>
            <h5 class="item-time">09:32:07 AM</h5>`;
            $(".company-card").append(sectionItems);
        }
    }
}

////////////// End Code for generating new company and items (SEARCH BOX) //////////////

////////////// Start Code for generating new company and items (ACCORDION) //////////////
function submitAccordion() {
    let totalCompaniesAccordion = $("input[name='companies-accordion']:checked").length;

    //remove all old results
    $("section").remove(".company-card");

    //Validation
    //companies
    if (totalCompaniesAccordion === 0) {
        $('.companies-error-accordion').css("display", "block");
    } else {
        $('.companies-error-accordion').css("display", "none");
    }

    //check if user select companies and items
    if (totalCompaniesAccordion !== 0) {
        //generating companies
        for (let i = 0; i < totalCompaniesAccordion; i++) {
            //generating company inside comparison holder
            let sectionCard = document.createElement('section');
            sectionCard.className = "company-card";
            if (i === 1) {
                sectionCard.classList.add("center");
            }
            sectionCard.innerHTML =
                `<a class="company-head" href="companies_details.html">
                    <img class="company-logo" src="../images/company_logo.png" alt="logo">
                    <h3 class="company-title">ناسكو ايجيبت</h3>
                </a>`;
            document.getElementById("comparison-holder").appendChild(sectionCard);
        }

        //generating items inside companies
        for (let k = 0; k < 1; k++) {
            //generating items inside companies
            let sectionItems = document.createElement('section');
            sectionItems.className = "item-box";
            sectionItems.innerHTML =
                `<h4 class="item-name">بادي نامي</h4>
            <h4 class="item-number">7500</h4>
            <h5 class="item-date">13-12-2020</h5>
            <h5 class="item-time">09:32:07 AM</h5>`;
            $(".company-card").append(sectionItems);
        }
    }
}

////////////// Start Code for generating new company and items (ACCORDION) //////////////
