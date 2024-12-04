// Fetch user bookings when the page loads
document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('./booking/get_bookings.php'); // Fetch bookings from the php file
        const data = await response.json();

        const tableBody = document.getElementById('bookingsTableBody');
        // If there is an error (returned by the php), display the error message in the table
        if (data.error) {
            tableBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
            return;
        }
        // If there is no error, display the bookings in the table
        tableBody.innerHTML = data.map(booking => `
            <tr>
                <td>${booking.room_name}</td>
                <td>${new Date(booking.start_time).toLocaleString()}</td>
                <td>${new Date(booking.end_time).toLocaleString()}</td>
                <td>${booking.status}</td>
                <td>
                    ${booking.status === 'confirmed' ? `<button class="cancelButton" data-booking-id="${booking.booking_id}">Cancel</button>` : 'N/A'}
                </td>
            </tr>
        `).join(''); // Here the map iterates over the bookings and for each one and create a row. The join('') is used to join the rows together.
    } catch (error) {
        //console.error('Error fetching bookings:', error);
    }
});

//Fetch rooms when the page loads
document.addEventListener('DOMContentLoaded', async function () {
    try {
        const response = await fetch('./booking/get_rooms.php'); // Fetch rooms from the php file
        const data = await response.json();

        const select = document.getElementById('room');
        // If there is an error (returned by the php), display the error message in the select
        if (data.error) {
            console.log(data.message);
            select.innerHTML = `<option>No rooms were found..</option>`;
            return;
        }

        // If there is no error, display the rooms in the select
        select.innerHTML = data.map(room => `
            <option value="${room.room_id}">${room.room_name}</option>
        `).join(''); // Here the map iterates over the rooms and for each one and create an option. The join('') is used to join the options together.
    } catch (error) {
        console.error('Error fetching rooms:', error);
    }

});

// Event listener when a click happens
document.addEventListener('click', async function (event) {
    // If the click is on the cancel button
    if (event.target.classList.contains('cancelButton')) {
        // Get the booking id from the button 
        const bookingId = event.target.dataset.bookingId;
        // Send a request to the server to cancel the booking
        try {
            const response = await fetch('cancel_booking.php', {
                method: 'POST',
                body: JSON.stringify({ booking_id: bookingId }),
                headers: { 'Content-Type': 'application/json' }
            });

            const data = await response.json();
            alert(data.message);

            if (data.success) {
                location.reload(); // Refresh the table to show the updated bookings
            }
        } catch (error) {
            console.error('Error canceling booking:', error);
        }
    }
});

// Event listener for View Available time button
document.getElementById('view-times-btn').addEventListener('click', async function () {
    const room = document.getElementById('room').value;
    const date = document.getElementById('date').value;
    
    if (!room || !date) {
        alert('Please select both room and date.');
        return;
    }

    try {
        const response = await fetch('./booking/get_available_times.php', { // Replace with actual API endpoint
            method: 'POST',
            body: JSON.stringify({ room_id: room, date: date }),
            headers: { 'Content-Type': 'application/json' }
        });
        const data = await response.json();

        if (data.error) {
            console.log(data.error);
            return;
        }

        const timeSelect = document.getElementById('time');
        timeSelect.innerHTML = data.map(time => `<option value="${time}">${time}</option>`).join('');

        // Show the available times container and hide the "View Available Times" button
        document.getElementById('available-times-container').classList.remove('hidden');
        this.style.display = 'none';
    } catch (error) {
        console.error('Error fetching available times:', error);
    }
});

// Event listener for Book Room button
document.getElementById('book-btn').addEventListener('click', async function () {
    const room = document.getElementById('room').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;

    if (!time) {
        alert('Please select a time.');
        return;
    }

    try {
        const response = await fetch('./booking/book_room.php', {
            method: 'POST',
            body: JSON.stringify({ room_id: room, booking_date: date, booking_time: time }),
            headers: { 'Content-Type': 'application/json' }
        });
        const result = await response.json();
        alert(result.message);

        if (result.success) {
            document.getElementById('booking-form').reset();
            document.getElementById('available-times-container').classList.add('hidden');
            document.getElementById('view-times-btn').style.display = 'block';
            location.reload(); // Refresh the table to show updated bookings
        }
    } catch (error) {
        console.error('Error booking room:', error);
    }
});
