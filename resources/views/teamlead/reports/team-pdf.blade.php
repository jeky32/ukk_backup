<!-- resources/views/teamlead/reports/team-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Team Performance Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.5; 
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 { 
            font-size: 26px; 
            margin-bottom: 8px; 
        }
        .header p { 
            font-size: 13px; 
            opacity: 0.9; 
        }
        .meta-info {
            background: #f0fdf4;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #10b981;
            border-radius: 4px;
        }
        .meta-info table { 
            width: 100%; 
        }
        .meta-info td { 
            padding: 5px 0; 
        }
        .meta-info strong { 
            color: #059669; 
        }
        h2 {
            color: #10b981;
            font-size: 17px;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }
        h3 {
            color: #059669;
            font-size: 15px;
            margin: 18px 0 10px 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f0fdf4;
            border: 1px solid #d1fae5;
        }
        .stat-box .number {
            font-size: 28px;
            font-weight: bold;
            color: #10b981;
            display: block;
            margin-bottom: 5px;
        }
        .stat-box .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #10b981;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        tr:hover {
            background: #f0fdf4;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-excellent { 
            background: #d1fae5; 
            color: #065f46; 
        }
        .badge-good { 
            background: #dbeafe; 
            color: #1e40af; 
        }
        .badge-average { 
            background: #fef3c7; 
            color: #92400e; 
        }
        .badge-poor { 
            background: #fee2e2; 
            color: #991b1b; 
        }
        .progress-bar {
            width: 100%;
            height: 18px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            display: inline-block;
        }
        .progress-fill {
            height: 100%;
            text-align: center;
            color: white;
            font-size: 9px;
            line-height: 18px;
            font-weight: bold;
            float: left;
        }
        .progress-excellent { background: linear-gradient(90deg, #10b981 0%, #059669 100%); }
        .progress-good { background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%); }
        .progress-average { background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%); }
        .progress-poor { background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%); }
        .summary-box {
            background: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }
        .summary-box h3 {
            color: #10b981;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .summary-item {
            padding: 8px 0;
            border-bottom: 1px solid #d1fae5;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .top-performer {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 10px 0;
        }
        .top-performer strong {
            color: #92400e;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        .page-break {
            page-break-after: always;
        }
        .rank-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: bold;
            font-size: 11px;
            color: white;
        }
        .rank-1 { background: #f59e0b; }
        .rank-2 { background: #94a3b8; }
        .rank-3 { background: #cd7f32; }
        .rank-other { background: #6b7280; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>?? TEAM PERFORMANCE REPORT</h1>
        <p>Comprehensive Team Analysis and Statistics</p>
    </div>

    <!-- Meta Info -->
    <div class="meta-info">
        <table>
            <tr>
                <td width="50%"><strong>Generated By:</strong> {{ $generatedBy->full_name }}</td>
                <td><strong>Generated At:</strong> {{ $generatedAt->format('d M Y, H:i') }}</td>
            </tr>
            @if($startDate && $endDate)
            <tr>
                <td colspan="2">
                    <strong>Report Period:</strong> 
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - 
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </td>
            </tr>
            @else
            <tr>
                <td colspan="2"><strong>Report Period:</strong> All Time</td>
            </tr>
            @endif
            <tr>
                <td colspan="2">
                    <strong>Projects Included:</strong> 
                    {{ $projects->pluck('project_name')->join(', ') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Overall Statistics -->
    <h2>?? Overall Team Statistics</h2>
    @php
        $totalTeamTasks = $teamPerformance->sum('total_tasks');
        $totalTeamCompleted = $teamPerformance->sum('completed_tasks');
        $totalTeamHours = $teamPerformance->sum('total_hours');
        $avgCompletionRate = $teamPerformance->avg('completion_rate');
    @endphp

    <div class="stats-grid">
        <div class="stat-box">
            <span class="number">{{ $teamPerformance->count() }}</span>
            <span class="label">Team Members</span>
        </div>
        <div class="stat-box">
            <span class="number">{{ $totalTeamTasks }}</span>
            <span class="label">Total Tasks</span>
        </div>
        <div class="stat-box">
            <span class="number">{{ $totalTeamCompleted }}</span>
            <span class="label">Completed Tasks</span>
        </div>
        <div class="stat-box">
            <span class="number">{{ number_format($totalTeamHours, 1) }}h</span>
            <span class="label">Total Hours</span>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <h3>?? Key Insights</h3>
        <div class="summary-item">
            <strong>Average Completion Rate:</strong> {{ number_format($avgCompletionRate, 1) }}%
        </div>
        <div class="summary-item">
            <strong>Average Hours per Task:</strong> 
            {{ $totalTeamCompleted > 0 ? number_format($totalTeamHours / $totalTeamCompleted, 1) : 0 }}h
        </div>
        <div class="summary-item">
            <strong>Most Productive Member:</strong> 
            {{ $teamPerformance->sortByDesc('completed_tasks')->first()['member']->full_name ?? 'N/A' }}
        </div>
        <div class="summary-item">
            <strong>Highest Completion Rate:</strong> 
            {{ $teamPerformance->sortByDesc('completion_rate')->first()['member']->full_name ?? 'N/A' }} 
            ({{ $teamPerformance->sortByDesc('completion_rate')->first()['completion_rate'] ?? 0 }}%)
        </div>
    </div>

    <!-- Top 3 Performers -->
    @if($teamPerformance->count() >= 3)
    <h2>?? Top Performers</h2>
    @foreach($teamPerformance->sortByDesc('completed_tasks')->take(3) as $top)
    <div class="top-performer">
        <table style="margin: 0;">
            <tr>
                <td width="5%">
                    <span class="rank-badge rank-{{ $loop->iteration }}">{{ $loop->iteration }}</span>
                </td>
                <td width="30%">
                    <strong>{{ $top['member']->full_name }}</strong><br>
                    <small style="color: #6b7280;">{{ ucfirst($top['member']->role) }}</small>
                </td>
                <td width="20%">
                    <strong>{{ $top['completed_tasks'] }}</strong> tasks completed
                </td>
                <td width="20%">
                    <strong>{{ $top['completion_rate'] }}%</strong> completion rate
                </td>
                <td width="25%">
                    <strong>{{ number_format($top['total_hours'], 1) }}h</strong> total hours
                </td>
            </tr>
        </table>
    </div>
    @endforeach
    @endif

    <div class="page-break"></div>

    <!-- Detailed Team Performance -->
    <h2>?? Detailed Team Performance</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="22%">Team Member</th>
                <th width="10%">Total Tasks</th>
                <th width="10%">Completed</th>
                <th width="10%">In Progress</th>
                <th width="18%">Completion Rate</th>
                <th width="12%">Total Hours</th>
                <th width="13%">Avg Hours/Task</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teamPerformance as $performance)
            <tr>
                <td style="text-align: center;">
                    @if($loop->iteration <= 3)
                        <span class="rank-badge rank-{{ $loop->iteration }}">{{ $loop->iteration }}</span>
                    @else
                        <span class="rank-badge rank-other">{{ $loop->iteration }}</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $performance['member']->full_name }}</strong><br>
                    <small style="color: #6b7280;">{{ ucfirst($performance['member']->role) }}</small>
                </td>
                <td style="text-align: center;">
                    <strong>{{ $performance['total_tasks'] }}</strong>
                </td>
                <td style="text-align: center;">
                    <strong style="color: #10b981;">{{ $performance['completed_tasks'] }}</strong>
                </td>
                <td style="text-align: center;">
                    <strong style="color: #f59e0b;">{{ $performance['in_progress_tasks'] }}</strong>
                </td>
                <td>
                    @php
                        $rate = $performance['completion_rate'];
                        if ($rate >= 80) {
                            $class = 'excellent';
                            $badge = 'excellent';
                            $label = 'Excellent';
                        } elseif ($rate >= 60) {
                            $class = 'good';
                            $badge = 'good';
                            $label = 'Good';
                        } elseif ($rate >= 40) {
                            $class = 'average';
                            $badge = 'average';
                            $label = 'Average';
                        } else {
                            $class = 'poor';
                            $badge = 'poor';
                            $label = 'Needs Improvement';
                        }
                    @endphp
                    <div class="progress-bar">
                        <div class="progress-fill progress-{{ $class }}" style="width: {{ $rate }}%">
                            {{ $rate }}%
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 3px;">
                        <span class="badge badge-{{ $badge }}">{{ $label }}</span>
                    </div>
                </td>
                <td style="text-align: center;">
                    <strong>{{ number_format($performance['total_hours'], 1) }}h</strong>
                </td>
                <td style="text-align: center;">
                    {{ number_format($performance['avg_hours_per_task'], 1) }}h
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Performance Analysis -->
    <h2>?? Performance Analysis</h2>
    
    <h3>Completion Rate Distribution</h3>
    @php
        $excellent = $teamPerformance->filter(fn($p) => $p['completion_rate'] >= 80)->count();
        $good = $teamPerformance->filter(fn($p) => $p['completion_rate'] >= 60 && $p['completion_rate'] < 80)->count();
        $average = $teamPerformance->filter(fn($p) => $p['completion_rate'] >= 40 && $p['completion_rate'] < 60)->count();
        $poor = $teamPerformance->filter(fn($p) => $p['completion_rate'] < 40)->count();
    @endphp
    
    <table>
        <thead>
            <tr>
                <th>Performance Level</th>
                <th width="15%">Count</th>
                <th width="15%">Percentage</th>
                <th width="35%">Visual</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-excellent">Excellent (=80%)</span></td>
                <td style="text-align: center;"><strong>{{ $excellent }}</strong></td>
                <td style="text-align: center;">{{ $teamPerformance->count() > 0 ? round(($excellent / $teamPerformance->count()) * 100) : 0 }}%</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill progress-excellent" 
                             style="width: {{ $teamPerformance->count() > 0 ? ($excellent / $teamPerformance->count()) * 100 : 0 }}%">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><span class="badge badge-good">Good (60-79%)</span></td>
                <td style="text-align: center;"><strong>{{ $good }}</strong></td>
                <td style="text-align: center;">{{ $teamPerformance->count() > 0 ? round(($good / $teamPerformance->count()) * 100) : 0 }}%</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill progress-good" 
                             style="width: {{ $teamPerformance->count() > 0 ? ($good / $teamPerformance->count()) * 100 : 0 }}%">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><span class="badge badge-average">Average (40-59%)</span></td>
                <td style="text-align: center;"><strong>{{ $average }}</strong></td>
                <td style="text-align: center;">{{ $teamPerformance->count() > 0 ? round(($average / $teamPerformance->count()) * 100) : 0 }}%</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill progress-average" 
                             style="width: {{ $teamPerformance->count() > 0 ? ($average / $teamPerformance->count()) * 100 : 0 }}%">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><span class="badge badge-poor">Needs Improvement (<40%)</span></td>
                <td style="text-align: center;"><strong>{{ $poor }}</strong></td>
                <td style="text-align: center;">{{ $teamPerformance->count() > 0 ? round(($poor / $teamPerformance->count()) * 100) : 0 }}%</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill progress-poor" 
                             style="width: {{ $teamPerformance->count() > 0 ? ($poor / $teamPerformance->count()) * 100 : 0 }}%">
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Recommendations -->
    <div class="summary-box" style="margin-top: 20px;">
        <h3>?? Recommendations</h3>
        @if($avgCompletionRate >= 80)
            <div class="summary-item">
                <strong>Overall Team Health:</strong> Excellent! The team is performing exceptionally well.
            </div>
        @elseif($avgCompletionRate >= 60)
            <div class="summary-item">
                <strong>Overall Team Health:</strong> Good performance. Consider focusing on members with lower completion rates.
            </div>
        @else
            <div class="summary-item">
                <strong>Overall Team Health:</strong> Attention needed. Review task assignments and provide additional support.
            </div>
        @endif
        
        @if($poor > 0)
            <div class="summary-item">
                <strong>Action Item:</strong> {{ $poor }} team member(s) need additional support or task reassignment.
            </div>
        @endif
        
        @if($excellent >= $teamPerformance->count() / 2)
            <div class="summary-item">
                <strong>Strength:</strong> More than half the team is performing excellently. Consider sharing best practices.
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Team Performance Report</strong> - Generated by {{ $generatedBy->full_name }} on {{ $generatedAt->format('d F Y, H:i') }}</p>
        <p>This report is confidential and intended for internal use only</p>
        <p>© {{ date('Y') }} Project Management System</p>
    </div>
</body>
</html>