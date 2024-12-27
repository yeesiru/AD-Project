document.addEventListener("DOMContentLoaded", () => {
    const footerHTML = `
        <footer>
        <div class="footerContainer px-5">
            <!--Social footer icon-->
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-content align-center">
                        <div class="socialIcon">
                            <a href=""><i class="fa-brands fa-facebook"></i></a>
                            <a href=""><i class="fa-brands fa-instagram"></i></a>
                            <a href=""><i class="fa-brands fa-linkedin"></i></a>
                            <a href=""><i class="fa-brands fa-youtube"></i></a>
                            <a href=""><i class="fa fa-envelope"></i></a>
                        </div>

                        <!--Footer copyright-->
                        <div class="footer-copyright my-2">
                            © St. John Ambulance of Malaysia | All Rights Reserved
                        </div>
                    </div>
    </footer>
    `;

    // Insert the footer into the DOM
    const footer = document.getElementById('footer');
    if (footer) {
        footer.innerHTML = footerHTML;
    } else {
        console.error("footer container not found.");
        return;
    }

});
