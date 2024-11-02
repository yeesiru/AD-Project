let ambulances = [];
let bookings = [];

// Navigate back to the homepage
const goBack = () => {
    window.location.href = "homepage.html";
};

// Show the form to add a new ambulance
const showAddAmbulanceForm = () => {
    document.getElementById("ambulance-form").style.display = "block";
    document.getElementById("form-title").innerText = "Add Ambulance";
};

// Save a new ambulance or update an existing one
const saveAmbulance = () => {
    const vehicleId = document.getElementById("vehicleId").value;
    const type = document.getElementById("type").value;
    const capacity = document.getElementById("capacity").value;
    const availability = document.getElementById("availability").value;

    if (!vehicleId || !type || !capacity) {
        alert("Please fill in all mandatory fields.");
        return;
    }

    if (capacity<1 || capacity>12) {
        alert("Please fill in correct capacity.");
        return;
    }

    const saveButton = document.getElementById("saveButton");
    const isEditing = saveButton.getAttribute("data-editing") === "true";
    const index = parseInt(saveButton.getAttribute("data-index"));

    if (isEditing && !isNaN(index)) {
        // Update the existing ambulance
        ambulances[index] = { vehicleId, type, capacity, availability };
        alert("Ambulance updated successfully!");

        // Reset the save button to default mode
        saveButton.removeAttribute("data-editing");
        saveButton.removeAttribute("data-index");
    } else {
        // Add a new ambulance
        ambulances.push({ vehicleId, type, capacity, availability });
        alert("Ambulance added successfully!");
    }

    displayAmbulances();
    closeForm();
};

// Edit an ambulance's details
const editAmbulance = (index) => {
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
};

// Delete an ambulance from the list
const deleteAmbulance = (index) => {
    if (confirm("Are you sure you want to delete this ambulance?")) {
        ambulances.splice(index, 1);
        displayAmbulances();
    }
};

// Display the list of ambulances
const displayAmbulances = () => {
    const ambulanceList = document.getElementById("ambulance-list");
    ambulanceList.innerHTML = ""; // Clear the current list

    // Check if there are any ambulances to display
    if (ambulances.length === 0) {
        ambulanceList.style.display = "none"; // Hide if no ambulances
    } else {
        ambulanceList.style.display = "block"; // Show if there are ambulances
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
};

// Show the form to add a new booking
const showAddBookingForm = () => {
    document.getElementById("booking-form").style.display = "block";
    document.getElementById("form-title").innerText = "Add Booking";
};

// Save a new booking
const saveBooking = () => {
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
};

// Display the list of bookings
const displayBookings = () => {
    const bookingList = document.getElementById("booking-list");
    bookingList.innerHTML = ""; // Clear the current list
    bookings.forEach((book, index) => {
        bookingList.innerHTML += `
            <div>
                <p>Booking Date: ${book.bookingDate}</p>
                <p>Booking Time: ${book.bookingTime}</p>
                <p>Destination: ${book.destination}</p>
                <button class="button" onclick="editBooking(${index})">Edit</button>
                <button class="button" onclick="deleteBooking(${index})">Delete</button>
            </div>
        `;
    });
};

// Close the form
const closeForm = () => {
    const bookingForm = document.getElementById("booking-form");
    const ambulanceForm = document.getElementById("ambulance-form");

    if (bookingForm) {
        bookingForm.style.display = "none";
    }
    if (ambulanceForm) {
        ambulanceForm.style.display = "none";
    }
};

// (Optional) Add functions to edit and delete bookings if needed
