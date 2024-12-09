/**
 *
 * @param {string} id
 * @returns
 */
export const InvokeDeleteRoom = async (id) => {
  if (!id) {
    alert("Room ID is required to delete a room.");
    return;
  }

  try {
    const response = await fetch("/admin/api/delete-room", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    });

    if (!response.ok) {
      throw new Error("Failed to delete the room.");
    }

    const result = await response.json();
    if (result.success) {
      alert(result.message || "Room deleted successfully!");
    } else {
      alert(result.message || "Failed to delete the room.");
    }
  } catch (error) {
    console.error("Error deleting room:", error);
    alert("An error occurred while trying to delete the room.");
  }
};

/**
 *
 * @param {Object} params
 * @param {string} params.name
 * @param {number} params.capacity
 * @param {string} params.equipment
 * @param {string|null} params.image_url
 */
export const InvokeCreateRoom = async ({ name, capacity, equipment, image_url = null }) => {
  if (name && capacity && equipment) {
    console.log("invoking create-room");

    try {
      const response = await fetch("/admin/api/create-room/", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name,
          capacity,
          equipment,
          image_url
        }),
      });

      const data = await response.json();
      
      if (response.status === 201 && data.success) {
        return { success: true };
      }
      
      throw new Error(data.message || "Failed to create room");
    } catch (error) {
      console.error("Error creating room:", error);
      return { 
        success: false, 
        details: {
          endpoint: "/admin/api/create-room/",
          requestData: { name, capacity, equipment, image_url }
        }
      };
    }
  } else {
    return { 
      success: false, 
      error: "Missing required fields",
      details: {
        name: Boolean(name),
        capacity: Boolean(capacity),
        equipment: Boolean(equipment)
      }
    };
  }
};

/**
 * Fetches the list of rooms from the server.
 * @returns {Promise<import("../types.mjs").Room[] | undefined>}
 */
export const fetchRooms = async () => {
  try {
    const res = await fetch("/admin/api/info");
    const rooms = await res.json();
    
    // @ts-ignore
    let parsedRooms = rooms.map((room) => ({
      id: room.room_id,
      name: room.room_name,
      capacity: room.capacity,
      equipment: room.equipment
    }));

    return parsedRooms;
  } catch (error) {
    alert("error fetching rooms, please try again later");
  }
};

/**
 * Invokes the edit room API endpoint
 * @param {import("../types.mjs").Room} room
 * @returns {Promise<void>}
 */
export const InvokeEditRoom = async (room) => {
  if (!room.id || !room.name || !room.capacity || !room.equipment) {
    alert("All fields are required to edit a room.");
    return;
  }

  try {
    const response = await fetch("/admin/api/edit-room/index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(room),
    });

    if (!response.ok) {
      throw new Error("Failed to edit the room.");
    }

    const result = await response.json();
    if (result.success) {
      alert(result.message || "Room edited successfully!");
    } else {
      alert(result.message || "Failed to edit the room.");
    }
  } catch (error) {
    console.error("Error editing room:", error);
    alert("An error occurred while trying to edit the room.");
  }
};
