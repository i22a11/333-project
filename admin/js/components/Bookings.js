export class Bookings extends HTMLElement {
  constructor() {
    super();
    /**
     * @type {any[]}
     */
    this.bookings = [];
  }

  async connectedCallback() {
    await this.fetchBookings();
    this.render();
    this.attachEventListeners();
  }

  async fetchBookings() {
    try {
      const response = await fetch("/admin/api/bookings");
      const data = await response.json();
      console.log(data);
      if (data.success) {
        this.bookings = data.data;
      }
    } catch (error) {
      console.error("Failed to fetch bookings:", error);
    }
  }

  /**
   *
   * @param {string} bookingId
   * @param {string} status
   */
  async updateBookingStatus(bookingId, status) {
    try {
      const response = await fetch("/admin/api/bookings/update-status", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ booking_id: bookingId, status }),
      });
      const data = await response.json();
      if (data.success) {
        await this.fetchBookings();
        window.location.reload();
      }
    } catch (error) {
      console.error("Failed to update booking status:", error);
    }
  }

  /**
   *
   * @param {string} status
   * @returns
   */
  getStatusBadgeClass(status) {
    const baseClasses = "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium";
    switch (status) {
      case "pending":
        return `${baseClasses} bg-yellow-50 text-yellow-800`;
      case "confirmed":
        return `${baseClasses} bg-green-50 text-green-800`;
      case "cancelled":
        return `${baseClasses} bg-red-50 text-red-800`;
      default:
        return `${baseClasses} bg-gray-50 text-gray-800`;
    }
  }

  /**
   *
   * @param {string} date
   * @param {string} time
   * @returns
   */
  formatDateTime(date, time) {
    const dateObj = new Date(`${date}T${time}`);
    return new Intl.DateTimeFormat("en-US", {
      dateStyle: "medium",
      timeStyle: "short",
    }).format(dateObj);
  }

  render() {
    this.innerHTML = `
            <div class="relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="w-1/4 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">User</th>
                                <th scope="col" class="w-1/6 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Room</th>
                                <th scope="col" class="w-1/4 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date & Time</th>
                                <th scope="col" class="w-1/6 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th scope="col" class="w-1/6 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            ${this.bookings.length === 0 ? `
                              <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                  <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-2"></i>
                                    <p>No bookings available</p>
                                    <p class="text-xs text-gray-400">Bookings will appear here when users make reservations</p>
                                  </div>
                                </td>
                              </tr>
                            ` : this.bookings.map((booking) => `
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">${booking.user_name}</div>
                                                <div class="text-xs text-gray-500">${booking.user_email}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-door-open text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900">${booking.room_name}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900">${this.formatDateTime(booking.date, booking.time)}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="${this.getStatusBadgeClass(booking.status)}">
                                            ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        ${booking.status === "pending" ? `
                                            <div class="flex space-x-2">
                                                <button 
                                                    data-booking-id="${booking.booking_id}" 
                                                    data-action="confirm"
                                                    class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1.5 text-sm font-medium text-green-700 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-check mr-1.5 h-3.5 w-3.5"></i>
                                                    Confirm
                                                </button>
                                                <button 
                                                    data-booking-id="${booking.booking_id}" 
                                                    data-action="cancel"
                                                    class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                    <i class="fas fa-times mr-1.5 h-3.5 w-3.5"></i>
                                                    Cancel
                                                </button>
                                            </div>
                                        ` : ""}
                                    </td>
                                </tr>
                            `).join("")}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
  }

  attachEventListeners() {
    this.querySelectorAll("button[data-booking-id]").forEach((button) => {
      button.addEventListener("click", (e) => {
        // @ts-ignore
        if (e.target && e.target.dataset) {
          // @ts-ignore
          const bookingId = e.target.dataset.bookingId;
          // @ts-ignore
          const action = e.target.dataset.action;
          const status = action === "confirm" ? "confirmed" : "cancelled";
          this.updateBookingStatus(bookingId, status);
        }
      });
    });
  }
}

customElements.define("bookings-table", Bookings);
