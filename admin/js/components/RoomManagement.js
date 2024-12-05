// @ts-nocheck
import { InvokeDeleteRoom } from "../api/rooms.js";

export default class RoomManagement extends HTMLElement {
  /**
   * 
   * @param {string} containerId 
   */
  constructor(containerId) {
    super()
    this.container = document.getElementById(containerId) ?? new HTMLElement();
    
    /** @type {import("../types.mjs").Room[]} */
    this.rooms = [];
  }

  /**
   *
   * @param {import("../types.mjs").Room} room
   */
  addRoom(room) {
    this.rooms.push(room);
    this.render();
  }

  render() {
    // Clear existing content
    this.container.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Room Management</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Room Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Equipment</th>
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

    // Add event listeners for delete buttons
    this.rooms.forEach(room => {
      const deleteButton = this.container.querySelector(`button[data-delete-room="${room.id}"]`);
      if (deleteButton) {
        // Remove any existing listeners
        const newDeleteButton = deleteButton.cloneNode(true);
        deleteButton.parentNode.replaceChild(newDeleteButton, deleteButton);
        
        newDeleteButton.addEventListener('click', async () => {
          if (confirm(`Are you sure you want to delete room "${room.name}"?`)) {
            await InvokeDeleteRoom(room.id);
            // Remove the room from the local array and re-render only if delete was successful
            this.rooms = this.rooms.filter(r => r.id !== room.id);
            this.render();
          }
        });
      }
    });

    const addRoomBtn = document.getElementById("add-room-btn");
    if (addRoomBtn) {
      const newAddRoomBtn = addRoomBtn.cloneNode(true);
      addRoomBtn.parentNode.replaceChild(newAddRoomBtn, addRoomBtn);
      newAddRoomBtn.addEventListener("click", () => {
        document.getElementById("add-room-dialog")?.open();
      });
    }
  }

  /**
   *
   * @param {import("../types.mjs").Room} room
   * @returns
   */
  renderRoomRow(room) {
    return `
          <tr>
              <td class="whitespace-nowrap px-6 py-4">${room.name}</td>
              <td class="whitespace-nowrap px-6 py-4">${room.capacity}</td>
              <td class="whitespace-nowrap px-6 py-4">${room.equipment}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                  <button class="mr-2 text-indigo-600 hover:text-indigo-900">Edit</button>
                  <button class="text-red-600 hover:text-red-900" data-delete-room="${room.id}">Delete</button>
              </td>
          </tr>
      `;
  }
}

customElements.define("room-management", RoomManagement);
