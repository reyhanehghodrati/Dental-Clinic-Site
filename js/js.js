const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

// افزودن حالت باز و بسته شدن منو
hamburger.addEventListener("click", mobileMenu);

function mobileMenu() {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
}

// حذف رفتار بستن منو با کلیک روی لینک
const navLink = document.querySelectorAll(".nav-link");

navLink.forEach((link) => {
    link.addEventListener("click", (event) => {
        console.log(`Clicked on ${event.target.textContent}`);
    });
});
// اسکرول به سمت راست هنگام کلیک روی دکمه
const scrollButton = document.getElementById("scrollButton");
const blogContainer = document.querySelector(".blog-container");

scrollButton.addEventListener("click", () => {
    blogContainer.scrollBy({
        left: 300, 
        behavior: "smooth", 
    });
});

