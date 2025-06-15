@extends('admin.layouts.app')

@section('title', 'Admin Panel - Course Reports')

@section('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #4361ee;
        color: white;
        border-radius: 10px 10px 0 0 !important;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-resolved {
        background-color: #d4edda;
        color: #155724;
    }
    .status-in-progress {
        background-color: #cce5ff;
        color: #004085;
    }
    .report-reason {
        font-weight: 600;
        color: #6c757d;
    }
    .action-btns .btn {
        margin-right: 5px;
    }
    .search-box {
        max-width: 300px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Course Reports Management</h2>
    <div class="search-box">
        <form action="{{ route('admin.reports') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search reports..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Reports</h5>
        <div class="filter-buttons">
            <a href="{{ route('admin.reports', ['status' => 'all']) }}" class="btn btn-sm btn-light {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="btn btn-sm btn-light {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.reports', ['status' => 'in-progress']) }}" class="btn btn-sm btn-light {{ request('status') == 'in-progress' ? 'active' : '' }}">In Progress</a>
            <a href="{{ route('admin.reports', ['status' => 'resolved']) }}" class="btn btn-sm btn-light {{ request('status') == 'resolved' ? 'active' : '' }}">Resolved</a>
        </div>
    </div>
    <div class="card-body">
        @if($reports->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-flag fa-3x text-muted mb-3"></i>
                <h5>No reports found</h5>
                <p class="text-muted">There are currently no course reports to display.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course</th>
                            <th>Reporter</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Reported At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>
                                    @if($report->course)
                                        <a href="{{ route('course.show', $report->course_id) }}" target="_blank">
                                            {{ $report->course->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">[Course Deleted]</span>
                                    @endif
                                </td>
                                @if($report->reporter)
                                    <td>{{ $report->reporter->username }}</td>
                                @else
                                    <td class="text-muted">[User Deleted]</td>
                                @endif
                                <td>
                                    <span class="report-reason">
                                        {{ ucfirst($report->reason) }}
                                    </span>
                                </td>
                                <td>
                                    @if($report->resolved)
                                        <span class="status-badge status-resolved">Resolved</span>
                                    @elseif($report->in_progress)
                                        <span class="status-badge status-in-progress">In Progress</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->diffForHumans() }}</td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-primary view-report" data-bs-toggle="modal" data-bs-target="#reportModal" data-id="{{ $report->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(!$report->resolved)
                                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="in-progress">
                                            <button type="submit" class="btn btn-sm btn-warning" title="Mark as In Progress">
                                                <i class="fas fa-spinner"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="btn btn-sm btn-success" title="Mark as Resolved">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this report?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Report Details Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reportDetails">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Load report details in modal
    $('.view-report').click(function() {
        const reportId = $(this).data('id');
        $('#reportDetails').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading report details...</p></div>');
        
        $.get(`/admin/reports/${reportId}/details`, function(data) {
            $('#reportDetails').html(`
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Course:</h6>
                        <p><a href="/course/${data.course_id}" target="_blank">${data.course_title}</a></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Reporter:</h6>
                        <p>${data.reporter_username}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Reason:</h6>
                        <p>${data.reason}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Status:</h6>
                        <p>
                            ${data.resolved ? 
                                '<span class="status-badge status-resolved">Resolved</span>' : 
                                (data.in_progress ? 
                                    '<span class="status-badge status-in-progress">In Progress</span>' : 
                                    '<span class="status-badge status-pending">Pending</span>')
                            }
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Reported At:</h6>
                        <p>${data.created_at}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Last Updated:</h6>
                        <p>${data.updated_at}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Details:</h6>
                    <div class="p-3 bg-light rounded">${data.details}</div>
                </div>
            `);
        }).fail(function() {
            $('#reportDetails').html('<div class="alert alert-danger">Failed to load report details. Please try again.</div>');
        });
    });
</script>
@endsection