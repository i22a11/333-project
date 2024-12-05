<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="/admin/js/index.js" type="module" defer></script>
</head>

<body class="bg-gray-900">
    <div class="flex h-screen bg-gray-100">
        <div class="flex flex-1 flex-col overflow-hidden">
            <header class="flex h-16 items-center justify-between bg-white px-6 shadow">
                <button class="text-gray-500 lg:hidden">
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>
                <div class="flex items-center justify-between w-full">
                    <div class="relative">
                        <button class="flex items-center text-sm focus:outline-none">
                            <span class="hidden md:block">John Doe</span>
                            <i class="fas fa-chevron-down ml-1 h-4 w-4"></i>
                        </button>
                    </div>

                    <a href="/admin/bookings.php" class="flex items-center rounded-md bg-green-500 px-4 py-2 text-white hover:bg-green-600">
                        <i class="fas fa-calendar-alt mr-2 h-5 w-5"></i>
                        Manage Bookings
                    </a>
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
        <form id="add-room-form" class="space-y-6">
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
                <button type="submit" id="submit-room" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </custom-dialog>
</body>

</html>