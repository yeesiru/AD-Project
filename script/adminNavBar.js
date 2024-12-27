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
    
    <!-- Navigation bar -->
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()> <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px"
                        viewBox="0 -960 960 960" width="24px" fill="black">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg></a></li>
            <li> <a href="../mainPage/adminHomepage.html">Home</a></li>
            <li> <a class="sub-btn">Manage &#x25BE;</a>
                <ul class="sidebar-dropdown">
                    <li><a href="#" class="sub-item">Hall</a></li>
                    <li><a href="#" class="sub-item">Ambulance</a></li>
                    <li><a href="#" class="sub-item">Equipment</a></li>
                </ul>
            </li>
            <li> <a href="../Feedback/admin_viewfeedback.php">Feedback</a></li>
            <li> <a href="../User/userManage.php">User List</a></li>
            <li> <a href="../User/ownProfile.php">Profile</a></li>
            <li> <a href="../mainPage/logout.php">Logout</a></li>
        </ul>

        <ul>
            <li class="hideOnMobile"> <a href="../mainPage/adminHomepage.html">Home</a></li>
            <li class="hideOnMobile"> <a href="../User/ownProfile.php">Profile</a></li>
            <li class="hideOnMobile"  style="width: 149px;"> <a href="#">Manage &#x25BE;</a>
                <ul class="dropdown">
                    <li><a href="#">Hall</a></li>
                    <li><a href="../ambulance/viewAmbulance.php">Ambulance</a></li>
                    <li><a href="../Equipment/equipmmentList.php">Equipment</a></li>
                </ul>
            </li>
            <li class="hideOnMobile"> <a href="../Feedback/admin_viewfeedback.php">Feedback</a></li>
            <li class="hideOnMobile"> <a href="../User/userManage.php">User List</a></li>
            <li class="hideOnMobile" style="text-align: right;"> <a href="../mainPage/logout.php">Logout</a></li>
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