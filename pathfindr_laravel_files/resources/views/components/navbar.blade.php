<nav class="bg-[#1A2A6C] p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route(\'/\') }}" class="flex items-center space-x-2 text-white text-2xl font-bold">
            <i class="fas fa-compass text-[#F5A623]"></i>
            <span>PathFindr</span>
        </a>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-white focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ route(\'/\') }}" class="text-white hover:text-[#F5A623] transition duration-300">Home</a>
            <a href="#features" class="text-white hover:text-[#F5A623] transition duration-300">Features</a>
            <a href="#how-it-works" class="text-white hover:text-[#F5A623] transition duration-300">How It Works</a>
            <a href="#about" class="text-white hover:text-[#F5A623] transition duration-300">About</a>

            <!-- Account Dropdown -->
            <div class="relative group">
                <button class="text-white hover:text-[#F5A623] transition duration-300 flex items-center space-x-1 focus:outline-none">
                    <span>Account</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block group-focus-within:block z-10">
                    <a href="{{ route(\'login\') }}" class="block px-4 py-2 text-[#2D3748] hover:bg-gray-100">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="{{ route(\'register\') }}" class="block px-4 py-2 text-[#2D3748] hover:bg-gray-100">
                        <i class="fas fa-user-plus mr-2"></i> Sign Up
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobile-menu" class="md:hidden bg-[#1A2A6C] mt-4 hidden">
        <a href="{{ route(\'/\') }}" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">Home</a>
        <a href="#features" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">Features</a>
        <a href="#how-it-works" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">How It Works</a>
        <a href="#about" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">About</a>
        <div class="border-t border-[#2D5F9E] my-2"></div>
        <a href="{{ route(\'login\') }}" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
        </a>
        <a href="{{ route(\'register\') }}" class="block px-4 py-2 text-white hover:bg-[#2D5F9E]">
            <i class="fas fa-user-plus mr-2"></i> Sign Up
        </a>
    </div>
</nav>

<!-- <script>
    document.getElementById(\'mobile-menu-button\').addEventListener(\'click\', function() {
        document.getElementById(\'mobile-menu\').classList.toggle(\'hidden\');
    });
</script> -->
