<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
  public function index()
{
    $reports = Project::with('creator')
        ->withCount([
            'tasks as task_total',
            'tasks as task_completed' => function ($q) { $q->where('status', 'done'); },
            'tasks as task_blocked' => function ($q) { $q->where('status', 'blocker'); },
        ])
        ->get()
        ->map(function ($proj) {
            $done = $proj->task_completed ?? 0;
            $total = ($proj->task_total ?? 0) > 0 ? $proj->task_total : 1;
            $proj->progress_percent = floor($done * 100 / $total);
            $proj->is_overdue = $proj->deadline && now()->isAfter($proj->deadline);
            return $proj;
        });

    return view('admin.reports.index', compact('reports'));
}

// Tambahkan untuk fungsi cetak PDF
public function exportPdf()
{
    $reports = Project::with('creator')
        ->withCount([
            'tasks as task_total',
            'tasks as task_completed' => function ($q) { $q->where('status', 'done'); },
            'tasks as task_blocked' => function ($q) { $q->where('status', 'blocker'); },
        ])
        ->get()
        ->map(function ($proj) {
            $done = $proj->task_completed ?? 0;
            $total = ($proj->task_total ?? 0) > 0 ? $proj->task_total : 1;
            $proj->progress_percent = floor($done * 100 / $total);
            $proj->is_overdue = $proj->deadline && now()->isAfter($proj->deadline);
            return $proj;
        });

    $pdf = Pdf::loadView('admin.reports.pdf', [
        'reports' => $reports,
        'printed_at' => now()->translatedFormat('l, d F Y H:i'),
    ]);

    return $pdf->download('project-reports-' . now()->format('Ymd_His') . '.pdf');
}

}
