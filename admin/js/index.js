import StatsCard from "./components/StatsCard.js";
import RoomManagement from "./components/RoomManagement.js";
import { fetchRooms } from "./api/rooms.js";
import { addRoom } from "./forms/addRoom.js";

document.addEventListener("DOMContentLoaded", async () => {
  const statsContainer = document.getElementById("stats-container");
  const roomManagement = new RoomManagement("room-management");

  const rooms = (await fetchRooms()) ?? [];

  // Render stats cards
  const statsCards = [
    new StatsCard("fas fa-hotel", "Total Rooms", rooms.length, "blue"),
    new StatsCard("fas fa-users", "Occupied Rooms", "35", "green"),
    new StatsCard("fas fa-door-open", "Available Rooms", "15", "yellow"),
    new StatsCard("fas fa-calendar-check", "Today's Bookings", "8", "purple"),
  ];

  if (statsContainer) {
    statsContainer.innerHTML = statsCards.map((card) => card.render()).join("");
  }

  rooms.forEach((element) => {
    roomManagement.addRoom({
      id: element.id,
      name: element.name,
      capacity: element.capacity,
      equipment: element.equipment,
    });
  });

  roomManagement.render();

  document.getElementById("add-room-form").addEventListener("submit", async (ev) => {
    ev.preventDefault();
    await addRoom(document.getElementById("add-room-form"));
  });
});
