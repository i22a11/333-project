// @ts-nocheck
import { InvokeDeleteRoom } from "../api/rooms.js";
import { editRoom } from "../forms/editRoom.js";

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

            <!-- Edit Room Dialog -->
            <custom-dialog id="edit-room-dialog" title="Edit Room" description="Update room details">
                <form id="edit-room-form" class="space-y-4">
                    <input type="hidden" name="id" id="edit-room-id">
                    <div>
                        <label for="edit-room-name" class="block text-sm font-medium text-gray-700">Room Name</label>
                        <input type="text" id="edit-room-name" name="name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="edit-room-capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" id="edit-room-capacity" name="capacity" min="1" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="edit-room-equipment" class="block text-sm font-medium text-gray-700">Equipment</label>
                        <input type="text" id="edit-room-equipment" name="equipment" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </form>
                <button slot="cancel" type="button">Cancel</button>
                <button slot="confirm" type="submit">Save Changes</button>
            </custom-dialog>
        `;

    // Add event listeners for delete buttons
    this.rooms.forEach(room => {
      const deleteButton = this.container.querySelector(`button[data-delete-room="${room.id}"]`);
      const editButton = this.container.querySelector(`button[data-edit-room="${room.id}"]`);
      
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

      if (editButton) {
        // Remove any existing listeners
        const newEditButton = editButton.cloneNode(true);
        editButton.parentNode.replaceChild(newEditButton, editButton);
        
        newEditButton.addEventListener('click', () => {
          const dialog = /** @type {import("./dialog").default} */ (
            document.getElementById('edit-room-dialog')
          );
          const form = document.getElementById('edit-room-form');
          
          if (!dialog || !form) return;
          
          // Populate form with current room data
          const idInput = form.querySelector('#edit-room-id');
          const nameInput = form.querySelector('#edit-room-name');
          const capacityInput = form.querySelector('#edit-room-capacity');
          const equipmentInput = form.querySelector('#edit-room-equipment');
          
          if (idInput instanceof HTMLInputElement) idInput.value = room.id;
          if (nameInput instanceof HTMLInputElement) nameInput.value = room.name;
          if (capacityInput instanceof HTMLInputElement) capacityInput.value = String(room.capacity);
          if (equipmentInput instanceof HTMLInputElement) equipmentInput.value = room.equipment;
          
          // Add confirm event listener
          const confirmHandler = async () => {
            if (form instanceof HTMLFormElement) {
              await editRoom(form, room);
              dialog.removeEventListener('confirm', confirmHandler);
            }
          };
          
          dialog.addEventListener('confirm', confirmHandler);
          dialog.open();
        });
      }
    });

    const addRoomBtn = document.getElementById("add-room-btn");
    if (addRoomBtn) {
      const newAddRoomBtn = addRoomBtn.cloneNode(true);
      addRoomBtn.parentNode.replaceChild(newAddRoomBtn, addRoomBtn);
      newAddRoomBtn.addEventListener("click", () => {
        document.getElementById("add-room-dialog")?.showModal();
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
                  <button class="mr-2 text-indigo-600 hover:text-indigo-900" data-edit-room="${room.id}">Edit</button>
                  <button class="text-red-600 hover:text-red-900" data-delete-room="${room.id}">Delete</button>
              </td>
          </tr>
      `;
  }
}

customElements.define("room-management", RoomManagement);
