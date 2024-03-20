let expandedCompanies = false;
let expandedItems = false;
let checkboxesCompanies = document.getElementById("checkboxes-companies");
let checkboxesItems = document.getElementById("checkboxes-items");
let searchInput = document.getElementById("search");
let searchTypesInput = document.getElementById("search__type");

let children = document.querySelectorAll('.company__filter__label');
let childrenTypes = document.querySelectorAll('.type__filter__label');
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

let childrenAccordion = document.querySelectorAll('.company__filter__label-accordion');
let childrenTypesAccordion = document.querySelectorAll('.type__filter__label-accordion');
let searchInputAccordion = document.getElementById("search__accordion");
let searchTypesInputAccordion = document.getElementById("search__type__accordion");
searchInputAccordion.addEventListener('input', function ($event) {
    const value = $event.target.value;
    if (value && value !== '' && value !== ' ' && value.length >= 3) {
        for (let i = 0; i < childrenAccordion.length; i++) {
            let element = childrenAccordion[i];
            if (!element.innerText.includes(value)) {
                element.classList.add('d-none');
            }
        }
    } else if (value === '' || value === ' ' || (value.length < 3 && value.length > 0)) {
        for (let i = 0; i < childrenAccordion.length; i++) {
            childrenAccordion[i].classList.remove('d-none');
        }
    }
})
searchTypesInputAccordion.addEventListener('input', function ($event) {
    const value = $event.target.value;
    if (value && value !== '' && value !== ' ' && value.length >= 3) {
        for (let i = 0; i < childrenTypesAccordion.length; i++) {
            let element = childrenTypesAccordion[i];
            if (!element.innerText.includes(value)) {
                element.classList.add('d-none');
            }
        }
    } else if (value === '' || value === ' ' || (value.length < 3 && value.length > 0)) {
        for (let i = 0; i < childrenTypesAccordion.length; i++) {
            childrenTypesAccordion[i].classList.remove('d-none');
        }
    }
})

////////////// Start Code for Open And Close Select in Comparison Box //////////////
function showCheckboxes(name) {

    // For Companies
    if (name === "companies[]") {
        // For Companies
        if (!expandedCompanies) {
            checkboxesCompanies.style.display = "block";
            expandedCompanies = true;
        } else {
            checkboxesCompanies.style.display = "none";
            expandedCompanies = false;
        }
        // For Items
    } else if (name === "items[]") {
        // For Items
        if (!expandedItems) {
            checkboxesItems.style.display = "block";
            expandedItems = true;
        } else {
            checkboxesItems.style.display = "none";
            expandedItems = false;
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
    // For Items
    if (!document.getElementById('multiselect-items').contains(e.target)) {
        // Clicked outside the box
        checkboxesItems.style.display = "none";
        expandedItems = false;
    }
});
////////////// Start Code If Click Away From Multiselect Box Close Select//////////////

////////////// Start Code For Select fixed number of checkboxes//////////////
//For Search Box
let limit = 3;
$('input.checks-company').on('change', function (evt) {
    if ($("input[name='companies[]']:checked").length >= limit) {
        $("input[name='companies[]']:not(:checked)").attr("disabled", true);
    } else {
        $("input[name='companies[]']:not(:checked)").attr("disabled", false);
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

////////////// Start Code Counter For Selected Items Without Fixed Number //////////////
//For Search Box
$('input.checks-item').on('change', function (evt) {
    if ($("input[name='items[]']:checked").length > 0) {
        $('#items-counter').text("عدد الأصناف : " + $("input[name='items[]']:checked").length);
    } else {
        $('#items-counter').text("عدد الأصناف : " + $("input[name='items[]']:checked").length);
    }
});
//For Accordion
$('input.checks-item-accordion').on('change', function (evt) {
    if ($("input[name='items-accordion']:checked").length > 0) {
        $('#items-counter-accordion').text("عدد الأصناف : " + $("input[name='items-accordion']:checked").length);
    } else {
        $('#items-counter-accordion').text("عدد الأصناف : " + $("input[name='items-accordion']:checked").length);
    }
});
////////////// End Code Counter For Selected Items Without Fixed Number //////////////

////////////// Start Code for generating new company and items (SEARCH BOX) //////////////
function submit() {
    let totalCompanies = $("input[name='companies[]']:checked").length;
    let totalItems = $("input[name='items[]']:checked").length;

    //remove all old results
    $("section").remove(".company-card");

    //Validation
    //companies
    if (totalCompanies === 0) {
        $('.companies-error').css("display", "block");
    } else {
        $('.companies-error').css("display", "none");
    }
    //items
    if (totalItems === 0) {
        $('.items-error').css("display", "block");
    } else {
        $('.items-error').css("display", "none");
    }

}

// if looping for items in html
// AddHoverEffects();

function AddHoverEffects() {
    let itemBoxes = document.querySelectorAll(`.item-box`);
    for (let i = 0; i < itemBoxes.length; i++) {
        itemBoxes[i].addEventListener('mouseenter', ($event) => {
            let attrValue = itemBoxes[i].getAttribute('data-item-box-id');
            let sameItemsElements = document.querySelectorAll(`[data-item-box-id="${attrValue}"]`);
            for (let j = 0; j < sameItemsElements.length; j++) {
                sameItemsElements[j].classList.add('item__box__hover')
            }
        })
        itemBoxes[i].addEventListener('mouseleave', ($event) => {
            let attrValue = itemBoxes[i].getAttribute('data-item-box-id');
            let sameItemsElements = document.querySelectorAll(`[data-item-box-id="${attrValue}"]`);
            for (let j = 0; j < sameItemsElements.length; j++) {
                sameItemsElements[j].classList.remove('item__box__hover')
            }
        })
    }
}


////////////// End Code for generating new company and items (SEARCH BOX) //////////////

////////////// Start Code for generating new company and items (ACCORDION) //////////////
function submitAccordion() {
    let totalCompaniesAccordion = $("input[name='companies-accordion']:checked").length;
    let totalItemsAccordion = $("input[name='items-accordion']:checked").length;

    //remove all old results
    $("section").remove(".company-card");

    //Validation
    //companies
    if (totalCompaniesAccordion === 0) {
        $('.companies-error-accordion').css("display", "block");
    } else {
        $('.companies-error-accordion').css("display", "none");
    }
    //items
    if (totalItemsAccordion === 0) {
        $('.items-error-accordion').css("display", "block");
    } else {
        $('.items-error-accordion').css("display", "none");
    }

    //check if user select companies and items
    if (totalCompaniesAccordion !== 0 && totalItemsAccordion !== 0) {
        //generating companies
        for (let i = 0; i < totalCompaniesAccordion; i++) {
            //generating company inside comparison holder
            let sectionCard = document.createElement('section');
            sectionCard.className = "company-card";
            if (i === 1) {
                sectionCard.classList.add("center");
            }
            sectionCard.innerHTML =
                `<a class="company-head" href="../companies/companies_details.html">
                    <img class="company-logo" src="../../images/card_img.png" alt="logo">
                    <h3 class="company-title">ناسكو ايجيبت</h3>
                </a>`;
            document.getElementById("comparison-holder").appendChild(sectionCard);
        }
        //generating items inside companies
        for (let k = 0; k < totalItemsAccordion; k++) {
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

    AddHoverEffects();
}

////////////// Start Code for generating new company and items (ACCORDION) //////////////
