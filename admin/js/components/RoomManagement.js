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

  connectedCallback() {
    this.render();
    this.attachEventListeners();
  }

  attachEventListeners() {
    const addRoomBtn = this.container.querySelector('#add-room-btn');
    const addRoomDialog = document.querySelector('#add-room-dialog');
    const editRoomDialog = document.querySelector('#edit-room-dialog');

    if (addRoomBtn) {
      addRoomBtn.addEventListener('click', () => {
        addRoomDialog?.open();
      });
    }

    // Add event listeners for delete and edit buttons
    this.rooms.forEach(room => {
      const deleteButton = this.container.querySelector(`button[data-delete-room="${room.id}"]`);
      const editButton = this.container.querySelector(`button[data-edit-room="${room.id}"]`);
      
      if (deleteButton) {
        deleteButton.addEventListener('click', async () => {
          if (confirm('Are you sure you want to delete this room?')) {
            await InvokeDeleteRoom(room.id);
            this.rooms = this.rooms.filter(r => r.id !== room.id);
            this.render();
          }
        });
      }

      if (editButton) {
        editButton.addEventListener('click', () => {
          // Populate form fields
          const form = document.getElementById('edit-room-form');
          if (form) {
            const idInput = form.querySelector('#edit-room-id');
            const nameInput = form.querySelector('#edit-room-name');
            const capacityInput = form.querySelector('#edit-room-capacity');
            const equipmentInput = form.querySelector('#edit-room-equipment');

            if (idInput) idInput.value = room.id;
            if (nameInput) nameInput.value = room.name;
            if (capacityInput) capacityInput.value = room.capacity;
            if (equipmentInput) equipmentInput.value = room.equipment;
          }
          editRoomDialog?.open();
        });
      }
    });

    // Handle edit form submission
    const editForm = document.getElementById('edit-room-form');
    if (editForm) {
      editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        await editRoom(editForm, {
          id: editForm.querySelector('#edit-room-id').value,
          name: editForm.querySelector('#edit-room-name').value,
          capacity: parseInt(editForm.querySelector('#edit-room-capacity').value),
          equipment: editForm.querySelector('#edit-room-equipment').value
        });
        editRoomDialog?.close();
        // Refresh the page to show updated data
        window.location.reload();
      });
    }
  }

  /**
   *
   * @param {import("../types.mjs").Room} room
   */
  addRoom(room) {
    this.rooms.push(room);
    this.render();
  }

  /**
   * @param {import("../types.mjs").Room} room
   */
  renderRoomRow(room) {
    return `
      <tr>
        <td class="w-1/4 px-6 py-4">
          <div class="flex items-center">
            <div class="h-10 w-10 flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-blue-900/50 text-blue-400 flex items-center justify-center">
                <i class="fas fa-door-open"></i>
              </div>
            </div>
            <div class="ml-4">
              <div class="text-sm font-medium text-zinc-100">${room.name}</div>
            </div>
          </div>
        </td>
        <td class="w-1/6 px-6 py-4 text-center">
          <span class="inline-flex rounded-full bg-zinc-700/50 px-3 py-1 text-sm font-medium text-zinc-300">
            ${room.capacity} seats
          </span>
        </td>
        <td class="w-2/5 px-6 py-4">
          <div class="text-sm text-zinc-300">${room.equipment || 'No equipment listed'}</div>
        </td>
        <td class="w-1/6 px-6 py-4 text-center">
          <div class="flex justify-center space-x-2">
            <button data-edit-room="${room.id}"
              class="rounded-md bg-blue-900/50 p-2 text-blue-400 hover:bg-blue-900/70 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
              <i class="fas fa-edit"></i>
            </button>
            <button data-delete-room="${room.id}"
              class="rounded-md bg-red-900/50 p-2 text-red-400 hover:bg-red-900/70 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  }

  render() {
    // Clear existing content
    this.container.innerHTML = `
      <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-bold text-zinc-100">Room Management</h2>
       <div class="flex items-center space-x-4">
           <button id="add-room-btn" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-800 transition-colors duration-200">
          <i class="fas fa-plus-circle mr-2 h-4 w-4"></i>
          Add Room
        </button>
         <a href="/admin/bookings.php" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-800 transition-colors duration-200">
                        <i class="fas fa-calendar-alt mr-2 h-4 w-4"></i>
                        Manage Bookings
         </a>
       </div>
      </div>
      <div class="relative overflow-hidden rounded-lg border border-zinc-700 bg-zinc-800 shadow-sm">
        <table class="w-full table-fixed divide-y divide-zinc-700">
          <thead>
            <tr class="bg-zinc-800/50">
              <th scope="col" class="w-1/4 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Room Name</th>
              <th scope="col" class="w-1/6 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-400">Capacity</th>
              <th scope="col" class="w-2/5 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">Equipment</th>
              <th scope="col" class="w-1/6 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-400">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-700 bg-zinc-800">
            ${this.rooms.map((room) => this.renderRoomRow(room)).join("")}
            ${
              this.rooms.length === 0
                ? `
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-400">
                  <div class="flex flex-col items-center justify-center space-y-2">
                    <i class="fas fa-inbox text-zinc-500 text-4xl mb-2"></i>
                    <p>No rooms available</p>
                    <p class="text-xs text-zinc-500">Click "Add Room" to create a new room</p>
                  </div>
                </td>
              </tr>
            `
                : ""
            }
          </tbody>
        </table>
      </div>

      <!-- Edit Room Dialog -->
      <custom-dialog id="edit-room-dialog" title="Edit Room" description="Update room details">
        <form id="edit-room-form" class="space-y-4">
          <input type="hidden" name="id" id="edit-room-id">
          <div>
            <label for="edit-room-name" class="block text-sm font-medium text-zinc-300">Room Name</label>
            <input type="text" id="edit-room-name" name="name" 
              class="mt-1 block w-full rounded-md bg-zinc-700 border-zinc-600 px-4 py-3 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
          </div>
          <div>
            <label for="edit-room-capacity" class="block text-sm font-medium text-zinc-300">Capacity</label>
            <input type="number" id="edit-room-capacity" name="capacity" min="1" 
              class="mt-1 block w-full rounded-md bg-zinc-700 border-zinc-600 px-4 py-3 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
          </div>
          <div>
            <label for="edit-room-equipment" class="block text-sm font-medium text-zinc-300">Equipment</label>
            <input type="text" id="edit-room-equipment" name="equipment" 
              class="mt-1 block w-full rounded-md bg-zinc-700 border-zinc-600 px-4 py-3 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
          </div>
        </form>
        <button slot="confirm" type="submit" 
          class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
          Save Changes
        </button>
      </custom-dialog>
    `;

    // Re-attach event listeners
    this.attachEventListeners();
  }
}

customElements.define("room-management", RoomManagement);
