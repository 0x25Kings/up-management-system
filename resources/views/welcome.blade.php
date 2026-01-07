@extends('layouts.app')

@section('title', 'UP Cebu Management System - University of the Philippines Cebu')

@push('styles')
<style>
    .intern-card { display: flex; flex-direction: column; align-items: center; text-align: center; transition: all 0.3s ease; }
    .intern-avatar {
        width: 120px; height: 120px; border-radius: 50%;
        background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
        border: 4px solid #FFBF00; margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: flex; align-items: center; justify-content: center;
        font-size: 48px; color: #7B1D3A; transition: all 0.3s ease;
    }
    .intern-card:hover .intern-avatar { transform: scale(1.1); box-shadow: 0 8px 20px rgba(255, 191, 0, 0.3); }
    .admin-card { display: grid; grid-template-columns: 350px 1fr; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); max-width: 1100px; margin: 0 auto; }
    .admin-left { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); padding: 40px 30px; display: flex; flex-direction: column; align-items: center; text-align: center; }
    .admin-photo { width: 200px; height: 200px; border-radius: 16px; object-fit: cover; border: 5px solid rgba(255, 191, 0, 0.3); margin-bottom: 24px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); }
    .admin-quote { font-size: 17px; color: #4B5563; line-height: 1.8; font-style: italic; padding: 24px; background: #f9fafb; border-radius: 12px; margin-bottom: 32px; position: relative; }
    .admin-quote::before { content: '\201C'; font-size: 60px; color: #FFBF00; position: absolute; top: -10px; left: 10px; opacity: 0.3; }
    .admin-detail-item { display: flex; align-items: start; gap: 16px; padding: 16px; background: #f9fafb; border-radius: 8px; border-left: 3px solid #FFBF00; transition: all 0.3s ease; }
    .admin-detail-item:hover { background: #fff9e6; transform: translateX(5px); }
    @media (max-width: 968px) { .admin-card { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="max-w-6xl mx-auto px-8 text-center text-white fade-in-up">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Unified Management<br>System for Interns & Startups
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Manage internships, track research, maintain digital records, monitor incubatees, and schedule eventsall in one integrated platform.
            </p>
            
            <!-- Portal Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('intern.portal') }}" class="btn-primary px-8 py-4 rounded-lg font-bold transition transform hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-user-graduate"></i> Intern Portal
                </a>
                <a href="{{ route('startup.portal') }}" class="btn-primary px-8 py-4 rounded-lg font-bold transition transform hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-rocket"></i> Startup Portal
                </a>
                <a href="{{ route('agency.portal') }}" class="btn-primary px-8 py-4 rounded-lg font-bold transition transform hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-building"></i> Agency Portal
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-8">
            <h2 class="text-4xl font-bold text-center mb-12" style="color: #7B1D3A;">About Our System</h2>
            <p class="text-gray-600 text-center text-lg max-w-3xl mx-auto">
                The University of the Philippines Cebu's integrated management system represents a comprehensive solution designed to streamline operations across multiple academic and administrative domains.
            </p>

            <!-- Admin Profile Card -->
            <div class="mt-20">
                <div class="admin-card">
                    <div class="admin-left">
                        <img src="/images/sir jason nieva.jpg" alt="Sir Jason Nieva" class="admin-photo">
                        <h3 class="text-white text-2xl font-bold">Sir Jason Nieva</h3>
                        <p class="text-yellow-400 font-semibold mt-2">Incubator Manager</p>
                        <p class="text-yellow-400 font-semibold">Design Thinking</p>
                        <p class="text-yellow-400 font-semibold">Project Management</p>
                        
                        <div class="flex gap-3 mt-6">
                            <a href="mailto:jnieva@up.edu.ph" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-yellow-400 hover:text-red-900 transition">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="tel:09154601907" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-yellow-400 hover:text-red-900 transition">
                                <i class="fas fa-phone"></i>
                            </a>
                        </div>
                    </div>

                    <div class="p-10">
                        <div class="text-sm text-red-900 font-bold uppercase tracking-wider mb-4">
                            <i class="fas fa-quote-left text-yellow-400 mr-2"></i> Message
                        </div>
                        <div class="admin-quote">
                            Empowering students through innovative technology solutions is at the heart of what we do. This integrated system represents our commitment to excellence and continuous improvement in education.
                        </div>

                        <div class="text-sm text-red-900 font-bold uppercase tracking-wider mb-4">
                            <i class="fas fa-info-circle text-yellow-400 mr-2"></i> Contact Information
                        </div>
                        <div class="space-y-4">
                            <div class="admin-detail-item">
                                <i class="fas fa-building text-red-900 text-xl"></i>
                                <div>
                                    <div class="text-xs text-gray-400 font-semibold uppercase">Department</div>
                                    <div class="text-gray-800 font-medium">University of the Philippines Cebu - InIT Program</div>
                                </div>
                            </div>
                            <div class="admin-detail-item">
                                <i class="fas fa-envelope text-red-900 text-xl"></i>
                                <div>
                                    <div class="text-xs text-gray-400 font-semibold uppercase">Email Address</div>
                                    <div class="text-gray-800 font-medium"><a href="mailto:jnieva@up.edu.ph" class="hover:text-yellow-500">jnieva@up.edu.ph</a></div>
                                </div>
                            </div>
                            <div class="admin-detail-item">
                                <i class="fas fa-phone text-red-900 text-xl"></i>
                                <div>
                                    <div class="text-xs text-gray-400 font-semibold uppercase">Phone Number</div>
                                    <div class="text-gray-800 font-medium"><a href="tel:09154601907" class="hover:text-yellow-500">09154601907</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section id="mission" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="text-4xl mb-4" style="color: #7B1D3A;"><i class="fas fa-bullseye"></i></div>
                    <h3 class="text-2xl font-bold mb-4" style="color: #7B1D3A;">Our Mission</h3>
                    <p class="text-gray-600">
                        Establish an enabling environment for technology based business, composed of above-average facilities & services, partnering with stakeholders from the different sectors and disciplines.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="text-4xl mb-4" style="color: #7B1D3A;"><i class="fas fa-eye"></i></div>
                    <h3 class="text-2xl font-bold mb-4" style="color: #7B1D3A;">Our Vision</h3>
                    <p class="text-gray-600">
                        To be a successful "inter-disciplinary" incubation facility in the Philippines, helping technology enterprises be sustainable and successfully commercialize technology innovations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Systems Overview Section -->
    <section id="systems" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-8">
            <h2 class="text-4xl font-bold text-center mb-4" style="color: #7B1D3A;">Five Integrated Systems</h2>
            <p class="text-center text-gray-600 mb-16 max-w-2xl mx-auto">
                All systems are seamlessly integrated into one dashboard, providing complete management of your academic programs.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                @foreach([
                    ['icon' => 'fas fa-briefcase', 'title' => 'Intern System', 'desc' => 'Manage internship placements, track student progress, and oversee company partnerships.', 'features' => ['Student profiles', 'Company management', 'Progress tracking']],
                    ['icon' => 'fas fa-flask', 'title' => 'Research System', 'desc' => 'Document, organize, and monitor all research projects and publications.', 'features' => ['Project management', 'Publication tracking', 'Data analysis']],
                    ['icon' => 'fas fa-file-alt', 'title' => 'Digital Records', 'desc' => 'Secure storage and management of all academic and administrative records.', 'features' => ['Document storage', 'Archive management', 'Version control']],
                    ['icon' => 'fas fa-chart-line', 'title' => 'Incubatee Tracker', 'desc' => 'Monitor startup progress, milestones, and business development metrics.', 'features' => ['Progress monitoring', 'Milestone tracking', 'Performance metrics']],
                    ['icon' => 'fas fa-calendar-alt', 'title' => 'Scheduler System', 'desc' => 'Organize events, meetings, and academic schedules in one unified calendar.', 'features' => ['Event management', 'Calendar sync', 'Notifications']]
                ] as $system)
                <div class="system-card bg-white p-6 rounded-xl shadow-md">
                    <div class="system-icon mb-4"><i class="{{ $system['icon'] }}"></i></div>
                    <h3 class="text-lg font-bold mb-3" style="color: #7B1D3A;">{{ $system['title'] }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $system['desc'] }}</p>
                    <ul class="text-xs text-gray-500 space-y-2">
                        @foreach($system['features'] as $feature)
                        <li><i class="fas fa-check mr-2" style="color: #FFD700;"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-8 text-center">
            <h2 class="text-4xl font-bold mb-6" style="color: #7B1D3A;">Get In Touch</h2>
            <p class="text-gray-600 text-lg mb-12 max-w-2xl mx-auto">
                Have questions or need support? We're here to help. Contact us anytime.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:init@up.edu.ph" class="btn-primary px-8 py-4 rounded-lg font-bold transition transform hover:scale-105">
                    <i class="fas fa-envelope mr-2"></i> Send Message
                </a>
                <a href="tel:09154601907" class="px-8 py-4 rounded-lg font-bold transition border-2 border-red-900 text-red-900 hover:bg-red-900 hover:text-white">
                    <i class="fas fa-phone mr-2"></i> Call Us
                </a>
            </div>
        </div>
    </section>
@endsection
