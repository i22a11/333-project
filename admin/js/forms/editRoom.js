import { InvokeEditRoom } from "../api/rooms.js";
import { uploadFileToSupabase } from "../imageUpload.js";

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
  // @ts-ignore
  const imageFile = form.querySelector('#room-image')?.files[0];

  // Validate and convert form values
  const name = typeof nameValue === "string" ? nameValue : "";
  const capacity = capacityValue ? parseInt(String(capacityValue)) : 0;
  const equipment = typeof equipmentValue === "string" ? equipmentValue : "";

  let image_url = currentRoom.image_url;

  // Upload new image if selected
  if (imageFile) {
    try {
      const submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        // @ts-ignore
        submitButton.disabled = true;
        submitButton.textContent = 'Uploading...';
      }

      image_url = await uploadFileToSupabase(imageFile);
    } catch (error) {
      console.error("Error uploading image:", error);
      alert("Failed to upload image. Please try again.");
      return;
    } finally {
      const submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        // @ts-ignore
        submitButton.disabled = false;
        submitButton.textContent = 'Save Changes';
      }
    }
  }

  const room = {
    id: currentRoom.id,
    name,
    capacity,
    equipment,
    image_url
  };

  await InvokeEditRoom(room);
  
  // Reset image preview
  const imagePreview = document.getElementById('image-preview');
  if (imagePreview) {
    imagePreview.classList.add('hidden');
    const previewImg = imagePreview.querySelector('img');
    if (previewImg) {
      previewImg.src = '';
    }
  }

  // Refresh the page to show updated data
  window.location.reload();
};
