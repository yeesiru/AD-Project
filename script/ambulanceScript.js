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
    // Get form data
    const vehicleId = document.getElementById("vehicleId").value;
    const type = document.getElementById("type").value;
    const capacity = document.getElementById("capacity").value;
    const availability = document.getElementById("availability").value;

    // Check for required fields
    if (!vehicleId || !type || !capacity) {
        alert("Please fill in all mandatory fields.");
        return;
    }

    if (capacity < 1 || capacity > 12) {
        alert("Please enter a valid capacity (1-12).");
        return;
    }

    // Prepare data to be sent
    const formData = new FormData();
    formData.append("vehicleId", vehicleId);
    formData.append("type", type);
    formData.append("capacity", capacity);
    formData.append("availability", availability);

    // Send data to add_ambulance.php
    fetch("add_ambulance.php", {
        method: "POST",
        body: formData,
    })
    .then(response => {
        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`Network response was not ok, status: ${response.status}`);
        }
        return response.json(); // Parse JSON response
    })
    .then(data => {
        if (data.status === "success") {
            alert("Ambulance added successfully!");
            displayAmbulances(); // Refresh the list (assuming this function pulls updated data from the database)
            closeForm();
        } else {
            throw new Error(data.message || "Failed to add ambulance");
        }
    })
    .catch(error => {
        console.error("Error:", error); // Log the error to the console
        alert("Failed to add ambulance. " + error.message);
    });
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
        const ambulanceId = ambulances[index].id; // Get the id of the ambulance to delete
        const formData = new FormData();
        formData.append("id", ambulanceId);

        fetch('delete_ambulance.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Ambulance deleted successfully!");
                displayAmbulances();
            } else {
                alert(data.message || "An error occurred");
            }
        })
        .catch(error => console.error('Error:', error));
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

window.onload = () => {
    fetch('fetch_ambulances.php')
        .then(response => response.json())
        .then(data => {
            ambulances = data;
            displayAmbulances();
        })
        .catch(error => console.error('Error fetching ambulances:', error));
};

window.onload = displayAmbulances;