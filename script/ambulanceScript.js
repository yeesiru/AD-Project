let ambulances = [];
let bookings = [];

function goBack(){
    window.location.href = "homepage.html";
}

function showAddAmbulanceForm() {
    document.getElementById("ambulance-form").style.display = "block";
    document.getElementById("form-title").innerText = "Add Ambulance";
}

function saveAmbulance() {
    const vehicleId = document.getElementById("vehicleId").value;
    const type = document.getElementById("type").value;
    const capacity = document.getElementById("capacity").value;
    const availability = document.getElementById("availability").value;

    if (!vehicleId || !type || !capacity) {
        alert("Please fill in all mandatory fields.");
        return;
    }

    const saveButton = document.getElementById("saveButton");
    const isEditing = saveButton.getAttribute("data-editing") === "true";
    const index = saveButton.getAttribute("data-index");

    if (isEditing) {
        // Update the existing ambulance
        ambulances[index] = { vehicleId, type, capacity, availability };
        
        // Reset the save button to default mode
        saveButton.removeAttribute("data-editing");
        saveButton.removeAttribute("data-index");
    }

    ambulances.push({ vehicleId, type, capacity, availability });

    alert("Ambulance added successfully!");
    alert(isEditing ? "Ambulance updated successfully!" : "Ambulance added successfully!")
    displayAmbulances();
    closeForm();
}

function displayAmbulances() {
    const ambulanceList = document.getElementById("ambulance-list");
    ambulanceList.innerHTML = "";
    ambulances.forEach((amb, index) => {
        ambulanceList.innerHTML += `
            <div>
                <p>Vehicle ID: ${amb.vehicleId}</p>
                <p>Type: ${amb.type}</p>
                <p>Capacity: ${amb.capacity}</p>
                <p>Availability: ${amb.availability}</p>
                <button class="button" onclick="editAmbulance(${index})">Edit</button>
                <button class="button" onclick="deleteAmbulance(${index})">Delete</button>
            </div>
        `;
    });
}

function showAddBookingForm() {
    document.getElementById("booking-form").style.display = "block";
    document.getElementById("form-title").innerText = "Add Booking";
}

function saveBooking() {
    const bookingDate = document.getElementById("bookingDate").value;
    const bookingTime = document.getElementById("bookingTime").value;
    const destination = document.getElementById("destination").value;

    if (!bookingDate || !bookingTime || !destination) {
        alert("Please fill in all mandatory fields.");
        return;
    }

    bookings.push({ bookingDate, bookingTime, destination });
    alert("Booking added successfully!");
    displayBookings();
    closeForm();
}

function displayBookings() {
    const bookingList = document.getElementById("booking-list");
    bookingList.innerHTML = "";
    bookings.forEach((book, index) => {
        bookingList.innerHTML += `
            <div>
                <p>Booking Date: ${book.bookingDate}</p>
                <p>Booking Time: ${book.bookingTime}</p>
                <p>Destination: ${book.destination}</p>
                <button onclick="editAmbulance(${index})">Edit</button>
                <button onclick="deleteAmbulance(${index})">Delete</button>
            </div>
        `;
    });
}

function closeForm() {
    const bookingForm = document.getElementById("booking-form");
    const ambulanceForm = document.getElementById("ambulance-form");
    
    if (bookingForm) {
        bookingForm.style.display = "none";
    }
    if (ambulanceForm) {
        ambulanceForm.style.display = "none";
    }
}

// Function to edit an ambulance's details
function editAmbulance(index) {
    const ambulance = ambulances[index];

    document.getElementById("vehicleId").value = ambulance.vehicleId;
    document.getElementById("type").value = ambulance.type;
    document.getElementById("capacity").value = ambulance.capacity;
    document.getElementById("availability").value = ambulance.availability;

    document.getElementById("form-title").innerText = "Edit Ambulance";
    document.getElementById("ambulance-form").style.display = "block";

    const saveButton = document.getElementById("saveButton");
    saveButton.setAttribute("data-editing", "true");
    saveButton.setAttribute("data-index", index);
}

// Function to delete an ambulance from the list
function deleteAmbulance(index) {
    if (confirm("Are you sure you want to delete this ambulance?")) {
        ambulances.splice(index, 1);
        displayAmbulances();
    }
}

// Similar functions for managing bookings
