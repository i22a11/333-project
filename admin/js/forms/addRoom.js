// @ts-nocheck
import { InvokeCreateRoom } from "../api/rooms.js";
import { uploadFileToSupabase } from "../imageUpload.js";
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

  if (formContainer && dialog) {
    // @ts-ignore
    const name = document.getElementById("room-name").value;
    const capacity = parseInt(
      // @ts-ignore
      document.getElementById("room-capacity").value,
      10
    );
    // @ts-ignore
    const equipment = document.getElementById("room-equipment").value;
    // @ts-ignore
    const imageFile = document.getElementById("room-image").files[0];

    if (!name || !capacity || !equipment) {
      alert("Please fill in all required fields.");
      return;
    }

    try {
      let imageUrl = null;
      
      // Upload image if one is selected
      if (imageFile) {
        try {
          const submitButton = formContainer.querySelector('button[type="submit"]');
          if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
          }

          imageUrl = await uploadFileToSupabase(imageFile);
        } catch (error) {
          console.error("Error uploading image:", error);
          alert("Failed to upload image. Please try again.");
          return;
        } finally {
          const submitButton = formContainer.querySelector('button[type="submit"]');
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Add Room';
          }
        }
      }

      // Create room with image URL if available
      await InvokeCreateRoom(name, capacity, equipment, imageUrl);
      alert("Room added successfully!");

      dialog.close();
      formContainer.reset();
      
      // Reset image preview
      const imagePreview = document.getElementById('image-preview');
      if (imagePreview) {
        imagePreview.classList.add('hidden');
        const previewImg = imagePreview.querySelector('img');
        if (previewImg) {
          previewImg.src = '';
        }
      }
    } catch (error) {
      console.error("Error adding room:", error);
      alert("Failed to add room. Please try again.");
    }
  }
};
