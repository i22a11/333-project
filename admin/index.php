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

<body class="bg-gray-50 antialiased">
    <div class="flex min-h-screen">
        <div class="flex flex-1 flex-col">
            <header class="flex h-16 items-center justify-between bg-white px-4 sm:px-6 shadow-sm border-b border-gray-200">
                <button class="text-gray-600 hover:text-gray-900 transition-colors lg:hidden focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md p-1">
                    <i class="fas fa-bars h-5 w-5"></i>
                </button>
                <div class="flex items-center justify-between w-full">
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md px-2 py-1">
                            <span class="hidden md:block font-medium">John Doe</span>
                            <i class="fas fa-chevron-down h-4 w-4"></i>
                        </button>
                    </div>

                    <a href="/admin/bookings.php" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-calendar-alt mr-2 h-4 w-4"></i>
                        Manage Bookings
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
                <div id="stats-container" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8"></div>
                <div id="room-management" class="rounded-lg bg-white p-6 shadow-sm border border-gray-200"></div>
            </main>
        </div>
    </div>

    <custom-dialog id="add-room-dialog" title="Add Room" description="Add a new room to the system.">
        <form id="add-room-form" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                <input type="text" name="name" id="name" placeholder="Enter room name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-gray-400">
            </div>
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700">Room Capacity</label>
                <input type="number" name="capacity" id="capacity" placeholder="Enter capacity" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-gray-400">
            </div>
            <div>
                <label for="equipment" class="block text-sm font-medium text-gray-700">Room Equipment</label>
                <textarea name="equipment" id="equipment" placeholder="List available equipment" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-gray-400 resize-none h-24"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="submit" id="submit-room" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                    Add Room
                </button>
            </div>
        </form>
    </custom-dialog>
</body>

</html>