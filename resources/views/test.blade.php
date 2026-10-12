<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PathFindr - Navbar Test</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Tailwind via CDN for quick testing -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <!-- Include the navbar -->
    @include('components.navbar')

    <!-- Page content -->
    <div class="max-w-7xl mx-auto px-4 py-20">
        <h1 class="text-4xl font-bold text-[#1A2A6C]">PathFindr Navbar Test</h1>
        <p class="text-[#2D3748] mt-4">If you see the navbar above, it's working.</p>
    </div>

</body>
</html>