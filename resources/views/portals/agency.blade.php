@extends('layouts.app')

@section('title', 'Agency Portal - UP Cebu Management System')

@push('styles')
<style>
    .portal-hero { background: linear-gradient(135deg, #166534 0%, #14532d 100%); min-height: 40vh; display: flex; align-items: center; padding-top: 80px; }
    .feature-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
    .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #166534; font-size: 24px; margin-bottom: 16px; }
</style>
@endpush

@section('content')
    <!-- Portal Hero -->
    <section class="portal-hero">
        <div class="max-w-6xl mx-auto px-8 text-center text-white">
            <div class="inline-block bg-yellow-400 text-green-900 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <i class="fas fa-building mr-2"></i> AGENCY PORTAL
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Agency & Company Management</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Manage interns, post opportunities, and partner with UP Cebu.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-8">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: #166534;">What You Can Do</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-user-plus"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Post Internships</h3>
                    <p class="text-gray-600">Create and post internship opportunities for UP Cebu students.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Manage Interns</h3>
                    <p class="text-gray-600">Track and evaluate your assigned interns from UP Cebu.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-star"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Provide Feedback</h3>
                    <p class="text-gray-600">Submit evaluations and feedback for intern performance.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Partnership</h3>
                    <p class="text-gray-600">Establish and maintain partnership agreements with the university.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Schedule Visits</h3>
                    <p class="text-gray-600">Coordinate campus visits and recruitment events.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-signature"></i></div>
                    <h3 class="text-xl font-bold mb-3" style="color: #166534;">Documentation</h3>
                    <p class="text-gray-600">Access and submit required partnership documents.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-8 text-center">
            <h2 class="text-3xl font-bold mb-8" style="color: #166534;">Quick Actions</h2>
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="bg-gradient-to-r from-green-600 to-green-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Post Opportunity
                </button>
                <button class="bg-gradient-to-r from-green-600 to-green-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-clipboard-check mr-2"></i> Submit Evaluation
                </button>
                <button class="bg-gradient-to-r from-green-600 to-green-800 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-envelope mr-2"></i> Contact Coordinator
                </button>
            </div>
        </div>
    </section>
@endsection
