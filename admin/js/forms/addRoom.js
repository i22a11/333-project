// @ts-nocheck
import { InvokeCreateRoom } from "../api/rooms.js";
import { uploadFileToSupabase } from "../imageUpload.js";
import CustomDialog from "../components/dialog.js";

const showFieldError = (fieldId, message) => {
  const field = document.getElementById(fieldId);
  const errorDiv = field.parentElement.querySelector('.field-error');
  
  // Create error div if it doesn't exist
  if (!errorDiv) {
    const div = document.createElement('div');
    div.className = 'field-error text-red-500 text-sm mt-1';
    field.parentElement.appendChild(div);
    div.textContent = message;
  } else {
    errorDiv.textContent = message;
  }
  
  field.classList.add('border-red-500');
};

const clearFieldError = (fieldId) => {
  const field = document.getElementById(fieldId);
  const errorDiv = field.parentElement.querySelector('.field-error');
  if (errorDiv) {
    errorDiv.remove();
  }
  field.classList.remove('border-red-500');
};

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
    // Clear previous errors
    ['room-name', 'room-capacity', 'room-equipment'].forEach(fieldId => {
      clearFieldError(fieldId);
    });

    // @ts-ignore
    const name = document.getElementById("room-name").value.trim();
    const capacity = parseInt(
      // @ts-ignore
      document.getElementById("room-capacity").value.trim(),
      10
    );
    // @ts-ignore
    const equipment = document.getElementById("room-equipment").value.trim();
    // @ts-ignore
    const imageFile = document.getElementById("room-image").files[0];

    let hasError = false;

    if (!name) {
      showFieldError('room-name', 'Room name is required');
      hasError = true;
    }
    
    if (!capacity || isNaN(capacity)) {
      showFieldError('room-capacity', 'Valid capacity number is required');
      hasError = true;
    }
    
    if (!equipment) {
      showFieldError('room-equipment', 'Equipment information is required');
      hasError = true;
    }

    if (hasError) {
      return;
    }

    try {
      let imageUrl = null;
      const submitButton = formContainer.querySelector('button[type="submit"]');
      
      // Upload image if one is selected
      if (imageFile) {
        try {
          if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
          }

          imageUrl = await uploadFileToSupabase(imageFile);
        } catch (error) {
          console.error("Error uploading image:", error);
          alert("Failed to upload image. Please try again.");
          return;
        }
      }

      if (submitButton) {
        submitButton.textContent = 'Creating Room...';
      }

      const response = await InvokeCreateRoom({
        name,
        capacity,
        equipment,
        image_url: imageUrl
      });

      if (response.success) {
        dialog.close();
        window.location.reload();
      } else {
        console.error('Room creation failed:', response);
        if (response.details) {
          console.log('Debug details:', response.details);
        }
        alert(response.error || 'Failed to create room. Please try again.');
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = 'Create Room';
        }
      }
    } catch (error) {
      console.error('Room creation error:', error);
      alert('An error occurred while creating the room');
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = 'Create Room';
      }
    } finally {
      const submitButton = formContainer.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = 'Add Room';
      }
    }
  }
};
