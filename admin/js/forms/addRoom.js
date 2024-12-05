import { InvokeCreateRoom } from "../api/rooms.js";
import CustomDialog from "../components/dialog.js";

/**
 *
 * @param {HTMLElement | null} formContainer
 */
export const addRoom = async (formContainer) => {
  /**
   * @type {CustomDialog | null}
   */
  const dialog = document.querySelector("custom-dialog#add-room-dialog");

  console.log(dialog);
  console.log(formContainer);

  if (formContainer && dialog) {
    // @ts-ignore
    const name = document.getElementById("name").value;
    const capacity = parseInt(
      // @ts-ignore
      document.getElementById("capacity").value,
      10
    );
    // @ts-ignore
    const equipment = document.getElementById("equipment").value;

    if (!name || !capacity || !equipment) {
      alert("Please fill in all fields.");
      return;
    }

    try {
      await InvokeCreateRoom(name, capacity, equipment);
      alert("Room added successfully!");

      dialog.close();
      // @ts-ignore
      formContainer.reset();
    } catch (error) {
      console.error("Error adding room:", error);
      alert("Failed to add room. Please try again.");
    }
  }
};
