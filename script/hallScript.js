function addHall() {
    // Get hall details from form inputs
    const name = document.getElementById('hallName').value;
    const id = document.getElementById('hallID').value;
    const capacity = document.getElementById('hallCapacity').value;
    const location = document.getElementById('hallLocation').value;
    const facility = document.getElementById('hallFacility').value;

    // Check if inputs are not empty
    if (name && capacity && location) {
        // Create a new list item to display hall info
        const hallItem = document.createElement('li');
        hallItem.textContent = `Name: ${name}, ID: ${id}, Capacity: ${capacity}, Location: ${location}, Facility: ${facility}`;
        
        // Add the list item to the hall list
        document.getElementById('hallList').appendChild(hallItem);

        // Clear the form inputs
        document.getElementById('hallForm').reset();
    } else {
        alert("Please fill in all fields.");
    }
}