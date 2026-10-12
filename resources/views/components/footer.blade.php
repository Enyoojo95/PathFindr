<footer style="background: linear-gradient(160deg, #0f1d4a 0%, #1A2A6C 60%, #163a55 100%);" class="text-white">

    {{-- Top section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10">

            {{-- Brand — wider column --}}
            <div class="lg:col-span-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-5 group w-fit">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="PathFindr"
                         class="h-9 w-auto transition-transform duration-200 group-hover:scale-105"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span style="display:none"
                          class="items-center gap-2 text-xl font-extrabold text-white">
                        PathFindr
                    </span>
                </a>

                <p class="text-blue-200/70 text-sm leading-relaxed max-w-xs">
                    Helping Nigerian JSS3 students discover their strengths and choose the right SS1 subject combination — Science, Commercial, or Arts — powered by AI.
                </p>

                {{-- Social icons --}}
                <div class="flex gap-2.5 mt-6">
                    <a href="#"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-[#43A047] flex items-center justify-center text-sm text-white/70 hover:text-white transition-all duration-200 hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-[#43A047] flex items-center justify-center text-sm text-white/70 hover:text-white transition-all duration-200 hover:scale-110">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-[#43A047] flex items-center justify-center text-sm text-white/70 hover:text-white transition-all duration-200 hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-[#43A047] flex items-center justify-center text-sm text-white/70 hover:text-white transition-all duration-200 hover:scale-110">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="lg:col-span-2">
                <h4 class="text-white font-semibold text-sm uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="w-4 h-0.5 bg-[#43A047] rounded"></span> Links
                </h4>
                <ul class="space-y-3">
                    @foreach ([
                        ['Home',         url('/')],
                        ['About',        url('/#about')],
                        ['Features',        url('/#features')],
                        ['How It Works',    url('/#how-it-works')],
                        ['Get Started',     route('register')],
                        ['Register School', route('school-registration')],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}"
                           class="text-blue-200/70 hover:text-white text-sm flex items-center gap-2 group transition-colors duration-150">
                            <i class="fas fa-chevron-right text-[#43A047] text-[10px] transition-transform duration-150 group-hover:translate-x-1"></i>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-3">
                <h4 class="text-white font-semibold text-sm uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="w-4 h-0.5 bg-[#43A047] rounded"></span> Contact
                </h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-envelope text-[#43A047] text-xs"></i>
                        </div>
                        <div>
                            <div class="text-white/50 text-xs mb-0.5">Email</div>
                            <span class="text-blue-200/80 text-sm">austingreatakubo@gmail.com</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-phone text-[#43A047] text-xs"></i>
                        </div>
                        <div>
                            <div class="text-white/50 text-xs mb-0.5">Phone</div>
                            <span class="text-blue-200/80 text-sm">+2348069260556</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-map-marker-alt text-[#43A047] text-xs"></i>
                        </div>
                        <div>
                            <div class="text-white/50 text-xs mb-0.5">Location</div>
                            <span class="text-blue-200/80 text-sm">Abuja, Nigeria</span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="lg:col-span-3">
                <h4 class="text-white font-semibold text-sm uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="w-4 h-0.5 bg-[#43A047] rounded"></span> Stay Updated
                </h4>
                <p class="text-blue-200/70 text-sm mb-4 leading-relaxed">
                    Get the latest PathFindr updates and Nigerian student resources.
                </p>
                <div class="space-y-2">
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                        <input type="email"
                               placeholder="your@email.com"
                               class="w-full pl-9 pr-4 py-2.5 bg-white/10 border border-white/15 hover:border-white/25 focus:border-[#43A047]/60 text-white placeholder-white/30 text-sm rounded-xl outline-none transition-all duration-200">
                    </div>
                    <button class="w-full bg-[#43A047] hover:bg-emerald-600 active:scale-[0.98] text-white font-semibold text-sm py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane text-xs"></i> Subscribe
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Divider with green accent --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-px bg-gradient-to-r from-transparent via-[#43A047]/40 to-transparent"></div>
    </div>

    {{-- Bottom bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row justify-between items-center gap-3">
        <p class="text-white/40 text-xs text-center">
            &copy; {{ date('Y') }} PathFindr. All rights reserved. Built for Nigerian Students.
        </p>
        <div class="flex items-center gap-5 text-xs text-white/40">
            <a href="#" class="hover:text-white/80 transition-colors duration-150">Privacy Policy</a>
            <span class="w-px h-3 bg-white/20"></span>
            <a href="#" class="hover:text-white/80 transition-colors duration-150">Terms of Use</a>
            <span class="w-px h-3 bg-white/20"></span>
            <span id="master-btn"
                  onclick="masterClick()"
                  style="font-size:10px; opacity:0.25; color:inherit; cursor:default; user-select:none;">MASTER</span>
        </div>
    </div>

</footer>

<script>
(function(){
    var _clicks = 0, _timer;
    window.masterClick = function(){
        clearTimeout(_timer);
        _clicks++;
        if (_clicks >= 3) {
            _clicks = 0;
            window.location.href = '{{ route("super-admin.login") }}';
        } else {
            _timer = setTimeout(function(){ _clicks = 0; }, 2000);
        }
    };
})();
</script>
