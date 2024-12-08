// Function to preview the selected image
// @ts-ignore
export function previewImage(input) {
    const preview = document.getElementById('image-preview');
    // @ts-ignore
    const previewImg = preview.querySelector('img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // @ts-ignore
            previewImg.src = e.target.result;
            // @ts-ignore
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        // @ts-ignore
        previewImg.src = '';
        // @ts-ignore
        preview.classList.add('hidden');
    }
}

export async function getSupabaseCredentials() {
  try {
    const response = await fetch("/admin/api/load-env/index.php");
    if (!response.ok) {
      throw new Error("Failed to load environment variables.");
    }

    const credentials = await response.json();
    return credentials;
  } catch (error) {
    // @ts-ignore
    console.error("Error fetching Supabase credentials:", error.message);
    throw error;
  }
}


/**
 * 
 * @param {File} file 
 * @returns 
 */
export async function uploadFileToSupabase(file) {
  try {
    const { supabaseUrl, supabaseKey, bucketName } =
      await getSupabaseCredentials();

    if (!file) {
      throw new Error("No file provided.");
    }

    const fileName = `${Date.now()}-${encodeURIComponent(file.name)}`;
    const uploadUrl = `${supabaseUrl}/storage/v1/object/${bucketName}/${fileName}`;

    const response = await fetch(uploadUrl, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${supabaseKey}`,
        "Content-Type": file.type,
      },
      body: file,
    });

    if (response.ok) {
      const publicUrl = `${supabaseUrl}/storage/v1/object/public/${bucketName}/${fileName}`;
      return publicUrl;
    } else {
      const error = await response.text();
      throw new Error(`Upload failed: ${error}`);
    }
  } catch (error) {
    // @ts-ignore
    console.error("Error uploading file:", error.message);
    throw error;
  }
}
