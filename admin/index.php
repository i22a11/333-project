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

<body class="bg-zinc-900 antialiased text-zinc-100">
    <div class="flex min-h-screen">
        <div class="flex flex-1 flex-col">
            <?php include '../components/navbar.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-900 p-4 sm:p-6 lg:p-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-100 mb-8">Dashboard</h1>
                <div id="stats-container" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8"></div>
                <div id="room-management" class="rounded-lg bg-zinc-800 p-6 shadow-sm border border-zinc-700"></div>
            </main>
        </div>
    </div>

    <custom-dialog id="add-room-dialog" title="Add Room" description="Add a new room to the system.">
        <form id="add-room-form" class="space-y-6">
            <div>
                <label for="room-name" class="block text-sm font-medium text-zinc-300">Room Name</label>
                <div class="mt-1">
                    <input type="text" name="name" id="room-name" required
                        class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3">
                </div>
            </div>

            <div>
                <label for="room-capacity" class="block text-sm font-medium text-zinc-300">Capacity</label>
                <div class="mt-1">
                    <input type="number" name="capacity" id="room-capacity" min="1" required
                        class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3">
                </div>
            </div>

            <div>
                <label for="room-equipment" class="block text-sm font-medium text-zinc-300">Equipment</label>
                <div class="mt-1">
                    <textarea name="equipment" id="room-equipment" rows="3"
                        class="block w-full rounded-md bg-zinc-700 border-zinc-600 text-zinc-100 placeholder-zinc-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3"
                        placeholder="Enter equipment details (optional)"></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" slot="confirm"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
                    Add Room
                </button>
            </div>
        </form>
    </custom-dialog>
</body>

</html>