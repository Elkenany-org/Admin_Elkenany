const ratingStars = [...document.getElementsByClassName("rating__star")];

function executeRating(stars, callback) {
    const starClassActive = "rating__star fas fa-star";
    const starClassInactive = "rating__star far fa-star";
    const starsLength = stars.length;
    let i;
    stars.map((star) => {
        star.onclick = () => {
            i = stars.indexOf(star);

            if (star.className.indexOf(starClassInactive) !== -1) {
                if (typeof callback === 'function') {
                    callback(i + 1);
                }
                for (i; i >= 0; --i) stars[i].className = starClassActive;
            } else {
                if (typeof callback === 'function') {
                    callback(i + 1);
                }
                for (i; i < starsLength; ++i) stars[i].className = starClassInactive;
            }
        };
    });
}


