<footer class="bg-white border-t border-gray-200 mt-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- Links -->
            <div>
                <h3
                    class="font-extrabold font-[Rammetto_One] text-sm text-[#7F64CE] mb-4 tracking-wide text-center md:text-left">
                    Links
                </h3>

                <ul class="space-y-3 text-sm text-gray-500 text-center md:text-left font-[Poppins]">
                    <li><a href="{{ url('/') }}" class="hover:text-[#7F64CE] transition">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-[#7F64CE] transition">About Us</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-[#7F64CE] transition">Contact Us</a></li>
                    <li><a href="{{ url('/programs') }}" class="hover:text-[#7F64CE] transition">Programs</a></li>
                    <li><a href="{{ url('/profile') }}" class="hover:text-[#7F64CE] transition">Profile</a></li>
                </ul>
            </div>

            <!-- Tools -->
            <div>
                <h3
                    class="font-extrabold font-[Rammetto_One] text-sm text-[#7F64CE] mb-4 tracking-wide text-center md:text-left">
                    Tools
                </h3>

                <ul class="space-y-3 text-sm text-gray-500 text-center md:text-left font-[Poppins]">
                    <li><a href="{{ url('/quiz') }}" class="hover:text-[#7F64CE] transition">Quiz</a></li>
                    <li><a href="{{ url('/recommendation') }}" class="hover:text-[#7F64CE] transition">Recommendation</a></li>
                    <li><a href="{{ url('/comparison') }}" class="hover:text-[#7F64CE] transition">Comparison Tool</a></li>
                    <li><a href="{{ url('/careers') }}" class="hover:text-[#7F64CE] transition">Careers</a></li>
                </ul>
            </div>

            <!-- Logo + Description -->
            <div>
                <div class="flex justify-center md:justify-start items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="UniPath Logo" class="h-10 w-10 mr-2">
                    <span class="text-2xl font-extrabold text-[#7F64CE]">UniPath</span>
                </div>

                <p class="mt-4 text-sm text-gray-500 text-center md:text-left font-[Poppins]">
                    Helping students discover the right university programs with smart tools and personalized guidance.
                </p>
            </div>

        </div>

        <!-- Bottom -->
        <div
            class="border-t border-gray-200 mt-10 pt-6 flex flex-col sm:flex-row justify-center items-center text-sm text-gray-400">
            <p>© {{ date('Y') }} UniPath. All rights reserved.</p>
        </div>

    </div>
</footer>