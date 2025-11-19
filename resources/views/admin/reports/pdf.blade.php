<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Reports</title>
    <style>
        table { border-collapse: collapse; width: 100%; font-size: 12px;}
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        h2 { margin-bottom: 2px;}
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <h2>Laporan Projek</h2>
    <p style="font-size: 11px; color: #444;">Dicetak: {{ $printed_at }}</p>
    <table>
        <thead>
            <tr>
                <th>PROJECT</th>
                <th>OWNER</th>
                <th>TOTAL TASKS</th>
                <th>COMPLETED</th>
                <th>BLOCKED</th>
                <th>PROGRESS</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reports as $proj)
            <tr>
                <td>{{ $proj->project_name }}</td>
                <td>{{ $proj->creator->full_name ?? $proj->creator->username ?? '-' }}</td>
                <td>{{ $proj->task_total }}</td>
                <td>{{ $proj->task_completed }}</td>
                <td>{{ $proj->task_blocked }}</td>
                <td>{{ $proj->progress_percent }}%</td>
                <td>
                    @php
                        $statusLabel = 'Ongoing';
                        if($proj->is_overdue) $statusLabel = 'Overdue';
                        elseif($proj->progress_percent == 100) $statusLabel = 'Done';
                    @endphp
                    {{ $statusLabel }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
