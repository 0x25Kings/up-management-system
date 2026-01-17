<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Leaders - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F9FAFB; }
        
        .card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 18px; font-weight: 700; color: #1F2937; }
        .card-body { padding: 24px; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 12px 16px; background: #F9FAFB; color: #6B7280; font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .data-table td { padding: 16px; border-bottom: 1px solid #E5E7EB; }
        .data-table tr:hover { background: #F9FAFB; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-info { background: #EFF6FF; color: #2563EB; }

        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; }
        .btn-primary:hover { box-shadow: 0 4px 15px rgba(123, 29, 58, 0.4); }
        .btn-danger { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: white; }
        .btn-secondary { background: #F3F4F6; color: #4B5563; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .alert { padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .alert-success { background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; }
        .alert-danger { background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; }

        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 16px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid #E5E7EB; display: flex; gap: 12px; justify-content: flex-end; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px; }
        .form-input { width: 100%; padding: 12px 16px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; }
        .form-input:focus { outline: none; border-color: #7B1D3A; box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1); }
    </style>
</head>
<body>
    <div style="padding: 32px; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; color: #1F2937;">Team Leaders</h1>
                <p style="color: #6B7280;">Manage team leaders for each school</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <button onclick="openModal('createModal')" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Team Leader
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Team Leaders ({{ $teamLeaders->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Assigned School</th>
                            <th>Interns</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamLeaders as $leader)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1e3a5f 0%, #152a45 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            {{ strtoupper(substr($leader->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $leader->name }}</div>
                                            <div style="font-size: 12px; color: #6B7280;">Team Leader</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $leader->email }}</td>
                                <td>
                                    @if($leader->school)
                                        <span class="badge badge-info">{{ $leader->school->name }}</span>
                                    @else
                                        <span style="color: #9CA3AF;">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leader->school)
                                        {{ $leader->school->approvedInterns()->count() }}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td>{{ $leader->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <button onclick="editTeamLeader({{ json_encode($leader) }})" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.team-leaders.destroy', $leader) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this team leader?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px; color: #6B7280;">
                                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px; display: block; color: #D1D5DB;"></i>
                                    <h4 style="font-size: 18px; color: #4B5563; margin-bottom: 8px;">No Team Leaders Yet</h4>
                                    <p>Create your first team leader to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-weight: 700; color: #1F2937;">Add Team Leader</h3>
                <button onclick="closeModal('createModal')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #6B7280;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.team-leaders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-input" required placeholder="Enter full name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" required placeholder="Enter email address">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" required minlength="8" placeholder="Minimum 8 characters">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign to School *</label>
                        <select name="school_id" class="form-input" required>
                            <option value="">Select a school</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('createModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Team Leader</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-weight: 700; color: #1F2937;">Edit Team Leader</h3>
                <button onclick="closeModal('editModal')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #6B7280;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" id="editName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="editEmail" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-input" minlength="8" placeholder="Leave blank to keep current">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign to School *</label>
                        <select name="school_id" id="editSchoolId" class="form-input" required>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Team Leader</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function editTeamLeader(leader) {
            document.getElementById('editForm').action = '/admin/team-leaders/' + leader.id;
            document.getElementById('editName').value = leader.name;
            document.getElementById('editEmail').value = leader.email;
            document.getElementById('editSchoolId').value = leader.school_id || '';
            openModal('editModal');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
