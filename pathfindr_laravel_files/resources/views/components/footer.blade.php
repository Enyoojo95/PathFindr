<footer class="bg-[#1A2A6C] text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Logo and Tagline -->
            <div class="mb-6 md:mb-0">
                <a href="{{ route(\"/\") }}" class="flex items-center space-x-2 text-white text-2xl font-bold mb-2">
                    <i class="fas fa-compass text-[#F5A623]"></i>
                    <span>PathFindr</span>
                </a>
                <p class="text-gray-300 text-sm">Guiding Nigerian JSS3 students to their future.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route(\"/\") }}" class="text-gray-300 hover:text-[#F5A623] transition duration-300">Home</a></li>
                    <li><a href="#about" class="text-gray-300 hover:text-[#F5A623] transition duration-300">About</a></li>
                    <li><a href="#features" class="text-gray-300 hover:text-[#F5A623] transition duration-300">Features</a></li>
                </ul>
            </div>

            <!-- Contact Info & Social Media -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                <p class="text-gray-300 mb-2"><i class="fas fa-envelope mr-2"></i> info@pathfindr.com</p>
                <p class="text-gray-300 mb-4"><i class="fas fa-phone mr-2"></i> +234 801 234 5678</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-[#F5A623] transition duration-300"><i class="fab fa-facebook-f text-xl"></i></a>
                    <a href="#" class="text-gray-300 hover:text-[#F5A623] transition duration-300"><i class="fab fa-twitter text-xl"></i></a>
                    <a href="#" class="text-gray-300 hover:text-[#F5A623] transition duration-300"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-300 hover:text-[#F5A623] transition duration-300"><i class="fab fa-linkedin-in text-xl"></i></a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-[#2D5F9E] mt-8 pt-6 text-center text-gray-400 text-sm">
            &copy; {{ date(\'Y\') }} PathFindr. All rights reserved.
        </div>
    </div>
</footer>
