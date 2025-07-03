const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i=> {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

if (menuBar && sidebar) {
    menuBar.addEventListener('click', function () {
        sidebar.classList.toggle('hide');
    });
    // Initial hide of the sidebar
    sidebar.classList.add('hide');
}

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

if (searchButton && searchButtonIcon && searchForm) {
    searchButton.addEventListener('click', function (e) {
        if (window.innerWidth < 576) {
            e.preventDefault();
            searchForm.classList.toggle('show');
            if (searchForm.classList.contains('show')) {
                searchButtonIcon.classList.replace('bx-search', 'bx-x');
            } else {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
            }
        }
    });

    window.addEventListener('resize', function () {
        if (this.innerWidth > 576) {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
            searchForm.classList.remove('show');
        }
    });
}

//dropdown
const profileDropdown = document.getElementById("profileDropdown");
if (profileDropdown) {
    profileDropdown.addEventListener("click", function () {
        var dropdownContent = document.getElementById("profileDropdownContent");
        if (dropdownContent) {
            dropdownContent.style.display = (dropdownContent.style.display === "block") ? "none" : "block";
        }
    });
}

// Close the dropdown when clicking outside
const profileDropdownContent = document.getElementById("profileDropdownContent");
if (profileDropdownContent) {
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".dropdown") && profileDropdownContent.style.display === "block") {
            profileDropdownContent.style.display = "none";
        }
    });
}

//show password
function password() {
    var passwordInput = document.getElementById("password");
    var eyeIcon = document.getElementById("show-hide-password").getElementsByTagName("i")[0];

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("mdi-eye");
        eyeIcon.classList.add("mdi-eye-off");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("mdi-eye-off");
        eyeIcon.classList.add("mdi-eye");
    }
}

function passwordconfirm() {
    var passwordInput = document.getElementById("password-confirm");
    var eyeIcon = document.getElementById("show-hide-password-confirm").getElementsByTagName("i")[0];

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("mdi-eye");
        eyeIcon.classList.add("mdi-eye-off");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("mdi-eye-off");
        eyeIcon.classList.add("mdi-eye");
    }
}


