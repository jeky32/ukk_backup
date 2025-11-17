<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring - Team Lead Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased min-h-screen">
    <div class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Team Monitoring Dashboard</h1>
                    <p class="text-gray-600 text-sm">Overview proyek dan status tim Anda secara real-time</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- SECTION 1: PROJECT OVERVIEW -->
            <div class="lg:col-span-3 bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    <div class="w-full md:w-32 h-32 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                        <i class="fas fa-rocket text-5xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">E-Commerce Platform Redesign</h2>
                        <div class="flex flex-wrap gap-4 md:gap-6 mb-4">
                            <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                <i class="fas fa-users text-primary-600"></i>
                                <span class="font-medium">8 Team Members</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600 text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                <i class="fas fa-calendar text-primary-600"></i>
                                <span class="font-medium">Started: Oct 1, 2024</span>
                            </div>
                            <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-white px-4 py-2 rounded-lg font-bold text-xs flex items-center gap-2 shadow-md">
                                <i class="fas fa-clock"></i> 5 days left
                            </div>
                        </div>

                        <div class="mt-5 bg-gray-50 p-4 rounded-xl">
                            <div class="flex justify-between mb-3 text-sm font-semibold">
                                <span class="text-gray-700">Overall Progress</span>
                                <span class="text-primary-600 text-lg">75%</span>
                            </div>
                            <div class="h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-primary-500 via-primary-600 to-primary-700 rounded-full shadow-sm" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl text-center border-t-4 border-gray-600 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-gray-600 uppercase mb-2 tracking-wide">To Do</h4>
                        <div class="text-4xl font-extrabold text-gray-900">3</div>
                        <div class="mt-2 text-xs text-gray-500">tasks pending</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl text-center border-t-4 border-primary-600 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-primary-700 uppercase mb-2 tracking-wide">In Progress</h4>
                        <div class="text-4xl font-extrabold text-primary-900">4</div>
                        <div class="mt-2 text-xs text-primary-600">active now</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-5 rounded-xl text-center border-t-4 border-amber-500 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-amber-700 uppercase mb-2 tracking-wide">Review</h4>
                        <div class="text-4xl font-extrabold text-amber-900">2</div>
                        <div class="mt-2 text-xs text-amber-600">awaiting review</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl text-center border-t-4 border-green-500 shadow-md hover:shadow-lg transition-shadow">
                        <h4 class="text-xs font-bold text-green-700 uppercase mb-2 tracking-wide">Done</h4>
                        <div class="text-4xl font-extrabold text-green-900">12</div>
                        <div class="mt-2 text-xs text-green-600">completed</div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: TEAM WORKLOAD -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-users text-primary-600"></i>
                        Team Members
                    </h3>
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">4 Active</span>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                AR
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-3 border-white rounded-full ring-2 ring-green-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Ahmad Rizki</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-code text-primary-600"></i>
                                Working on API Integration
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 90%"></div>
                            </div>
                            <p class="text-xs font-bold text-green-700 mt-1.5">Productivity: 90%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                SN
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-3 border-white rounded-full ring-2 ring-green-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Siti Nurhaliza</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-vial text-purple-600"></i>
                                Testing Login Module
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 85%"></div>
                            </div>
                            <p class="text-xs font-bold text-green-700 mt-1.5">Productivity: 85%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                BS
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-amber-500 border-3 border-white rounded-full ring-2 ring-amber-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Budi Santoso</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-palette text-indigo-600"></i>
                                UI Design Review
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs font-bold text-amber-700 mt-1.5">Productivity: 75%</p>
                        </div>
                    </div>

                    <div class="flex gap-3 p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-500 hover:shadow-md transition-shadow">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                DL
                            </div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-red-500 border-3 border-white rounded-full ring-2 ring-red-200"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Dewi Lestari</h4>
                            <p class="text-xs text-gray-600 mb-2 flex items-center gap-1">
                                <i class="fas fa-tasks text-pink-600"></i>
                                Sprint Planning
                            </p>
                            <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full" style="width: 60%"></div>
                            </div>
                            <p class="text-xs font-bold text-red-700 mt-1.5">Productivity: 60%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PRIORITY TASKS -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-fire text-red-500"></i>
                        Priority Tasks
                    </h3>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">4 Urgent</span>
                </div>

                <div class="space-y-3">
                    <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Complete Payment Gateway Integration</h4>
                            <span class="bg-red-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">High</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    AR
                                </div>
                                <span class="font-medium">Ahmad Rizki</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-clock text-red-500"></i> Today
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-700 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-l-4 border-red-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Fix Critical Bug in Checkout Flow</h4>
                            <span class="bg-red-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">High</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    SN
                                </div>
                                <span class="font-medium">Siti Nurhaliza</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-clock text-red-500"></i> Today
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-700 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Update User Dashboard Design</h4>
                            <span class="bg-amber-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">Medium</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    BS
                                </div>
                                <span class="font-medium">Budi Santoso</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-calendar-day text-amber-500"></i> Tomorrow
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-700 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-l-4 border-amber-600 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-sm font-bold text-gray-900 flex-1 pr-2">Database Performance Optimization</h4>
                            <span class="bg-amber-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase flex-shrink-0 shadow-sm">Medium</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                <div class="w-6 h-6 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    DL
                                </div>
                                <span class="font-medium">Dewi Lestari</span>
                            </div>
                            <span class="bg-white px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 shadow-sm border border-gray-200">
                                <i class="fas fa-calendar-day text-amber-500"></i> Tomorrow
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-700 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: ACTIVITY FEED -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-rss text-primary-600"></i>
                        Recent Activity
                    </h3>
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-bold">Live</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-200 via-primary-300 to-transparent"></div>

                    <div class="space-y-1">
                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    AR
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Ahmad Rizki</strong> completed task <span class="text-primary-600 font-semibold">"API Documentation"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 2 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    SN
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Siti Nurhaliza</strong> moved <span class="text-purple-600 font-semibold">"Login Module"</span> to Review
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 3 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    BS
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Budi Santoso</strong> commented on <span class="text-indigo-600 font-semibold">"UI Design"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 5 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 pb-4 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-pink-600 to-pink-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    DL
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Dewi Lestari</strong> started <span class="text-pink-600 font-semibold">"Database Migration"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> 6 hours ago
                                </p>
                            </div>
                        </div>

                        <div class="relative flex gap-3 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                            <div class="relative z-10 flex-shrink-0">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-600 to-primary-800 rounded-full flex items-center justify-center text-white font-bold text-sm border-3 border-white shadow-md">
                                    AR
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-xs text-gray-900 mb-1">
                                    <strong class="font-bold">Ahmad Rizki</strong> created new task <span class="text-primary-600 font-semibold">"Security Audit"</span>
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Yesterday
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: BLOCKERS -->
            <div class="lg:col-span-3">
                <div class="bg-gradient-to-br from-red-50 to-rose-100 border-3 border-red-500 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-900 mb-1 flex items-center gap-2">
                                Team Members Blocked
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">3 Urgent</span>
                            </h3>
                            <p class="text-sm text-red-800 font-medium">3 team members need your immediate help to continue their tasks</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-xl flex flex-col gap-3 shadow-md hover:shadow-lg transition-shadow border border-red-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    RH
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900">Rudi Hermawan</h4>
                                    <p class="text-xs text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-key text-red-500"></i>
                                        Waiting for API credentials
                                    </p>
                                </div>
                            </div>
                            <button class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                                <i class="fas fa-hands-helping"></i> Provide Help
                            </button>
                        </div>

                        <div class="bg-white p-4 rounded-xl flex flex-col gap-3 shadow-md hover:shadow-lg transition-shadow border border-red-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    IA
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900">Indah Ayu</h4>
                                    <p class="text-xs text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-check-circle text-red-500"></i>
                                        Need design approval
                                    </p>
                                </div>
                            </div>
                            <button class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                                <i class="fas fa-hands-helping"></i> Provide Help
                            </button>
                        </div>

                        <div class="bg-white p-4 rounded-xl flex flex-col gap-3 shadow-md hover:shadow-lg transition-shadow border border-red-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    FA
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900">Fajar Ardi</h4>
                                    <p class="text-xs text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-server text-red-500"></i>
                                        Server access required
                                    </p>
                                </div>
                            </div>
                            <button class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold flex items-center justify-center gap-2 shadow-md transition-all">
                                <i class="fas fa-hands-helping"></i> Provide Help
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
