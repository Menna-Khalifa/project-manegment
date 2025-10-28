@extends('dashboard.layouts.master')

@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ asset('dashboard/assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ asset('dashboard/assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <style>
        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: #fff !important;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-card.primary {
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }

        .stats-card.success {
            --gradient-start: #11998e;
            --gradient-end: #38ef7d;
        }

        .stats-card.warning {
            --gradient-start: #f093fb;
            --gradient-end: #f5576c;
        }

        .stats-card.danger {
            --gradient-start: #4facfe;
            --gradient-end: #00f2fe;
        }

        .stats-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .custom-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .modern-list-item {
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            padding: 15px;
        }

        .modern-list-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .project-progress {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }

        .project-progress .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .timeline-item {
            position: relative;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #007bff;
        }

        .timeline-item:nth-child(2n) {
            border-left-color: #28a745;
        }

        .timeline-item:nth-child(3n) {
            border-left-color: #ffc107;
        }

        .modern-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .gradient-text {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-modern {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table-modern thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px;
        }

        .table-modern tbody td {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .animate-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .animate-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 2px;
        }
    </style>
@endsection


@section('content')
    <!-- Stats Cards Row -->
    <div class="row mt-5">
        <!-- إجمالي المشاريع -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card stats-card bg-primary-gradient">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stats-number animate-number">{{ $totalProjects }}</div>
                    <div class="stats-label">Total Projects</div>
                    <div class="stats-change">
                        <i class="fas fa-arrow-up me-1"></i>
                        +{{ $todayProjects }} Today Project
                    </div>
                </div>
            </div>
        </div>

        <!-- إجمالي قيمة المشاريع -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card stats-card bg-danger-gradient">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number animate-number">{{ number_format($totalProjectsCost, 2) }}</div>
                    <div class="stats-label">Total Project Value</div>
                    <div class="stats-change">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ number_format($totalRemainingAmount, 2) }} Remaining
                    </div>
                </div>
            </div>
        </div>

        <!-- إجمالي المدفوعات -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card stats-card bg-success-gradient">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="stats-number animate-number">{{ number_format($totalPaidAmount, 2) }}</div>
                    <div class="stats-label">Total Payments</div>
                    <div class="stats-change">
                        <i class="fas fa-arrow-up me-1"></i>
                        +{{ number_format($todayApprovedPayments, 2) }} Today
                    </div>
                </div>
            </div>
        </div>

        <!-- المدفوعات المعلقة -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card stats-card bg-warning-gradient">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number animate-number">{{ number_format($pendingPayments, 2) }}</div>
                    <div class="stats-label">Pending payments</div>
                    <div class="stats-change">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $pendingInvoices->count() }} Pending Invoice
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Row -->
    <div class="row">
        <!-- Project Status Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card custom-card">
                <div class="card-header border-0 bg-white">
                    <h5 class="section-title mb-0 gradient-text">Overview of projects</h5>
                    <p class="text-muted mb-0">Comprehensive statistics of the status of projects and their distribution</p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="icon-circle mx-auto mb-2" style="background: #667eea;">
                                <i class="fas fa-play"></i>
                            </div>
                            <h4 class="font-weight-bold text-primary">{{ $activeProjects }}</h4>
                            <p class="text-muted mb-0">Active projects</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="icon-circle mx-auto mb-2" style="background: #28a745;">
                                <i class="fas fa-check"></i>
                            </div>
                            <h4 class="font-weight-bold text-success">{{ $compeletedProjects }}</h4>
                            <p class="text-muted mb-0">Completed projects</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="icon-circle mx-auto mb-2" style="background: #ffc107;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4 class="font-weight-bold text-warning">{{ $pendingProjects }}</h4>
                            <p class="text-muted mb-0">Pending projects</p>
                        </div>
                        <div class="col-md-4 mt-2 text-center">
                            <div class="icon-circle mx-auto mb-2" style="background: red;">
                                <i class="fas fa-times"></i>
                            </div>
                            <h4 class="font-weight-bold text-danger">{{ $canceledProjects }}</h4>
                            <p class="text-muted mb-0">Cancelled projects</p>
                        </div>
                    </div>
                    <div id="projectsChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Project Types -->
        <div class="col-lg-4 mb-4">
            <div class="card custom-card h-100">
                <div class="card-header border-0 bg-white">
                    <h5 class="section-title mb-0">Distribution of projects</h5>
                    <p class="text-muted mb-0">By type</p>
                </div>
                <div class="card-body">
                    @foreach ($projectsByType as $type => $count)
                        <div class="modern-list-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle mr-3"
                                    style="background:
                                @if ($type == 'government') #dc3545
                                @elseif($type == 'commercial') #007bff
                                @else #28a745 @endif; width: 40px; height: 40px; font-size: 1rem;">
                                    <i
                                        class="
                                    @if ($type == 'government') fas fa-landmark
                                    @elseif($type == 'commercial') fas fa-building
                                    @else fas fa-home @endif"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">
                                        @if ($type == 'government')
                                            Government projects
                                        @elseif($type == 'commercial')
                                            Commercial projects
                                        @else
                                            Residential projects
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ round(($count / $totalProjects) * 100, 1) }}% Out of the
                                        total</small>
                                </div>
                            </div>
                            <span class="modern-badge badge-primary">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="row">

        <!-- Project Progress Table -->
        <div class="col-lg-12 mb-4">
            <div class="card custom-card">
                <div class="card-header border-0 bg-white">
                    <h5 class="section-title mb-0">projects progress</h5>
                    <p class="text-muted mb-0">Completion rates for active projects</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Project Duration</th>
                                    <th>Status</th>
                                    <th>Project Cost</th>
                                    <th>Payments</th>
                                    <th>Completion percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentProjects as $key => $project)
                                    <tr>
                                        <td>
                                            @can('show_project')
                                                <a href="{{ route('projects.show', $project->id) }}">
                                                    {{ $project->po_num ?? __('general.not_found') }}
                                                </a>
                                            @else
                                                {{ $project->po_num ?? __('general.not_found') }}
                                            @endcan
                                        </td>
                                        <td>{{ $project->type ?? __('general.not_found') }}</td>
                                        <td>{{ $project->name ?? __('general.not_found') }}</td>
                                        <td>{{ $project->start_date ? $project->start_date->format('Y-m-d') : __('general.not_found') }}
                                        </td>
                                        <td>{{ $project->end_date ? $project->end_date->format('Y-m-d') : __('general.not_found') }}
                                        </td>
                                        @php
                                            $remainingDays = now()->diffInDays($project->end_date, false); // باقي من اليوم لحد نهاية المشروع
                                        @endphp

                                        <td @class([
                                            'text-success' => $remainingDays >= 40, // أخضر
                                            'text-warning' => $remainingDays >= 20 && $remainingDays < 40, // أصفر
                                            'text-danger' => $remainingDays < 20, // أحمر
                                        ])>
                                            {{ $remainingDays }} days
                                        </td>
                                        <td>
                                            @php
                                                // تحديد لون البادج بناءً على حالة المشروع
                                                $statusClass = match ($project->status) {
                                                    'active' => 'primary',
                                                    'completed' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($project->project_cost) }}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                Pending: {{ number_format($project->total_payment_pending) }}
                                            </span>
                                            <span class="badge bg-info">
                                                Remaining: {{ number_format($project->remaining_amount) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped
                                                    {{ $project->completion_percentage == 100 ? 'bg-success' : ($project->completion_percentage >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                    role="progressbar"
                                                    style="width: {{ $project->completion_percentage }}%">
                                                    {{ $project->completion_percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Groups -->
        <div class="col-lg-6 mb-4">
            <div class="card custom-card">
                <div class="card-header border-0 bg-white">
                    <h5 class="section-title mb-0">The best collections</h5>
                    <p class="text-muted mb-0">Most involved in projects</p>
                </div>
                <div class="card-body">
                    @foreach ($topGroups as $index => $group)
                        <div class="modern-list-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle mr-3"
                                    style="background:
                                @if ($index == 0) #ffd700
                                @elseif($index == 1) #c0c0c0
                                @elseif($index == 2) #cd7f32
                                @else #6c757d @endif; width: 40px; height: 40px;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $group->name }}</h6>
                                    <small class="text-muted">{{ $group->users_count }} Active member</small>
                                </div>
                            </div>
                            <span class="modern-badge badge-secondary">#{{ $index + 1 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Top Groups -->
        <div class="col-lg-6 mb-4">
            <!-- Quick Stats -->
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="icon-circle mx-auto mb-2" style="background: #667eea;">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="mb-0">{{ $activeUsers }}</h5>
                            <small class="text-muted">Active user</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="icon-circle mx-auto mb-2" style="background: #f093fb;">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h5 class="mb-0">{{ $totalSections }}</h5>
                            <small class="text-muted">Section</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
@endsection

@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ asset('dashboard/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ asset('dashboard/assets/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ asset('dashboard/assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ asset('dashboard/assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ asset('dashboard/assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ asset('dashboard/assets/js/index.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/jquery.vmap.sampledata.js') }}"></script>

    <script>
        $(document).ready(function() {
            // إعداد الرسم البياني للمشاريع مع إظهار النسب
            function initProjectsChart() {
                const ctx = document.getElementById('projectsChart');
                if (!ctx) return;

                let canvas;
                if (ctx.tagName.toLowerCase() === 'canvas') {
                    canvas = ctx;
                } else {
                    canvas = document.createElement('canvas');
                    canvas.style.height = '300px';
                    canvas.style.width = '100%';
                    ctx.appendChild(canvas);
                }

                // حساب البيانات
                const activeProjects = {{ $activeProjects }};
                const completedProjects = {{ $compeletedProjects }};
                const pendingProjects = {{ $pendingProjects }};
                const canceledProjects = {{ $canceledProjects }};
                const otherProjects =
                    {{ $totalProjects - $activeProjects - $compeletedProjects - $pendingProjects - $canceledProjects }};

                const dataValues = [activeProjects, completedProjects, pendingProjects, canceledProjects,
                    otherProjects
                ];
                const totalProjects = dataValues.reduce((a, b) => a + b, 0);

                // حساب النسب
                const percentages = dataValues.map(value =>
                    totalProjects > 0 ? ((value / totalProjects) * 100).toFixed(1) : 0
                );

                const projectsData = {
                    // الطريقة الأولى: إظهار النسب في التسميات
                    labels: [
                        `Active projects (${percentages[0]}%)`,
                        `Completed projects (${percentages[1]}%)`,
                        `Pending projects (${percentages[2]}%)`,
                        `Cancelled projects (${percentages[3]}%)`,
                    ],
                    datasets: [{
                        label: 'Number of projects',
                        data: dataValues,
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)', // أزرق - نشطة
                            'rgba(40, 167, 69, 0.8)', // أخضر - مكتملة
                            'rgba(255, 193, 7, 0.8)', // أصفر - معلقة
                            'rgba(220, 53, 69, 0.8)', // أحمر - ملغاة
                        ],
                        borderColor: [
                            'rgba(102, 126, 234, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)',
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                };

                new Chart(canvas, {
                    type: 'doughnut',
                    data: projectsData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12,
                                        family: 'Cairo, Arial, sans-serif'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.2)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.raw / total) * 100).toFixed(1);
                                        return `${context.raw} Project (${percentage}%)`;
                                    }
                                }
                            },
                            // الطريقة الثانية: إظهار النسب داخل الرسم البياني
                            datalabels: {
                                color: 'white',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                formatter: (value, context) => {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    // إظهار النسبة فقط إذا كانت أكبر من 5%
                                    return percentage > 5 ? `${percentage}%` : '';
                                }
                            }
                        },
                        cutout: '60%',
                        animation: {
                            animateRotate: true,
                            duration: 1500
                        }
                    }
                });
            }

            // الطريقة الثالثة: إضافة plugin مخصص لإظهار النسب في المركز
            function initProjectsChartWithCenterText() {
                const ctx = document.getElementById('projectsChart');
                if (!ctx) return;

                let canvas;
                if (ctx.tagName.toLowerCase() === 'canvas') {
                    canvas = ctx;
                } else {
                    canvas = document.createElement('canvas');
                    canvas.style.height = '300px';
                    canvas.style.width = '100%';
                    ctx.appendChild(canvas);
                }

                const activeProjects = {{ $activeProjects }};
                const completedProjects = {{ $compeletedProjects }};
                const pendingProjects = {{ $pendingProjects }};
                const canceledProjects = {{ $canceledProjects }};
                const otherProjects =
                    {{ $totalProjects - $activeProjects - $compeletedProjects - $pendingProjects - $canceledProjects }};

                const dataValues = [activeProjects, completedProjects, pendingProjects, canceledProjects,
                    otherProjects
                ];
                const totalProjects = dataValues.reduce((a, b) => a + b, 0);

                const projectsData = {
                    labels: ['Active projects', 'Completed projects', 'Pending projects', 'Cancelled projects'],
                    datasets: [{
                        label: 'Number of projects',
                        data: dataValues,
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(220, 53, 69, 0.8)',
                            'rgba(108, 117, 125, 0.8)'
                        ],
                        borderColor: [
                            'rgba(102, 126, 234, 1)',
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)',
                            'rgba(108, 117, 125, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                };

                // Plugin مخصص لإظهار النص في المركز
                const centerTextPlugin = {
                    id: 'centerText',
                    beforeDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();

                        const centerX = chart.getDatasetMeta(0).data[0].x;
                        const centerY = chart.getDatasetMeta(0).data[0].y;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 16px Cairo, Arial, sans-serif';
                        ctx.fillStyle = '#666';

                        ctx.fillText('Total projects', centerX, centerY - 10);
                        ctx.font = 'bold 24px Cairo, Arial, sans-serif';
                        ctx.fillStyle = '#333';
                        ctx.fillText(totalProjects.toString(), centerX, centerY + 15);

                        ctx.restore();
                    }
                };

                new Chart(canvas, {
                    type: 'doughnut',
                    data: projectsData,
                    plugins: [centerTextPlugin],
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12,
                                        family: 'Cairo, Arial, sans-serif'
                                    },
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);

                                        return data.labels.map((label, index) => {
                                            const value = data.datasets[0].data[index];
                                            const percentage = total > 0 ? ((value / total) *
                                                100).toFixed(1) : 0;

                                            return {
                                                text: `${label}: ${value} (${percentage}%)`,
                                                fillStyle: data.datasets[0].backgroundColor[
                                                    index],
                                                strokeStyle: data.datasets[0].borderColor[
                                                    index],
                                                lineWidth: data.datasets[0].borderWidth,
                                                pointStyle: 'circle',
                                                hidden: false,
                                                index: index
                                            };
                                        });
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.2)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.raw / total) * 100).toFixed(1);
                                        return `${context.raw} Project (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '60%',
                        animation: {
                            animateRotate: true,
                            duration: 1500
                        }
                    }
                });
            }

            // رسم بياني للمعدات المستخدمة
            function initEquipmentChart() {
                const equipmentChartElement = document.getElementById('equipmentChart');
                if (!equipmentChartElement) return;

                let canvas = document.createElement('canvas');
                canvas.style.height = '250px';
                equipmentChartElement.appendChild(canvas);

                const equipmentData = {
                    labels: [
                        @foreach ($equipmentUsage as $equipment)
                            '{{ $equipment->equipment->name }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'كمية الاستخدام',
                        data: [
                            @foreach ($equipmentUsage as $equipment)
                                {{ $equipment->total_used }},
                            @endforeach
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                };

                new Chart(canvas, {
                    type: 'bar',
                    data: equipmentData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // رسم بياني للمدفوعات الشهرية
            function initPaymentsChart() {
                const paymentsChartElement = document.getElementById('paymentsChart');
                if (!paymentsChartElement) return;

                let canvas = document.createElement('canvas');
                canvas.style.height = '300px';
                paymentsChartElement.appendChild(canvas);

                // بيانات وهمية للمدفوعات (يمكن تمريرها من المتحكم)
                const months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
                const paymentsData = [150000, 200000, 175000, 300000, 250000, 400000];
                const costsData = [180000, 220000, 190000, 320000, 280000, 450000];

                const data = {
                    labels: months,
                    datasets: [{
                            label: 'المبلغ المدفوع',
                            data: paymentsData,
                            borderColor: 'rgba(40, 167, 69, 1)',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'التكلفة الإجمالية',
                            data: costsData,
                            borderColor: 'rgba(220, 53, 69, 1)',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                };

                new Chart(canvas, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Cairo, Arial, sans-serif'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'الأشهر'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'المبلغ (بالدولار)'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // تشغيل جميع الرسوم البيانية
            initProjectsChart();
            initEquipmentChart();
            initPaymentsChart();

            // تحديث البيانات كل 5 دقائق (اختياري)
            setInterval(function() {
                // يمكن إضافة كود AJAX لتحديث البيانات هنا
                console.log('تحديث البيانات...');
            }, 300000);
        });

        // دالة لتحديث الرسم البياني عبر AJAX
        function updateDashboardCharts() {
            $.ajax({
                url: '{{ route('charts.data') }}', // تأكد من إنشاء هذا المسار
                method: 'GET',
                success: function(response) {
                    // تحديث البيانات هنا
                    console.log('تم تحديث البيانات:', response);
                },
                error: function(xhr, status, error) {
                    console.error('خطأ في تحديث البيانات:', error);
                }
            });
        }
    </script>

    <!-- إضافة عناصر إضافية للرسوم البيانية الأخرى إذا لزم الأمر -->
    <script>
        // رسم بياني لتوزيع المشاريع حسب النوع
        $(document).ready(function() {
            const projectTypeChart = document.getElementById('projectTypeChart');
            if (projectTypeChart) {
                let canvas = document.createElement('canvas');
                projectTypeChart.appendChild(canvas);

                const typeData = {
                    labels: [
                        @foreach ($projectsByType as $type => $count)
                            '{{ $type == 'government' ? 'حكومي' : ($type == 'commercial' ? 'تجاري' : 'سكني') }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach ($projectsByType as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#dc3545',
                            '#007bff',
                            '#28a745'
                        ]
                    }]
                };

                new Chart(canvas, {
                    type: 'pie',
                    data: typeData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
