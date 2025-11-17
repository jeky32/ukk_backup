@extends('layouts.teamlead')

@section('title', 'Team Lead Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->full_name)

@push('styles')
<style>

</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 animate-gradient">


    <!-- Main Content -->
    <div class="ml-2 p-8">
        <!-- Page Header -->
        <div class="bg-white rounded-xl p-6 mb-6 shadow-sm">
            <div class="flex items-center mb-4">
                <h2 class="font-bold text-2xl mb-0">Tasks Pending Review</h2>
                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-semibold ml-3">5</span>
            </div>
            <div class="flex gap-3 mt-4">
                <input type="text" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search tasks..." id="searchInput">
                <select class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white min-w-[180px] text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" id="filterDropdown">
                    <option>All Projects</option>
                    <option>E-Commerce Platform</option>
                    <option>Mobile App Redesign</option>
                    <option>API Development</option>
                </select>
            </div>
        </div>

        <!-- Task Cards -->
        <div class="tasks-container">
            <!-- Task Card 1 -->
            <div class="task-card bg-white rounded-xl p-6 mb-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Implement User Authentication API</h3>
                        <div>
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">HIGH PRIORITY</span>
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold ml-2">PENDING REVIEW</span>
                        </div>
                    </div>
                </div>

                <div class="task-content mb-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                            DC
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">David Chen</div>
                            <div class="text-gray-500 text-sm">Submitted 2 hours ago</div>
                        </div>
                    </div>

                    <p class="text-gray-800 mb-4 leading-relaxed">
                        Completed REST API endpoints for user registration, login, and JWT token management.
                        Includes password hashing with bcrypt and refresh token functionality.
                    </p>

                    <div class="flex gap-3 mb-4">
                        <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-file-code text-4xl text-indigo-600"></i>
                        </div>
                        <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-file-text text-4xl text-indigo-600"></i>
                        </div>
                    </div>

                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-semibold">
                        <i class="bi bi-folder mr-1"></i> E-Commerce Platform
                    </span>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2.5 border border-indigo-600 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition-colors" onclick="openDetailModal(1)">
                        <i class="bi bi-eye mr-2"></i>View Details
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                        <i class="bi bi-check-circle mr-2"></i>Approve
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                        <i class="bi bi-x-circle mr-2"></i>Reject
                    </button>
                </div>
            </div>

            <!-- Task Card 2 -->
            <div class="task-card bg-white rounded-xl p-6 mb-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Mobile Responsive Design - Homepage</h3>
                        <div>
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">MEDIUM PRIORITY</span>
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold ml-2">PENDING REVIEW</span>
                        </div>
                    </div>
                </div>

                <div class="task-content mb-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-400 flex items-center justify-center text-white font-semibold text-sm">
                            SW
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Sari Wijaya</div>
                            <div class="text-gray-500 text-sm">Submitted 5 hours ago</div>
                        </div>
                    </div>

                    <p class="text-gray-800 mb-4 leading-relaxed">
                        Redesigned homepage layout with fully responsive breakpoints for mobile, tablet, and desktop.
                        Optimized images and added smooth scrolling animations.
                    </p>

                    <div class="flex gap-3 mb-4">
                        <div class="w-[100px] h-[100px] rounded-lg bg-amber-100 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-image text-4xl text-amber-600"></i>
                        </div>
                        <div class="w-[100px] h-[100px] rounded-lg bg-green-100 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-phone text-4xl text-green-600"></i>
                        </div>
                        <div class="w-[100px] h-[100px] rounded-lg bg-blue-100 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-tablet text-4xl text-blue-600"></i>
                        </div>
                    </div>

                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-semibold">
                        <i class="bi bi-folder mr-1"></i> Mobile App Redesign
                    </span>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2.5 border border-indigo-600 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition-colors" onclick="openDetailModal(2)">
                        <i class="bi bi-eye mr-2"></i>View Details
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                        <i class="bi bi-check-circle mr-2"></i>Approve
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                        <i class="bi bi-x-circle mr-2"></i>Reject
                    </button>
                </div>
            </div>

            <!-- Task Card 3 -->
            <div class="task-card bg-white rounded-xl p-6 mb-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Database Migration - User Profiles</h3>
                        <div>
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">LOW PRIORITY</span>
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold ml-2">PENDING REVIEW</span>
                        </div>
                    </div>
                </div>

                <div class="task-content mb-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-400 flex items-center justify-center text-white font-semibold text-sm">
                            RK
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Rina Kusuma</div>
                            <div class="text-gray-500 text-sm">Submitted 1 day ago</div>
                        </div>
                    </div>

                    <p class="text-gray-800 mb-4 leading-relaxed">
                        Created Laravel migration files for extended user profile fields including bio, avatar,
                        social links, and preferences. Added proper foreign key constraints and indexes.
                    </p>

                    <div class="flex gap-3 mb-4">
                        <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                            <i class="bi bi-database text-4xl text-cyan-600"></i>
                        </div>
                    </div>

                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-semibold">
                        <i class="bi bi-folder mr-1"></i> E-Commerce Platform
                    </span>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2.5 border border-indigo-600 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition-colors" onclick="openDetailModal(3)">
                        <i class="bi bi-eye mr-2"></i>View Details
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                        <i class="bi bi-check-circle mr-2"></i>Approve
                    </button>
                    <button class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                        <i class="bi bi-x-circle mr-2"></i>Reject
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div class="text-center py-20 bg-white rounded-xl hidden" id="emptyState">
            <i class="bi bi-check-circle-fill text-6xl text-green-500 mb-4"></i>
            <h3 class="text-gray-800 font-bold text-xl mb-2">All caught up!</h3>
            <p class="text-gray-500">No tasks pending review at the moment.</p>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-[1000] items-center justify-center hidden" id="detailModal">
        <div class="bg-white rounded-xl w-11/12 max-w-[800px] max-h-[90vh] overflow-y-auto p-8">
            <div class="mb-6 pb-4 border-b border-gray-200">
                <span class="float-right text-2xl cursor-pointer text-gray-500 hover:text-gray-800" onclick="closeDetailModal()">&times;</span>
                <h3 class="font-bold text-xl mb-2">Implement User Authentication API</h3>
                <div class="mt-2">
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold uppercase">HIGH PRIORITY</span>
                    <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-md text-xs font-semibold ml-2">PENDING REVIEW</span>
                </div>
            </div>

            <div class="modal-body">
                <h5 class="font-bold mb-3">Description</h5>
                <p class="text-gray-800 leading-relaxed mb-4">
                    Completed REST API endpoints for user registration, login, and JWT token management.
                    Includes password hashing with bcrypt and refresh token functionality. All endpoints
                    have been tested with Postman and documented in the API specification.
                </p>

                <h5 class="font-bold mb-3 mt-6">Assignee Information</h5>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                        DC
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">David Chen</div>
                        <div class="text-gray-500 text-sm">Backend Developer</div>
                    </div>
                </div>

                <h5 class="font-bold mb-3 mt-6">Files & Deliverables</h5>
                <div class="flex gap-3 mb-4">
                    <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                        <i class="bi bi-file-code text-4xl text-indigo-600"></i>
                    </div>
                    <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                        <i class="bi bi-file-text text-4xl text-indigo-600"></i>
                    </div>
                    <div class="w-[100px] h-[100px] rounded-lg bg-gray-50 flex items-center justify-center border border-gray-200">
                        <i class="bi bi-filetype-pdf text-4xl text-red-600"></i>
                    </div>
                </div>

                <div class="my-6 p-4 bg-gray-50 rounded-lg">
                    <h5 class="font-bold mb-3">Timeline</h5>
                    <div class="flex gap-3 mb-3">
                        <div class="w-3 h-3 rounded-full bg-indigo-600 mt-1"></div>
                        <div>
                            <div class="font-semibold">Task Created</div>
                            <div class="text-gray-500 text-sm">Nov 10, 2025 - 09:00 AM</div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3">
                        <div class="w-3 h-3 rounded-full bg-amber-500 mt-1"></div>
                        <div>
                            <div class="font-semibold">Work Started</div>
                            <div class="text-gray-500 text-sm">Nov 10, 2025 - 10:30 AM</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-500 mt-1"></div>
                        <div>
                            <div class="font-semibold">Submitted for Review</div>
                            <div class="text-gray-500 text-sm">Nov 13, 2025 - 11:00 AM</div>
                        </div>
                    </div>
                </div>

                <h5 class="font-bold mb-3">Developer Notes</h5>
                <p class="text-gray-800 bg-gray-50 p-4 rounded-lg leading-relaxed mb-4">
                    "I've implemented all authentication endpoints with proper error handling and validation.
                    The JWT tokens have a 24-hour expiry with refresh token support. All passwords are hashed
                    using bcrypt with a cost factor of 10. Please review the API documentation for detailed
                    endpoint specifications."
                </p>

                <div class="mt-6 p-5 bg-gray-50 rounded-lg">
                    <h5 class="font-bold mb-3">Review Feedback</h5>
                    <textarea class="w-full px-3 py-3 border border-gray-300 rounded-lg min-h-[120px] mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add your feedback or notes here..."></textarea>

                    <h6 class="font-semibold mb-2">Review Criteria</h6>
                    <div class="mb-4">
                        <div class="py-2">
                            <input type="checkbox" id="criteria1" class="mr-2 w-4 h-4">
                            <label for="criteria1" class="cursor-pointer">Code follows project standards</label>
                        </div>
                        <div class="py-2">
                            <input type="checkbox" id="criteria2" class="mr-2 w-4 h-4">
                            <label for="criteria2" class="cursor-pointer">All tests passing</label>
                        </div>
                        <div class="py-2">
                            <input type="checkbox" id="criteria3" class="mr-2 w-4 h-4">
                            <label for="criteria3" class="cursor-pointer">Documentation complete</label>
                        </div>
                        <div class="py-2">
                            <input type="checkbox" id="criteria4" class="mr-2 w-4 h-4">
                            <label for="criteria4" class="cursor-pointer">No security vulnerabilities</label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button class="flex-1 px-4 py-3.5 bg-green-500 text-white rounded-lg font-semibold text-base hover:bg-green-600 transition-colors">
                        <i class="bi bi-check-circle mr-2"></i>Approve & Mark as Done
                    </button>
                    <button class="flex-1 px-4 py-3.5 bg-amber-500 text-white rounded-lg font-semibold text-base hover:bg-amber-600 transition-colors">
                        <i class="bi bi-arrow-clockwise mr-2"></i>Request Revision
                    </button>
                    <button class="px-6 py-3.5 bg-white text-gray-800 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors" onclick="closeDetailModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(taskId) {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.task-card');

            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter by project
        document.getElementById('filterDropdown').addEventListener('change', function(e) {
            const selectedProject = e.target.value;
            const cards = document.querySelectorAll('.task-card');

            if (selectedProject === 'All Projects') {
                cards.forEach(card => card.style.display = 'block');
            } else {
                cards.forEach(card => {
                    const projectTag = card.querySelector('span:last-of-type').textContent;
                    if (projectTag.includes(selectedProject)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        });
    </script>
</div>
@endsection
