@extends('layouts.app')

@section('title', 'PathFindr - Know Your Strengths. Choose Your Future.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-[#1A2A6C] text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Know Your Strengths. Choose Your Future.</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Helping Nigerian JSS3 students discover their strengths and choose the right SS1 subject combination — Science, Commercial, or Arts.
            </p>
            <div class="flex justify-center space-x-4 mb-8">
                <a href="{{ route('register') }}" class="bg-[#43A047] hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition duration-300">
                    Get Started
                </a>
                <a href="#how-it-works" class="border-2 border-[#1A2A6C] bg-transparent hover:bg-[#2D5F9E] text-white font-bold py-3 px-8 rounded-full transition duration-300">
                    Learn More
                </a>
            </div>
            <p class="text-lg text-gray-300">
                <i class="fas fa-check-circle text-[#F5A623] mr-2"></i> Partnered with Crystalfield Academy, Karshi, Abuja
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-[#EBF4FF]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-[#1A2A6C] mb-12">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <i class="fas fa-brain text-5xl text-[#F5A623] mb-4"></i>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-4">Psychometric Testing</h3>
                    <p class="text-[#2D3748]">30-minute test measuring numerical, verbal, spatial, and interest strengths.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <i class="fas fa-robot text-5xl text-[#F5A623] mb-4"></i>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-4">AI-Powered Recommendations</h3>
                    <p class="text-[#2D3748]">OpenAI analyzes scores to suggest subject combinations, careers, and universities.</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <i class="fas fa-file-pdf text-5xl text-[#F5A623] mb-4"></i>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-4">PDF Reports</h3>
                    <p class="text-[#2D3748]">Download a professional report to share with parents and counselors.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-[#1A2A6C] mb-12">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 rounded-lg">
                    <div class="flex items-center justify-center w-16 h-16 bg-[#F5A623] text-white rounded-full mx-auto mb-4 text-3xl font-bold">
                        1
                    </div>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-2">Register & Login</h3>
                    <p class="text-[#2D3748]">Create your free account in minutes.</p>
                </div>
                <div class="p-6 rounded-lg">
                    <div class="flex items-center justify-center w-16 h-16 bg-[#F5A623] text-white rounded-full mx-auto mb-4 text-3xl font-bold">
                        2
                    </div>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-2">Take the Test</h3>
                    <p class="text-[#2D3748]">Complete a 30-minute psychometric assessment.</p>
                </div>
                <div class="p-6 rounded-lg">
                    <div class="flex items-center justify-center w-16 h-16 bg-[#F5A623] text-white rounded-full mx-auto mb-4 text-3xl font-bold">
                        3
                    </div>
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-2">Get Your Path</h3>
                    <p class="text-[#2D3748]">Receive subject, career, and university recommendations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Schools Section -->
    <section class="py-20 bg-[#EBF4FF]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-[#1A2A6C] mb-12">Our Partner Schools</h2>
            <div class="flex justify-center items-center">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-[#1A2A6C] mb-2">Crystalfield Academy</h3>
                    <p class="text-[#2D3748]">Karshi, Abuja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-[#2D5F9E] text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Discover Your Path?</h2>
            <p class="text-xl mb-8">Join hundreds of Nigerian students already finding their way.</p>
            <a href="{{ route('register') }}" class="bg-[#F5A623] hover:bg-yellow-600 text-white font-bold py-4 px-10 rounded-full text-xl transition duration-300">
                Get Started Now
            </a>
        </div>
    </section>
@endsection
