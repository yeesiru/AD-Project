function showSideBar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex'
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none'
}

document.addEventListener("DOMContentLoaded", () => {
    const subBtn = document.querySelector('.sub-btn');
    const dropdown = document.querySelector('.sidebar-dropdown');

    subBtn.addEventListener('click', () => {
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const navbarHTML = `
        <div class="top">
            <div class="container">
                <div class="logo">
                    <img src="../img/SJAM-logo.png"/>
                    <p>SJAM Connect</p>
                </div>
            </div>
        </div>
        
        <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()> <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px"
                        viewBox="0 -960 960 960" width="24px" fill="black">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg></a></li>
            <li> <a href="../mainPage/officerHomepage.html">Home</a></li>
            <li> <a class="sub-btn">Our Services &#x25BE;</a>
                <ul class="sidebar-dropdown">
                    <li><a href="#" class="sub-item">Hall Booking</a></li>
                    <li><a href="#" class="sub-item">Ambulance Booking</a></li>
                    <li><a href="../Equipment_user/manageEquipmentBooking.php" class="sub-item">Equipment Booking</a></li>
                </ul>
            </li>
            <li> <a href="../Feedback/officer-feedback.php">Feedback</a></li>
            <li> <a href="#">Contact Us</a></li>
            <li> <a href="../mainPage/logout.php">Logout</a></li>
        </ul>

        <ul>
            <li class="hideOnMobile"> <a href="../mainPage/officerHomepage.html">Home</a></li>
            <li class="hideOnMobile"> <a href="#"> Our Services &#x25BE;</a>
                <ul class="dropdown">
                    <li><a href="#">Hall Booking</a></li>
                    <li><a href="#">Ambulance Booking</a></li>
                    <li><a href="#">Equipment Booking</a></li>
                </ul>
            </li>
            <li class="hideOnMobile"> <a href="../Feedback/officer-feedback.php">Feedback</a></li>
            <li class="hideOnMobile"> <a href="#">Contact Us</a></li>
            <li class="hideOnMobile"> <a href="../mainPage/logout.php">Logout</a></li>
            <li class="menuButton" onclick=showSideBar()> <a href="#"><svg xmlns="http://www.w3.org/2000/svg"
                        height="24px" viewBox="0 -960 960 960" width="24px" fill="black">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg></a></li>
        </ul>
    </nav>
    `;

    // Insert the navbar into an element with the ID 'navbar'
    document.getElementById('navbar').innerHTML = navbarHTML;

    const subBtn = document.querySelector('.sub-btn');
    const dropdown = document.querySelector('.sidebar-dropdown');

    subBtn.addEventListener('click', () => {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
});