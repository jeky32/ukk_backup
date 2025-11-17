{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - Manajemen Proyek')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Manajemen Proyek dan Tim')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tombol Tambah Proyek -->
            <div class="mb-6 text-right">
                <a href="{{ route('admin.projects.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 inline-flex">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Proyek</span>
                </a>
            </div>

            <!-- Tabel Daftar Proyek -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Proyek</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($projects as $index => $project)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $project->project_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $project->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $project->deadline ? date('d M Y', strtotime($project->deadline)) : '-' }}
                                    </td>

                                    <!-- Hitung progress -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $totalCards = $project->boards->flatMap->cards->count();
                                            $doneCards = $project->boards->flatMap->cards->where('status', 'done')->count();
                                            $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center space-x-3">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 w-12">{{ $progress }}%</span>
                                        </div>
                                    </td>

                                    <!-- Tombol Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.projects.show', $project->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin menghapus proyek ini?')" 
                                                        class="text-red-600 hover:text-red-900 transition-colors"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-folder-open text-4xl mb-2 text-gray-300"></i>
                                        <p class="text-lg">Belum ada proyek</p>
                                        <p class="text-sm mt-1">Mulai dengan membuat proyek pertama Anda</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection