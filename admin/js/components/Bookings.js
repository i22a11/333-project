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
    const baseClasses = "px-2 py-1 text-xs font-medium rounded-full";
    switch (status) {
      case "pending":
        return `${baseClasses} bg-yellow-100 text-yellow-800`;
      case "confirmed":
        return `${baseClasses} bg-green-100 text-green-800`;
      case "cancelled":
        return `${baseClasses} bg-red-100 text-red-800`;
      default:
        return `${baseClasses} bg-gray-100 text-gray-800`;
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
            <div class="rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${this.bookings
                              .map(
                                (booking) => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">${
                                                  booking.user_name
                                                }</div>
                                                <div class="text-sm text-gray-500">${
                                                  booking.user_email
                                                }</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${
                                          booking.room_name
                                        }</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${this.formatDateTime(
                                          booking.date,
                                          booking.time
                                        )}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="${this.getStatusBadgeClass(
                                          booking.status
                                        )}">
                                            ${
                                              booking.status
                                                .charAt(0)
                                                .toUpperCase() +
                                              booking.status.slice(1)
                                            }
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        ${
                                          booking.status === "pending"
                                            ? `
                                            <button 
                                                data-booking-id="${booking.booking_id}" 
                                                data-action="confirm"
                                                class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded-md">
                                                Confirm
                                            </button>
                                            <button 
                                                data-booking-id="${booking.booking_id}" 
                                                data-action="cancel"
                                                class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md">
                                                Cancel
                                            </button>
                                        `
                                            : ""
                                        }
                                    </td>
                                </tr>
                            `
                              )
                              .join("")}
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
