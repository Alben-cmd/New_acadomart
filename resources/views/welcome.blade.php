<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acadomart - Bridge the Gap Between Academia & Industry</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .gradient-text {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .hero-bg {
                background-image: radial-gradient(circle at top, rgba(59, 130, 246, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            }
            @media (prefers-color-scheme: dark) {
                .hero-bg {
                    background-image: radial-gradient(circle at top, rgba(29, 78, 216, 0.15) 0%, rgba(10, 10, 10, 0) 70%);
                }
            }
            .dark .hero-bg {
                background-image: radial-gradient(circle at top, rgba(29, 78, 216, 0.15) 0%, rgba(10, 10, 10, 0) 70%);
            }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased transition-colors duration-300 hero-bg">

        <!-- Top Navigation -->
        <header class="sticky top-0 z-50 backdrop-blur-md bg-[#FDFDFC]/80 dark:bg-[#0a0a0a]/80 border-b border-gray-200/50 dark:border-gray-800/50">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2 font-bold text-xl tracking-tight">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-600/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c4.168 0 7.828-2.122 9.96-5.328a60.428 60.428 0 00-.49-6.347m-15.7 3.393l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m0 0L12 3.126l7.733 4.305m-7.733-4.305l-7.732 4.305m0 0a8.91 8.91 0 00-.12 1.229v.203m15.464-3.417A9.045 9.045 0 0120.4 12m0 0l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m-1.531 6.462a48.62 48.62 0 00-6.733-3.087m0 0L12 3.126m0 0v17.774" />
                        </svg>
                    </span>
                    <span>Acadomart</span>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="#features" class="hover:text-blue-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="hover:text-blue-600 transition-colors">How It Works</a>
                    <a href="#stats" class="hover:text-blue-600 transition-colors">Metrics</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="/{{ auth()->user()->role->value }}" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5">
                            Go to Dashboard
                        </a>
                        <form method="POST" action="{{ route('filament.portal.auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="/login" class="px-4 py-2 text-sm font-medium hover:text-blue-600 transition-colors">
                            Log In
                        </a>
                        <a href="/register" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 pt-20 pb-16 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-600/10 text-blue-700 dark:text-blue-400 text-xs font-semibold uppercase tracking-wider mb-6">
                🚀 Dynamic Talent Hub
            </div>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-6 leading-tight max-w-4xl mx-auto">
                <span class="gradient-text">Bridge the Gap</span> Between Academia & Industry
            </h1>
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Acadomart is a collaborative hub where students showcase real-world projects, earn verified badges, and apply to challenges, while companies discover verified talent.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                @auth
                    <a href="/{{ auth()->user()->role->value }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-xl shadow-blue-600/25 transition-all hover:-translate-y-1">
                        Go to Your Dashboard
                    </a>
                @else
                    <a href="/register" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-xl shadow-blue-600/25 transition-all hover:-translate-y-1">
                        Join as a Student
                    </a>
                    <a href="/register" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-950 text-gray-800 dark:text-white font-semibold border border-gray-300 dark:border-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 transition-all hover:-translate-y-1">
                        Partner as Industry
                    </a>
                @endif
            </div>

            <!-- Stats Dashboard Preview -->
            <div id="stats" class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 rounded-2xl bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-900 shadow-xl max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-blue-600 mb-1">1,200+</div>
                    <div class="text-xs md:text-sm text-gray-500 uppercase font-semibold">Active Students</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-blue-600 mb-1">45+</div>
                    <div class="text-xs md:text-sm text-gray-500 uppercase font-semibold">Challenges Posted</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-blue-600 mb-1">150+</div>
                    <div class="text-xs md:text-sm text-gray-500 uppercase font-semibold">Portfolios Built</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-blue-600 mb-1">98%</div>
                    <div class="text-xs md:text-sm text-gray-500 uppercase font-semibold">Verified Skill Rate</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 border-t border-gray-100 dark:border-gray-900 bg-gray-50/50 dark:bg-gray-950/20">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Tailored Workflows For Both Paths</h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Discover how Acadomart addresses key pain points of recruiters and students alike.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Student Side -->
                    <div class="p-8 rounded-2xl bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-900 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center mb-6 font-bold text-xl">🎓</div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">For Students</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Stand out with more than just a resume. Build a comprehensive portfolio backed by verified academic records.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Build Profile:</strong> Add your GPA, graduation details, and course focus.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Showcase Skills:</strong> List your core competencies (PHP, Laravel, React, etc.).
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Upload Portfolio:</strong> Link GitHub repositories, demo links, and descriptions.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Discover Opportunities:</strong> Apply directly to challenges and hackathons posted by companies.
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Industry Side -->
                    <div class="p-8 rounded-2xl bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-900 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center mb-6 font-bold text-xl">💼</div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">For Industry</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Recruit directly based on real-world projects and verified academic criteria.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Create Profile:</strong> Share your company mission, logo, and active industries.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Search Talent:</strong> Filter students by University, graduation year, GPA, or specific skills.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Post Challenges:</strong> Host hackathons, coding tasks, or internships with rewards.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-blue-600 mt-1">✔</span>
                                <div>
                                    <strong class="text-gray-900 dark:text-white">Review Applicants:</strong> Check submissions, cover letters, and grade solutions instantly.
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">How it Works</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Get connected and verify your credentials in four simple steps.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="relative text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">1</div>
                    <h4 class="font-bold text-lg mb-2">Sign Up & Setup</h4>
                    <p class="text-sm text-gray-500">Create your account and select either Student or Industry Partner role.</p>
                </div>
                <div class="relative text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">2</div>
                    <h4 class="font-bold text-lg mb-2">Build & Verify</h4>
                    <p class="text-sm text-gray-500">Input your GPA, skills, portfolio projects, or company description.</p>
                </div>
                <div class="relative text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">3</div>
                    <h4 class="font-bold text-lg mb-2">Launch Challenges</h4>
                    <p class="text-sm text-gray-500">Companies post active opportunities, students apply and submit links.</p>
                </div>
                <div class="relative text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center mx-auto mb-4">4</div>
                    <h4 class="font-bold text-lg mb-2">Bridge the Gap</h4>
                    <p class="text-sm text-gray-500">Recruiters review projects, interview applicants, and secure talent.</p>
                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="max-w-5xl mx-auto px-6 pb-20">
            <div class="p-8 md:p-12 rounded-3xl bg-blue-600 text-white text-center shadow-xl shadow-blue-600/10 relative overflow-hidden">
                <h2 class="text-3xl md:text-4xl font-black mb-4">Ready to start collaborating?</h2>
                <p class="text-blue-100 max-w-lg mx-auto mb-8">Create your profile today and find new ways to connect academics with industrial innovations.</p>
                @auth
                    <a href="/{{ auth()->user()->role->value }}" class="px-8 py-4 bg-white text-blue-600 font-bold rounded-xl shadow-lg transition-all hover:-translate-y-0.5 inline-block">
                        Go to Dashboard
                    </a>
                @else
                    <a href="/register" class="px-8 py-4 bg-white text-blue-600 font-bold rounded-xl shadow-lg transition-all hover:-translate-y-0.5 inline-block">
                        Get Started For Free
                    </a>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-200 dark:border-gray-900 bg-white dark:bg-gray-950 py-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2 font-bold text-lg">
                    <span class="w-6 h-6 rounded-md bg-blue-600 flex items-center justify-center text-white text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c4.168 0 7.828-2.122 9.96-5.328a60.428 60.428 0 00-.49-6.347m-15.7 3.393l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m0 0L12 3.126l7.733 4.305m-7.733-4.305l-7.732 4.305m0 0a8.91 8.91 0 00-.12 1.229v.203m15.464-3.417A9.045 9.045 0 0120.4 12m0 0l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m-1.531 6.462a48.62 48.62 0 00-6.733-3.087m0 0L12 3.126m0 0v17.774" />
                        </svg>
                    </span>
                    <span>Acadomart</span>
                </div>
                <div class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Acadomart. All rights reserved.
                </div>
            </div>
        </footer>

    </body>
</html>
