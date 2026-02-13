@extends('startup.layout')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h1>Payment History</h1>
                <p>View your past payment records</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 8px;">
        <a href="{{ route('startup.billing') }}" style="padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ $statusFilter === 'all' ? 'background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; box-shadow: 0 4px 12px rgba(123,29,58,0.3);' : 'background: white; color: #6B7280; border: 1px solid #E5E7EB;' }}">
            All
        </a>
        <a href="{{ route('startup.billing', ['status' => 'pending']) }}" style="padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ $statusFilter === 'pending' ? 'background: linear-gradient(135deg, #D97706, #F59E0B); color: white; box-shadow: 0 4px 12px rgba(217,119,6,0.3);' : 'background: white; color: #6B7280; border: 1px solid #E5E7EB;' }}">
            Pending
        </a>
        <a href="{{ route('startup.billing', ['status' => 'approved']) }}" style="padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ $statusFilter === 'approved' ? 'background: linear-gradient(135deg, #059669, #10B981); color: white; box-shadow: 0 4px 12px rgba(5,150,105,0.3);' : 'background: white; color: #6B7280; border: 1px solid #E5E7EB;' }}">
            Approved
        </a>
        <a href="{{ route('startup.billing', ['status' => 'rejected']) }}" style="padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s; {{ $statusFilter === 'rejected' ? 'background: linear-gradient(135deg, #DC2626, #EF4444); color: white; box-shadow: 0 4px 12px rgba(220,38,38,0.3);' : 'background: white; color: #6B7280; border: 1px solid #E5E7EB;' }}">
            Rejected
        </a>
    </div>

    <!-- Payments Table -->
    <div class="form-card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #F9FAFB; border-bottom: 2px solid #E5E7EB;">
                        <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Tracking Code</th>
                        <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                        <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Amount</th>
                        <th style="padding: 14px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        <th style="padding: 14px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr style="border-bottom: 1px solid #F3F4F6; transition: background 0.2s;" onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='white'">
                            <td style="padding: 16px 20px;">
                                <span style="font-family: 'Courier New', monospace; font-size: 13px; font-weight: 600; color: #7B1D3A; background: rgba(123,29,58,0.08); padding: 4px 10px; border-radius: 6px;">
                                    {{ $payment->tracking_code ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 16px 20px;">
                                <div style="font-size: 14px; font-weight: 600; color: #1F2937;">{{ $payment->title ?? 'Payment' }}</div>
                                @if($payment->notes)
                                    <div style="font-size: 12px; color: #6B7280; margin-top: 2px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $payment->notes }}</div>
                                @endif
                            </td>
                            <td style="padding: 16px 20px; text-align: right;">
                                <span style="font-size: 15px; font-weight: 700; color: #1F2937;">₱{{ number_format($payment->amount ?? 0, 2) }}</span>
                            </td>
                            <td style="padding: 16px 20px; text-align: center;">
                                <span style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                    @if($payment->status === 'approved')
                                        background: #DCFCE7; color: #166534;
                                    @elseif($payment->status === 'pending')
                                        background: #FEF3C7; color: #92400E;
                                    @elseif($payment->status === 'rejected')
                                        background: #FEE2E2; color: #991B1B;
                                    @else
                                        background: #F3F4F6; color: #374151;
                                    @endif
                                ">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td style="padding: 16px 20px;">
                                <div style="font-size: 13px; color: #374151;">{{ $payment->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 11px; color: #9CA3AF;">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                            <td style="padding: 16px 20px; text-align: center;">
                                @if($payment->file_path)
                                    <button onclick="viewProof('{{ asset('storage/' . $payment->file_path) }}', '{{ $payment->title ?? 'Payment Proof' }}')" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #7B1D3A; cursor: pointer; transition: all 0.3s;" title="View Proof">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    <span style="color: #D1D5DB;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px 20px;">
                                <div style="width: 64px; height: 64px; background: #F3F4F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px; color: #9CA3AF;">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h3 style="font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px;">No payment records</h3>
                                <p style="font-size: 13px; color: #6B7280;">{{ $statusFilter !== 'all' ? 'No ' . $statusFilter . ' payments found.' : 'You haven\'t submitted any payments yet.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $payments->appends(['status' => $statusFilter])->links() }}
        </div>
    @endif

    <!-- Proof Viewer Modal -->
    <div id="proofModal" onclick="closeProofModal(event)" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="background: white; border-radius: 16px; max-width: 800px; width: 90%; max-height: 90vh; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3); position: relative;" onclick="event.stopPropagation();">
            <!-- Modal Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #E5E7EB;">
                <h3 id="proofModalTitle" style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Payment Proof</h3>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <a id="proofDownloadBtn" href="#" target="_blank" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #7B1D3A; text-decoration: none; transition: all 0.3s;" title="Open in new tab">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button onclick="closeProofModal()" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #6B7280; cursor: pointer; transition: all 0.3s;" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- Modal Body -->
            <div id="proofModalBody" style="padding: 24px; display: flex; align-items: center; justify-content: center; max-height: calc(90vh - 80px); overflow: auto; background: #F9FAFB;">
                <!-- Content injected by JS -->
            </div>
        </div>
    </div>

    <script>
        function viewProof(url, title) {
            const modal = document.getElementById('proofModal');
            const body = document.getElementById('proofModalBody');
            const modalTitle = document.getElementById('proofModalTitle');
            const downloadBtn = document.getElementById('proofDownloadBtn');

            modalTitle.textContent = title;
            downloadBtn.href = url;

            const ext = url.split('.').pop().toLowerCase().split('?')[0];

            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(ext)) {
                body.innerHTML = '<img src="' + url + '" style="max-width: 100%; max-height: 70vh; border-radius: 8px; object-fit: contain;" alt="Payment Proof">';
            } else if (ext === 'pdf') {
                body.innerHTML = '<iframe src="' + url + '" style="width: 100%; height: 70vh; border: none; border-radius: 8px;"></iframe>';
            } else {
                body.innerHTML = '<div style="text-align: center; padding: 40px;">' +
                    '<i class="fas fa-file" style="font-size: 48px; color: #9CA3AF; margin-bottom: 16px;"></i>' +
                    '<p style="color: #6B7280; margin-bottom: 16px;">This file cannot be previewed directly.</p>' +
                    '<a href="' + url + '" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px;">' +
                    '<i class="fas fa-download"></i> Download File</a></div>';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeProofModal(event) {
            if (event && event.target !== document.getElementById('proofModal')) return;
            const modal = document.getElementById('proofModal');
            modal.style.display = 'none';
            document.getElementById('proofModalBody').innerHTML = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeProofModal();
        });
    </script>
@endsection
