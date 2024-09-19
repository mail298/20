// Add hovered class to selected list items
let list = document.querySelectorAll(".navigation li");

function activeLink(){
    list.forEach((item) => {
        item.classList.remove("hovered"); 
    });
    this.classList.add("hovered");
}

// Menggunakan event 'mouseover' atau bisa diganti dengan 'click'
list.forEach((item) => item.addEventListener('click', activeLink));

// Menu toggle
document.addEventListener("DOMContentLoaded", function() {
    let toggle = document.querySelector(".toggle");
    let navigation = document.querySelector(".navigation");
    let main = document.querySelector(".main");

    toggle.onclick = function() {
        navigation.classList.toggle("active");
        main.classList.toggle("active");
    };
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('dropdown');
    const submenu = dropdown.querySelector('.submenu');

    dropdown.addEventListener('click', function() {
        // Toggle the visibility of the submenu
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
        } else {
            submenu.style.display = 'block';
        }
    });

    // Close the submenu if clicked outside
    document.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target)) {
            submenu.style.display = 'none';
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const registerLink = document.querySelector(".register-link");
    const loginLink = document.querySelector(".login-link");
    const formBoxLogin = document.querySelector(".form-box-login");
    const formBoxRegister = document.querySelector(".form-box-register");

    registerLink.addEventListener("click", function(e) {
        e.preventDefault();
        formBoxLogin.style.display = "none";
        formBoxRegister.style.display = "block";
    });

    loginLink.addEventListener("click", function(e) {
        e.preventDefault();
        formBoxRegister.style.display = "none";
        formBoxLogin.style.display = "block";
    });
});

