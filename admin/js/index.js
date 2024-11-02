const fetchRooms = async () => {
  try {
    const res = await fetch("/admin/info");

    return res.json();
  } catch (error) {
    alert("error fetching rooms, please try again later");
  }
};

document.addEventListener("DOMContentLoaded", async () => {
  const statsContainer = document.getElementById("stats-container");
  const roomManagement = new RoomManagement("room-management");

  const rooms = await fetchRooms();

  // Render stats cards
  const statsCards = [
    new StatsCard("fas fa-hotel", "Total Rooms", rooms.length, "blue"),
    new StatsCard("fas fa-users", "Occupied Rooms", "35", "green"),
    new StatsCard("fas fa-door-open", "Available Rooms", "15", "yellow"),
    new StatsCard("fas fa-calendar-check", "Today's Bookings", "8", "purple"),
  ];

  statsContainer.innerHTML = statsCards.map((card) => card.render()).join("");


  rooms.forEach((element) => {
    roomManagement.addRoom({
      number: element.room_id,
      name:  element.room_name,
      capacity: element.capacity,
      equipment: element.equipment,
    });
  });
});
