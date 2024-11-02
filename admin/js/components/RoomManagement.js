class RoomManagement {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    this.rooms = [];
  }

  addRoom(room) {
    this.rooms.push(room);
    this.render();
  }

  render() {
    this.container.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Room Management</h2>
                <button id="add-room-btn" class="flex items-center rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    <i class="fas fa-plus-circle mr-2 h-5 w-5"></i>
                    Add Room
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Room Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Equipment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        ${this.rooms
                          .map((room) => this.renderRoomRow(room))
                          .join("")}
                    </tbody>
                </table>
            </div>
        `;

    document.getElementById("add-room-btn").addEventListener("click", () => {
      document.getElementById("add-room-dialog").open();
    });
  }

  renderRoomRow(room) {
    return `
            <tr>
                <td class="whitespace-nowrap px-6 py-4">${room.number}</td>
                <td class="whitespace-nowrap px-6 py-4">${room.capacity}</td>
                <td class="whitespace-nowrap px-6 py-4">$${room.equipment}</td>
                <td class="whitespace-nowrap px-6 py-4">
                    <span class="rounded-full bg-${
                      room.status === "Available" ? "green" : "red"
                    }-100 px-2 py-1 text-xs font-medium text-${
      room.status === "Available" ? "green" : "red"
    }-800">${room.status}</span>
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                    <button class="mr-2 text-indigo-600 hover:text-indigo-900">Edit</button>
                    <button class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        `;
  }
}

customElements.define("room-management", RoomManagement);
