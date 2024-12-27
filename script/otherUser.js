function showUserDetails(user) {
    user = JSON.parse(decodeURIComponent(user));
    document.getElementById('modalName').innerText = `${user.name}`;
    document.getElementById('modalRole').innerText = `${user.role}`;
    document.getElementById('modalGender').innerText = `${user.gender}`;
    document.getElementById('modalEmail').innerText = `${user.email}`;
    document.getElementById('modalPhone').innerText = `${user.phone}`;
    document.getElementById('modalSchool').innerText = `${user.school}`;
    document.getElementById('modalImage').src = user.image || '../uploads/default.png';
    document.getElementById('userModal').style.display = 'flex';
}

// Close the modal
function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}