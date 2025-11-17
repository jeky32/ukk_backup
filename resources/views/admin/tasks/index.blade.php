@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-10 gap-6">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Task List</h1>
        <button onclick="openCreateModal()" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus"></i> Add New
        </button>
    </div>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Task</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Priority</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Assigned</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Due Date</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Created</th>
                    <th class="px-6 py-5 text-left text-xs font-bold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-5 font-bold text-gray-900 whitespace-nowrap">
                        {{ $task->title }}
                        @if($task->description)
                        <div class="text-xs text-gray-400 font-normal">{{ Str::limit($task->description, 40) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs shadow-sm">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 font-semibold text-xs shadow-sm">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-5 flex items-center">
                        @if($task->assignedUser)
                        <span class="w-8 h-8 rounded-full bg-indigo-50 border-2 border-white flex items-center justify-center font-bold text-indigo-700 mr-2">
                            {{ strtoupper(substr($task->assignedUser->username, 0, 2)) }}
                        </span>
                        <span class="text-sm text-gray-800">{{ $task->assignedUser->full_name }}</span>
                        @else
                        <span class="text-gray-400 italic text-xs">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-gray-700 text-sm">
                        @if($task->due_date)
                            {{ userTime($task->due_date, 'd M Y') }}
                        @else
                            <span class="text-gray-400">No deadline</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-gray-500 text-xs">{{ $task->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-5 flex gap-2">
                        <a href="{{ route('admin.tasks.index', $task) }}" class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 rounded-md font-semibold hover:bg-indigo-100 transition">Edit</a>
                        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block px-3 py-1 bg-red-50 text-red-500 font-semibold rounded-md hover:bg-red-100 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-400 py-20">No tasks found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
