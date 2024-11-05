// Show the form to add a new hall
function showAddHallForm() {
    document.getElementById("form-title").textContent = "Add Hall";
    document.getElementById("hallId").value = "";
    document.getElementById("name").value = "";
    document.getElementById("capacity").value = "";
    document.getElementById("location").value = "";
    document.getElementById("facility").value = "";
    document.getElementById("hall-form").style.display = "block";
    document.querySelector(".overlay").style.display = "block";
}

// Close the form popup
function closeForm() {
    document.getElementById("hall-form").style.display = "none";
    document.querySelector(".overlay").style.display = "none";
}

// Save hall information
function saveHall() {
    const hallId = document.getElementById("hallId").value;
    const name = document.getElementById("name").value;
    const capacity = document.getElementById("capacity").value;
    const location = document.getElementById("location").value;
    const facility = document.getElementById("facility").value;
    
    // Validate form fields
    if (!hallId || !name || !capacity || !location || !facility) {
        alert("Please fill out all fields.");
        return;
    }

    // Create a hall item element
    const hallList = document.getElementById("hall-list");
    const hallItem = document.createElement("div");
    hallItem.className = "hall-item";
    hallItem.innerHTML = `
        <strong>Hall ID:</strong> ${hallId} <br>
        <strong>Name:</strong> ${name} <br>
        <strong>Capacity:</strong> ${capacity} <br>
        <strong>Location:</strong> ${location} <br>
        <strong>Facility:</strong> ${facility} <br>
        <button onclick="editHall(this)">Edit</button>
        <button onclick="deleteHall(this)">Delete</button>
    `;

    // Add the new hall item to the hall list
    hallList.appendChild(hallItem);

    // Close the form after saving
    closeForm();
}

// Edit hall information
function editHall(button) {
    const hallItem = button.parentNode;
    const hallId = hallItem.querySelector("strong:nth-child(1)").nextSibling.nodeValue.trim();
    const name = hallItem.querySelector("strong:nth-child(2)").nextSibling.nodeValue.trim();
    const capacity = hallItem.querySelector("strong:nth-child(3)").nextSibling.nodeValue.trim();
    const location = hallItem.querySelector("strong:nth-child(4)").nextSibling.nodeValue.trim();
    const facility = hallItem.querySelector("strong:nth-child(5)").nextSibling.nodeValue.trim();

    document.getElementById("form-title").textContent = "Edit Hall";
    document.getElementById("hallId").value = hallId;
    document.getElementById("name").value = name;
    document.getElementById("capacity").value = capacity;
    document.getElementById("location").value = location;
    document.getElementById("facility").value = facility;
    showAddHallForm();
}

// Delete hall information
function deleteHall(button) {
    const hallItem = button.parentNode;
    hallItem.remove();
}

// Event listener for the overlay to close the form when clicked
document.querySelector(".overlay").addEventListener("click", closeForm);
