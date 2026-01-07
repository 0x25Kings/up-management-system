@extends('layouts.app')

@section('title', 'Startup Portal - UP Cebu Management System')

@push('styles')
<style>
    .portal-hero { background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); min-height: 40vh; display: flex; align-items: center; padding-top: 80px; }
    .feature-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
    .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #1e3a8a; font-size: 24px; margin-bottom: 16px; }
</style>
@endpush

@section('content')
    <!-- Portal Hero -->
    <section class="portal-hero">
        <div class="max-w-6xl mx-auto px-8 text-center text-white">
            <div class="inline-block bg-yellow-400 text-blue-900 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <i class="fas fa-rocket mr-2"></i> STARTUP PORTAL
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Startup & Incubatee Management</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Track your startup journey, access resources, and connect with mentors.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-8">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: #1e3a8a;">What You Can Do</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-pie"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Track Milestones</h3>
                    <p class="text-gray-600">Monitor your startup milestones and key performance indicators.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Connect with Mentors</h3>
                    <p class="text-gray-600">Access experienced mentors and industry experts for guidance.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Resources</h3>
                    <p class="text-gray-600">Access training materials, workshops, and business development resources.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Funding Opportunities</h3>
                    <p class="text-gray-600">Discover grants, investors, and funding programs for your startup.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-network-wired"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Networking</h3>
                    <p class="text-gray-600">Connect with other startups and build valuable partnerships.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-contract"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1e3a8a;">Submit Reports</h3>
                    <p class="text-gray-600">Submit progress reports and documentation for your incubation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-8 text-center">
            <h2 class="text-3xl font-bold mb-8" style="color: #1e3a8a;">Quick Actions</h2>
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Update Progress
                </button>
                <button class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-calendar mr-2"></i> Book Consultation
                </button>
                <button class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-folder mr-2"></i> Access Resources
                </button>
            </div>
        </div>
    </section>
@endsection
