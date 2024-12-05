<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management - Admin Panel</title>
    <link rel="stylesheet" href="../output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="/admin/js/components/Bookings.js" type="module"></script>
</head>

<body class="bg-gray-900">
    <div class="flex h-screen bg-gray-100">
        <div class="flex flex-1 flex-col overflow-hidden">
            <header class="flex h-16 items-center justify-between bg-white px-6 shadow">
                <a href="/admin" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left h-6 w-6"></i>
                </a>
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Bookings Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="flex items-center text-sm focus:outline-none">
                            <span class="hidden md:block">Admin</span>
                            <i class="fas fa-chevron-down ml-1 h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <div class="container mx-auto">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Current Bookings</h2>
                        </div>
                        
                        <bookings-table class="w-full"></bookings-table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
