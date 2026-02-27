
        // Mobile Sidebar Toggle Functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            menuBtn.classList.toggle('active');

            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');

            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            menuBtn.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Auto-close sidebar when clicking menu items on mobile
        document.querySelectorAll('.sidebar .menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(closeMobileSidebar, 150);
                }
            });
        });

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });

        // Store data for modals
        const tasksData = [{"id":1,"intern_id":2,"group_id":null,"title":"Arrange the files on the office","description":null,"requirements":null,"checklist":[{"label":"Planning","done":false},{"label":"Design","done":false},{"label":"Implementation","done":false}],"priority":"Medium","status":"Completed","progress":100,"notes":null,"documents":null,"started_at":"2026-02-24 13:55:21","due_date":"2026-02-28T00:00:00.000000Z","completed_date":"2026-02-24T00:00:00.000000Z","assigned_by":4,"created_at":"2026-02-23T06:17:51.000000Z","updated_at":"2026-02-24T06:11:42.000000Z","intern":{"id":2,"school_id":1,"reference_code":"INT-2026-SGYXJM","profile_picture":null,"name":"Juliana M. Laurena","age":22,"gender":"Female","email":"laurenajuliana04@gmail.com","phone":"0912345678","school":"The College of Maasin","course":"BS Information Technology","year_level":"4th Year","start_date":"2026-02-23T00:00:00.000000Z","end_date":null,"required_hours":50,"completed_hours":0,"status":"Active","approval_status":"approved","rejection_reason":null,"approved_at":"2026-02-23T05:51:11.000000Z","approved_by":2,"created_at":"2026-02-23T05:50:00.000000Z","updated_at":"2026-02-24T06:11:09.000000Z"}},{"id":2,"intern_id":1,"group_id":null,"title":"UP Start up Website","description":"zwasdcfvtgybunjim,kolp;","requirements":null,"checklist":[{"label":"Planning","done":false},{"label":"design","done":false}],"priority":"Medium","status":"In Progress","progress":100,"notes":null,"documents":null,"started_at":"2026-02-24 13:55:45","due_date":"2026-02-28T00:00:00.000000Z","completed_date":"2026-02-24T00:00:00.000000Z","assigned_by":2,"created_at":"2026-02-23T08:34:03.000000Z","updated_at":"2026-02-24T05:55:45.000000Z","intern":{"id":1,"school_id":1,"reference_code":"INT-2026-1N07ND","profile_picture":null,"name":"Crissa Mae Tagra","age":22,"gender":"Female","email":"tagracrissamae@gmail.com","phone":"0912345678","school":"The College of Maasin","course":"BS Information Technology","year_level":"4th Year","start_date":"2026-02-23T00:00:00.000000Z","end_date":null,"required_hours":481,"completed_hours":0,"status":"Active","approval_status":"approved","rejection_reason":null,"approved_at":"2026-02-23T05:46:11.000000Z","approved_by":2,"created_at":"2026-02-23T05:43:06.000000Z","updated_at":"2026-02-23T06:04:16.000000Z"}}];
        const reportsData = [{"id":2,"team_leader_id":4,"school_id":1,"title":"Weekly Progress report","report_type":"weekly","summary":"Provide summary of report","accomplishments":null,"challenges":null,"recommendations":null,"period_start":"2026-02-23T00:00:00.000000Z","period_end":"2026-02-28T00:00:00.000000Z","intern_highlights":null,"task_statistics":{"total":1,"completed":0,"in_progress":0,"pending":0,"completion_rate":0},"attachments":null,"status":"submitted","reviewed_by":null,"reviewed_at":null,"admin_feedback":null,"created_at":"2026-02-23T06:21:01.000000Z","updated_at":"2026-02-23T06:21:01.000000Z"},{"id":1,"team_leader_id":4,"school_id":1,"title":"Weekly Progress report","report_type":"weekly","summary":"Provide summary of report","accomplishments":null,"challenges":null,"recommendations":null,"period_start":"2026-02-23T00:00:00.000000Z","period_end":"2026-02-28T00:00:00.000000Z","intern_highlights":null,"task_statistics":{"total":1,"completed":0,"in_progress":0,"pending":0,"completion_rate":0},"attachments":null,"status":"submitted","reviewed_by":null,"reviewed_at":null,"admin_feedback":null,"created_at":"2026-02-23T06:20:59.000000Z","updated_at":"2026-02-23T06:20:59.000000Z"}];
        const internsData = [{"id":1,"school_id":1,"reference_code":"INT-2026-1N07ND","profile_picture":null,"name":"Crissa Mae Tagra","age":22,"gender":"Female","email":"tagracrissamae@gmail.com","phone":"0912345678","school":"The College of Maasin","course":"BS Information Technology","year_level":"4th Year","start_date":"2026-02-23T00:00:00.000000Z","end_date":null,"required_hours":481,"completed_hours":0,"status":"Active","approval_status":"approved","rejection_reason":null,"approved_at":"2026-02-23T05:46:11.000000Z","approved_by":2,"created_at":"2026-02-23T05:43:06.000000Z","updated_at":"2026-02-23T06:04:16.000000Z","school_relation":{"id":1,"name":"The College of Maasin","required_hours":481,"max_interns":20,"contact_person":"Kingsley Laran","contact_email":"larankingsley@gmail.com","contact_phone":"0913245678","status":"Active","notes":null,"interns_start_date":null,"is_finished":false,"finished_at":null,"created_at":"2026-02-23T05:36:58.000000Z","updated_at":"2026-02-23T05:36:58.000000Z"}},{"id":2,"school_id":1,"reference_code":"INT-2026-SGYXJM","profile_picture":null,"name":"Juliana M. Laurena","age":22,"gender":"Female","email":"laurenajuliana04@gmail.com","phone":"0912345678","school":"The College of Maasin","course":"BS Information Technology","year_level":"4th Year","start_date":"2026-02-23T00:00:00.000000Z","end_date":null,"required_hours":50,"completed_hours":0,"status":"Active","approval_status":"approved","rejection_reason":null,"approved_at":"2026-02-23T05:51:11.000000Z","approved_by":2,"created_at":"2026-02-23T05:50:00.000000Z","updated_at":"2026-02-24T06:11:09.000000Z","school_relation":{"id":1,"name":"The College of Maasin","required_hours":481,"max_interns":20,"contact_person":"Kingsley Laran","contact_email":"larankingsley@gmail.com","contact_phone":"0913245678","status":"Active","notes":null,"interns_start_date":null,"is_finished":false,"finished_at":null,"created_at":"2026-02-23T05:36:58.000000Z","updated_at":"2026-02-23T05:36:58.000000Z"}}];


        // Page navigation
        const menuItems = document.querySelectorAll('.menu-item[data-page]');
        const pageContents = document.querySelectorAll('.page-content');
        const pageTitle = document.getElementById('pageTitle');
        const pageTitles = {
            'dashboard': 'Dashboard',
            'interns': 'My Interns',
            'tasks': 'Task Management',
            'attendance': 'Team Attendance',
            'reports': 'My Reports',
            'scheduler': 'Scheduler',
            'research-tracking': 'Research Tracking',
            'incubatee-tracker': 'Incubatee Tracker',
            'issues-management': 'Issues & Complaints',
            'digital-records': 'Digital Records',
            'profile': 'My Profile'
        };

        // Profile dropdown toggle
        const tlProfileBtn = document.getElementById('tlProfileBtn');
        const tlProfileDropdown = document.getElementById('tlProfileDropdown');

        if (tlProfileBtn && tlProfileDropdown) {
            tlProfileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                tlProfileDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!tlProfileDropdown.contains(event.target)) {
                    tlProfileDropdown.classList.remove('active');
                }
            });
        }

        // Navigate to profile page
        function navigateToProfile(e) {
            e.preventDefault();
            tlProfileDropdown.classList.remove('active');
            menuItems.forEach(mi => mi.classList.remove('active'));
            pageContents.forEach(pc => pc.classList.remove('active'));
            document.getElementById('profile').classList.add('active');
            pageTitle.textContent = 'My Profile';
        }

        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');
                pageContents.forEach(pc => pc.classList.remove('active'));
                document.getElementById(page).classList.add('active');
                pageTitle.textContent = pageTitles[page] || 'Dashboard';

                if (page === 'digital-records') {
                    tlDrInit();
                }
            });
        });

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            // Handle both modal patterns: class-based (.active) and style-based (display)
            modal.classList.remove('active');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close modal on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal(overlay.id);
            });
        });

        // Toast notification
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.toggle('error', isError);
            toast.classList.add('active');
            setTimeout(() => toast.classList.remove('active'), 3000);
        }


        // ========== RECENT TASKS PAGINATION ==========
        let recentTaskCurrentPage = 1;
        const recentTaskPerPage = 3;
        const recentTaskItems = document.querySelectorAll('.recent-task-item');
        const recentTaskTotalPages = Math.ceil(recentTaskItems.length / recentTaskPerPage);

        function changeRecentTaskPage(direction) {
            recentTaskCurrentPage += direction;
            if (recentTaskCurrentPage < 1) recentTaskCurrentPage = 1;
            if (recentTaskCurrentPage > recentTaskTotalPages) recentTaskCurrentPage = recentTaskTotalPages;

            const start = (recentTaskCurrentPage - 1) * recentTaskPerPage;
            const end = start + recentTaskPerPage;

            recentTaskItems.forEach((item, index) => {
                item.style.display = (index >= start && index < end) ? '' : 'none';
            });

            const pageInfo = document.getElementById('recentTaskPageInfo');
            const prevBtn = document.getElementById('recentTaskPrev');
            const nextBtn = document.getElementById('recentTaskNext');

            if (pageInfo) pageInfo.textContent = `Page ${recentTaskCurrentPage} of ${recentTaskTotalPages}`;
            if (prevBtn) prevBtn.disabled = recentTaskCurrentPage === 1;
            if (nextBtn) nextBtn.disabled = recentTaskCurrentPage === recentTaskTotalPages;
        }

        // ========== INTERNS NEEDING ATTENTION PAGINATION ==========
        let attentionInternCurrentPage = 1;
        const attentionInternPerPage = 3;
        const attentionInternItems = document.querySelectorAll('.attention-intern-item');
        const attentionInternTotalPages = Math.ceil(attentionInternItems.length / attentionInternPerPage);

        function changeAttentionInternPage(direction) {
            attentionInternCurrentPage += direction;
            if (attentionInternCurrentPage < 1) attentionInternCurrentPage = 1;
            if (attentionInternCurrentPage > attentionInternTotalPages) attentionInternCurrentPage = attentionInternTotalPages;

            const start = (attentionInternCurrentPage - 1) * attentionInternPerPage;
            const end = start + attentionInternPerPage;

            attentionInternItems.forEach((item, index) => {
                item.style.display = (index >= start && index < end) ? '' : 'none';
            });

            const pageInfo = document.getElementById('attentionInternPageInfo');
            const prevBtn = document.getElementById('attentionInternPrev');
            const nextBtn = document.getElementById('attentionInternNext');

            if (pageInfo) pageInfo.textContent = `Page ${attentionInternCurrentPage} of ${attentionInternTotalPages}`;
            if (prevBtn) prevBtn.disabled = attentionInternCurrentPage === 1;
            if (nextBtn) nextBtn.disabled = attentionInternCurrentPage === attentionInternTotalPages;
        }

        // ========== TASK FUNCTIONS ==========
        function openCreateTaskModal() {
            document.getElementById('createTaskForm').reset();
            openModal('createTaskModal');
        }

        function editTask(taskId) {
            const task = tasksData.find(t => t.id === taskId);
            if (!task) return showToast('Task not found', true);

            document.getElementById('editTaskForm').action = `/team-leader/tasks/${taskId}`;
            document.getElementById('editTaskIntern').value = task.intern_id;
            document.getElementById('editTaskTitle').value = task.title;
            document.getElementById('editTaskDescription').value = task.description || '';
            document.getElementById('editTaskRequirements').value = task.requirements || '';
            document.getElementById('editTaskPriority').value = task.priority;
            document.getElementById('editTaskStatus').value = task.status;
            document.getElementById('editTaskDueDate').value = task.due_date ? task.due_date.split('T')[0] : '';
            document.getElementById('editTaskProgress').value = task.progress || 0;
            document.getElementById('editTaskNotes').value = task.notes || '';

            openModal('editTaskModal');
        }

        function archiveTask(taskId) {
            document.getElementById('archiveForm').action = `/team-leader/tasks/${taskId}`;
            document.getElementById('archiveConfirmMessage').textContent = 'This task will be archived.';
            openModal('archiveConfirmModal');
        }

        // ========== INTERN FUNCTIONS ==========
        function viewIntern(internId) {
            openModal('viewInternModal');

            const intern = internsData.find(i => i.id === internId);
            if (!intern) {
                document.getElementById('viewInternContent').innerHTML = '<p style="text-align: center; color: #DC2626;">Intern not found</p>';
                return;
            }

            const progress = intern.required_hours > 0
                ? Math.round((intern.completed_hours / intern.required_hours) * 100)
                : 0;

            const internTasks = tasksData.filter(t => t.intern_id === internId);

            document.getElementById('viewInternContent').innerHTML = `
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.08);">
                    ${intern.profile_picture_url
                        ? `<img src="${intern.profile_picture_url}" alt="${intern.name}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--maroon);">`
                        : `<div class="list-item-avatar" style="width: 80px; height: 80px; font-size: 28px; margin: 0;">${intern.name.charAt(0).toUpperCase()}</div>`
                    }
                    <div>
                        <h2 style="font-size: 22px; font-weight: 700; color: #1F2937; margin-bottom: 4px;">${intern.name}</h2>
                        <p style="color: #6B7280;">${intern.email}</p>
                        <span class="badge badge-${intern.status === 'Active' ? 'success' : (intern.status === 'Completed' ? 'info' : 'warning')}">${intern.status}</span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Course</label>
                        <p>${intern.course || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>${intern.phone || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Start Date</label>
                        <p>${intern.start_date || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>End Date</label>
                        <p>${intern.end_date || 'N/A'}</p>
                    </div>
                </div>

                <div style="background: var(--off-white); padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 12px; color: var(--maroon);">Hours Progress</h4>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600;">${Number(intern.completed_hours || 0).toFixed(1)} / ${intern.required_hours} hours</span>
                        <span style="font-weight: 700; color: var(--maroon);">${progress}%</span>
                    </div>
                    <div class="progress-container" style="height: 12px;">
                        <div class="progress-bar ${progress < 30 ? 'red' : (progress < 70 ? 'gold' : 'green')}" style="width: ${progress}%"></div>
                    </div>
                </div>

                <h4 style="margin-bottom: 12px; color: var(--maroon);">Assigned Tasks (${internTasks.length})</h4>
                ${internTasks.length > 0 ? `
                    <div style="max-height: 200px; overflow-y: auto;">
                        ${internTasks.map(task => `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--off-white); border-radius: 8px; margin-bottom: 8px;">
                                <div>
                                    <div style="font-weight: 600;">${task.title}</div>
                                    <div style="font-size: 12px; color: #6B7280;">Due: ${task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No date'}</div>
                                </div>
                                ${(() => {
                                    const isPending = task.status === 'Completed' && !task.completed_date;
                                    const label = isPending ? 'Pending Admin Approval' : task.status;
                                    const badge = isPending ? 'info' : (task.status === 'Completed' ? 'success' : (task.status === 'In Progress' ? 'info' : 'warning'));
                                    return '<span class="badge badge-' + badge + '">' + label + '</span>';
                                })()}
                            </div>
                        `).join('')}
                    </div>
                ` : '<p style="color: #6B7280; text-align: center; padding: 20px;">No tasks assigned yet</p>'}
            `;
        }

        function editIntern(internId) {
            const intern = internsData.find(i => i.id === internId);
            if (!intern) {
                showToast('error', 'Error', 'Intern not found');
                return;
            }

            document.getElementById('editInternId').value = intern.id;
            document.getElementById('editInternName').value = intern.name || '';
            document.getElementById('editInternEmail').value = intern.email || '';
            document.getElementById('editInternPhone').value = intern.phone || '';
            document.getElementById('editInternCourse').value = intern.course || '';
            document.getElementById('editInternYearLevel').value = intern.year_level || '';
            document.getElementById('editInternRequiredHours').value = intern.required_hours || '';
            document.getElementById('editInternStartDate').value = intern.start_date || '';
            document.getElementById('editInternEndDate').value = intern.end_date || '';
            document.getElementById('editInternStatus').value = intern.status || 'Active';

            openModal('editInternModal');
        }

        async function submitEditIntern(event) {
            event.preventDefault();
            const internId = document.getElementById('editInternId').value;
            const submitBtn = document.querySelector('#editInternForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                const response = await fetch(`/admin/interns/${internId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: document.getElementById('editInternName').value,
                        email: document.getElementById('editInternEmail').value,
                        phone: document.getElementById('editInternPhone').value,
                        course: document.getElementById('editInternCourse').value,
                        year_level: document.getElementById('editInternYearLevel').value,
                        required_hours: document.getElementById('editInternRequiredHours').value || null,
                        start_date: document.getElementById('editInternStartDate').value || null,
                        end_date: document.getElementById('editInternEndDate').value || null,
                        status: document.getElementById('editInternStatus').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Success', 'Intern details updated successfully');
                    closeModal('editInternModal');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to update intern');
                }
            } catch (error) {
                console.error('Error updating intern:', error);
                showToast('error', 'Error', error.message || 'Failed to update intern');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        // ========== REPORT FUNCTIONS ==========
        function openCreateReportModal() {
            document.getElementById('createReportForm').reset();
            openModal('createReportModal');
        }

        function viewReport(reportId) {
            openModal('viewReportModal');
            const report = reportsData.find(r => r.id === reportId);

            if (!report) {
                document.getElementById('viewReportContent').innerHTML = '<p style="text-align: center; color: #DC2626;">Report not found</p>';
                return;
            }

            document.getElementById('viewReportContent').innerHTML = `
                <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid rgba(0,0,0,0.08);">
                    <h2 style="font-size: 22px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">${report.title}</h2>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <span class="badge badge-info">${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)}</span>
                        <span class="badge badge-${report.status === 'reviewed' ? 'success' : (report.status === 'submitted' ? 'info' : 'warning')}">${report.status.charAt(0).toUpperCase() + report.status.slice(1)}</span>
                    </div>
                </div>

                <div class="info-grid" style="margin-bottom: 24px;">
                    <div class="info-item">
                        <label>Period Start</label>
                        <p>${report.period_start ? new Date(report.period_start).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Period End</label>
                        <p>${report.period_end ? new Date(report.period_end).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Created</label>
                        <p>${new Date(report.created_at).toLocaleDateString()}</p>
                    </div>
                    <div class="info-item">
                        <label>Last Updated</label>
                        <p>${new Date(report.updated_at).toLocaleDateString()}</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--maroon); margin-bottom: 8px;">Summary</h4>
                    <p style="background: var(--off-white); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.summary || 'No summary provided'}</p>
                </div>

                ${report.accomplishments ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--forest-green); margin-bottom: 8px;">Accomplishments</h4>
                    <p style="background: rgba(34, 139, 34, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.accomplishments}</p>
                </div>
                ` : ''}

                ${report.challenges ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #F59E0B; margin-bottom: 8px;">Challenges</h4>
                    <p style="background: rgba(245, 158, 11, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.challenges}</p>
                </div>
                ` : ''}

                ${report.recommendations ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--maroon); margin-bottom: 8px;">Recommendations</h4>
                    <p style="background: rgba(123, 17, 19, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.recommendations}</p>
                </div>
                ` : ''}

                ${report.admin_feedback ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--forest-green); margin-bottom: 8px;">Admin Feedback</h4>
                    <p style="background: rgba(34, 139, 34, 0.1); padding: 16px; border-radius: 10px; border-left: 4px solid var(--forest-green); white-space: pre-wrap;">${report.admin_feedback}</p>
                </div>
                ` : ''}
            `;
        }

        function editReport(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return showToast('Report not found', true);

            document.getElementById('editReportForm').action = `/team-leader/reports/${reportId}`;
            document.getElementById('editReportTitle').value = report.title;
            document.getElementById('editReportType').value = report.report_type;
            document.getElementById('editReportPeriodStart').value = report.period_start ? report.period_start.split('T')[0] : '';
            document.getElementById('editReportPeriodEnd').value = report.period_end ? report.period_end.split('T')[0] : '';
            document.getElementById('editReportSummary').value = report.summary || '';
            document.getElementById('editReportAccomplishments').value = report.accomplishments || '';
            document.getElementById('editReportChallenges').value = report.challenges || '';
            document.getElementById('editReportRecommendations').value = report.recommendations || '';
            document.getElementById('editReportStatus').value = report.status;

            openModal('editReportModal');
        }

        function archiveReport(reportId) {
            document.getElementById('archiveForm').action = `/team-leader/reports/${reportId}`;
            document.getElementById('archiveConfirmMessage').textContent = 'This report will be archived.';
            openModal('archiveConfirmModal');
        }

        // ========== AJAX FORM SUBMISSIONS ==========
        function submitCreateReport(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_token', 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb');

            fetch('http://localhost/team-leader/reports', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    showToast('Report created successfully!');
                    closeModal('createReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else if (response.ok) {
                    showToast('Report created successfully!');
                    closeModal('createReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    return response.text().then(text => {
                        throw new Error('Failed to create report');
                    });
                }
            })
            .catch(err => {
                showToast('Error creating report', true);
            });
        }

        function submitEditReport(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_token', 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb');
            formData.append('_method', 'PUT');
            const reportId = document.getElementById('editReportId').value;

            fetch(`/team-leader/reports/${reportId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected || response.ok) {
                    showToast('Report updated successfully!');
                    closeModal('editReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error('Failed to update report');
                }
            })
            .catch(err => {
                showToast('Error updating report', true);
            });
        }

        // ==================== AUTO-REFRESH FUNCTIONALITY ====================
        let autoRefreshInterval = null;
        const REFRESH_INTERVAL = 30000; // 30 seconds
        let isRefreshing = false;

        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
        });

        function startAutoRefresh() {
            // Clear any existing interval
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            // Set up new interval
            autoRefreshInterval = setInterval(function() {
                refreshData();
            }, REFRESH_INTERVAL);

            console.log('Auto-refresh started (every 30 seconds)');
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        function manualRefresh() {
            refreshData(true);
        }

        // Profile picture upload function
        async function uploadProfilePicture(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, or GIF)');
                return;
            }

            const formData = new FormData();
            formData.append('profile_picture', file);
            formData.append('_token', 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb');

            try {
                const response = await fetch('http://localhost/team-leader/profile/upload-picture', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update the profile picture on the page
                    const container = document.getElementById('profilePictureContainer');
                    const existingImg = document.getElementById('profilePictureImg');
                    const existingInitial = document.getElementById('profilePictureInitial');

                    if (existingImg) {
                        existingImg.src = data.image_url;
                    } else if (existingInitial) {
                        // Replace initial with image
                        const img = document.createElement('img');
                        img.id = 'profilePictureImg';
                        img.src = data.image_url;
                        img.alt = 'Profile';
                        img.style.cssText = 'width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 8px 24px rgba(123, 29, 58, 0.3);';
                        existingInitial.replaceWith(img);
                    }

                    // Also update sidebar avatar if it exists
                    const sidebarAvatar = document.querySelector('.sidebar-user-avatar img');
                    if (sidebarAvatar) {
                        sidebarAvatar.src = data.image_url;
                    }

                    alert('Profile picture updated successfully! This will also be synced with your intern account if you have one.');
                } else {
                    alert(data.message || 'Failed to upload profile picture');
                }
            } catch (error) {
                console.error('Error uploading profile picture:', error);
                alert('An error occurred while uploading the profile picture');
            }

            // Clear the input
            input.value = '';
        }

        async function refreshData(isManual = false) {
            if (isRefreshing) return;
            isRefreshing = true;

            const refreshIcon = document.getElementById('refreshIcon');
            refreshIcon.classList.add('fa-spin');

            try {
                // Fetch updated tasks
                const tasksResponse = await fetch('/team-leader/api/tasks', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (tasksResponse.ok) {
                    const tasksData = await tasksResponse.json();
                    if (tasksData.success) {
                        updateTasksTable(tasksData.tasks);
                        document.getElementById('lastUpdatedTime').textContent = tasksData.updated_at;
                    }
                }

                // Fetch updated stats
                const statsResponse = await fetch('/team-leader/api/stats', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (statsResponse.ok) {
                    const statsData = await statsResponse.json();
                    if (statsData.success) {
                        updateDashboardStats(statsData.stats);
                    }
                }

                // Fetch updated interns
                const internsResponse = await fetch('/team-leader/api/interns', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (internsResponse.ok) {
                    const internsData = await internsResponse.json();
                    if (internsData.success) {
                        updateInternsTable(internsData.interns);
                    }
                }

                // Fetch updated attendance
                const attendanceResponse = await fetch('/team-leader/api/attendance', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (attendanceResponse.ok) {
                    const attendanceData = await attendanceResponse.json();
                    if (attendanceData.success) {
                        updateAttendanceTable(attendanceData.attendances, attendanceData.stats);
                    }
                }

                if (isManual) {
                    showToast('Data refreshed successfully!');
                }

            } catch (error) {
                console.error('Error refreshing data:', error);
                if (isManual) {
                    showToast('Failed to refresh data', true);
                }
            } finally {
                isRefreshing = false;
                refreshIcon.classList.remove('fa-spin');
            }
        }

        function updateTasksTable(tasks) {
            const tbody = document.querySelector('#tasks .data-table tbody');
            if (!tbody || tasks.length === 0) return;

            // Update existing rows or rebuild table
            let html = '';
            tasks.forEach(task => {
                const priorityBg = task.priority === 'High' ? '#DC2626' :
                                   (task.priority === 'Medium' ? '#F59E0B' : '#228B22');
                const priorityColor = 'white';

                const statusClass = task.status === 'Completed' ? 'success' :
                                    (task.status === 'In Progress' ? 'info' :
                                    (task.status === 'On Hold' ? 'danger' : 'warning'));

                html += `
                    <tr data-task-id="${task.id}">
                        <td>
                            <div style="font-weight: 600; color: var(--maroon);">${escapeHtml(task.title)}</div>
                            ${task.is_overdue ? '<span style="font-size: 11px; color: #DC2626; font-weight: 600;"><i class="fas fa-exclamation-circle"></i> Overdue</span>' : ''}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 26px; height: 26px; border-radius: 8px; background: var(--maroon); color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600;">
                                    ${task.intern_initial}
                                </div>
                                <span style="font-size: 13px;">${escapeHtml(task.intern_name)}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: ${priorityBg}; color: ${priorityColor};">
                                ${task.priority}
                            </span>
                        </td>
                        <td>
                            <span style="${task.is_overdue ? 'color: #DC2626; font-weight: 600;' : ''}">
                                ${task.due_date}
                            </span>
                        </td>
                        <td style="min-width: 100px;">
                            <div class="progress-container" style="margin-bottom: 4px;">
                                <div class="progress-bar green" style="width: ${task.progress}%"></div>
                            </div>
                            <span style="font-size: 11px; color: #6B7280;">${task.progress}%</span>
                        </td>
                        <td>
                            <span class="badge badge-${statusClass}">
                                ${task.status}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-secondary" onclick="editTask(${task.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            // Update task stats cards
            const pendingCount = tasks.filter(t => t.status === 'Not Started').length;
            const inProgressCount = tasks.filter(t => t.status === 'In Progress').length;
            const completedCount = tasks.filter(t => t.status === 'Completed').length;
            const overdueCount = tasks.filter(t => t.is_overdue).length;

            // Update stat cards on tasks page
            const taskStatCards = document.querySelectorAll('#tasks .stat-value');
            if (taskStatCards.length >= 4) {
                taskStatCards[0].textContent = pendingCount;
                taskStatCards[1].textContent = inProgressCount;
                taskStatCards[2].textContent = completedCount;
                taskStatCards[3].textContent = overdueCount;
            }
        }

        function updateDashboardStats(stats) {
            // Update dashboard stat cards - these have specific IDs or we target by position
            // Dashboard page stats (total interns, active interns, etc.)
            const dashboardStats = document.querySelectorAll('#dashboard .stat-value');

            // Update task overview on dashboard if it exists
            const taskOverviewValues = document.querySelectorAll('.task-overview-value');
            if (taskOverviewValues.length >= 4) {
                taskOverviewValues[0].textContent = stats.in_progress_tasks;
                taskOverviewValues[1].textContent = stats.completed_tasks;
                taskOverviewValues[2].textContent = stats.pending_tasks;
                taskOverviewValues[3].textContent = stats.overdue_tasks;
            }
        }

        function updateInternsTable(interns) {
            const tbody = document.querySelector('#interns .data-table tbody');
            if (!tbody) return;

            if (interns.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            <i class="fas fa-users" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                            No interns assigned yet
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            interns.forEach(intern => {
                const progressClass = intern.progress < 30 ? 'red' : (intern.progress < 70 ? 'gold' : 'green');
                const statusClass = intern.status === 'Active' ? 'success' : (intern.status === 'Completed' ? 'info' : 'warning');

                html += `
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                ${intern.profile_picture_url
                                    ? `<img src="${intern.profile_picture_url}" alt="${escapeHtml(intern.name)}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--maroon);">`
                                    : `<div class="list-item-avatar" style="width: 36px; height: 36px; font-size: 12px; margin: 0;">${intern.initial}</div>`
                                }
                                <div>
                                    <div style="font-weight: 600;">${escapeHtml(intern.name)}</div>
                                    <div style="font-size: 12px; color: #6B7280;">${escapeHtml(intern.email)}</div>
                                </div>
                            </div>
                        </td>
                        <td>${escapeHtml(intern.course)}</td>
                        <td style="min-width: 140px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="progress-container" style="flex: 1;">
                                    <div class="progress-bar ${progressClass}" style="width: ${intern.progress}%"></div>
                                </div>
                                <span style="font-size: 12px; font-weight: 600;">${intern.progress}%</span>
                            </div>
                            <div style="font-size: 11px; color: #6B7280; margin-top: 4px;">
                                ${intern.completed_hours} / ${intern.required_hours} hrs
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-${statusClass}">
                                ${intern.status}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-secondary" onclick="viewIntern(${intern.id})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm" style="background: #F59E0B; color: white;" onclick="editIntern(${intern.id})" title="Edit Intern">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            // Update interns page stats
            const internStatCards = document.querySelectorAll('#interns .stat-value');
            if (internStatCards.length >= 3) {
                internStatCards[0].textContent = interns.length;
                internStatCards[1].textContent = interns.filter(i => i.status === 'Active').length;
                internStatCards[2].textContent = interns.filter(i => i.status === 'Completed').length;
            }
        }

        function updateAttendanceTable(attendances, stats) {
            const tbody = document.querySelector('#attendance .data-table tbody');
            if (!tbody) return;

            if (attendances.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            <i class="fas fa-calendar-check" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                            No attendance records for today
                        </td>
                    </tr>
                `;
            } else {
                let html = '';
                attendances.forEach(attendance => {
                    html += `
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="list-item-avatar" style="width: 36px; height: 36px; font-size: 12px; margin: 0;">
                                        ${attendance.intern_initial}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">${escapeHtml(attendance.intern_name)}</div>
                                        <div style="font-size: 12px; color: #6B7280;">${escapeHtml(attendance.intern_course)}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="${attendance.is_late ? 'color: #F59E0B; font-weight: 600;' : ''}">
                                    ${attendance.time_in}
                                    ${attendance.is_late ? '<i class="fas fa-exclamation-circle"></i>' : ''}
                                </span>
                            </td>
                            <td>
                                ${attendance.time_out ? attendance.time_out : '<span style="color: var(--forest-green); font-weight: 500;">Still working</span>'}
                            </td>
                            <td>
                                ${attendance.hours_worked > 0
                                    ? `<span style="font-weight: 600; color: var(--maroon);">${attendance.hours_worked.toFixed(1)} hrs</span>`
                                    : '<span style="color: #9CA3AF;">--</span>'}
                            </td>
                            <td>
                                <span class="badge badge-${attendance.status === 'Present' ? 'success' : 'danger'}">
                                    ${attendance.status}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            }

            // Update attendance page stats
            const attendanceStatCards = document.querySelectorAll('#attendance .stat-value');
            if (attendanceStatCards.length >= 3 && stats) {
                attendanceStatCards[0].textContent = stats.present;
                attendanceStatCards[1].textContent = stats.absent;
                attendanceStatCards[2].textContent = stats.late;
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Pause auto-refresh when tab is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
                refreshData(); // Refresh immediately when tab becomes visible
            }
        });


        // ===== ADMIN MODULE FUNCTIONS (START) =====
        function renderPaginationControls(containerId, currentPage, totalPages, totalItems, goFnName) {
            const container = document.getElementById(containerId);
            if (!container) return;
            if (totalPages <= 1) {
                container.innerHTML = totalItems > 0 ? `<span style="font-size: 13px; color: #6B7280;">Showing ${totalItems} item${totalItems !== 1 ? 's' : ''}</span>` : '';
                return;
            }
            const btnStyle = (disabled) => `padding: 6px 12px; border: 1px solid #E5E7EB; border-radius: 6px; background: white; cursor: pointer; font-size: 13px; ${disabled ? 'opacity:.5;cursor:not-allowed;' : ''}`;
            let html = `<span style="font-size: 13px; color: #6B7280; margin-right: 8px;">Page ${currentPage} of ${totalPages} (${totalItems} items)</span>`;
            html += `<button onclick="${goFnName}(1)" ${currentPage === 1 ? 'disabled' : ''} style="${btnStyle(currentPage === 1)}"><i class="fas fa-angle-double-left"></i></button>`;
            html += `<button onclick="${goFnName}(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} style="${btnStyle(currentPage === 1)}"><i class="fas fa-angle-left"></i></button>`;
            html += `<button onclick="${goFnName}(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} style="${btnStyle(currentPage === totalPages)}"><i class="fas fa-angle-right"></i></button>`;
            html += `<button onclick="${goFnName}(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''} style="${btnStyle(currentPage === totalPages)}"><i class="fas fa-angle-double-right"></i></button>`;
            container.innerHTML = html;
        }

        async function loadTeamLeadersData() {
        // ===== RESEARCH TRACKING FUNCTIONS =====

        function switchResearchView(viewType) {
            const kanbanView = document.getElementById('kanban-view');
            const listView = document.getElementById('list-view');

            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.closest('.filter-tab').classList.add('active');

            if (viewType === 'kanban') {
                kanbanView.style.display = 'grid';
                listView.style.display = 'none';
            } else if (viewType === 'list') {
                kanbanView.style.display = 'none';
                listView.style.display = 'block';
            } else if (viewType === 'timeline') {
                alert('Timeline view coming soon!');
            }
        }

        function filterResearchProjects() {
            console.log('Filtering research projects...');
            // In a real app, this would filter the projects based on selected filters
        }

        function searchResearchProjects() {
            console.log('Searching research projects...');
            // In a real app, this would search projects
        }

        function openNewProjectModal() {
            alert('New Project Modal - Feature coming soon!\n\nThis will allow you to:\n- Enter project name and description\n- Add team members\n- Set project category\n- Define initial milestones');
        }

        function viewProjectDetails(projectId) {
            alert(`Viewing Project #${projectId} Details\n\nThis will show:\n- Complete project information\n- Stage-by-stage progress\n- Training completion status\n- Development phase details\n- Team members and roles\n- Deliverables and milestones\n- Timeline and history`);
        }

        function promoteToIncubatee(projectId) {
            if (confirm('Promote this project to Incubatee status?\n\nThis will:\n- Move project to Incubatee Tracker\n- Create new MOA record\n- Notify team members\n- Archive research tracking history')) {
                alert(`Project #${projectId} promoted successfully!\nRedirecting to Incubatee Tracker...`);
            }
        }

        // ===== INCUBATEE TRACKER FUNCTIONS =====

        function switchIncubateeTab(tabType) {
            const moaTable = document.getElementById('moa-table');
            const paymentsTable = document.getElementById('payments-table');
            const alertsTable = document.getElementById('alerts-table');
            const moaBtn = document.getElementById('moaTabBtn');
            const paymentsBtn = document.getElementById('paymentsTabBtn');
            const alertsBtn = document.getElementById('alertsTabBtn');

            // Hide all
            moaTable.style.display = 'none';
            paymentsTable.style.display = 'none';
            if (alertsTable) alertsTable.style.display = 'none';
            moaBtn.classList.remove('active');
            paymentsBtn.classList.remove('active');
            if (alertsBtn) alertsBtn.classList.remove('active');

            if (tabType === 'moa') {
                moaTable.style.display = 'block';
                moaBtn.classList.add('active');
            } else if (tabType === 'payments') {
                paymentsTable.style.display = 'block';
                paymentsBtn.classList.add('active');
            } else if (tabType === 'alerts') {
                if (alertsTable) alertsTable.style.display = 'block';
                if (alertsBtn) alertsBtn.classList.add('active');
            }
        }

        function filterIncubatees() {
            const statusFilter = document.getElementById('incubateeStatusFilter').value;
            const rows = document.querySelectorAll('.incubatee-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const matchStatus = statusFilter === 'all' || status === statusFilter;
                row.style.display = matchStatus ? '' : 'none';
            });
        }

        function searchIncubatees() {
            const searchTerm = document.getElementById('incubateeSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.incubatee-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // ========== ALERT & REMINDER FUNCTIONS ==========
        function sendMoaReminder(startupId, companyName) {
            if (!startupId) {
                showToast('error', 'Error', 'Invalid startup ID');
                return;
            }
            showConfirmModal({
                type: 'info',
                title: 'Send MOA Reminder',
                message: `Send a reminder to "${companyName}" to submit their MOA?`,
                confirmText: 'Send Reminder',
                onConfirm: () => {
                    fetch(`/admin/send-moa-reminder/${startupId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Reminder Sent', data.message || `Reminder sent to ${companyName}`);
                        } else {
                            showToast('error', 'Error', data.message || 'Failed to send reminder');
                        }
                    })
                    .catch(() => showToast('error', 'Error', 'An error occurred'));
                }
            });
        }

        function sendPaymentReminder(startupId, companyName) {
            if (!startupId) {
                showToast('error', 'Error', 'Invalid startup ID');
                return;
            }
            showConfirmModal({
                type: 'warning',
                title: 'Send Payment Reminder',
                message: `Send an overdue payment reminder to "${companyName}"?`,
                confirmText: 'Send Reminder',
                onConfirm: () => {
                    fetch(`/admin/send-payment-reminder/${startupId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Reminder Sent', data.message || `Payment reminder sent to ${companyName}`);
                        } else {
                            showToast('error', 'Error', data.message || 'Failed to send reminder');
                        }
                    })
                    .catch(() => showToast('error', 'Error', 'An error occurred'));
                }
            });
        }

        // ========== PAYMENT SCHEDULE & MOA EXPIRY FUNCTIONS ==========
        function openPaymentScheduleModal(id, name, amount, duration, startDate) {
            document.getElementById('paymentScheduleStartupId').value = id;
            document.getElementById('paymentScheduleStartupName').textContent = name;
            document.getElementById('paymentScheduleAmount').value = amount || '';
            document.getElementById('paymentScheduleDuration').value = duration || '';
            document.getElementById('paymentScheduleStartDate').value = startDate || '';
            const modal = document.getElementById('paymentScheduleModal');
            modal.style.display = 'flex';
        }

        function closePaymentScheduleModal() {
            document.getElementById('paymentScheduleModal').style.display = 'none';
        }

        function savePaymentSchedule() {
            const startupId = document.getElementById('paymentScheduleStartupId').value;
            const amount = document.getElementById('paymentScheduleAmount').value;
            const duration = document.getElementById('paymentScheduleDuration').value;
            const startDate = document.getElementById('paymentScheduleStartDate').value;

            if (!amount || !duration || !startDate) {
                showToast('error', 'Validation Error', 'Please fill in all fields.');
                return;
            }

            fetch(`/admin/startup/${startupId}/payment-schedule`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    payment_amount: amount,
                    payment_duration: duration,
                    payment_start_date: startDate
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Schedule Saved', data.message || 'Payment schedule updated successfully.');
                    closePaymentScheduleModal();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to save payment schedule.');
                }
            })
            .catch(() => showToast('error', 'Error', 'An error occurred while saving.'));
        }

        function openMoaExpiryModal(id, name, expiry, moaStatus) {
            document.getElementById('moaExpiryStartupId').value = id;
            document.getElementById('moaExpiryStartupName').textContent = name;
            document.getElementById('moaExpiryDate').value = expiry || '';
            document.getElementById('moaExpiryStatus').value = moaStatus || 'none';
            const modal = document.getElementById('moaExpiryModal');
            modal.style.display = 'flex';
        }

        function closeMoaExpiryModal() {
            document.getElementById('moaExpiryModal').style.display = 'none';
        }

        function saveMoaExpiry() {
            const startupId = document.getElementById('moaExpiryStartupId').value;
            const expiryDate = document.getElementById('moaExpiryDate').value;
            const moaStatus = document.getElementById('moaExpiryStatus').value;

            if (!expiryDate) {
                showToast('error', 'Validation Error', 'Please select an expiry date.');
                return;
            }

            fetch(`/admin/startup/${startupId}/moa-expiry`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ moa_expiry: expiryDate, moa_status: moaStatus })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'MOA Updated', data.message || 'MOA settings updated successfully.');
                    closeMoaExpiryModal();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to update MOA settings.');
                }
            })
            .catch(() => showToast('error', 'Error', 'An error occurred while saving.'));
        }

        function sendPaymentDueReminder(startupId, companyName) {
            if (!startupId) {
                showToast('error', 'Error', 'Invalid startup ID');
                return;
            }
            showConfirmModal({
                type: 'warning',
                title: 'Send Payment Due Reminder',
                message: `Send a payment due reminder to "${companyName}"?`,
                confirmText: 'Send Reminder',
                onConfirm: () => {
                    fetch(`/admin/startup/${startupId}/payment-due-reminder`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Reminder Sent', data.message || `Payment due reminder sent to ${companyName}`);
                        } else {
                            showToast('error', 'Error', data.message || 'Failed to send reminder');
                        }
                    })
                    .catch(() => showToast('error', 'Error', 'An error occurred'));
                }
            });
        }

        function sendMoaExpiryReminder(startupId, companyName) {
            if (!startupId) {
                showToast('error', 'Error', 'Invalid startup ID');
                return;
            }
            showConfirmModal({
                type: 'info',
                title: 'Send MOA Expiry Reminder',
                message: `Send an MOA expiry reminder to "${companyName}"?`,
                confirmText: 'Send Reminder',
                onConfirm: () => {
                    fetch(`/admin/startup/${startupId}/moa-expiry-reminder`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Reminder Sent', data.message || `MOA expiry reminder sent to ${companyName}`);
                        } else {
                            showToast('error', 'Error', data.message || 'Failed to send reminder');
                        }
                    })
                    .catch(() => showToast('error', 'Error', 'An error occurred'));
                }
            });
        }

        // ========== INCUBATEE TRACKER EXPORT FUNCTIONS ==========
        function exportMoaReport() {
            const headers = ['Tracking Code', 'Company/Startup', 'Email', 'Contact Person', 'Phone', 'MOA Purpose', 'Status', 'Submitted Date', 'Reviewed Date'];
            const rows = [];

            // Use incubateeMoaData (available after page load)
            const moaData = typeof incubateeMoaData !== 'undefined' ? incubateeMoaData : {};
            for (const key in moaData) {
                const moa = moaData[key];
                rows.push([
                    moa.tracking_code || '',
                    moa.company_name || '',
                    moa.email || '',
                    moa.contact_person || '',
                    moa.phone || '',
                    moa.moa_purpose || '',
                    (moa.status || '').replace('_', ' ').toUpperCase(),
                    moa.created_at || '',
                    moa.reviewed_at || 'N/A'
                ]);
            }

            if (rows.length === 0) {
                if (typeof showToast === 'function') {
                    showToast('warning', 'No Data', 'There are no MOA requests to export.');
                } else {
                    alert('There are no MOA requests to export.');
                }
                return;
            }

            downloadCsv('MOA_Requests_Report', headers, rows);
            if (typeof showToast === 'function') {
                showToast('success', 'Export Complete', `Successfully exported ${rows.length} MOA request(s) to CSV.`);
            }
        }

        function exportPaymentsReport() {
            const headers = ['Tracking Code', 'Company/Startup', 'Contact Person', 'Email', 'Invoice #', 'Amount', 'Payment Method', 'Payment Date', 'Status', 'Submitted Date', 'Reviewed Date'];
            const rows = [];

            const paymentsData = typeof paymentSubmissionsData !== 'undefined' ? paymentSubmissionsData : {};
            for (const key in paymentsData) {
                const payment = paymentsData[key];
                const methodLabels = {
                    'bank_transfer': 'Bank Transfer',
                    'bank_deposit': 'Bank Deposit',
                    'gcash': 'GCash',
                    'maya': 'Maya',
                    'check': 'Check',
                    'cash': 'Cash'
                };
                rows.push([
                    payment.tracking_code || '',
                    payment.company_name || '',
                    payment.contact_person || '',
                    payment.email || '',
                    payment.invoice_number || '',
                    payment.amount ? parseFloat(payment.amount).toFixed(2) : '0.00',
                    methodLabels[payment.payment_method] || payment.payment_method || 'N/A',
                    payment.payment_date || 'N/A',
                    (payment.status || '').replace('_', ' ').toUpperCase(),
                    payment.created_at || '',
                    payment.reviewed_at || 'N/A'
                ]);
            }

            if (rows.length === 0) {
                if (typeof showToast === 'function') {
                    showToast('warning', 'No Data', 'There are no payment submissions to export.');
                } else {
                    alert('There are no payment submissions to export.');
                }
                return;
            }

            downloadCsv('Payment_Submissions_Report', headers, rows);
            if (typeof showToast === 'function') {
                showToast('success', 'Export Complete', `Successfully exported ${rows.length} payment submission(s) to CSV.`);
            }
        }

        function downloadCsv(filenamePrefix, headers, rows) {
            let csvContent = headers.map(h => '"' + h.replace(/"/g, '""') + '"').join(',') + '\n';
            rows.forEach(row => {
                csvContent += row.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',') + '\n';
            });

            const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const now = new Date();
            const dateStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            link.href = URL.createObjectURL(blob);
            link.download = `${filenamePrefix}_${dateStr}.csv`;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }
        // ========== ISSUES & TASKS EXPORT FUNCTIONS ==========
        function exportIssuesReport() {
            const headers = ['Tracking Code', 'Room Number', 'Issue Type', 'Description', 'Company', 'Contact Person', 'Email', 'Phone', 'Priority', 'Status', 'Reported Date', 'Resolved Date', 'Admin Notes'];
            const rows = [];

            const issuesData = typeof roomIssuesData !== 'undefined' ? roomIssuesData : {};
            for (const key in issuesData) {
                const issue = issuesData[key];
                rows.push([
                    issue.tracking_code || '',
                    issue.room_number || '',
                    issue.issue_type_label || issue.issue_type || '',
                    (issue.description || '').replace(/[\r\n]+/g, ' '),
                    issue.company_name || '',
                    issue.contact_person || '',
                    issue.email || '',
                    issue.phone || '',
                    (issue.priority || '').toUpperCase(),
                    issue.status_label || (issue.status || '').replace('_', ' ').toUpperCase(),
                    issue.created_at || '',
                    issue.resolved_at || 'N/A',
                    (issue.admin_notes || '').replace(/[\r\n]+/g, ' ')
                ]);
            }

            if (rows.length === 0) {
                if (typeof showToast === 'function') {
                    showToast('warning', 'No Data', 'There are no issues to export.');
                } else {
                    alert('There are no issues to export.');
                }
                return;
            }

            downloadCsv('Issues_Complaints_Report', headers, rows);
            if (typeof showToast === 'function') {
                showToast('success', 'Export Complete', `Successfully exported ${rows.length} issue(s) to CSV.`);
            }
        }

        function exportTasksReport() {
            // Use the server-side export for a proper Excel file
            window.location.href = '/admin/export/tasks';
        }
        // ========== STARTUP DATA FOR MODALS ==========
        
        const startupDocumentsData = [];
        const incubateeMoaData = [];
        const paymentSubmissionsData = [];
        const roomIssuesData = [];

        // ========== SCHOOL INTERNS DATA FOR PDF EXPORT ==========
                const schoolInternsForPdf = [];

        let currentDocId = null;
        let currentMoaId = null;
        let currentPaymentId = null;
        let currentIssueId = null;

        // ========== KANBAN DRAG AND DROP FUNCTIONS ==========

        let draggedCard = null;

        function dragStart(event) {
            draggedCard = event.target;
            event.target.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', event.target.dataset.id);
        }

        function dragEnd(event) {
            event.target.classList.remove('dragging');
            // Remove drag-over class from all columns
            document.querySelectorAll('.kanban-column').forEach(col => {
                col.classList.remove('drag-over');
            });
            draggedCard = null;
        }

        function allowDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.add('drag-over');
        }

        function dragLeave(event) {
            event.currentTarget.classList.remove('drag-over');
        }

        function dropCard(event) {
            event.preventDefault();
            const column = event.currentTarget;
            column.classList.remove('drag-over');

            const docId = event.dataTransfer.getData('text/plain');
            const newStatus = column.dataset.status;

            if (draggedCard && docId) {
                // Move card to new column
                const cardsContainer = column.querySelector('.kanban-cards');

                // Remove empty message if exists
                const emptyMsg = cardsContainer.querySelector('.kanban-empty');
                if (emptyMsg) {
                    emptyMsg.remove();
                }

                // Update card class based on new status
                draggedCard.classList.remove('pending', 'reviewing', 'success', 'rejected');

                // Update the status badge label and style
                const statusBadge = draggedCard.querySelector('.status-badge');

                // Get the button container
                const buttonContainer = draggedCard.querySelector('div[style*="margin-top: auto"]');
                const filePath = draggedCard.querySelector('a[href*="storage"]')?.getAttribute('href') || '#';

                switch(newStatus) {
                    case 'pending':
                        draggedCard.classList.add('pending');
                        if (statusBadge) {
                            statusBadge.textContent = 'Pending';
                            statusBadge.style.background = '#FEF3C7';
                            statusBadge.style.color = '#92400E';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-check"></i> Review
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                    case 'under_review':
                        draggedCard.classList.add('reviewing');
                        if (statusBadge) {
                            statusBadge.textContent = 'Reviewing';
                            statusBadge.style.background = '#DBEAFE';
                            statusBadge.style.color = '#1E40AF';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-check"></i> Approve/Reject
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                    case 'approved':
                        draggedCard.classList.add('success');
                        if (statusBadge) {
                            statusBadge.textContent = 'Approved';
                            statusBadge.style.background = '#DCFCE7';
                            statusBadge.style.color = '#166534';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center;">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            `;
                        }
                        break;
                    case 'rejected':
                        draggedCard.classList.add('rejected');
                        if (statusBadge) {
                            statusBadge.textContent = 'Rejected';
                            statusBadge.style.background = '#FEE2E2';
                            statusBadge.style.color = '#991B1B';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #6B7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-redo"></i> Re-review
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                }

                cardsContainer.appendChild(draggedCard);

                // Update status via AJAX
                updateDocumentStatus(docId, newStatus);

                // Update counts
                updateKanbanCounts();
            }
        }

        function updateDocumentStatus(docId, newStatus) {
            fetch(`/admin/startup-documents/${docId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Document status updated successfully', 'success');
                } else {
                    showNotification('Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating status', 'error');
            });
        }

        function updateKanbanCounts() {
            const statuses = ['pending', 'under_review', 'approved', 'rejected'];
            statuses.forEach(status => {
                const column = document.querySelector(`.kanban-column[data-status="${status}"]`);
                if (column) {
                    const count = column.querySelectorAll('.kanban-card').length;
                    const countBadge = document.getElementById(`${status}-count`);
                    if (countBadge) {
                        countBadge.textContent = count;
                    }
                }
            });
        }

        // ========== DOCUMENT DETAILS MODAL FUNCTIONS ==========

        function viewDocumentDetails(docId) {
            const doc = startupDocumentsData[docId];
            if (!doc) {
                alert('Document not found');
                return;
            }

            currentDocId = docId;

            const statusColors = {
                'pending': { bg: '#FEF3C7', text: '#92400E' },
                'under_review': { bg: '#DBEAFE', text: '#1E40AF' },
                'approved': { bg: '#DCFCE7', text: '#166534' },
                'rejected': { bg: '#FEE2E2', text: '#991B1B' }
            };
            const color = statusColors[doc.status] || { bg: '#E5E7EB', text: '#374151' };

            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${doc.tracking_code}</div>
                        </div>
                        <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ${doc.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company Name</div>
                            <div style="font-weight: 600;">${doc.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Person</div>
                            <div style="font-weight: 600;">${doc.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${doc.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${doc.phone || 'N/A'}</div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Document Information</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <span style="font-weight: 600;">Type:</span> ${doc.document_type}
                            </div>
                            <div>
                                <span style="font-weight: 600;">File:</span> ${doc.original_filename}
                            </div>
                        </div>
                    </div>

                    ${doc.notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Submitter Notes</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px;">${doc.notes}</div>
                    </div>
                    ` : ''}

                    ${doc.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${doc.admin_notes}</div>
                    </div>
                    ` : ''}

                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Submitted: ${doc.created_at}</div>
                        ${doc.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Reviewed: ${doc.reviewed_at}</div>` : ''}
                    </div>
                </div>
            `;

            document.getElementById('documentDetailsContent').innerHTML = content;
            document.getElementById('documentDownloadBtn').href = '/storage/' + doc.file_path;
            document.getElementById('documentDetailsModal').style.display = 'flex';
        }

        function closeDocumentDetailsModal() {
            document.getElementById('documentDetailsModal').style.display = 'none';
            currentDocId = null;
        }

        function openReviewDocumentModal(docId = null) {
            const id = docId || currentDocId;
            const doc = startupDocumentsData[id];
            if (!doc) return;

            document.getElementById('reviewDocId').value = id;
            document.getElementById('reviewDocInfo').innerHTML = `
                <strong>${doc.tracking_code}</strong><br>
                ${doc.company_name} - ${doc.document_type}
            `;
            document.getElementById('reviewDocAction').value = '';
            document.getElementById('reviewDocNotes').value = '';

            closeDocumentDetailsModal();
            document.getElementById('reviewDocumentModal').style.display = 'flex';
        }

        function closeReviewDocumentModal() {
            document.getElementById('reviewDocumentModal').style.display = 'none';
        }

        function toggleReviewNotes() {
            const action = document.getElementById('reviewDocAction').value;
            document.getElementById('reviewNotesGroup').style.display = action === 'rejected' ? 'block' : 'block';
        }

        function submitDocumentReview() {
            const docId = document.getElementById('reviewDocId').value;
            const action = document.getElementById('reviewDocAction').value;
            const notes = document.getElementById('reviewDocNotes').value;

            if (!action) {
                alert('Please select a review action');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewDocumentModal .btn-modal.primary');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }

            fetch(`/admin/submissions/${docId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: action,
                    admin_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Document ${data.submission.tracking_code} has been ${action === 'approved' ? 'approved' : action === 'rejected' ? 'rejected' : 'updated'}!`);
                    closeReviewDocumentModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update submission');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the submission');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // ========== MOA REVIEW/TEMPLATE MODAL FUNCTIONS ==========

        function openReviewMoaModal() {
            let moa = moaRequestsData.find(m => m.id == currentMoaId);
            if (!moa) {
                moa = incubateeMoaData[currentMoaId];
            }
            if (!moa) return;

            document.getElementById('reviewMoaId').value = currentMoaId;
            document.getElementById('reviewMoaInfo').innerHTML = `
                <strong>${moa.tracking_code}</strong><br>
                ${moa.company_name} - ${moa.moa_purpose}
            `;
            document.getElementById('reviewMoaAction').value = '';
            document.getElementById('reviewMoaNotes').value = '';
            document.getElementById('moaApproveFields').style.display = 'none';
            document.getElementById('moaRejectFields').style.display = 'none';
            document.getElementById('moaRejectionRemarks').value = '';
            document.getElementById('moaPaymentStartDate').value = '';
            document.getElementById('moaPaymentEndDate').value = '';
            removeMoaApproveFile();

            closeMoaDetailsModal();
            document.getElementById('reviewMoaModal').style.display = 'flex';
        }

        function closeReviewMoaModal() {
            document.getElementById('reviewMoaModal').style.display = 'none';
        }

        function toggleMoaReviewFields() {
            const action = document.getElementById('reviewMoaAction').value;
            document.getElementById('moaApproveFields').style.display = action === 'approved' ? 'block' : 'none';
            document.getElementById('moaRejectFields').style.display = action === 'rejected' ? 'block' : 'none';
        }

        // MOA approve file handling
        let moaApproveSelectedFile = null;

        function handleMoaApproveFile(file) {
            if (!file) return;
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please upload a PDF, DOC, or DOCX file.');
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB.');
                return;
            }
            moaApproveSelectedFile = file;
            document.getElementById('moaApproveFileName').textContent = file.name;
            document.getElementById('moaApproveFileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('moaApproveFilePreview').style.display = 'flex';
            document.getElementById('moaApproveDropZone').style.display = 'none';
        }

        function removeMoaApproveFile() {
            moaApproveSelectedFile = null;
            const fileInput = document.getElementById('moaApproveFileInput');
            if (fileInput) fileInput.value = '';
            const preview = document.getElementById('moaApproveFilePreview');
            if (preview) preview.style.display = 'none';
            const dropZone = document.getElementById('moaApproveDropZone');
            if (dropZone) dropZone.style.display = 'block';
        }

        function submitMoaReview() {
            const moaId = document.getElementById('reviewMoaId').value;
            const action = document.getElementById('reviewMoaAction').value;
            const notes = document.getElementById('reviewMoaNotes').value;

            if (!action) {
                alert('Please select a review action');
                return;
            }

            // Validate rejection requires remarks
            if (action === 'rejected') {
                const remarks = document.getElementById('moaRejectionRemarks').value.trim();
                if (!remarks) {
                    alert('Please provide rejection remarks');
                    return;
                }
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewMoaModal .btn-modal.primary');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }

            // If approving with a file, use FormData for multipart upload
            if (action === 'approved' && moaApproveSelectedFile) {
                const formData = new FormData();
                formData.append('status', action);
                formData.append('admin_notes', notes);
                formData.append('moa_document', moaApproveSelectedFile);

                // Billing schedule fields
                const billingAmount = document.getElementById('moaBillingAmount').value;
                const billingDuration = document.getElementById('moaBillingDuration').value;
                const billingStartDate = document.getElementById('moaBillingStartDate').value;
                if (billingAmount) formData.append('billing_amount', billingAmount);
                if (billingDuration) formData.append('billing_duration', billingDuration);
                if (billingStartDate) formData.append('billing_start_date', billingStartDate);

                // MOA settings fields
                const moaStatus = document.getElementById('moaApproveStatus').value;
                const moaExpiryDate = document.getElementById('moaApproveExpiryDate').value;
                if (moaStatus) formData.append('moa_status', moaStatus);
                if (moaExpiryDate) formData.append('moa_expiry', moaExpiryDate);

                fetch(`/admin/moa-requests/${moaId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'MOA Approved', data.message || 'MOA has been approved successfully!');
                        closeReviewMoaModal();
                        location.reload();
                    } else {
                        showToast('error', 'Error', data.message || 'Failed to approve MOA request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while approving the MOA request');
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            } else if (action === 'rejected') {
                // Reject with remarks
                const remarks = document.getElementById('moaRejectionRemarks').value.trim();
                fetch(`/admin/moa-requests/${moaId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        admin_notes: notes,
                        rejection_remarks: remarks
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'MOA Rejected', data.message || 'MOA request has been rejected.');
                        closeReviewMoaModal();
                        location.reload();
                    } else {
                        showToast('error', 'Error', data.message || 'Failed to reject MOA request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while rejecting the MOA request');
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            } else {
                // Under review or approve without file - use existing flow
                const body = {
                    status: action,
                    admin_notes: notes
                };

                if (action === 'approved') {
                    // Billing schedule fields
                    const billingAmount = document.getElementById('moaBillingAmount').value;
                    const billingDuration = document.getElementById('moaBillingDuration').value;
                    const billingStartDate = document.getElementById('moaBillingStartDate').value;
                    if (billingAmount) body.billing_amount = billingAmount;
                    if (billingDuration) body.billing_duration = billingDuration;
                    if (billingStartDate) body.billing_start_date = billingStartDate;

                    // MOA settings fields
                    const moaStatus = document.getElementById('moaApproveStatus').value;
                    const moaExpiryDate = document.getElementById('moaApproveExpiryDate').value;
                    if (moaStatus) body.moa_status = moaStatus;
                    if (moaExpiryDate) body.moa_expiry = moaExpiryDate;
                }

                fetch(`/admin/submissions/${moaId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Updated', `MOA Request has been updated!`);
                        closeReviewMoaModal();
                        location.reload();
                    } else {
                        showToast('error', 'Error', data.message || 'Failed to update MOA request');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred while updating the MOA request');
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        }

        function generateMoaFromTemplate() {
            let moa = moaRequestsData.find(m => m.id == currentMoaId);
            if (!moa) {
                moa = incubateeMoaData[currentMoaId];
            }
            closeMoaDetailsModal();

            // Pre-fill the template form with MOA data
            if (moa) {
                document.getElementById('moaCompanyName').value = moa.company_name;
                document.getElementById('moaRepresentative').value = moa.contact_person;
                // Set purpose based on moa_purpose
                const purposeMap = {
                    'Incubation Program': 'incubation',
                    'Co-working Space': 'coworking',
                    'Mentorship': 'mentorship',
                    'Partnership': 'partnership'
                };
                const mappedPurpose = purposeMap[moa.moa_purpose] || 'other';
                document.getElementById('moaPurpose').value = mappedPurpose;

                // Handle "other" purpose case
                if (mappedPurpose === 'other' && moa.moa_purpose) {
                    document.getElementById('moaOtherPurpose').value = moa.moa_purpose;
                    document.getElementById('otherPurposeGroup').style.display = 'block';
                } else {
                    document.getElementById('otherPurposeGroup').style.display = 'none';
                }
            }

            document.getElementById('moaTemplateModal').style.display = 'flex';
        }

        // ========== MOA TEMPLATE MODAL FUNCTIONS ==========

        function closeMoaTemplateModal() {
            document.getElementById('moaTemplateModal').style.display = 'none';
        }

        function openMoaTemplateModal() {
            // Clear the form for a fresh MOA
            document.getElementById('moaCompanyName').value = '';
            document.getElementById('moaRepresentative').value = '';
            document.getElementById('moaPosition').value = '';
            document.getElementById('moaAddress').value = '';
            document.getElementById('moaPurpose').value = '';
            document.getElementById('moaOtherPurpose').value = '';
            document.getElementById('otherPurposeGroup').style.display = 'none';
            document.getElementById('moaStartDate').value = '';
            document.getElementById('moaEndDate').value = '';
            document.getElementById('moaFee').value = '';
            document.getElementById('moaTerms').value = '';
            // Reset work arrangement radio buttons
            document.querySelectorAll('input[name="moaWorkArrangement"]').forEach(radio => radio.checked = false);
            document.getElementById('moaPreviewContent').innerHTML = '<p style="color: #6B7280; font-style: italic;">Fill in the form and click "Generate Preview" to see the MOA document.</p>';

            document.getElementById('moaTemplateModal').style.display = 'flex';
        }

        function toggleOtherPurpose() {
            const purposeSelect = document.getElementById('moaPurpose');
            const otherPurposeGroup = document.getElementById('otherPurposeGroup');
            const otherPurposeInput = document.getElementById('moaOtherPurpose');

            if (purposeSelect.value === 'other') {
                otherPurposeGroup.style.display = 'block';
                otherPurposeInput.setAttribute('required', 'required');
            } else {
                otherPurposeGroup.style.display = 'none';
                otherPurposeInput.removeAttribute('required');
                otherPurposeInput.value = '';
            }
        }

        function generateMoaPreview() {
            const companyName = document.getElementById('moaCompanyName').value;
            const representative = document.getElementById('moaRepresentative').value;
            const position = document.getElementById('moaPosition').value;
            const address = document.getElementById('moaAddress').value;
            const purpose = document.getElementById('moaPurpose').value;
            const otherPurpose = document.getElementById('moaOtherPurpose').value;
            const startDate = document.getElementById('moaStartDate').value;
            const endDate = document.getElementById('moaEndDate').value;
            const fee = document.getElementById('moaFee').value;
            const terms = document.getElementById('moaTerms').value;
            const workArrangement = document.querySelector('input[name="moaWorkArrangement"]:checked')?.value || '';

            if (!companyName || !representative || !position || !address || !purpose || !startDate || !endDate) {
                alert('Please fill in all required fields');
                return;
            }

            if (purpose === 'other' && !otherPurpose) {
                alert('Please specify the MOA purpose');
                return;
            }

            const purposeLabels = {
                'incubation': 'Business Incubation Program',
                'coworking': 'Co-working Space Usage',
                'mentorship': 'Mentorship Program',
                'partnership': 'Partnership Agreement',
                'other': otherPurpose || 'Other Services'
            };

            const workArrangementLabels = {
                'onsite': 'Onsite (Physical workspace at UP Cebu TBI)',
                'virtual': 'Virtual (Remote access to services)',
                'hybrid': 'Hybrid (Combination of onsite and virtual)'
            };

            const formatDate = (dateStr) => {
                const d = new Date(dateStr);
                return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            };

            const preview = `
                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="/images/up-cebu-logo.png" alt="UP Cebu Logo" style="height: 60px; margin-bottom: 12px;" onerror="this.style.display='none'">
                    <h2 style="font-size: 18px; font-weight: 700; color: #7B1D3A; margin: 0;">UNIVERSITY OF THE PHILIPPINES CEBU</h2>
                    <p style="font-size: 12px; color: #6B7280; margin: 4px 0;">Gorordo Avenue, Lahug, Cebu City 6000</p>
                    <h3 style="font-size: 16px; font-weight: 700; margin: 16px 0 0 0; text-decoration: underline;">MEMORANDUM OF AGREEMENT</h3>
                </div>

                <div style="text-align: justify; font-size: 12px; line-height: 1.8;">
                    <p><strong>KNOW ALL MEN BY THESE PRESENTS:</strong></p>

                    <p>This Memorandum of Agreement (MOA) is entered into by and between:</p>

                    <p style="margin-left: 20px;">
                        <strong>UNIVERSITY OF THE PHILIPPINES CEBU</strong>, a constituent unit of the University of the Philippines System,
                        represented herein by its Chancellor, with office address at Gorordo Avenue, Lahug, Cebu City 6000,
                        hereinafter referred to as "<strong>UP CEBU</strong>";
                    </p>

                    <p style="text-align: center;">- and -</p>

                    <p style="margin-left: 20px;">
                        <strong>${companyName.toUpperCase()}</strong>, represented herein by <strong>${representative}</strong>,
                        ${position}, with business address at ${address},
                        hereinafter referred to as the "<strong>PARTNER</strong>";
                    </p>

                    <p><strong>WITNESSETH:</strong></p>

                    <p><strong>WHEREAS</strong>, UP CEBU, through its Technology Business Incubator, aims to support and nurture startup
                    enterprises and innovative business ventures;</p>

                    <p><strong>WHEREAS</strong>, the PARTNER desires to avail of the ${purposeLabels[purpose]} offered by UP CEBU;</p>

                    <p><strong>NOW, THEREFORE</strong>, for and in consideration of the foregoing premises and the mutual covenants
                    herein contained, the parties agree as follows:</p>

                    <p><strong>ARTICLE I - PURPOSE</strong></p>
                    <p>This MOA is entered into for the purpose of: <strong>${purposeLabels[purpose]}</strong></p>

                    ${workArrangement ? `
                    <p><strong>ARTICLE II - WORK ARRANGEMENT</strong></p>
                    <p>The PARTNER shall operate under the following arrangement: <strong>${workArrangementLabels[workArrangement]}</strong></p>
                    ` : ''}

                    <p><strong>ARTICLE ${workArrangement ? 'III' : 'II'} - TERM</strong></p>
                    <p>This Agreement shall be effective from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>,
                    unless sooner terminated by either party upon thirty (30) days prior written notice.</p>

                    ${fee ? `
                    <p><strong>ARTICLE ${workArrangement ? 'IV' : 'III'} - FEES</strong></p>
                    <p>The PARTNER agrees to pay a monthly fee of <strong>â‚±${parseFloat(fee).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
                    for the duration of this agreement, payable on or before the 5th day of each month.</p>
                    ` : ''}

                    <p><strong>ARTICLE ${workArrangement && fee ? 'V' : (workArrangement || fee ? 'IV' : 'III')} - OBLIGATIONS</strong></p>
                    <p>Both parties shall comply with all applicable laws, rules, and regulations, and shall perform their
                    respective obligations under this Agreement in good faith.</p>

                    ${terms ? `
                    <p><strong>ARTICLE ${workArrangement && fee ? 'VI' : (workArrangement || fee ? 'V' : 'IV')} - SPECIAL TERMS</strong></p>
                    <p>${terms}</p>
                    ` : ''}

                    <p><strong>IN WITNESS WHEREOF</strong>, the parties have hereunto set their hands this _____ day of ____________, 20____.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px;">
                        <div style="text-align: center;">
                            <p style="margin-bottom: 40px;"><strong>FOR UP CEBU:</strong></p>
                            <div style="border-top: 1px solid #000; padding-top: 8px;">
                                <strong>DR. LIZA L. CORRO</strong><br>
                                <span style="font-size: 11px;">Chancellor</span>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <p style="margin-bottom: 40px;"><strong>FOR THE PARTNER:</strong></p>
                            <div style="border-top: 1px solid #000; padding-top: 8px;">
                                <strong>${representative.toUpperCase()}</strong><br>
                                <span style="font-size: 11px;">${position}</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 40px;">
                        <p><strong>SIGNED IN THE PRESENCE OF:</strong></p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 20px;">
                            <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center;">Witness 1</div>
                            <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center;">Witness 2</div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('moaPreviewContent').innerHTML = preview;
        }

        function printMoa() {
            const content = document.getElementById('moaPreviewContent').innerHTML;
            if (content.includes('Fill in the form')) {
                alert('Please generate a preview first');
                return;
            }

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>MOA - ${document.getElementById('moaCompanyName').value}</title>
                    <style>
                        body { font-family: 'Times New Roman', serif; padding: 40px; max-width: 800px; margin: 0 auto; }
                        @media print { body { padding: 20px; } }
                    </style>
                </head>
                <body>${content}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function downloadMoaAsPdf() {
            alert('PDF download feature requires a PDF library like jsPDF or server-side generation.\n\nFor now, please use the Print function and select "Save as PDF" as the destination.');
            printMoa();
        }

        // ========== PAYMENT DETAILS MODAL FUNCTIONS ==========

        function viewPaymentDetails(paymentId) {
            const payment = paymentSubmissionsData[paymentId];
            if (!payment) {
                alert('Payment submission not found');
                return;
            }

            currentPaymentId = paymentId;

            const statusColors = {
                'pending': { bg: '#FEF3C7', text: '#92400E' },
                'under_review': { bg: '#DBEAFE', text: '#1E40AF' },
                'approved': { bg: '#DCFCE7', text: '#166534' },
                'rejected': { bg: '#FEE2E2', text: '#991B1B' }
            };
            const color = statusColors[payment.status] || { bg: '#E5E7EB', text: '#374151' };

            const paymentMethodLabels = {
                'bank_transfer': 'ðŸ¦ Bank Transfer',
                'bank_deposit': 'ðŸ’µ Bank Deposit',
                'gcash': '<img src="/images/gcash.jpg" alt="GCash" style="height: 16px; width: auto; vertical-align: middle; margin-right: 4px;">GCash',
                'maya': 'ðŸ“± Maya',
                'check': 'ðŸ“„ Check Payment',
                'cash': 'ðŸ’° Cash'
            };
            const methodLabel = paymentMethodLabels[payment.payment_method] || payment.payment_method || 'N/A';

            const content = `
                <div style="display: flex; gap: 24px;">
                    <!-- Left side: Payment Details -->
                    <div style="flex: 1; display: grid; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                            <div>
                                <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                                <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${payment.tracking_code}</div>
                            </div>
                            <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                ${payment.status === 'approved' ? 'VERIFIED' : payment.status.replace('_', ' ').toUpperCase()}
                            </span>
                        </div>

                        <div style="background: linear-gradient(135deg, #10B981, #059669); color: white; padding: 16px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 12px; opacity: 0.9;">Payment Amount</div>
                            <div style="font-size: 28px; font-weight: 700;">â‚±${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                            <div style="font-size: 13px; margin-top: 4px;">Invoice #${payment.invoice_number}</div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Company Name</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.company_name}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Contact Person</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.contact_person}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Email</div>
                                <div style="font-size: 13px;">${payment.email}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Phone</div>
                                <div style="font-size: 13px;">${payment.phone || 'N/A'}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Payment Method</div>
                                <div style="font-weight: 600; font-size: 13px;">${methodLabel}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Payment Date</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.payment_date || 'N/A'}</div>
                            </div>
                        </div>

                        ${payment.notes ? `
                        <div>
                            <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px;">Submitter Notes</div>
                            <div style="background: #F3F4F6; padding: 10px; border-radius: 8px; font-size: 13px;">${payment.notes}</div>
                        </div>
                        ` : ''}

                        ${payment.admin_notes ? `
                        <div>
                            <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                            <div style="background: #FEF3C7; padding: 10px; border-radius: 8px; font-size: 13px;">${payment.admin_notes}</div>
                        </div>
                        ` : ''}

                        <div style="display: flex; gap: 16px; font-size: 11px; color: #6B7280;">
                            <div><i class="fas fa-calendar"></i> Submitted: ${payment.created_at}</div>
                            ${payment.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Verified: ${payment.reviewed_at}</div>` : ''}
                        </div>
                    </div>

                    <!-- Right side: Payment Proof -->
                    ${payment.payment_proof_path ? `
                    <div style="width: 280px; flex-shrink: 0;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px; font-weight: 600;">Payment Proof</div>
                        <a href="/storage/${payment.payment_proof_path}" target="_blank" style="display: block; border: 2px solid #E5E7EB; border-radius: 12px; overflow: hidden; transition: all 0.3s ease; height: calc(100% - 28px);" onmouseover="this.style.borderColor='#6366F1'" onmouseout="this.style.borderColor='#E5E7EB'">
                            <img src="/storage/${payment.payment_proof_path}" alt="Payment Proof" style="width: 100%; height: 100%; object-fit: contain; background: #F9FAFB;" onerror="this.parentElement.innerHTML='<div style=\'padding: 40px 20px; text-align: center; color: #6366F1; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;\'><i class=\'fas fa-file-pdf\' style=\'font-size: 48px; margin-bottom: 8px;\'></i><span>Click to view document</span></div>'">
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('paymentDetailsContent').innerHTML = content;
            document.getElementById('paymentDetailsModal').style.display = 'flex';
        }

        function closePaymentDetailsModal() {
            document.getElementById('paymentDetailsModal').style.display = 'none';
            currentPaymentId = null;
        }

        function openReviewPaymentModal() {
            const payment = paymentSubmissionsData[currentPaymentId];
            if (!payment) return;

            document.getElementById('reviewPaymentId').value = currentPaymentId;
            document.getElementById('reviewPaymentInfo').innerHTML = `
                <strong>${payment.tracking_code}</strong><br>
                ${payment.company_name} - â‚±${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}
            `;
            document.getElementById('reviewPaymentAction').value = '';
            document.getElementById('reviewPaymentNotes').value = '';

            closePaymentDetailsModal();
            document.getElementById('reviewPaymentModal').style.display = 'flex';
        }

        function closeReviewPaymentModal() {
            document.getElementById('reviewPaymentModal').style.display = 'none';
        }

        function submitPaymentReview() {
            const paymentId = document.getElementById('reviewPaymentId').value;
            const action = document.getElementById('reviewPaymentAction').value;
            const notes = document.getElementById('reviewPaymentNotes').value;

            if (!action) {
                alert('Please select a verification action');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewPaymentModal .btn-modal.primary');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }

            fetch(`/admin/submissions/${paymentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: action,
                    admin_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Payment ${data.submission.tracking_code} has been ${action === 'approved' ? 'verified' : action === 'rejected' ? 'rejected' : 'updated'}!`);
                    closeReviewPaymentModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update payment submission');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the payment submission');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // ========== ROOM ISSUE DETAILS MODAL FUNCTIONS ==========

        function viewRoomIssueDetails(issueId) {
            // Try both string and number keys
            const issue = roomIssuesData[issueId] || roomIssuesData[String(issueId)] || roomIssuesData[Number(issueId)];
            if (!issue) {
                alert('Issue not found');
                return;
            }

            currentIssueId = issueId;

            const statusColors = {
                'pending': { bg: '#FEE2E2', text: '#991B1B' },
                'in_progress': { bg: '#FEF3C7', text: '#92400E' },
                'resolved': { bg: '#DCFCE7', text: '#166534' },
                'closed': { bg: '#E5E7EB', text: '#374151' }
            };
            const color = statusColors[issue.status] || { bg: '#E5E7EB', text: '#374151' };

            const priorityColors = {
                'urgent': '#991B1B',
                'high': '#DC2626',
                'medium': '#F59E0B',
                'low': '#228B22'
            };

            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${issue.tracking_code}</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span style="background: ${priorityColors[issue.priority] || '#6B7280'}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                ${issue.priority.toUpperCase()}
                            </span>
                            <span style="background: ${color.bg}; color: ${color.text}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                ${issue.status_label}
                            </span>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; padding: 16px; border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 12px; opacity: 0.9;">Room Number</div>
                                <div style="font-size: 24px; font-weight: 700;">${issue.room_number}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 12px; opacity: 0.9;">Issue Type</div>
                                <div style="font-size: 16px; font-weight: 600;">${issue.issue_type_label}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Issue Description</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; white-space: pre-wrap;">${issue.description}</div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company Name</div>
                            <div style="font-weight: 600;">${issue.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Reported By</div>
                            <div style="font-weight: 600;">${issue.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${issue.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${issue.phone || 'N/A'}</div>
                        </div>
                    </div>

                    ${issue.photo_path ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Photo Evidence</div>
                        <a href="/storage/${issue.photo_path}" target="_blank">
                            <img src="/storage/${issue.photo_path}" alt="Issue Photo" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #E5E7EB;">
                        </a>
                    </div>
                    ` : ''}

                    ${issue.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Resolution Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${issue.admin_notes}</div>
                    </div>
                    ` : ''}

                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Reported: ${issue.created_at}</div>
                        ${issue.resolved_at ? `<div><i class="fas fa-check-circle"></i> Resolved: ${issue.resolved_at}</div>` : ''}
                    </div>
                </div>
            `;

            document.getElementById('roomIssueDetailsContent').innerHTML = content;
            document.getElementById('roomIssueDetailsModal').style.display = 'flex';
        }

        function closeRoomIssueDetailsModal() {
            document.getElementById('roomIssueDetailsModal').style.display = 'none';
            currentIssueId = null;
        }

        function openUpdateIssueStatusModal() {
            // Try both string and number keys
            const issue = roomIssuesData[currentIssueId] || roomIssuesData[String(currentIssueId)] || roomIssuesData[Number(currentIssueId)];
            if (!issue) return;

            document.getElementById('updateIssueId').value = currentIssueId;
            document.getElementById('updateIssueInfo').innerHTML = `
                <strong>${issue.tracking_code}</strong><br>
                Room ${issue.room_number} - ${issue.issue_type_label}
            `;
            document.getElementById('updateIssueNewStatus').value = issue.status;
            document.getElementById('updateIssueAssignee').value = '';
            document.getElementById('updateIssueNotes').value = issue.admin_notes || '';

            closeRoomIssueDetailsModal();
            document.getElementById('updateIssueStatusModal').style.display = 'flex';
        }

        function closeUpdateIssueStatusModal() {
            document.getElementById('updateIssueStatusModal').style.display = 'none';
        }

        function updateIssueStatus(issueId) {
            currentIssueId = issueId;
            // Try both string and number keys since JSON might have integer keys
            const issue = roomIssuesData[issueId] || roomIssuesData[String(issueId)] || roomIssuesData[Number(issueId)];

            console.log('Updating issue:', issueId, typeof issueId);
            console.log('Issue data:', issue);
            console.log('All issues data keys:', Object.keys(roomIssuesData));

            if (!issue) {
                alert('Issue data not found. Please refresh the page and try again.');
                return;
            }

            document.getElementById('updateIssueId').value = issueId;
            document.getElementById('updateIssueInfo').innerHTML = `
                <strong>${issue.tracking_code}</strong><br>
                Room ${issue.room_number} - ${issue.issue_type_label}
            `;
            document.getElementById('updateIssueNewStatus').value = issue.status;
            document.getElementById('updateIssueAssignee').value = '';
            document.getElementById('updateIssueNotes').value = issue.admin_notes || '';

            document.getElementById('updateIssueStatusModal').style.display = 'flex';
        }

        function submitIssueStatusUpdate() {
            const issueId = document.getElementById('updateIssueId').value;
            const newStatus = document.getElementById('updateIssueNewStatus').value;
            const assignee = document.getElementById('updateIssueAssignee').value;
            const notes = document.getElementById('updateIssueNotes').value;

            if (!issueId) {
                alert('No issue selected. Please try again.');
                return;
            }

            if (!newStatus) {
                alert('Please select a new status');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#updateIssueStatusModal .btn-modal.primary');
            if (!submitBtn) {
                console.error('Submit button not found');
                return;
            }
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            fetch(`/admin/room-issues/${issueId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    admin_notes: notes,
                    assignee: assignee
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Server error: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(`Issue ${data.issue.tracking_code} has been updated to: ${data.issue.status_label}!`);
                    closeUpdateIssueStatusModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update issue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the issue: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        // ===== ISSUES MANAGEMENT FUNCTIONS =====
        let issuesCurrentPage = 1;
        const issuesPerPage = 15;

        function filterIssues() {
            issuesCurrentPage = 1;
            applyIssuesFilter();
        }

        function searchIssues() {
            issuesCurrentPage = 1;
            applyIssuesFilter();
        }

        function applyIssuesFilter() {
            const statusFilter = document.getElementById('issueStatusFilter').value;
            const typeFilter = document.getElementById('issueTypeFilter').value;
            const priorityFilter = document.getElementById('issuePriorityFilter').value;
            const searchTerm = document.getElementById('issueSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.issue-row');

            let visibleRows = [];
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');
                const priority = row.getAttribute('data-priority');
                const text = row.textContent.toLowerCase();

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;
                const matchPriority = priorityFilter === 'all' || priority === priorityFilter;
                const matchSearch = !searchTerm || text.includes(searchTerm);

                if (matchStatus && matchType && matchPriority && matchSearch) {
                    visibleRows.push(row);
                }
                row.style.display = 'none';
            });

            // Paginate
            const totalPages = Math.ceil(visibleRows.length / issuesPerPage);
            if (issuesCurrentPage > totalPages && totalPages > 0) issuesCurrentPage = totalPages;
            const start = (issuesCurrentPage - 1) * issuesPerPage;
            const end = start + issuesPerPage;
            visibleRows.forEach((row, i) => {
                row.style.display = (i >= start && i < end) ? '' : 'none';
            });

            renderPaginationControls('issuesPagination', issuesCurrentPage, totalPages, visibleRows.length, 'goIssuesPage');
        }

        function goIssuesPage(page) {
            issuesCurrentPage = page;
            applyIssuesFilter();
        }

        // Initialize issues pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.issue-row').length > 0) {
                applyIssuesFilter();
            }
        });
        // ===== PROJECT PROGRESS FUNCTIONS =====
        let progressCurrentPage = 1;
        const progressPerPage = 15;

        // Generic modal close function
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function filterProgress() {
            progressCurrentPage = 1;
            applyProgressFilter();
        }

        function applyProgressFilter() {
            const statusFilter = document.getElementById('progressStatusFilter').value;
            const typeFilter = document.getElementById('progressTypeFilter').value;
            const rows = document.querySelectorAll('.progress-row');

            let visibleRows = [];
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;

                if (matchStatus && matchType) {
                    visibleRows.push(row);
                }
                row.style.display = 'none';
            });

            // Paginate
            const totalPages = Math.ceil(visibleRows.length / progressPerPage);
            if (progressCurrentPage > totalPages && totalPages > 0) progressCurrentPage = totalPages;
            const start = (progressCurrentPage - 1) * progressPerPage;
            const end = start + progressPerPage;
            visibleRows.forEach((row, i) => {
                row.style.display = (i >= start && i < end) ? '' : 'none';
            });

            renderPaginationControls('progressPagination', progressCurrentPage, totalPages, visibleRows.length, 'goProgressPage');
        }

        function goProgressPage(page) {
            progressCurrentPage = page;
            applyProgressFilter();
        }

        // Initialize progress pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.progress-row').length > 0) {
                applyProgressFilter();
            }
        });

        function viewProgressDetails(progressId) {
            viewAndRespondProgress(progressId);
        }

        function viewAndRespondProgress(progressId) {
            fetch(`/admin/progress/${progressId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const progress = data.progress;
                    const typeLabels = {
                        'development': 'Product Development',
                        'funding': 'Funding/Investment',
                        'partnership': 'Partnership',
                        'launch': 'Product Launch',
                        'achievement': 'Achievement/Award',
                        'other': 'Other Update'
                    };
                    const statusLabels = {
                        'submitted': 'Pending Review',
                        'reviewed': 'Reviewed',
                        'acknowledged': 'Acknowledged'
                    };

                    // Check if file is an image
                    const isImage = progress.file_path && /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(progress.file_path);

                    const content = `
                        <div style="display: grid; gap: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #7B1D3A, #A62450); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                        ${progress.startup.company_name.substring(0, 2).toUpperCase()}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1F2937;">${progress.startup.company_name}</div>
                                        <div style="font-size: 12px; color: #6B7280;">${progress.startup.startup_code}</div>
                                    </div>
                                </div>
                                <span style="padding: 6px 14px; background: #EDE9FE; color: #5B21B6; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    ${typeLabels[progress.milestone_type] || 'Update'}
                                </span>
                            </div>

                            <div>
                                <div style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">${progress.title}</div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px; line-height: 1.6; color: #374151;">${progress.description}</div>
                            </div>

                            ${isImage ? `
                            <div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Attached Image</div>
                                <div style="border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden; background: #F9FAFB;">
                                    <img src="${progress.file_url}" alt="Progress Image" style="width: 100%; max-height: 400px; object-fit: contain; cursor: pointer;" onclick="window.open('${progress.file_url}', '_blank')">
                                </div>
                                <div style="margin-top: 8px;">
                                    <a href="${progress.file_url}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: #DBEAFE; color: #1E40AF; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 13px;">
                                        <i class="fas fa-external-link-alt"></i>
                                        Open Full Size
                                    </a>
                                </div>
                            </div>
                            ` : (progress.file_path ? `
                            <div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Attached File</div>
                                <a href="${progress.file_url}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #DBEAFE; color: #1E40AF; border-radius: 8px; text-decoration: none; font-weight: 500;">
                                    <i class="fas fa-paperclip"></i>
                                    ${progress.original_filename}
                                </a>
                            </div>
                            ` : '')}

                            ${progress.admin_comment ? `
                            <div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Previous Admin Response</div>
                                <div style="background: #F0F9FF; border-left: 3px solid #0284C7; padding: 12px 16px; border-radius: 0 8px 8px 0; color: #0C4A6E;">
                                    ${progress.admin_comment}
                                </div>
                            </div>
                            ` : ''}

                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #E5E7EB; font-size: 12px; color: #6B7280;">
                                <span><i class="fas fa-clock"></i> Submitted: ${progress.created_at}</span>
                                <span style="padding: 4px 10px; background: ${progress.status === 'acknowledged' ? '#D1FAE5' : (progress.status === 'reviewed' ? '#DBEAFE' : '#FEF3C7')}; color: ${progress.status === 'acknowledged' ? '#065F46' : (progress.status === 'reviewed' ? '#1E40AF' : '#92400E')}; border-radius: 20px; font-weight: 500;">
                                    ${statusLabels[progress.status] || progress.status}
                                </span>
                            </div>
                        </div>
                    `;

                    document.getElementById('progressDetailContent').innerHTML = content;
                    document.getElementById('respondProgressId').value = progressId;
                    document.getElementById('respondProgressStatus').value = progress.status || 'reviewed';
                    document.getElementById('respondProgressComment').value = progress.admin_comment || '';
                    document.getElementById('progressCombinedModal').style.display = 'flex';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'Failed to load progress details');
            });
        }

        function respondToProgress(progressId) {
            viewAndRespondProgress(progressId);
        }

        function submitProgressResponse(event) {
            event.preventDefault();

            const progressId = document.getElementById('respondProgressId').value;
            const status = document.getElementById('respondProgressStatus').value;
            const comment = document.getElementById('respondProgressComment').value;

            // Get submit button and show loading state
            const submitBtn = document.querySelector('#progressRespondForm button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Submitting...';

            fetch(`/admin/progress/${progressId}/respond`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status, admin_comment: comment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal('progressCombinedModal');
                    showToast('success', 'Response Submitted!', 'Your response has been saved successfully.');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    showToast('error', 'Failed', data.message || 'Failed to submit response');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                showToast('error', 'Error', 'An error occurred while submitting the response');
            });
        }


        // ===== DOCUMENT TRACKING FUNCTIONS =====

        let documentsCurrentPage = 1;
        const documentsPerPage = 15;

        function filterDocuments() {
            documentsCurrentPage = 1;
            applyDocumentsFilter();
        }

        function searchDocuments() {
            documentsCurrentPage = 1;
            applyDocumentsFilter();
        }

        function applyDocumentsFilter() {
            const statusFilter = document.getElementById('documentStatusFilter').value;
            const typeFilter = document.getElementById('documentTypeFilter').value;
            const searchTerm = document.getElementById('documentSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.document-row');

            let visibleRows = [];
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');
                const text = row.textContent.toLowerCase();

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;
                const matchSearch = !searchTerm || text.includes(searchTerm);

                if (matchStatus && matchType && matchSearch) {
                    visibleRows.push(row);
                }
                row.style.display = 'none';
            });

            // Paginate
            const totalPages = Math.ceil(visibleRows.length / documentsPerPage);
            if (documentsCurrentPage > totalPages && totalPages > 0) documentsCurrentPage = totalPages;
            const start = (documentsCurrentPage - 1) * documentsPerPage;
            const end = start + documentsPerPage;
            visibleRows.forEach((row, i) => {
                row.style.display = (i >= start && i < end) ? '' : 'none';
            });

            renderPaginationControls('documentsPagination', documentsCurrentPage, totalPages, visibleRows.length, 'goDocumentsPage');
        }

        function goDocumentsPage(page) {
            documentsCurrentPage = page;
            applyDocumentsFilter();
        }

        // Initialize document pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.document-row').length > 0) {
                applyDocumentsFilter();
            }
        });

        // Review submission - routes to appropriate modal based on type
        function reviewSubmission(submissionId, type) {
            if (type === 'moa') {
                currentMoaId = submissionId;
                openReviewMoaModal();
            } else if (type === 'finance') {
                currentPaymentId = submissionId;
                openReviewPaymentModal();
            } else if (type === 'document') {
                currentDocId = submissionId;
                openReviewDocumentModal(submissionId);
            }
        }

        // ============================================
        // Booking & Scheduler Functions
        // ============================================

        // Booking data from server
                let schedulerBookings = [];
        let blockedDates = [];
        let schedulerEvents = [];

        let schedulerCurrentMonth = new Date().getMonth();
        let schedulerCurrentYear = new Date().getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // ========== CONFIRMATION MODAL SYSTEM ==========
        let confirmCallback = null;

        function showConfirmModal(options) {
            const { type = 'warning', title, message, confirmText = 'Confirm', onConfirm } = options;

            const modal = document.getElementById('confirmModal');
            const iconEl = document.getElementById('confirmModalIcon');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const confirmBtn = document.getElementById('confirmModalBtn');

            // Set icon based on type
            const icons = {
                warning: { icon: 'fa-exclamation-triangle', class: 'warning' },
                danger: { icon: 'fa-trash-alt', class: 'danger' },
                info: { icon: 'fa-question-circle', class: 'info' }
            };

            const iconConfig = icons[type] || icons.warning;
            iconEl.className = `confirm-modal-icon ${iconConfig.class}`;
            iconEl.innerHTML = `<i class="fas ${iconConfig.icon}"></i>`;

            titleEl.textContent = title;
            messageEl.textContent = message;
            confirmBtn.innerHTML = `<i class="fas fa-check"></i> ${confirmText}`;

            // Set button style based on type
            confirmBtn.className = 'confirm-modal-btn confirm';
            if (type === 'info') {
                confirmBtn.classList.add('success');
            }

            confirmCallback = onConfirm;
            modal.classList.add('active');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('active');
            confirmCallback = null;
        }

        function executeConfirmAction() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        }

        // Switch between booking tabs
        function switchBookingTab(tabName) {
            // Remove active from all tabs
            document.querySelectorAll('#scheduler .filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Find and activate the correct tab
            const tabs = document.querySelectorAll('#scheduler .filter-tab');
            tabs.forEach(tab => {
                const tabText = tab.textContent.toLowerCase();
                if ((tabName === 'pending' && tabText.includes('pending')) ||
                    (tabName === 'calendar' && tabText.includes('calendar')) ||
                    (tabName === 'events' && tabText.includes('event')) ||
                    (tabName === 'all' && tabText.includes('all booking')) ||
                    (tabName === 'archived' && tabText.includes('archived'))) {
                    tab.classList.add('active');
                }
            });

            // Hide all tab contents
            document.querySelectorAll('.booking-tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Show selected tab
            if (tabName === 'pending') {
                document.getElementById('pendingBookingsTab').style.display = 'block';
            } else if (tabName === 'calendar') {
                document.getElementById('calendarViewTab').style.display = 'block';
                loadSchedulerEvents();
            } else if (tabName === 'events') {
                document.getElementById('eventsTab').style.display = 'block';
                loadAdminEvents();
            } else if (tabName === 'all') {
                document.getElementById('allBookingsTab').style.display = 'block';
                // Initialize pagination for all bookings
                if (typeof initAllBookingsPagination === 'function') {
                    initAllBookingsPagination();
                }
            } else if (tabName === 'archived') {
                document.getElementById('archivedBookingsTab').style.display = 'block';
                // Initialize pagination for archived bookings
                if (typeof initArchivedBookingsPagination === 'function') {
                    initArchivedBookingsPagination();
                }
            }
        }

        // Booking Action Modal Functions
        function openBookingActionModal(id, agencyName, date, time, purpose, contactPerson, email, phone, notes, attachmentUrl, status, adminEmailed) {
            document.getElementById('currentBookingId').value = id;
            document.getElementById('modalAgencyName').textContent = agencyName;
            document.getElementById('modalBookingDate').textContent = date;
            document.getElementById('modalBookingTime').textContent = time;
            document.getElementById('modalPurpose').textContent = purpose;
            document.getElementById('modalContactPerson').textContent = contactPerson;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPhone').textContent = phone;
            document.getElementById('modalNotes').textContent = notes || 'No notes provided';

            // Handle attachment
            const attachmentSection = document.getElementById('modalAttachmentSection');
            const attachmentLink = document.getElementById('modalAttachmentLink');
            if (attachmentUrl) {
                attachmentSection.style.display = 'block';
                attachmentLink.href = attachmentUrl;
            } else {
                attachmentSection.style.display = 'none';
            }

            // Handle status badge and buttons
            const statusBadge = document.getElementById('modalStatusBadge');
            const approveBtn = document.getElementById('btnApproveBooking');
            const rejectBtn = document.getElementById('btnRejectBooking');
            const emailNotificationSection = document.getElementById('emailNotificationSection');
            const emailSentSection = document.getElementById('emailSentSection');

            // Reset sections
            emailNotificationSection.style.display = 'none';
            emailSentSection.style.display = 'none';

            if (status === 'pending') {
                statusBadge.style.background = '#FEF3C7';
                statusBadge.style.color = '#D97706';
                statusBadge.textContent = 'Pending';
                approveBtn.style.display = 'inline-flex';
                rejectBtn.style.display = 'inline-flex';
            } else if (status === 'approved') {
                statusBadge.style.background = '#D1FAE5';
                statusBadge.style.color = '#059669';
                statusBadge.textContent = 'Approved';
                approveBtn.style.display = 'none';
                rejectBtn.style.display = 'none';

                // Show email notification section based on admin_emailed status
                if (adminEmailed) {
                    emailSentSection.style.display = 'block';
                } else {
                    emailNotificationSection.style.display = 'block';
                    // Generate email preview
                    generateEmailPreview(agencyName, date, time, purpose, email);
                }
            } else {
                statusBadge.style.background = '#FEE2E2';
                statusBadge.style.color = '#DC2626';
                statusBadge.textContent = 'Rejected';
                approveBtn.style.display = 'none';
                rejectBtn.style.display = 'none';
            }

            document.getElementById('bookingActionModal').classList.add('active');
        }

        function generateEmailPreview(agencyName, date, time, purpose, email) {
            const subject = `Booking Approved - ${purpose} on ${date}`;
            const body = `Dear ${agencyName},

We are pleased to inform you that your booking request has been APPROVED.

ðŸ“… BOOKING DETAILS:
â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”â”
Date: ${date}
Time: ${time}
Purpose: ${purpose}

Please arrive at least 15 minutes before your scheduled time. If you need to reschedule or cancel, please contact us as soon as possible.

We look forward to seeing you!

Best regards,
UP Cebu Innovation & Technology Hub
University of the Philippines Cebu
ðŸ“§ info@upcebu.edu.ph
ðŸ“ž +63 32 123 4567`;

            document.getElementById('emailPreviewTo').textContent = email;
            document.getElementById('emailPreviewSubject').textContent = subject;
            document.getElementById('emailPreviewBody').textContent = body;

            // Store for copy/mail functions
            document.getElementById('currentBookingEmail').value = email;
            document.getElementById('currentBookingAgency').value = agencyName;
            document.getElementById('currentBookingDate').value = date;
            document.getElementById('currentBookingTime').value = time;
            document.getElementById('currentBookingPurpose').value = purpose;
        }

        function copyEmailContent() {
            const email = document.getElementById('currentBookingEmail').value;
            const subject = document.getElementById('emailPreviewSubject').textContent;
            const body = document.getElementById('emailPreviewBody').textContent;

            const fullContent = `To: ${email}\nSubject: ${subject}\n\n${body}`;

            navigator.clipboard.writeText(fullContent).then(() => {
                showToast('success', 'Copied!', 'Email content copied to clipboard. Paste it into your email application.');
            }).catch(err => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = fullContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('success', 'Copied!', 'Email content copied to clipboard.');
            });
        }

        function openMailClient() {
            const email = document.getElementById('currentBookingEmail').value;
            const subject = encodeURIComponent(document.getElementById('emailPreviewSubject').textContent);
            const body = encodeURIComponent(document.getElementById('emailPreviewBody').textContent);

            window.open(`mailto:${email}?subject=${subject}&body=${body}`, '_blank');
            showToast('info', 'Email App Opened', 'Your default email application should now open with the pre-filled content.');
        }

        function markAsEmailed() {
            const bookingId = document.getElementById('currentBookingId').value;

            fetch(`/admin/bookings/${bookingId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Marked as Emailed', 'Email notification status has been updated.');
                    // Update UI
                    document.getElementById('emailNotificationSection').style.display = 'none';
                    document.getElementById('emailSentSection').style.display = 'block';
                } else {
                    showToast('error', 'Error', data.message || 'Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while updating the status.');
            });
        }

        function closeBookingActionModal() {
            document.getElementById('bookingActionModal').classList.remove('active');
        }

        function confirmApproveBooking() {
            const bookingId = document.getElementById('currentBookingId').value;
            approveBooking(bookingId);
        }

        function confirmRejectBooking() {
            const bookingId = document.getElementById('currentBookingId').value;
            showConfirmModal({
                type: 'danger',
                title: 'Reject Booking?',
                message: 'This booking request will be rejected. The booker will not be able to use this time slot. This action cannot be undone.',
                confirmText: 'Reject Booking',
                onConfirm: () => rejectBooking(bookingId)
            });
        }

        // Approve booking
        function approveBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeBookingActionModal();
                    showToast('success', 'Booking Approved!', 'You can now send an email notification to the booker.', 3000);
                    // Remove from pending table
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    // Update pending count
                    updatePendingCount(-1);
                    // Reload to update all views
                    setTimeout(() => window.location.reload(), 3000);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to approve booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while approving the booking.');
            });
        }

        // Reject booking
        function rejectBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeBookingActionModal();
                    showToast('warning', 'Booking Rejected', 'The booking request has been rejected.');
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    updatePendingCount(-1);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to reject booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while rejecting the booking.');
            });
        }

        // Archive booking
        function archiveBooking(bookingId) {
            showConfirmModal({
                type: 'danger',
                title: 'Archive Booking?',
                message: 'This will archive the booking record. This action cannot be undone.',
                confirmText: 'Archive',
                onConfirm: () => executeArchiveBooking(bookingId)
            });
        }

        function executeArchiveBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Archived', 'Booking has been archived.');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to archive booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while archiving the booking.');
            });
        }

        // View booking details
        function viewBookingDetails(bookingId) {
            // Find booking in schedulerBookings data
            const booking = schedulerBookings.find(b => b.id === bookingId);

            if (booking) {
                openBookingActionModal(
                    booking.id,
                    booking.agency,
                    booking.formatted_date,
                    booking.time,
                    booking.event,
                    booking.contact,
                    booking.email,
                    booking.phone,
                    booking.purpose,
                    booking.attachment,
                    booking.status,
                    booking.admin_emailed
                );
            } else {
                showToast('error', 'Error', 'Booking not found');
            }
        }

        // Update pending count badge
        function updatePendingCount(change) {
            const countEl = document.getElementById('pendingBookingsCount');
            const badgeEl = document.getElementById('pendingBadge');
            if (countEl) {
                const newCount = Math.max(0, parseInt(countEl.textContent) + change);
                countEl.textContent = newCount;
                if (badgeEl) badgeEl.textContent = newCount;
            }
            // Check if pending table is empty
            const tbody = document.getElementById('pendingBookingsBody');
            if (tbody && tbody.querySelectorAll('tr:not(#noPendingRow)').length === 0) {
                tbody.innerHTML = `<tr id="noPendingRow">
                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                        <i class="fas fa-check-circle" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                        No pending booking requests
                    </td>
                </tr>`;
            }
        }

        // Filter bookings in All Bookings tab
        function filterBookings() {
            const searchValue = document.getElementById('searchBookings').value.toLowerCase();
            const statusFilter = document.getElementById('filterBookingStatus').value;

            document.querySelectorAll('#allBookingsBody .booking-row').forEach(row => {
                const searchText = row.getAttribute('data-search');
                const status = row.getAttribute('data-status');

                const matchesSearch = !searchValue || searchText.includes(searchValue);
                const matchesStatus = !statusFilter || status === statusFilter;

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        // Scheduler calendar navigation
        function schedulerPrevMonth() {
            schedulerCurrentMonth--;
            if (schedulerCurrentMonth < 0) {
                schedulerCurrentMonth = 11;
                schedulerCurrentYear--;
            }
            renderSchedulerCalendar();
        }

        function schedulerNextMonth() {
            schedulerCurrentMonth++;
            if (schedulerCurrentMonth > 11) {
                schedulerCurrentMonth = 0;
                schedulerCurrentYear++;
            }
            renderSchedulerCalendar();
        }

        // Load events for scheduler calendar
        async function loadSchedulerEvents() {
            try {
                const response = await fetch('/intern/events', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                schedulerEvents = data.events || [];
                renderSchedulerCalendar();
            } catch (error) {
                console.error('Error loading events:', error);
                renderSchedulerCalendar();
            }
        }

        // Render scheduler calendar
        function renderSchedulerCalendar() {
            const titleEl = document.getElementById('schedulerMonthTitle');
            const mainTitleEl = document.getElementById('schedulerMainTitle');
            const miniCalEl = document.getElementById('schedulerMiniCalendar');
            const mainCalEl = document.getElementById('schedulerCalendarGrid');

            const monthYear = `${monthNames[schedulerCurrentMonth]} ${schedulerCurrentYear}`;
            if (titleEl) titleEl.textContent = monthYear;
            if (mainTitleEl) mainTitleEl.textContent = monthYear;

            const firstDay = new Date(schedulerCurrentYear, schedulerCurrentMonth, 1).getDay();
            const daysInMonth = new Date(schedulerCurrentYear, schedulerCurrentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(schedulerCurrentYear, schedulerCurrentMonth, 0).getDate();
            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            // Generate mini calendar
            let miniHtml = '';
            for (let i = firstDay - 1; i >= 0; i--) {
                miniHtml += `<div class="mini-day other-month">${daysInPrevMonth - i}</div>`;
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${schedulerCurrentYear}-${String(schedulerCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;
                const hasBooking = schedulerBookings.some(b => b.date === dateString);
                const hasEvent = schedulerEvents.some(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });
                const blockedInfo = blockedDates.find(b => b.date === dateString);
                let classes = 'mini-day';
                if (isToday) classes += ' today';
                if (hasBooking || hasEvent) classes += ' has-events';
                if (blockedInfo) classes += ' blocked';

                let style = blockedInfo ? `background: ${blockedInfo.reason_color}20; color: ${blockedInfo.reason_color}; font-weight: 600;` : '';
                miniHtml += `<div class="${classes}" style="${style}" onclick="openBlockDateModal('${dateString}')">${day}</div>`;
            }
            const remainingMini = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingMini; i++) {
                miniHtml += `<div class="mini-day other-month">${i}</div>`;
            }
            if (miniCalEl) miniCalEl.innerHTML = miniHtml;

            // Generate main calendar
            let mainHtml = '';
            for (let i = firstDay - 1; i >= 0; i--) {
                mainHtml += `<div class="calendar-day other-month"><div class="day-number">${daysInPrevMonth - i}</div></div>`;
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${schedulerCurrentYear}-${String(schedulerCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;
                const dayBookings = schedulerBookings.filter(b => b.date === dateString);
                const blockedInfo = blockedDates.find(b => b.date === dateString);

                let classes = 'calendar-day clickable-day';
                if (isToday) classes += ' current-day';
                if (blockedInfo) classes += ' blocked-day';

                let eventsHtml = '';

                // Show blocked status
                if (blockedInfo) {
                    eventsHtml += `
                        <div class="calendar-event blocked" style="background: #FEE2E2; border-left: 3px solid #DC2626; color: #DC2626; font-weight: 600;">
                            <div class="event-name"><i class="fas fa-ban" style="margin-right: 4px;"></i>${blockedInfo.reason_label}</div>
                            ${blockedInfo.description ? `<div class="event-time">${blockedInfo.description}</div>` : ''}
                        </div>
                    `;
                }

                // Show bookings
                dayBookings.forEach(booking => {
                    eventsHtml += `
                        <div class="calendar-event meeting" onclick="event.stopPropagation(); viewBookingDetails(${booking.id})" style="cursor: pointer; background: #DBEAFE; border-left-color: #3B82F6;">
                            <div class="event-time">${booking.time.split(' - ')[0]}</div>
                            <div class="event-name">${booking.agency}</div>
                        </div>
                    `;
                });

                // Show events
                const dayEvents = schedulerEvents.filter(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });

                dayEvents.forEach(event => {
                    const eventStart = new Date(event.start_date);
                    const eventStartDate = eventStart.toISOString().split('T')[0];
                    const isStartDay = dateString === eventStartDate;
                    const timeStr = (event.all_day || !isStartDay) ? '' : eventStart.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                    const eventLabel = isStartDay ? escapeHtml(event.title) : `â†” ${escapeHtml(event.title)}`;
                    eventsHtml += `
                        <div class="calendar-event" onclick="event.stopPropagation(); editEvent(${event.id})" style="cursor: pointer; background: ${event.color}20; border-left-color: ${event.color};">
                            ${timeStr ? `<div class="event-time">${timeStr}</div>` : ''}
                            <div class="event-name">${eventLabel}</div>
                        </div>
                    `;
                });

                let dayStyle = blockedInfo ? `background: #FEE2E2; border: 2px solid #EF4444;` : '';
                mainHtml += `<div class="${classes}" style="${dayStyle}" onclick="openBlockDateModal('${dateString}')"><div class="day-number" ${blockedInfo ? 'style="color: #DC2626; font-weight: 700;"' : ''}>${day}</div>${eventsHtml}</div>`;
            }
            const remainingMain = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingMain; i++) {
                mainHtml += `<div class="calendar-day other-month"><div class="day-number">${i}</div></div>`;
            }
            if (mainCalEl) mainCalEl.innerHTML = mainHtml;
        }

        // ===== BLOCKED DATE MODAL FUNCTIONS =====
        let currentBlockDateId = null;

        function openBlockDateModal(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            const formattedDate = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

            document.getElementById('blockDateDisplay').value = formattedDate;
            document.getElementById('blockDateValue').value = dateString;
            document.getElementById('blockDateDays').value = '1';
            document.getElementById('blockDateReason').value = '';
            document.getElementById('blockDateDescription').value = '';
            document.getElementById('blockDateWarning').style.display = 'none';
            document.getElementById('blockedDateInfo').style.display = 'none';
            document.getElementById('blockDateRangeDisplay').style.display = 'none';
            document.getElementById('blockDateSubmitBtn').style.display = 'inline-flex';
            document.getElementById('unblockDateBtn').style.display = 'none';
            currentBlockDateId = null;
            updateBlockDateRange();

            // Check if date is already blocked
            const blockedInfo = blockedDates.find(b => b.date === dateString);
            if (blockedInfo) {
                currentBlockDateId = blockedInfo.id;
                document.getElementById('blockedDateInfo').style.display = 'block';
                document.getElementById('blockedDateInfoText').innerHTML = `
                    <strong>${blockedInfo.reason_label}</strong><br>
                    ${blockedInfo.description || 'No description provided'}
                `;
                document.getElementById('blockDateSubmitBtn').style.display = 'none';
                document.getElementById('unblockDateBtn').style.display = 'inline-flex';
            }

            // Check for existing bookings on this date
            const dateBookings = schedulerBookings.filter(b => b.date === dateString);
            if (dateBookings.length > 0 && !blockedInfo) {
                document.getElementById('blockDateWarning').style.display = 'block';
                let bookingsList = dateBookings.map(b => `â€¢ ${b.agency} (${b.time})`).join('<br>');
                document.getElementById('blockDateWarningText').innerHTML = `
                    There are ${dateBookings.length} approved booking(s) on this date:<br>${bookingsList}<br>
                    <small>Blocking this date will not cancel existing bookings.</small>
                `;
            }

            document.getElementById('blockDateModal').classList.add('active');
        }

        function closeBlockDateModal() {
            document.getElementById('blockDateModal').classList.remove('active');
            currentBlockDateId = null;
        }

        function updateBlockDateRange() {
            const startDate = document.getElementById('blockDateValue').value;
            const days = parseInt(document.getElementById('blockDateDays').value) || 1;

            if (!startDate || days < 1) {
                document.getElementById('blockDateRangeDisplay').style.display = 'none';
                return;
            }

            const start = new Date(startDate + 'T00:00:00');
            const end = new Date(start);
            end.setDate(end.getDate() + days - 1);

            const startFormatted = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const endFormatted = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            const rangeDisplay = document.getElementById('blockDateRangeDisplay');
            const rangeText = document.getElementById('blockDateRangeText');

            if (days === 1) {
                rangeText.textContent = `Blocking: ${startFormatted}`;
            } else {
                rangeText.textContent = `Blocking ${days} days: ${startFormatted} - ${endFormatted}`;
            }

            rangeDisplay.style.display = 'block';
        }

        function submitBlockDate() {
            const dateValue = document.getElementById('blockDateValue').value;
            const days = parseInt(document.getElementById('blockDateDays').value) || 1;
            const reason = document.getElementById('blockDateReason').value;
            const description = document.getElementById('blockDateDescription').value;

            if (!reason) {
                alert('Please select a reason.');
                return;
            }

            if (days < 1 || days > 365) {
                alert('Number of days must be between 1 and 365.');
                return;
            }

            // Generate all dates to block
            const datesToBlock = [];
            const startDate = new Date(dateValue + 'T00:00:00');

            for (let i = 0; i < days; i++) {
                const currentDate = new Date(startDate);
                currentDate.setDate(currentDate.getDate() + i);
                // Use local date formatting to avoid timezone issues
                const year = currentDate.getFullYear();
                const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                const day = String(currentDate.getDate()).padStart(2, '0');
                const dateString = `${year}-${month}-${day}`;
                datesToBlock.push(dateString);
            }

            // Block all dates
            const promises = datesToBlock.map(date =>
                fetch('/admin/blocked-dates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        blocked_date: date,
                        reason: reason,
                        description: description
                    })
                }).then(response => response.json())
            );

            Promise.all(promises)
                .then(results => {
                    const successCount = results.filter(data => data.success).length;
                    const failCount = results.length - successCount;

                    if (successCount > 0) {
                        // Add all successfully blocked dates to local array
                        results.forEach(data => {
                            if (data.success && data.blockedDate) {
                                blockedDates.push(data.blockedDate);
                            }
                        });

                        if (failCount === 0) {
                            alert(`Successfully blocked ${successCount} day${successCount > 1 ? 's' : ''}!`);
                        } else {
                            alert(`Blocked ${successCount} day${successCount > 1 ? 's' : ''}, but ${failCount} day${failCount > 1 ? 's were' : ' was'} already blocked or failed.`);
                        }

                        closeBlockDateModal();
                        renderSchedulerCalendar();
                    } else {
                        alert('Failed to block dates. Some or all dates may already be blocked.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while blocking the dates.');
                });
        }

        function unblockDate() {
            if (!currentBlockDateId) return;

            if (!confirm('Are you sure you want to unblock this date?')) return;

            fetch(`/admin/blocked-dates/${currentBlockDateId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'NhgNNjX4nYbAUp4oSTalBU0qnNYpeqa4Zpp2zNeb',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Date unblocked successfully!');
                    // Remove from local array
                    blockedDates = blockedDates.filter(b => b.id !== currentBlockDateId);
                    closeBlockDateModal();
                    renderSchedulerCalendar();
                } else {
                    alert(data.message || 'Failed to unblock date.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while unblocking the date.');
            });
        }

        // Initialize scheduler calendar when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Pre-render calendar if scheduler tab gets shown
            if (document.getElementById('scheduler').classList.contains('active')) {
                renderSchedulerCalendar();
            }

            // Load Digital Records when the page loads
            loadDigitalRecords();
            loadDigitalRecordsStats();
        });

        // ===== MOA MANAGEMENT FUNCTIONS =====
        let moaRequestsData = [];

        function loadMoaRequestsData() {
            fetch('/admin/moa-requests', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                moaRequestsData = Array.isArray(data) ? data : [];
                renderMoaRequestsTable();
                updateMoaStats();
            })
            .catch(error => {
                console.error('Error loading MOA requests:', error);
                document.getElementById('moaRequestsTableBody').innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #EF4444;">
                            <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 12px; display: block;"></i>
                            Failed to load MOA requests. Please try again.
                        </td>
                    </tr>
                `;
            });
        }

        function updateMoaStats() {
            const total = moaRequestsData.length;
            const pending = moaRequestsData.filter(m => m.status === 'pending' || m.status === 'under_review').length;
            const approved = moaRequestsData.filter(m => m.status === 'approved' || m.status === 'completed').length;
            const awaitingUpload = moaRequestsData.filter(m => (m.status === 'approved' || m.status === 'completed') && !m.admin_moa_document_path).length;

            document.getElementById('totalMoaCount').textContent = total;
            document.getElementById('pendingMoaReqCount').textContent = pending;
            document.getElementById('approvedMoaCount').textContent = approved;
            document.getElementById('awaitingUploadCount').textContent = awaitingUpload;
        }

        function renderMoaRequestsTable() {
            const tbody = document.getElementById('moaRequestsTableBody');
            const statusFilter = document.getElementById('moaStatusFilter').value;
            const documentFilter = document.getElementById('moaDocumentFilter').value;
            const searchTerm = document.getElementById('moaSearchInput').value.toLowerCase();

            let filteredData = moaRequestsData;

            // Apply status filter
            if (statusFilter !== 'all') {
                filteredData = filteredData.filter(m => m.status === statusFilter);
            }

            // Apply document filter
            if (documentFilter === 'uploaded') {
                filteredData = filteredData.filter(m => m.admin_moa_document_path);
            } else if (documentFilter === 'not_uploaded') {
                filteredData = filteredData.filter(m => !m.admin_moa_document_path);
            }

            // Apply search filter
            if (searchTerm) {
                filteredData = filteredData.filter(m =>
                    (m.tracking_code && m.tracking_code.toLowerCase().includes(searchTerm)) ||
                    (m.company_name && m.company_name.toLowerCase().includes(searchTerm)) ||
                    (m.moa_purpose && m.moa_purpose.toLowerCase().includes(searchTerm)) ||
                    (m.contact_person && m.contact_person.toLowerCase().includes(searchTerm))
                );
            }

            if (filteredData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 60px 40px;">
                            <i class="fas fa-file-contract" style="font-size: 48px; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                            <h4 style="font-size: 18px; font-weight: 600; color: #6B7280; margin-bottom: 8px;">No MOA Requests Found</h4>
                            <p style="font-size: 14px; color: #9CA3AF;">No MOA requests match your current filters.</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filteredData.map(moa => {
                const statusBadge = getStatusBadge(moa.status);
                const documentStatus = moa.admin_moa_document_path
                    ? `<span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;"><i class="fas fa-check-circle" style="margin-right: 4px;"></i>Uploaded</span>`
                    : `<span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;"><i class="fas fa-times-circle" style="margin-right: 4px;"></i>Not Uploaded</span>`;

                const submittedDate = moa.created_at ? new Date(moa.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';

                return `
                    <tr class="moa-row" data-status="${moa.status}" data-document="${moa.admin_moa_document_path ? 'uploaded' : 'not_uploaded'}">
                        <td style="padding: 14px 16px;">
                            <span style="font-weight: 700; color: #4F46E5;">${escapeHtml(moa.tracking_code || '')}</span>
                            <div style="margin-top: 4px;">
                                ${moa.source === 'startup' ?
                                    '<span style="background: #DBEAFE; color: #1E40AF; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600;"><i class=\'fas fa-user-check\' style=\'margin-right: 3px;\'></i>Startup Account</span>' :
                                    '<span style="background: #F3E8FF; color: #6D28D9; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600;"><i class=\'fas fa-globe\' style=\'margin-right: 3px;\'></i>Public Portal</span>'
                                }
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="font-weight: 600; color: #1F2937;">${escapeHtml(moa.company_name || '')}</div>
                            <div style="font-size: 12px; color: #6B7280;">${escapeHtml(moa.contact_person || '')} â€¢ ${escapeHtml(moa.email || '')}</div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="font-weight: 500; color: #374151;">${escapeHtml(moa.moa_purpose || '')}</div>
                            <div style="font-size: 12px; color: #9CA3AF; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${escapeHtml(moa.moa_details || '')}</div>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <span style="font-weight: 500; color: #374151;">${escapeHtml(moa.moa_duration || 'N/A')}</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            ${statusBadge}
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            ${documentStatus}
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <span style="font-size: 13px; color: #6B7280;">${submittedDate}</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button onclick="viewMoaDetails(${moa.id})" style="padding: 8px 12px; background: #EFF6FF; color: #2563EB; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openUploadMoaModal(${moa.id}, '${escapeHtml(moa.company_name || '')}')" style="padding: 8px 12px; background: #ECFDF5; color: #059669; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;" title="${moa.admin_moa_document_path ? 'Replace' : 'Upload'} MOA Document">
                                    <i class="fas fa-${moa.admin_moa_document_path ? 'sync-alt' : 'upload'}"></i>
                                </button>
                                ${moa.admin_moa_document_path ? `
                                <button onclick="downloadMoaDocument(${moa.id})" style="padding: 8px 12px; background: #F3E8FF; color: #7C3AED; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;" title="Download MOA">
                                    <i class="fas fa-download"></i>
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function getStatusBadge(status) {
            const badges = {
                'pending': '<span style="background: #FEF3C7; color: #D97706; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Pending</span>',
                'under_review': '<span style="background: #DBEAFE; color: #2563EB; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Under Review</span>',
                'approved': '<span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Approved</span>',
                'rejected': '<span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Rejected</span>',
                'completed': '<span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Completed</span>'
            };
            return badges[status] || '<span style="background: #F3F4F6; color: #6B7280; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Unknown</span>';
        }

        function filterMoaRequests() {
            renderMoaRequestsTable();
        }

        function searchMoaRequests() {
            renderMoaRequestsTable();
        }

        function refreshMoaData() {
            document.getElementById('moaRequestsTableBody').innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #6B7280;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
                        <p>Loading MOA requests...</p>
                    </td>
                </tr>
            `;
            loadMoaRequestsData();
        }

        function viewMoaDetails(moaId) {
            let moa = moaRequestsData.find(m => m.id == moaId);
            if (!moa) {
                moa = incubateeMoaData[moaId];
            }
            if (!moa) return;

            currentMoaId = moaId;

            document.getElementById('moaModalTrackingCode').textContent = moa.tracking_code || '';

            const statusBadge = getStatusBadge(moa.status);
            const submittedDate = moa.created_at ? new Date(moa.created_at).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
            const uploadedDate = moa.admin_moa_uploaded_at ? new Date(moa.admin_moa_uploaded_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) : null;

            let documentSection = '';
            if (moa.admin_moa_document_path) {
                documentSection = `
                    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; padding: 16px; margin-top: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: #D1FAE5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-pdf" style="color: #059669; font-size: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #065F46;">${escapeHtml(moa.admin_moa_document_filename || 'MOA Document')}</div>
                                    <div style="font-size: 12px; color: #6B7280;">Uploaded: ${uploadedDate}</div>
                                </div>
                            </div>
                            <button onclick="downloadMoaDocument(${moa.id})" style="padding: 8px 16px; background: #059669; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;">
                                <i class="fas fa-download" style="margin-right: 6px;"></i>Download
                            </button>
                        </div>
                    </div>
                `;
            } else {
                documentSection = `
                    <div style="background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 10px; padding: 16px; margin-top: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: #FDE68A; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-triangle" style="color: #D97706; font-size: 18px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #92400E;">MOA Document Not Yet Uploaded</div>
                                <div style="font-size: 12px; color: #B45309;">Admin has not uploaded the signed MOA document yet.</div>
                            </div>
                        </div>
                    </div>
                `;
            }

            document.getElementById('moaDetailsContent').innerHTML = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Company Name</label>
                        <div style="font-size: 15px; font-weight: 600; color: #1F2937; margin-top: 4px;">${escapeHtml(moa.company_name || '')}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Status</label>
                        <div style="margin-top: 4px;">${statusBadge}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Contact Person</label>
                        <div style="font-size: 15px; font-weight: 500; color: #1F2937; margin-top: 4px;">${escapeHtml(moa.contact_person || '')}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Email</label>
                        <div style="font-size: 15px; font-weight: 500; color: #1F2937; margin-top: 4px; word-break: break-all;">${escapeHtml(moa.email || '')}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">MOA Purpose</label>
                        <div style="font-size: 15px; font-weight: 500; color: #1F2937; margin-top: 4px;">${escapeHtml(moa.moa_purpose || '')}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Duration</label>
                        <div style="font-size: 15px; font-weight: 500; color: #1F2937; margin-top: 4px;">${escapeHtml(moa.moa_duration || 'N/A')}</div>
                    </div>
                </div>
                ${moa.payment_start_date || moa.payment_end_date ? `
                <div style="margin-top: 16px; background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #1E40AF; margin-bottom: 8px;"><i class="fas fa-calendar-alt" style="margin-right: 6px;"></i>Payment Period</h4>
                    <div style="display: flex; gap: 20px; font-size: 14px; color: #374151;">
                        <div><strong>Start:</strong> ${moa.payment_start_date ? new Date(moa.payment_start_date).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'}) : 'N/A'}</div>
                        <div><strong>End:</strong> ${moa.payment_end_date ? new Date(moa.payment_end_date).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'}) : 'N/A'}</div>
                    </div>
                </div>
                ` : ''}
                ${moa.rejection_remarks ? `
                <div style="margin-top: 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; padding: 14px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #991B1B; margin-bottom: 8px;"><i class="fas fa-comment-alt" style="margin-right: 6px;"></i>Rejection Remarks</h4>
                    <div style="font-size: 14px; color: #7F1D1D;">${escapeHtml(moa.rejection_remarks)}</div>
                </div>
                ` : ''}
                <div style="margin-top: 20px;">
                    <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">MOA Details</label>
                    <div style="font-size: 14px; color: #374151; margin-top: 4px; background: #F9FAFB; padding: 12px; border-radius: 8px; max-height: 150px; overflow-y: auto;">${escapeHtml(moa.moa_details || 'No details provided.')}</div>
                </div>
                <div style="margin-top: 16px;">
                    <label style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Submitted On</label>
                    <div style="font-size: 14px; color: #374151; margin-top: 4px;">${submittedDate}</div>
                </div>
                ${documentSection}
                <div style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-end;">
                    <button onclick="openUploadMoaModal(${moa.id}, '${escapeHtml(moa.company_name || '')}')" style="padding: 10px 16px; background: linear-gradient(135deg, #059669, #10B981); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                        <i class="fas fa-${moa.admin_moa_document_path ? 'sync-alt' : 'upload'}"></i>
                        <span>${moa.admin_moa_document_path ? 'Replace MOA' : 'Upload MOA'}</span>
                    </button>
                </div>
            `;

            document.getElementById('moaDetailsModal').style.display = 'flex';
        }

        function closeMoaDetailsModal() {
            document.getElementById('moaDetailsModal').style.display = 'none';
            currentMoaId = null;
        }

        function openUploadMoaModal(moaId, companyName) {
            document.getElementById('uploadMoaId').value = moaId;
            document.getElementById('uploadMoaCompanyName').textContent = companyName;
            document.getElementById('moaFileInput').value = '';
            document.getElementById('uploadMoaNotes').value = '';
            document.getElementById('moaFilePreview').style.display = 'none';
            document.getElementById('moaDropZone').style.display = 'block';
            document.getElementById('uploadMoaModal').style.display = 'flex';
        }

        function closeUploadMoaModal() {
            document.getElementById('uploadMoaModal').style.display = 'none';
        }

        function handleMoaFileDrop(event) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file) {
                processMoaFile(file);
            }
            document.getElementById('moaDropZone').style.borderColor = '#D1D5DB';
            document.getElementById('moaDropZone').style.background = '#F9FAFB';
        }

        function handleMoaFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                processMoaFile(file);
            }
        }

        function processMoaFile(file) {
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (!allowedTypes.includes(file.type)) {
                alert('Please upload a PDF, DOC, or DOCX file.');
                return;
            }

            if (file.size > maxSize) {
                alert('File size must be less than 10MB.');
                return;
            }

            // Create a DataTransfer to set the file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('moaFileInput').files = dataTransfer.files;

            document.getElementById('moaFileName').textContent = file.name;
            document.getElementById('moaFileSize').textContent = formatFileSize(file.size);
            document.getElementById('moaDropZone').style.display = 'none';
            document.getElementById('moaFilePreview').style.display = 'block';
        }

        function clearMoaFile() {
            document.getElementById('moaFileInput').value = '';
            document.getElementById('moaFilePreview').style.display = 'none';
            document.getElementById('moaDropZone').style.display = 'block';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        document.getElementById('uploadMoaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const moaId = document.getElementById('uploadMoaId').value;
            const fileInput = document.getElementById('moaFileInput');
            const adminNotes = document.getElementById('uploadMoaNotes').value;
            const submitBtn = document.getElementById('uploadMoaSubmitBtn');

            if (!fileInput.files[0]) {
                alert('Please select a file to upload.');
                return;
            }

            // Set loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Uploading...</span>';

            const formData = new FormData();
            formData.append('moa_document', fileInput.files[0]);
            formData.append('admin_notes', adminNotes);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const response = await fetch(`/admin/moa-requests/${moaId}/upload-document`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'MOA document uploaded successfully!');
                    closeUploadMoaModal();
                    closeMoaDetailsModal();
                    loadMoaRequestsData();
                } else {
                    alert(result.message || 'Failed to upload MOA document.');
                }
            } catch (error) {
                console.error('Error uploading MOA:', error);
                alert('An error occurred while uploading the document.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload"></i> <span>Upload MOA</span>';
            }
        });

        function downloadMoaDocument(moaId) {
            window.open(`/admin/moa-requests/${moaId}/download-document`, '_blank');
        }

        // Digital Records Functions
        let currentFolder = 'root';
        let currentPath = '';
        let viewMode = 'grid'; // 'grid' or 'list'
        let folderHistory = [];

        function loadDigitalRecordsStats() {
            const totalFoldersEl = document.getElementById('dr-total-folders');
            const totalFilesEl = document.getElementById('dr-total-files');
            const storageUsedEl = document.getElementById('dr-storage-used');
            const recentUploadsEl = document.getElementById('dr-recent-uploads');

            if (!totalFoldersEl || !totalFilesEl || !storageUsedEl || !recentUploadsEl) {
                return;
            }

            const formatBytes = (bytes) => {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                const value = (bytes / Math.pow(k, i)).toFixed(2);
                return `${value} ${sizes[i]}`;
            };

            fetch('/admin/documents/stats', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.ok ? response.json() : Promise.reject('Failed to load stats'))
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Unable to load stats');
                }

                totalFoldersEl.textContent = data.folders ?? '--';
                totalFilesEl.textContent = data.files ?? '--';
                storageUsedEl.textContent = data.storage_human || formatBytes(data.storage_bytes || 0);
                recentUploadsEl.textContent = data.recent_uploads ?? '--';
            })
            .catch(error => {
                console.error('Error loading digital records stats:', error);
                totalFoldersEl.textContent = '--';
                totalFilesEl.textContent = '--';
                storageUsedEl.textContent = '--';
                recentUploadsEl.textContent = '--';
            });
        }

        function loadDigitalRecords() {
            if (currentPath === '') {
                loadRootFolders();
            } else {
                loadFolderContents(currentPath);
            }
        }

        function loadRootFolders() {
            fetch('/admin/documents/all-folders', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Combine shared folders and intern folders
                    const sharedFolders = data.shared_folders.map(f => ({
                        id: f.id,
                        name: f.name,
                        path: f.storage_path || f.path || `Shared/${f.name}`,
                        item_count: f.item_count || 0,
                        is_folder: true,
                        folder_type: 'shared',
                        color: f.color,
                        allowed_users: f.allowed_users
                    }));

                    const allFolders = [...sharedFolders, ...data.intern_folders];
                    displayItems(allFolders, []);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to load folders');
                }
            })
            .catch(error => {
                console.error('Error loading folders:', error);
                showToast('error', 'Error', 'An error occurred while loading folders');
            });
        }

        function loadFolderContents(path) {
            console.log('Loading folder contents for path:', path);
            fetch(`/admin/documents/contents?path=${encodeURIComponent(path)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Folder contents response:', data);
                if (data.success) {
                    console.log('Items received:', data.items);
                    console.log('Folders with IDs:', data.items.filter(i => i.is_folder).map(f => ({ name: f.name, id: f.id })));
                    console.log('Files:', data.items.filter(i => !i.is_folder));
                    displayItems(data.items.filter(i => i.is_folder), data.items.filter(i => !i.is_folder));
                } else {
                    showToast('error', 'Error', data.message || 'Failed to load folder contents');
                }
            })
            .catch(error => {
                console.error('Error loading folder contents:', error);
                showToast('error', 'Error', 'An error occurred while loading folder contents');
            });
        }

        function displayItems(folders, files) {
            const gridView = document.getElementById('grid-view');
            const listViewBody = document.getElementById('list-view-body');

            if (folders.length === 0 && files.length === 0) {
                gridView.innerHTML = `
                    <div style="grid-column: span 7; text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-folder-open" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">This folder is empty</p>
                    </div>
                `;
                listViewBody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 50px; color: #9CA3AF;">
                            <i class="fas fa-folder-open" style="font-size: 30px; margin-bottom: 16px;"></i>
                            <p>This folder is empty</p>
                        </td>
                    </tr>
                `;
                return;
            }

            // Grid View
            let gridHtml = '';
            folders.forEach(folder => {
                const displayName = formatFolderName(folder.name);
                const folderColor = folder.color || '#FFBF00';
                const deleteButton = folder.id ? `
                    <button class="delete-btn" onclick="event.stopPropagation(); deleteFolder(${folder.id}, '${escapeHtml(folder.name)}')"
                            style="position: absolute; top: 8px; right: 8px; background: #EF4444; color: white; border: none;
                                   border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center;
                                   justify-content: center; opacity: 0.9; transition: opacity 0.2s; z-index: 10;"
                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                    </button>
                ` : '';

                gridHtml += `
                    <div class="file-item folder-item" style="position: relative;">
                        ${deleteButton}
                        <div onclick="openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')">
                            <div class="file-icon folder-icon" style="color: ${folderColor};">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="file-name" title="${escapeHtml(folder.name)}">${escapeHtml(displayName)}</div>
                            <div class="file-meta">${folder.item_count} item(s)</div>
                        </div>
                    </div>
                `;
            });

            files.forEach(file => {
                const fileIcon = getFileIcon(file.name);
                gridHtml += `
                    <div class="file-item file-doc" style="position: relative;">
                        <button class="delete-btn" onclick="event.stopPropagation(); deleteFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')"
                                style="position: absolute; top: 8px; right: 8px; background: #EF4444; color: white; border: none;
                                       border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center;
                                       justify-content: center; opacity: 0.9; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                        </button>
                        <div onclick="viewFileDetails('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                            <div class="file-icon" style="${fileIcon.style}">
                                <i class="${fileIcon.icon}"></i>
                            </div>
                            <div class="file-name">${escapeHtml(file.name)}</div>
                            <div class="file-meta">${file.size} â€¢ ${file.modified}</div>
                        </div>
                    </div>
                `;
            });
            gridView.innerHTML = gridHtml;

            // List View
            let listHtml = '';
            folders.forEach(folder => {
                const displayName = formatFolderName(folder.name);
                const folderColor = folder.color || '#FFBF00';
                const deleteButtonList = folder.id ? `
                    <button class="action-btn" onclick="event.stopPropagation(); deleteFolder(${folder.id}, '${escapeHtml(folder.name)}')"
                            style="color: #EF4444;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ` : '';

                listHtml += `
                    <tr onclick="openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')" style="cursor: pointer;">
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-folder" style="font-size: 24px; color: ${folderColor};"></i>
                                <div>
                                    <span style="font-weight: 600;">${escapeHtml(displayName)}</span>
                                    <div style="font-size: 11px; color: #6B7280;">${escapeHtml(folder.name)}</div>
                                </div>
                            </div>
                        </td>
                        <td>Folder</td>
                        <td>${folder.item_count} items</td>
                        <td>-</td>
                        <td>
                            <button class="action-btn" onclick="event.stopPropagation(); openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')">
                                <i class="fas fa-folder-open"></i>
                            </button>
                            ${deleteButtonList}
                        </td>
                    </tr>
                `;
            });

            files.forEach(file => {
                const fileIcon = getFileIcon(file.name);
                listHtml += `
                    <tr style="cursor: pointer;">
                        <td onclick="viewFileDetails('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="${fileIcon.icon}" style="font-size: 20px; ${fileIcon.style}"></i>
                                <span>${escapeHtml(file.name)}</span>
                            </div>
                        </td>
                        <td>${getFileType(file.name)}</td>
                        <td>${file.size}</td>
                        <td>${file.modified}</td>
                        <td>
                            <button class="action-btn" onclick="downloadFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn" onclick="deleteFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')"
                                    style="color: #EF4444;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            listViewBody.innerHTML = listHtml;
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                'pdf': { icon: 'fas fa-file-pdf', style: 'color: #EF4444;' },
                'doc': { icon: 'fas fa-file-word', style: 'color: #2563EB;' },
                'docx': { icon: 'fas fa-file-word', style: 'color: #2563EB;' },
                'xls': { icon: 'fas fa-file-excel', style: 'color: #10B981;' },
                'xlsx': { icon: 'fas fa-file-excel', style: 'color: #10B981;' },
                'ppt': { icon: 'fas fa-file-powerpoint', style: 'color: #F59E0B;' },
                'pptx': { icon: 'fas fa-file-powerpoint', style: 'color: #F59E0B;' },
                'zip': { icon: 'fas fa-file-archive', style: 'color: #6B7280;' },
                'rar': { icon: 'fas fa-file-archive', style: 'color: #6B7280;' },
                'jpg': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'jpeg': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'png': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'gif': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'txt': { icon: 'fas fa-file-alt', style: 'color: #6B7280;' },
                'csv': { icon: 'fas fa-file-csv', style: 'color: #10B981;' },
            };
            return icons[ext] || { icon: 'fas fa-file', style: 'color: #9CA3AF;' };
        }

        function getFileType(filename) {
            const ext = filename.split('.').pop().toUpperCase();
            return ext + ' File';
        }

        function formatFolderName(folderName) {
            // Format intern folder names like "John_Doe_15" to "John Doe (ID: 15)"
            const match = folderName.match(/^(.+)_(\d+)$/);
            if (match) {
                const name = match[1].replace(/_/g, ' ');
                const id = match[2];
                return `${name} (ID: ${id})`;
            }
            // Otherwise just replace underscores with spaces
            return folderName.replace(/_/g, ' ');
        }

        function openFolder(path, name) {
            folderHistory.push({ path: currentPath, name: currentFolder });
            currentPath = path;
            currentFolder = name;

            const pathParts = path.split('/');
            let breadcrumb = '<i class="fas fa-home"></i> Root';
            pathParts.forEach((part, index) => {
                if (part) {
                    breadcrumb += ` > ${part.replace(/_/g, ' ')}`;
                }
            });

            document.getElementById('current-path').innerHTML = breadcrumb;
            document.getElementById('back-btn').style.display = 'flex';

            loadFolderContents(path);
        }

        function goBackFolder() {
            if (folderHistory.length > 0) {
                const previous = folderHistory.pop();
                currentPath = previous.path;
                currentFolder = previous.name;

                if (currentPath === '') {
                    document.getElementById('current-path').innerHTML = '<i class="fas fa-home"></i> Root';
                    document.getElementById('back-btn').style.display = 'none';
                    loadRootFolders();
                } else {
                    const pathParts = currentPath.split('/');
                    let breadcrumb = '<i class="fas fa-home"></i> Root';
                    pathParts.forEach(part => {
                        if (part) breadcrumb += ` > ${part.replace(/_/g, ' ')}`;
                    });
                    document.getElementById('current-path').innerHTML = breadcrumb;
                    loadFolderContents(currentPath);
                }
            }
        }

        function toggleViewMode() {
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');
            const viewIcon = document.getElementById('view-icon');

            if (viewMode === 'grid') {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                viewIcon.className = 'fas fa-th-large';
                viewMode = 'list';
            } else {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
                viewIcon.className = 'fas fa-th';
                viewMode = 'grid';
            }
        }

        function searchFiles(query) {
            if (query.length > 0) {
                console.log('Searching for:', query);
                // In production, this would filter the displayed files
            }
        }

        function openNewFolderModal() {
            document.getElementById('createFolderModal').style.display = 'flex';
            document.getElementById('folderName').value = '';
            document.getElementById('folderDescription').value = '';
            document.getElementById('folderSizeLimit').value = '100';
            
            // Reset selections
            document.querySelectorAll('#folderPermissionsContainer input[type="checkbox"]').forEach(cb => cb.checked = false);
            updatePermissionCounts();
            
            // Show loading, hide container
            document.getElementById('folderPermissionsLoading').style.display = 'block';
            document.getElementById('folderPermissionsContainer').style.display = 'none';
            
            // Fetch interns, team leaders, and startups
            loadFolderPermissionOptions();
        }

        function loadFolderPermissionOptions() {
            fetch('/admin/interns/list', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                populateFolderPermissions(data);
                document.getElementById('folderPermissionsLoading').style.display = 'none';
                document.getElementById('folderPermissionsContainer').style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading folder permissions:', error);
                document.getElementById('folderPermissionsLoading').innerHTML = '<p style="color: #EF4444;">Failed to load. Please try again.</p>';
            });
        }

        function populateFolderPermissions(data) {
            // Populate Interns by School
            const internsContainer = document.getElementById('internsBySchoolContainer');
            let internsHtml = '';
            
            if (Object.keys(data.internsBySchool).length === 0) {
                internsHtml = '<p style="color: #6B7280; text-align: center; padding: 10px;">No active interns found</p>';
            } else {
                for (const [school, interns] of Object.entries(data.internsBySchool)) {
                    internsHtml += `
                        <div class="permission-school-group">
                            <div class="permission-school-header" onclick="toggleSchoolPermission('intern-school-${escapeHtml(school).replace(/[^a-zA-Z0-9]/g, '')}')">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" class="school-select-all" data-school="${escapeHtml(school)}" data-type="intern" onclick="event.stopPropagation(); toggleSchoolSelectAll(this)" style="width: 16px; height: 16px;">
                                    <span style="font-weight: 500; color: #374151;">${escapeHtml(school)}</span>
                                    <span style="background: #E5E7EB; padding: 2px 6px; border-radius: 10px; font-size: 11px;">${interns.length}</span>
                                </div>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: #9CA3AF;"></i>
                            </div>
                            <div id="intern-school-${escapeHtml(school).replace(/[^a-zA-Z0-9]/g, '')}" class="permission-school-body" style="display: none;">
                                ${interns.map(intern => `
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="allowed_intern_ids" value="${intern.id}" onchange="updatePermissionCounts()">
                                        <span style="font-size: 13px; color: #374151;">${escapeHtml(intern.name)}</span>
                                    </label>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
            }
            internsContainer.innerHTML = internsHtml;

            // Populate Team Leaders by School
            const teamLeadersContainer = document.getElementById('teamLeadersBySchoolContainer');
            let teamLeadersHtml = '';
            
            if (Object.keys(data.teamLeadersBySchool).length === 0) {
                teamLeadersHtml = '<p style="color: #6B7280; text-align: center; padding: 10px;">No active team leaders found</p>';
            } else {
                for (const [school, teamLeaders] of Object.entries(data.teamLeadersBySchool)) {
                    teamLeadersHtml += `
                        <div class="permission-school-group">
                            <div class="permission-school-header" onclick="toggleSchoolPermission('tl-school-${escapeHtml(school).replace(/[^a-zA-Z0-9]/g, '')}')">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" class="school-select-all" data-school="${escapeHtml(school)}" data-type="team_leader" onclick="event.stopPropagation(); toggleSchoolSelectAll(this)" style="width: 16px; height: 16px;">
                                    <span style="font-weight: 500; color: #374151;">${escapeHtml(school)}</span>
                                    <span style="background: #E5E7EB; padding: 2px 6px; border-radius: 10px; font-size: 11px;">${teamLeaders.length}</span>
                                </div>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: #9CA3AF;"></i>
                            </div>
                            <div id="tl-school-${escapeHtml(school).replace(/[^a-zA-Z0-9]/g, '')}" class="permission-school-body" style="display: none;">
                                ${teamLeaders.map(tl => `
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="allowed_team_leader_ids" value="${tl.id}" onchange="updatePermissionCounts()">
                                        <span style="font-size: 13px; color: #374151;">${escapeHtml(tl.name)}</span>
                                    </label>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
            }
            teamLeadersContainer.innerHTML = teamLeadersHtml;

            // Populate Startups
            const startupsContainer = document.getElementById('startupsContainer');
            let startupsHtml = '';
            
            if (data.startups.length === 0) {
                startupsHtml = '<p style="color: #6B7280; text-align: center; padding: 10px;">No active startups found</p>';
            } else {
                startupsHtml = `
                    <div style="margin-bottom: 8px;">
                        <label class="permission-checkbox-item" style="background: #F3F4F6; border-radius: 6px;">
                            <input type="checkbox" id="selectAllStartups" onclick="toggleAllStartups(this)">
                            <span style="font-size: 13px; font-weight: 600; color: #374151;">Select All Startups</span>
                        </label>
                    </div>
                `;
                data.startups.forEach(startup => {
                    startupsHtml += `
                        <label class="permission-checkbox-item">
                            <input type="checkbox" name="allowed_startup_ids" value="${startup.id}" onchange="updatePermissionCounts()">
                            <span style="font-size: 13px; color: #374151;">${escapeHtml(startup.name)}</span>
                            <span style="font-size: 11px; color: #9CA3AF;">(${escapeHtml(startup.startup_code)})</span>
                        </label>
                    `;
                });
            }
            startupsContainer.innerHTML = startupsHtml;
        }

        function togglePermissionAccordion(accordionId) {
            const accordion = document.getElementById(accordionId);
            const icon = document.getElementById(accordionId + 'Icon');
            
            if (accordion.style.display === 'none') {
                accordion.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                accordion.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleSchoolPermission(schoolId) {
            const schoolBody = document.getElementById(schoolId);
            if (schoolBody) {
                schoolBody.style.display = schoolBody.style.display === 'none' ? 'block' : 'none';
            }
        }

        function toggleSchoolSelectAll(checkbox) {
            const school = checkbox.dataset.school;
            const type = checkbox.dataset.type;
            const inputName = type === 'intern' ? 'allowed_intern_ids' : 'allowed_team_leader_ids';
            
            // Find all checkboxes in this school group
            const parent = checkbox.closest('.permission-school-group');
            const checkboxes = parent.querySelectorAll(`input[name="${inputName}"]`);
            
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updatePermissionCounts();
        }

        function toggleAllStartups(checkbox) {
            document.querySelectorAll('input[name="allowed_startup_ids"]').forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updatePermissionCounts();
        }

        function updatePermissionCounts() {
            const internsCount = document.querySelectorAll('input[name="allowed_intern_ids"]:checked').length;
            const teamLeadersCount = document.querySelectorAll('input[name="allowed_team_leader_ids"]:checked').length;
            const startupsCount = document.querySelectorAll('input[name="allowed_startup_ids"]:checked').length;
            
            document.getElementById('internsSelectedCount').textContent = internsCount + ' selected';
            document.getElementById('teamLeadersSelectedCount').textContent = teamLeadersCount + ' selected';
            document.getElementById('startupsSelectedCount').textContent = startupsCount + ' selected';
        }

        function closeCreateFolderModal() {
            document.getElementById('createFolderModal').style.display = 'none';
        }

        function selectColor(btn) {
            document.querySelectorAll('.color-option').forEach(b => {
                b.classList.remove('selected');
                b.style.borderColor = 'transparent';
            });
            btn.classList.add('selected');
            btn.style.borderColor = btn.style.backgroundColor;
            document.getElementById('selectedColor').value = btn.getAttribute('data-color');
        }

        function submitCreateFolder(event) {
            event.preventDefault();

            const folderName = document.getElementById('folderName').value;
            const color = document.getElementById('selectedColor').value;
            const description = document.getElementById('folderDescription').value;
            const sizeLimit = document.getElementById('folderSizeLimit').value || 10;
            
            // Get selected intern IDs
            const allowedInternIds = Array.from(document.querySelectorAll('input[name="allowed_intern_ids"]:checked')).map(cb => parseInt(cb.value));
            
            // Get selected team leader IDs
            const allowedTeamLeaderIds = Array.from(document.querySelectorAll('input[name="allowed_team_leader_ids"]:checked')).map(cb => parseInt(cb.value));
            
            // Get selected startup IDs
            const allowedStartupIds = Array.from(document.querySelectorAll('input[name="allowed_startup_ids"]:checked')).map(cb => parseInt(cb.value));

            // Build allowed_users array based on selections
            const allowedUsers = [];
            if (allowedInternIds.length > 0) allowedUsers.push('intern');
            if (allowedTeamLeaderIds.length > 0) allowedUsers.push('team_leader');
            if (allowedStartupIds.length > 0) allowedUsers.push('startup');

            if (allowedUsers.length === 0) {
                showToast('error', 'Error', 'Please select at least one intern, team leader, or startup who can upload');
                return;
            }

            fetch('/admin/documents/create-folder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: folderName,
                    color: color,
                    description: description,
                    allowed_users: allowedUsers,
                    allowed_intern_ids: allowedInternIds,
                    allowed_team_leader_ids: allowedTeamLeaderIds,
                    allowed_startup_ids: allowedStartupIds,
                    size_limit_mb: parseInt(sizeLimit)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Success', 'Folder created successfully');
                    closeCreateFolderModal();
                    loadDigitalRecords();
                } else {
                    showToast('error', 'Error', data.message || 'Failed to create folder');
                }
            })
            .catch(error => {
                console.error('Error creating folder:', error);
                showToast('error', 'Error', 'An error occurred while creating folder');
            });
        }

        function openUploadFileModal() {
            // Check if we're in a folder
            if (!currentPath || currentPath === '') {
                showToast('warning', 'Select a Folder', 'Please navigate to a folder before uploading files.');
                return;
            }
            document.getElementById('uploadFileModal').style.display = 'flex';
            document.getElementById('adminFileUpload').value = '';
            document.getElementById('uploadFileList').innerHTML = '<p style="color: #9CA3AF; text-align: center;">No files selected</p>';
        }

        function closeUploadFileModal() {
            document.getElementById('uploadFileModal').style.display = 'none';
        }

        function handleAdminFileSelect(input) {
            const files = input.files;
            const listContainer = document.getElementById('uploadFileList');

            if (files.length === 0) {
                listContainer.innerHTML = '<p style="color: #9CA3AF; text-align: center;">No files selected</p>';
                return;
            }

            const maxSizeMB = 50;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'ppt', 'pptx', 'csv'];

            let html = '';
            for (let file of files) {
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                const extension = file.name.split('.').pop().toLowerCase();
                let status = 'valid';
                let statusIcon = '<i class="fas fa-check-circle" style="color: #10B981;"></i>';
                let statusText = '';

                if (file.size > maxSizeBytes) {
                    status = 'error';
                    statusIcon = '<i class="fas fa-exclamation-circle" style="color: #EF4444;"></i>';
                    statusText = `<span style="color: #EF4444; font-size: 12px;">Too large (max ${maxSizeMB} MB)</span>`;
                } else if (!allowedExtensions.includes(extension)) {
                    status = 'error';
                    statusIcon = '<i class="fas fa-exclamation-circle" style="color: #EF4444;"></i>';
                    statusText = '<span style="color: #EF4444; font-size: 12px;">Unsupported format</span>';
                }

                html += `
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border: 1px solid ${status === 'error' ? '#FCA5A5' : '#E5E7EB'}; border-radius: 8px; margin-bottom: 8px; background: ${status === 'error' ? '#FEF2F2' : '#F9FAFB'};">
                        <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0;">
                            ${statusIcon}
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-weight: 500; color: #1F2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(file.name)}</div>
                                <div style="font-size: 12px; color: #6B7280;">${fileSizeMB} MB</div>
                                ${statusText}
                            </div>
                        </div>
                    </div>
                `;
            }
            listContainer.innerHTML = html;
        }

        function submitUploadFiles() {
            const input = document.getElementById('adminFileUpload');
            const files = input.files;

            if (files.length === 0) {
                showToast('warning', 'No Files', 'Please select files to upload.');
                return;
            }

            const maxSizeMB = 50;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'ppt', 'pptx', 'csv'];

            let validFiles = [];
            let errorMessages = [];

            for (let file of files) {
                const extension = file.name.split('.').pop().toLowerCase();

                if (file.size > maxSizeBytes) {
                    const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                    errorMessages.push(`"${file.name}" is too large (${fileSizeMB} MB). Maximum: ${maxSizeMB} MB.`);
                    continue;
                }

                if (!allowedExtensions.includes(extension)) {
                    errorMessages.push(`"${file.name}" has unsupported format.`);
                    continue;
                }

                validFiles.push(file);
            }

            if (errorMessages.length > 0) {
                errorMessages.forEach(msg => showToast('error', 'Upload Error', msg));
            }

            if (validFiles.length === 0) {
                return;
            }

            // Upload valid files
            let uploadCount = 0;
            let successCount = 0;

            validFiles.forEach(file => {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('path', currentPath);

                fetch('/admin/documents/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => {
                    return response.text().then(text => {
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            if (response.status === 413 || text.includes('upload_max_filesize') || text.includes('post_max_size')) {
                                throw new Error(`File "${file.name}" is too large. Maximum allowed size is 50 MB.`);
                            }
                            throw new Error(`Upload failed for "${file.name}". Please try again.`);
                        }

                        if (!response.ok) {
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat();
                                throw new Error(errorMessages.map(msg => msg.includes('may not be greater than') ? `File "${file.name}" is too large. Maximum: 50 MB.` : msg).join(', '));
                            }
                            throw new Error(data.message || `Upload failed for "${file.name}"`);
                        }
                        return data;
                    });
                })
                .then(data => {
                    if (data.success) {
                        successCount++;
                    } else {
                        showToast('error', 'Upload Failed', data.message || `Failed to upload "${file.name}"`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Upload Error', error.message);
                })
                .finally(() => {
                    uploadCount++;
                    if (uploadCount === validFiles.length) {
                        if (successCount > 0) {
                            showToast('success', 'Upload Complete', `${successCount} file(s) uploaded successfully.`);
                            closeUploadFileModal();
                            loadFolderContents(currentPath);
                        }
                    }
                });
            });
        }

        function viewFileDetails(filePath, fileName) {
            currentPreviewFile = { path: filePath, name: fileName };
            const ext = fileName.split('.').pop().toLowerCase();
            const fileIcon = getFileIcon(fileName);
            const downloadUrl = `/admin/documents/download?path=${encodeURIComponent(filePath)}`;

            // Update modal header
            document.getElementById('filePreviewName').textContent = fileName;
            document.getElementById('filePreviewIcon').className = fileIcon.icon;
            document.getElementById('filePreviewIcon').style.cssText = fileIcon.style;

            // Determine preview type
            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            const pdfExts = ['pdf'];
            const textExts = ['txt', 'csv', 'log', 'json', 'xml', 'html', 'css', 'js'];
            const officeExts = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

            let previewContent = '';

            if (imageExts.includes(ext)) {
                previewContent = `
                    <div style="padding: 20px; text-align: center; width: 100%;">
                        <img src="${downloadUrl}" alt="${escapeHtml(fileName)}" style="max-width: 100%; max-height: 60vh; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);" onerror="this.parentElement.innerHTML='<div style=\\'text-align: center; color: #6B7280; padding: 40px;\\'><i class=\\'fas fa-exclamation-triangle\\' style=\\'font-size: 48px; margin-bottom: 12px; color: #F59E0B;\\'></i><p>Failed to load image</p></div>'">
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = 'Image Preview';
            } else if (pdfExts.includes(ext)) {
                previewContent = `
                    <iframe src="${downloadUrl}#toolbar=1&navpanes=0" style="width: 100%; height: 100%; min-height: 500px; border: none;" title="PDF Preview"></iframe>
                `;
                document.getElementById('filePreviewInfo').textContent = 'PDF Document';
            } else if (textExts.includes(ext)) {
                previewContent = `
                    <div style="padding: 20px; width: 100%;">
                        <div id="textPreviewContent" style="background: white; border-radius: 8px; padding: 20px; font-family: 'Consolas', 'Monaco', monospace; font-size: 13px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; max-height: 60vh; overflow: auto; border: 1px solid #E5E7EB;">
                            <div style="text-align: center; color: #6B7280;"><i class="fas fa-spinner fa-spin"></i> Loading content...</div>
                        </div>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = `${ext.toUpperCase()} File`;

                // Fetch text content
                fetch(downloadUrl)
                    .then(response => response.text())
                    .then(text => {
                        const textEl = document.getElementById('textPreviewContent');
                        if (textEl) {
                            const maxChars = 100000;
                            if (text.length > maxChars) {
                                text = text.substring(0, maxChars) + '\n\n... (File truncated. Download to see full content)';
                            }
                            textEl.textContent = text || '(Empty file)';
                        }
                    })
                    .catch(error => {
                        const textEl = document.getElementById('textPreviewContent');
                        if (textEl) {
                            textEl.innerHTML = '<div style="text-align: center; color: #EF4444;"><i class="fas fa-exclamation-circle"></i> Failed to load file content</div>';
                        }
                    });
            } else if (officeExts.includes(ext)) {
                previewContent = `
                    <div style="text-align: center; padding: 60px 40px;">
                        <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="${fileIcon.icon}" style="font-size: 48px; ${fileIcon.style}"></i>
                        </div>
                        <h3 style="color: #1F2937; margin-bottom: 8px; font-size: 20px;">${escapeHtml(fileName)}</h3>
                        <p style="color: #6B7280; margin-bottom: 24px; font-size: 14px;">Office documents cannot be previewed directly in the browser.</p>
                        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                            <a href="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + downloadUrl)}" target="_blank" style="padding: 12px 24px; background: linear-gradient(135deg, #2563EB, #1D4ED8); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-external-link-alt"></i> Open in Office Online
                            </a>
                            <button onclick="downloadCurrentFile()" style="padding: 12px 24px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = 'Office Document';
            } else {
                previewContent = `
                    <div style="text-align: center; padding: 60px 40px;">
                        <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #F3F4F6, #E5E7EB); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="${fileIcon.icon}" style="font-size: 48px; ${fileIcon.style}"></i>
                        </div>
                        <h3 style="color: #1F2937; margin-bottom: 8px; font-size: 20px;">${escapeHtml(fileName)}</h3>
                        <p style="color: #6B7280; margin-bottom: 24px; font-size: 14px;">Preview not available for this file type.</p>
                        <button onclick="downloadCurrentFile()" style="padding: 12px 24px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-download"></i> Download File
                        </button>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = ext.toUpperCase() + ' File';
            }

            document.getElementById('filePreviewContent').innerHTML = previewContent;
            document.getElementById('filePreviewMeta').innerHTML = `<i class="fas fa-folder" style="margin-right: 6px;"></i> ${escapeHtml(filePath)}`;
            document.getElementById('filePreviewModal').style.display = 'flex';
        }

        let currentPreviewFile = null;

        function closeFilePreview() {
            document.getElementById('filePreviewModal').style.display = 'none';
            currentPreviewFile = null;
        }

        function downloadCurrentFile() {
            if (currentPreviewFile) {
                downloadFile(currentPreviewFile.path, currentPreviewFile.name);
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('filePreviewModal').style.display === 'flex') {
                    closeFilePreview();
                }
                if (document.getElementById('bulkRejectModal').style.display === 'flex') {
                    closeBulkRejectModal();
                }
            }
        });

        function downloadFile(filePath, fileName) {
            window.location.href = `/admin/documents/download?path=${encodeURIComponent(filePath)}`;
        }

        function deleteFile(filePath, fileName) {
            if (!confirm(`Are you sure you want to delete "${fileName}"? This action cannot be undone.`)) {
                return;
            }

            fetch('/admin/documents/file', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ path: filePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Success', 'File deleted successfully');
                    // Reload current folder
                    if (currentPath === '') {
                        loadRootFolders();
                    } else {
                        loadFolderContents(currentPath);
                    }
                } else {
                    showToast('error', 'Error', data.message || 'Failed to delete file');
                }
            })
            .catch(error => {
                console.error('Error deleting file:', error);
                showToast('error', 'Error', 'An error occurred while deleting file');
            });
        }

        function deleteFolder(folderId, folderName) {
            console.log('Delete folder called with:', { folderId, folderName });

            if (!folderId) {
                showToast('error', 'Error', 'Folder ID is missing. Please refresh the page and try again.');
                return;
            }

            showConfirmModal({
                type: 'danger',
                title: 'Delete Folder',
                message: `Are you sure you want to delete folder "${folderName}"? This will delete all contents inside. This action cannot be undone.`,
                confirmText: 'Delete',
                onConfirm: () => {
                    fetch('/admin/documents/folder', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ folder_id: folderId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Success', 'Folder deleted successfully');
                            // Reload current view
                            if (currentPath === '') {
                                loadRootFolders();
                            } else {
                                // Go back one level if we're inside the deleted folder
                                goBackFolder();
                            }
                        } else {
                            showToast('error', 'Error', data.message || 'Failed to delete folder');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting folder:', error);
                        showToast('error', 'Error', 'An error occurred while deleting folder');
                    });
                }
            });
        }

        // Toggle school group table
        // ===== SCHEDULER PAGINATION =====

        // Pending Bookings Pagination
        let pendingCurrentPage = 1;
        let pendingTotalPages = 1;

        function initPendingBookingsPagination() {
            const tbody = document.getElementById('pendingBookingsBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr:not(#noPendingRow)');
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('pendingBookingsPagination').style.display = 'none';
                return;
            }

            pendingTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            updatePendingBookingsPagination();
        }

        function updatePendingBookingsPagination() {
            const tbody = document.getElementById('pendingBookingsBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(#noPendingRow)'));
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('pendingBookingsPagination').style.display = 'none';
                return;
            }

            document.getElementById('pendingBookingsPagination').style.display = 'flex';

            const start = (pendingCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('pendingShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('pendingShowingEnd').textContent = end;
            document.getElementById('pendingTotalItems').textContent = totalRows;

            document.getElementById('pendingPrevBtn').disabled = pendingCurrentPage === 1;
            document.getElementById('pendingPrevBtn').style.opacity = pendingCurrentPage === 1 ? '0.5' : '1';
            document.getElementById('pendingNextBtn').disabled = pendingCurrentPage >= pendingTotalPages;
            document.getElementById('pendingNextBtn').style.opacity = pendingCurrentPage >= pendingTotalPages ? '0.5' : '1';

            document.getElementById('pendingPageInfo').textContent = `${pendingCurrentPage} / ${pendingTotalPages}`;
        }

        function pendingPrevPage() {
            if (pendingCurrentPage > 1) {
                pendingCurrentPage--;
                updatePendingBookingsPagination();
            }
        }

        function pendingNextPage() {
            if (pendingCurrentPage < pendingTotalPages) {
                pendingCurrentPage++;
                updatePendingBookingsPagination();
            }
        }

        // All Bookings Pagination
        let allCurrentPage = 1;
        let allTotalPages = 1;

        function initAllBookingsPagination() {
            const tbody = document.getElementById('allBookingsBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr.booking-row');
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('allBookingsPagination').style.display = 'none';
                return;
            }

            allTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            updateAllBookingsPagination();
        }

        function updateAllBookingsPagination() {
            const tbody = document.getElementById('allBookingsBody');
            const rows = Array.from(tbody.querySelectorAll('tr.booking-row'));
            // Apply filters first
            const searchTerm = document.getElementById('searchBookings')?.value?.toLowerCase() || '';
            const statusFilter = document.getElementById('filterBookingStatus')?.value || '';
            const emailFilter = document.getElementById('filterEmailStatus')?.value || '';

            const visibleRows = rows.filter(row => {
                const matchesSearch = !searchTerm || row.dataset.search.includes(searchTerm);
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                let matchesEmail = true;
                if (emailFilter === 'sent') {
                    matchesEmail = row.dataset.emailed === 'yes';
                } else if (emailFilter === 'not-sent') {
                    matchesEmail = row.dataset.emailed === 'no' && row.dataset.status === 'approved';
                }
                return matchesSearch && matchesStatus && matchesEmail;
            });

            const totalRows = visibleRows.length;

            if (totalRows === 0) {
                document.getElementById('allBookingsPagination').style.display = 'none';
                rows.forEach(row => row.style.display = 'none');
                return;
            }

            // Reset to first page if current page is out of range
            allTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            if (allCurrentPage > allTotalPages) {
                allCurrentPage = 1;
            }

            document.getElementById('allBookingsPagination').style.display = 'flex';

            const start = (allCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show only filtered rows for current page
            visibleRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('allShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('allShowingEnd').textContent = end;
            document.getElementById('allTotalItems').textContent = totalRows;

            document.getElementById('allPrevBtn').disabled = allCurrentPage === 1;
            document.getElementById('allPrevBtn').style.opacity = allCurrentPage === 1 ? '0.5' : '1';
            document.getElementById('allNextBtn').disabled = allCurrentPage >= allTotalPages;
            document.getElementById('allNextBtn').style.opacity = allCurrentPage >= allTotalPages ? '0.5' : '1';

            document.getElementById('allPageInfo').textContent = `${allCurrentPage} / ${allTotalPages}`;
        }

        function allBookingsPrevPage() {
            if (allCurrentPage > 1) {
                allCurrentPage--;
                updateAllBookingsPagination();
            }
        }

        function allBookingsNextPage() {
            if (allCurrentPage < allTotalPages) {
                allCurrentPage++;
                updateAllBookingsPagination();
            }
        }

        // Override filterBookings to work with pagination
        const originalFilterBookings = window.filterBookings || function() {};
        window.filterBookings = function() {
            allCurrentPage = 1; // Reset to first page on filter change
            updateAllBookingsPagination();
        };

        // Archived Bookings Pagination
        let archivedCurrentPage = 1;
        let archivedTotalPages = 1;

        function initArchivedBookingsPagination() {
            const tbody = document.getElementById('archivedBookingsBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr.archived-booking-row');
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('archivedBookingsPagination').style.display = 'none';
                return;
            }

            archivedTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            updateArchivedBookingsPagination();
        }

        function updateArchivedBookingsPagination() {
            const tbody = document.getElementById('archivedBookingsBody');
            const rows = Array.from(tbody.querySelectorAll('tr.archived-booking-row'));
            const searchTerm = document.getElementById('searchArchivedBookings')?.value?.toLowerCase() || '';

            const visibleRows = rows.filter(row => {
                return !searchTerm || row.dataset.search.includes(searchTerm);
            });

            const totalRows = visibleRows.length;

            if (totalRows === 0) {
                document.getElementById('archivedBookingsPagination').style.display = 'none';
                rows.forEach(row => row.style.display = 'none');
                return;
            }

            archivedTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            if (archivedCurrentPage > archivedTotalPages) {
                archivedCurrentPage = 1;
            }

            document.getElementById('archivedBookingsPagination').style.display = 'flex';

            const start = (archivedCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            rows.forEach(row => row.style.display = 'none');

            visibleRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('archivedShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('archivedShowingEnd').textContent = end;
            document.getElementById('archivedTotalItems').textContent = totalRows;

            document.getElementById('archivedPrevBtn').disabled = archivedCurrentPage === 1;
            document.getElementById('archivedPrevBtn').style.opacity = archivedCurrentPage === 1 ? '0.5' : '1';
            document.getElementById('archivedNextBtn').disabled = archivedCurrentPage >= archivedTotalPages;
            document.getElementById('archivedNextBtn').style.opacity = archivedCurrentPage >= archivedTotalPages ? '0.5' : '1';

            document.getElementById('archivedPageInfo').textContent = `${archivedCurrentPage} / ${archivedTotalPages}`;
        }

        function archivedBookingsPrevPage() {
            if (archivedCurrentPage > 1) {
                archivedCurrentPage--;
                updateArchivedBookingsPagination();
            }
        }

        function archivedBookingsNextPage() {
            if (archivedCurrentPage < archivedTotalPages) {
                archivedCurrentPage++;
                updateArchivedBookingsPagination();
            }
        }

        function filterArchivedBookings() {
            archivedCurrentPage = 1;
            updateArchivedBookingsPagination();
        }

        // Switch time tab function
        function switchTimeTab(event, tabId) {
        function loadAdminEvents() {
            fetch('/intern/events', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                displayAdminEvents(data.events || []);
            })
            .catch(error => {
                console.error('Error loading events:', error);
                document.getElementById('eventsListContainer').innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                        <i class="fas fa-calendar" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No events found</p>
                    </div>
                `;
            });
        }

        function displayAdminEvents(events) {
            const container = document.getElementById('eventsListContainer');

            if (!events || events.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                        <i class="fas fa-calendar" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No events created yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Click "Create Event" to add your first event</p>
                    </div>
                `;
                return;
            }

            let html = '';
            events.forEach(event => {
                const startDate = new Date(event.start_date);
                const endDate = new Date(event.end_date);

                // Format date range
                const startDateOnly = startDate.toISOString().split('T')[0];
                const endDateOnly = endDate.toISOString().split('T')[0];
                const isSameDay = startDateOnly === endDateOnly;

                let dateStr;
                if (isSameDay) {
                    dateStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                } else {
                    const startFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    dateStr = `${startFormatted} - ${endFormatted}`;
                }

                const timeStr = event.all_day ? 'All Day' : `${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;

                html += `
                    <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #E5E7EB; border-left: 4px solid ${event.color};">
                        <div style="display: flex; justify-content: space-between; align-items: start; gap: 16px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${event.color}; border-radius: 50%;"></div>
                                    <h3 style="font-size: 18px; font-weight: 600; color: #1F2937; margin: 0;">${escapeHtml(event.title)}</h3>
                                </div>
                                ${event.description ? `<p style="color: #6B7280; font-size: 14px; margin-bottom: 12px;">${escapeHtml(event.description)}</p>` : ''}
                                <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 14px; color: #6B7280;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-calendar"></i>
                                        <span>${dateStr}</span>
                                    </div>
                                    ${!event.all_day ? `<div style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-clock"></i>
                                        <span>${timeStr}</span>
                                    </div>` : ''}
                                    ${event.location ? `<div style="display: flex; align-items: center; gap: 6px;"><i class="fas fa-map-marker-alt"></i><span>${escapeHtml(event.location)}</span></div>` : ''}
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button onclick="editEvent(${event.id})" style="padding: 8px 12px; background: #F3F4F6; border: none; border-radius: 6px; cursor: pointer; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteEvent(${event.id})" style="padding: 8px 12px; background: #FEE2E2; border: none; border-radius: 6px; cursor: pointer; color: #DC2626; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function toggleAllDayEvent() {
            const isAllDay = document.getElementById('eventAllDay').checked;
            const startInput = document.getElementById('eventStartDate');
            const endInput = document.getElementById('eventEndDate');
            const startLabel = document.getElementById('startDateLabel');
            const endLabel = document.getElementById('endDateLabel');

            if (isAllDay) {
                // Switch to date-only inputs
                const startValue = startInput.value ? startInput.value.split('T')[0] : '';
                const endValue = endInput.value ? endInput.value.split('T')[0] : '';

                startInput.type = 'date';
                endInput.type = 'date';
                startInput.value = startValue;
                endInput.value = endValue;

                startLabel.textContent = 'Start Date *';
                endLabel.textContent = 'End Date *';
            } else {
                // Switch to datetime inputs
                const startValue = startInput.value ? startInput.value + 'T09:00' : '';
                const endValue = endInput.value ? endInput.value + 'T17:00' : '';

                startInput.type = 'datetime-local';
                endInput.type = 'datetime-local';
                startInput.value = startValue;
                endInput.value = endValue;

                startLabel.textContent = 'Start Date & Time *';
                endLabel.textContent = 'End Date & Time *';
            }
        }

        function showCreateEventModal() {
            document.getElementById('eventId').value = '';
            document.getElementById('eventModalTitle').textContent = 'Create Event';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDeleteBtn').style.display = 'none';
            document.getElementById('eventDescription').value = '';
            document.getElementById('eventStartDate').value = '';
            document.getElementById('eventEndDate').value = '';
            document.getElementById('eventLocation').value = '';
            document.getElementById('eventColor').value = '#3B82F6';
            document.getElementById('eventAllDay').checked = false;

            // Reset to datetime-local inputs
            document.getElementById('eventStartDate').type = 'datetime-local';
            document.getElementById('eventEndDate').type = 'datetime-local';
            document.getElementById('startDateLabel').textContent = 'Start Date & Time *';
            document.getElementById('endDateLabel').textContent = 'End Date & Time *';

            document.getElementById('eventModal').style.display = 'flex';
        }

        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        async function editEvent(eventId) {
            try {
                const response = await fetch(`/intern/events`);
                const data = await response.json();
                const event = data.events.find(e => e.id === eventId);

                if (event) {
                    document.getElementById('eventId').value = event.id;
                    document.getElementById('eventModalTitle').textContent = 'Edit Event';
                    document.getElementById('eventTitle').value = event.title;
                    document.getElementById('eventDeleteBtn').style.display = 'flex';
                    document.getElementById('eventDescription').value = event.description || '';
                    document.getElementById('eventLocation').value = event.location || '';
                    document.getElementById('eventColor').value = event.color;
                    document.getElementById('eventAllDay').checked = event.all_day;

                    // Set input type and values based on all_day
                    if (event.all_day) {
                        document.getElementById('eventStartDate').type = 'date';
                        document.getElementById('eventEndDate').type = 'date';
                        document.getElementById('eventStartDate').value = event.start_date.split(' ')[0];
                        document.getElementById('eventEndDate').value = event.end_date.split(' ')[0];
                        document.getElementById('startDateLabel').textContent = 'Start Date *';
                        document.getElementById('endDateLabel').textContent = 'End Date *';
                    } else {
                        document.getElementById('eventStartDate').type = 'datetime-local';
                        document.getElementById('eventEndDate').type = 'datetime-local';
                        document.getElementById('eventStartDate').value = new Date(event.start_date).toISOString().slice(0, 16);
                        document.getElementById('eventEndDate').value = new Date(event.end_date).toISOString().slice(0, 16);
                        document.getElementById('startDateLabel').textContent = 'Start Date & Time *';
                        document.getElementById('endDateLabel').textContent = 'End Date & Time *';
                    }

                    document.getElementById('eventModal').style.display = 'flex';
                }
            } catch (error) {
                console.error('Error loading event:', error);
                showToast('error', 'Error', 'Failed to load event details');
            }
        }

        async function saveEvent() {
            const eventId = document.getElementById('eventId').value;
            const title = document.getElementById('eventTitle').value.trim();
            const description = document.getElementById('eventDescription').value.trim();
            const startDate = document.getElementById('eventStartDate').value;
            const endDate = document.getElementById('eventEndDate').value;
            const location = document.getElementById('eventLocation').value.trim();
            const color = document.getElementById('eventColor').value;
            const allDay = document.getElementById('eventAllDay').checked;

            if (!title || !startDate || !endDate) {
                showToast('error', 'Validation Error', 'Please fill in all required fields');
                return;
            }

            const data = {
                title,
                description,
                start_date: startDate,
                end_date: endDate,
                location,
                color,
                all_day: allDay
            };

            try {
                const url = eventId ? `/admin/events/${eventId}` : '/admin/events';
                const method = eventId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message || 'Event saved successfully');
                    closeEventModal();
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    const errors = result.errors;
                    if (errors) {
                        const firstError = Object.values(errors)[0][0];
                        showToast('error', 'Validation Error', firstError);
                    } else {
                        showToast('error', 'Error', 'Error saving event');
                    }
                }
            } catch (error) {
                console.error('Error saving event:', error);
                showToast('error', 'Error', 'An error occurred while saving the event');
            }
        }

        async function deleteEvent(eventId) {
            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message || 'Event deleted successfully');
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    showToast('error', 'Error', 'Error deleting event');
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                showToast('error', 'Error', 'An error occurred while deleting the event');
            }
        }

        async function deleteEventFromModal() {
            const eventId = document.getElementById('eventId').value;
            if (!eventId) return;

            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message || 'Event deleted successfully');
                    closeEventModal();
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    showToast('error', 'Error', 'Error deleting event');
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                showToast('error', 'Error', 'An error occurred while deleting the event');
            }
        }

        function escapeHtml(text) {

        // ===== ADMIN MODULE FUNCTIONS (END) =====
    
