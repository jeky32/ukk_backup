<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report - Team Lead Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <style>
        :root {
            --primary: #4F46E5;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --bg-light: #F9FAFB;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-dark);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-body {
            padding: 24px;
        }

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin: 12px 0;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .progress-gradient {
            height: 12px;
            background: linear-gradient(90deg, var(--primary) 0%, #7C3AED 100%);
            border-radius: 6px;
        }

        .progress {
            height: 12px;
            border-radius: 6px;
            background-color: #E5E7EB;
        }

        .btn-export {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid #E5E7EB;
            background: white;
            color: var(--text-dark);
            margin-left: 8px;
        }

        .btn-export:hover {
            background: var(--bg-light);
        }

        .btn-share {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-share:hover {
            background: #4338CA;
            color: white;
        }

        .date-filter .btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid #E5E7EB;
            background: white;
            color: var(--text-muted);
            margin-right: 8px;
        }

        .date-filter .btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .project-thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, #7C3AED 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
        }

        .team-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .priority-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-high {
            background: #FEE2E2;
            color: var(--danger);
        }

        .badge-medium {
            background: #FEF3C7;
            color: var(--warning);
        }

        .badge-low {
            background: #D1FAE5;
            color: var(--success);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-fixed {
            background: #D1FAE5;
            color: var(--success);
        }

        .status-pending {
            background: #FEF3C7;
            color: var(--warning);
        }

        .recommendations-box {
            background: #FFFBEB;
            border-left: 4px solid var(--warning);
            border-radius: 8px;
            padding: 20px;
        }

        .recommendations-box ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .recommendations-box li {
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .trend-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .trend-up {
            background: #D1FAE5;
            color: var(--success);
        }

        .mini-progress {
            height: 6px;
            border-radius: 3px;
            background-color: #E5E7EB;
        }

        .mini-progress-bar {
            height: 100%;
            border-radius: 3px;
        }

        table {
            margin-bottom: 0;
        }

        .table-hover tbody tr:hover {
            background-color: var(--bg-light);
        }

        .alert-box {
            border-left: 4px solid var(--warning);
            border-radius: 8px;
            background: #FFFBEB;
            padding: 16px;
            margin-bottom: 12px;
        }

        .time-bar {
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            margin-bottom: 12px;
            color: white;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-4">
        <!-- HEADER SECTION -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-start mb-4">
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="project-thumbnail me-3">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h1 class="mb-1 fw-bold">Project Report</h1>
                            <h4 class="text-muted mb-0">E-Commerce Platform Redesign</h4>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-export">
                            <i class="bi bi-file-pdf me-2"></i>PDF
                        </button>
                        <button class="btn btn-export">
                            <i class="bi bi-file-excel me-2"></i>Excel
                        </button>
                        <button class="btn btn-export btn-share">
                            <i class="bi bi-share me-2"></i>Share
                        </button>
                    </div>
                </div>

                <!-- Overall Progress -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Overall Progress</span>
                        <span class="fs-3 fw-bold" style="color: var(--primary);">75%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-gradient" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>

                <!-- Date Range Picker -->
                <div class="date-filter">
                    <button class="btn active">Minggu Ini</button>
                    <button class="btn">Bulan Ini</button>
                    <button class="btn">Custom Range</button>
                </div>
            </div>
        </div>

        <!-- SECTION 1: SUMMARY CARDS -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="background: #EEF2FF;">
                                <i class="bi bi-clipboard-data" style="color: var(--primary);"></i>
                            </div>
                            <span class="trend-badge trend-up">
                                <i class="bi bi-arrow-up me-1"></i>+12%
                            </span>
                        </div>
                        <p class="stat-label mb-2">Total Tasks</p>
                        <p class="stat-number mb-0">21</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="background: #D1FAE5;">
                                <i class="bi bi-check-circle-fill" style="color: var(--success);"></i>
                            </div>
                            <div class="mini-progress" style="width: 60px;">
                                <div class="mini-progress-bar" style="width: 57%; background: var(--success);"></div>
                            </div>
                        </div>
                        <p class="stat-label mb-2">Completed</p>
                        <p class="stat-number mb-0">12 <span class="fs-5 text-muted">(57%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="background: #FEF3C7;">
                                <i class="bi bi-clock-fill" style="color: var(--warning);"></i>
                            </div>
                        </div>
                        <p class="stat-label mb-2">Active</p>
                        <p class="stat-number mb-0">6 <span class="fs-5 text-muted">(28%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="background: #FEE2E2;">
                                <i class="bi bi-exclamation-triangle-fill" style="color: var(--danger);"></i>
                            </div>
                        </div>
                        <p class="stat-label mb-2">Delayed</p>
                        <p class="stat-number mb-0">3 <span class="fs-5 text-muted">(15%)</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2 & 3: TASK BREAKDOWN + TEAM PERFORMANCE -->
        <div class="row">
            <!-- Task Breakdown -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Task Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="taskBreakdownChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Performance -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Team Performance</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Tasks</th>
                                        <th>Productivity</th>
                                        <th>Avg Time</th>
                                        <th>On Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="team-avatar me-2">DC</div>
                                                <span class="fw-semibold">David Chen</span>
                                            </div>
                                        </td>
                                        <td><strong>15</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mini-progress me-2" style="width: 80px;">
                                                    <div class="mini-progress-bar" style="width: 92%; background: var(--success);"></div>
                                                </div>
                                                <span class="fw-semibold">92%</span>
                                            </div>
                                        </td>
                                        <td>3.2h</td>
                                        <td><span class="badge" style="background: var(--success); color: white;">92%</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="team-avatar me-2" style="background: linear-gradient(135deg, #EC4899 0%, #F472B6 100%);">SW</div>
                                                <span class="fw-semibold">Sari Wijaya</span>
                                            </div>
                                        </td>
                                        <td><strong>10</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mini-progress me-2" style="width: 80px;">
                                                    <div class="mini-progress-bar" style="width: 85%; background: var(--success);"></div>
                                                </div>
                                                <span class="fw-semibold">85%</span>
                                            </div>
                                        </td>
                                        <td>4.5h</td>
                                        <td><span class="badge" style="background: var(--warning); color: white;">80%</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="team-avatar me-2" style="background: linear-gradient(135deg, #14B8A6 0%, #2DD4BF 100%);">RK</div>
                                                <span class="fw-semibold">Rina Kusuma</span>
                                            </div>
                                        </td>
                                        <td><strong>8</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mini-progress me-2" style="width: 80px;">
                                                    <div class="mini-progress-bar" style="width: 78%; background: var(--warning);"></div>
                                                </div>
                                                <span class="fw-semibold">78%</span>
                                            </div>
                                        </td>
                                        <td>3.8h</td>
                                        <td><span class="badge" style="background: var(--success); color: white;">85%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: TIME TRACKING -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Time Tracking</h5>
                        <span class="badge bg-primary fs-6">Total: 120 hours</span>
                    </div>
                    <div class="card-body">
                        <div class="time-bar" style="background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%); width: 70%;">
                            <span>David Chen</span>
                            <span>35h</span>
                        </div>
                        <div class="time-bar" style="background: linear-gradient(90deg, #EC4899 0%, #F472B6 100%); width: 56%;">
                            <span>Sari Wijaya</span>
                            <span>28h</span>
                        </div>
                        <div class="time-bar" style="background: linear-gradient(90deg, #14B8A6 0%, #2DD4BF 100%); width: 64%;">
                            <span>Rina Kusuma</span>
                            <span>32h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5 & 6: TASK PRIORITY + BLOCKERS -->
        <div class="row">
            <!-- Task Priority -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Task Priority</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 p-3 rounded" style="background: #FEE2E2;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="priority-badge badge-high me-2">HIGH PRIORITY</span>
                                    <strong>5 tasks</strong>
                                </div>
                                <strong style="color: var(--danger);">60%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 60%; background: var(--danger);"></div>
                            </div>
                        </div>

                        <div class="mb-3 p-3 rounded" style="background: #FEF3C7;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="priority-badge badge-medium me-2">MEDIUM PRIORITY</span>
                                    <strong>8 tasks</strong>
                                </div>
                                <strong style="color: var(--warning);">75%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 75%; background: var(--warning);"></div>
                            </div>
                        </div>

                        <div class="p-3 rounded" style="background: #D1FAE5;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="priority-badge badge-low me-2">LOW PRIORITY</span>
                                    <strong>8 tasks</strong>
                                </div>
                                <strong style="color: var(--success);">50%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%; background: var(--success);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blockers & Issues -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Blockers & Issues</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert-box">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4" style="color: var(--warning);"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold">API Integration Issue</h6>
                                            <small class="text-muted">Duration: 2 days</small>
                                        </div>
                                        <span class="status-badge status-fixed">Fixed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert-box">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4" style="color: var(--warning);"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold">Design Delay - Mobile View</h6>
                                            <small class="text-muted">Duration: 1 day</small>
                                        </div>
                                        <span class="status-badge status-pending">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 7: RECOMMENDATIONS -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="recommendations-box">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-lightbulb-fill me-3 fs-3" style="color: var(--warning);"></i>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-3">Recommendations</h5>
                                    <ul>
                                        <li>Pertimbangkan menambah 1 developer untuk mempercepat backend development</li>
                                        <li>Training API integration diperlukan untuk tim frontend</li>
                                        <li>Review daily standup time - banyak member tidak bisa hadir jam 9 pagi</li>
                                        <li>Gunakan template code review untuk mempercepat proses</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Task Breakdown Donut Chart
        const ctx = document.getElementById('taskBreakdownChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['To Do', 'In Progress', 'Review', 'Done'],
                datasets: [{
                    data: [3, 6, 3, 9],
                    backgroundColor: [
                        '#9CA3AF',
                        '#4F46E5',
                        '#F59E0B',
                        '#10B981'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13,
                                weight: 600
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return {
                                            text: `${label} (${value} - ${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} tasks (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // Date filter buttons
        const dateButtons = document.querySelectorAll('.date-filter .btn');
        dateButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                dateButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
