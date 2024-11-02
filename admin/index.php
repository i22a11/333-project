<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="../components/dialog.js" defer></script>
    <script src="./js/components/StatsCard.js" defer></script>
    <script src="./js/components/RoomManagement.js" defer></script>
    <script src="./js/index.js" defer></script>
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $capacity = $_POST["capacity"];
    $equipment = $_POST["equipment"];

    if (empty($name) || empty($capacity) || empty($equipment)) {
        echo "<script>
                if (confirm('Please fill in all fields. Click OK to go back to the form.')) {
                    window.location.href = '/admin/';
                }
              </script>";
        exit;
    }

    $db = new PDO("mysql:host=127.0.0.1;dbname=csDB", "cs-user", "xqyCsCu");

    $query = "INSERT INTO Rooms (room_name, capacity, equipment) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$name, $capacity, $equipment]);
}
?>

<body class="bg-gray-900">
    <div class="flex h-screen bg-gray-100">
        <div class="flex flex-1 flex-col overflow-hidden">
            <header class="flex h-16 items-center justify-between bg-white px-6 shadow">
                <button class="text-gray-500 lg:hidden">
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>
                <div class="flex items-center">
                    <div class="relative">
                        <button class="flex items-center text-sm focus:outline-none">
                            <span class="hidden md:block">John Doe</span>
                            <i class="fas fa-chevron-down ml-1 h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <h1 class="mb-6 text-3xl font-semibold text-gray-800">Dashboard</h1>
                <div id="stats-container" class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4"></div>
                <div id="room-management" class="rounded-lg bg-white p-6 shadow"></div>
            </main>
        </div>
    </div>

    <custom-dialog id="add-room-dialog" title="Add Room" description="Add a new room to the system.">
        <form id="add-room-form" class="space-y-6" action="" method="POST">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                <input type="text" name="name" id="name" placeholder="Room Name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150 ease-in-out">
            </div>
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700">Room Capacity</label>
                <input type="number" name="capacity" id="capacity" placeholder="Room Capacity" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150 ease-in-out">
            </div>
            <div>
                <label for="equipment" class="block text-sm font-medium text-gray-700">Room Equipment</label>
                <textarea name="equipment" id="equipment" placeholder="Room Equipment" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150 ease-in-out resize-none h-24"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out" data-close>Cancel</button>
                <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </custom-dialog>
</body>

</html>