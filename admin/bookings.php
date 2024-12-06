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

<body class="bg-gray-50 antialiased">
    <div class="flex min-h-screen">
        <div class="flex flex-1 flex-col">
            <header class="flex h-16 items-center justify-between bg-white px-4 sm:px-6 shadow-sm border-b border-gray-200">
                <a href="/admin" class="text-gray-600 hover:text-gray-900 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md p-1">
                    <i class="fas fa-arrow-left h-5 w-5"></i>
                </a>
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">Bookings Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md px-2 py-1">
                            <span class="hidden md:block font-medium">Admin</span>
                            <i class="fas fa-chevron-down h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Current Bookings</h2>
                        </div>
                        
                        <bookings-table class="w-full"></bookings-table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
