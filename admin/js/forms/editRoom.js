import { InvokeEditRoom } from "../api/rooms.js";

/**
 * Handles the edit room form submission
 * @param {HTMLFormElement} form
 * @param {import("../types.mjs").Room} currentRoom
 * @returns {Promise<void>}
 */
export const editRoom = async (form, currentRoom) => {
  const formData = new FormData(form);
  
  // Get form values with type assertions
  const nameValue = formData.get("name");
  const capacityValue = formData.get("capacity");
  const equipmentValue = formData.get("equipment");

  // Validate and convert form values
  const name = typeof nameValue === "string" ? nameValue : "";
  const capacity = capacityValue ? parseInt(String(capacityValue)) : 0;
  const equipment = typeof equipmentValue === "string" ? equipmentValue : "";

  const room = {
    id: currentRoom.id,
    name,
    capacity,
    equipment
  };

  await InvokeEditRoom(room);
  
  // Refresh the page to show updated data
  window.location.reload();
};
