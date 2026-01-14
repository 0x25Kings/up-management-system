<?php

namespace Database\Seeders;

use App\Models\RoomIssue;
use App\Models\StartupSubmission;
use Illuminate\Database\Seeder;

class StartupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Document Submissions
        $documents = [
            [
                'tracking_code' => 'DOC-2026-A1B2C3',
                'company_name' => 'TechStart Solutions',
                'contact_person' => 'Juan Dela Cruz',
                'email' => 'juan@techstart.ph',
                'phone' => '09171234567',
                'type' => 'document',
                'document_type' => 'Business Plan',
                'file_path' => 'startup-documents/sample_business_plan.pdf',
                'original_filename' => 'TechStart_BusinessPlan_2026.pdf',
                'notes' => 'Updated business plan for Q1 2026',
                'status' => 'pending',
            ],
            [
                'tracking_code' => 'DOC-2026-D4E5F6',
                'company_name' => 'GreenFarm PH',
                'contact_person' => 'Maria Santos',
                'email' => 'maria@greenfarm.ph',
                'phone' => '09189876543',
                'type' => 'document',
                'document_type' => 'Progress Report',
                'file_path' => 'startup-documents/progress_report.pdf',
                'original_filename' => 'GreenFarm_ProgressReport_Dec2025.pdf',
                'notes' => 'Monthly progress report for December 2025',
                'status' => 'under_review',
            ],
            [
                'tracking_code' => 'DOC-2026-G7H8I9',
                'company_name' => 'EduTech Innovations',
                'contact_person' => 'Pedro Reyes',
                'email' => 'pedro@edutech.ph',
                'phone' => '09205551234',
                'type' => 'document',
                'document_type' => 'Financial Report',
                'file_path' => 'startup-documents/financial_report.xlsx',
                'original_filename' => 'EduTech_FinancialReport_2025.xlsx',
                'notes' => 'Year-end financial report',
                'status' => 'approved',
                'reviewed_at' => now()->subDays(2),
            ],
            [
                'tracking_code' => 'DOC-2026-J1K2L3',
                'company_name' => 'HealthHub App',
                'contact_person' => 'Ana Gonzales',
                'email' => 'ana@healthhub.ph',
                'phone' => '09178889999',
                'type' => 'document',
                'document_type' => 'Legal Document',
                'file_path' => 'startup-documents/legal_doc.pdf',
                'original_filename' => 'HealthHub_Registration.pdf',
                'notes' => 'SEC registration documents',
                'status' => 'rejected',
                'admin_notes' => 'Missing required signatures on pages 3 and 5',
                'reviewed_at' => now()->subDays(1),
            ],
        ];

        foreach ($documents as $doc) {
            StartupSubmission::create($doc);
        }

        // Sample MOA Requests
        $moaRequests = [
            [
                'tracking_code' => 'MOA-2026-M1N2O3',
                'company_name' => 'FoodTech Cebu',
                'contact_person' => 'Carlos Tan',
                'email' => 'carlos@foodtechcebu.ph',
                'phone' => '09321234567',
                'type' => 'moa',
                'moa_purpose' => 'Incubation Program Partnership',
                'moa_details' => 'We are requesting an MOA for the incubation program. Our startup focuses on sustainable food delivery solutions in Cebu City. We have a team of 5 developers and 2 business analysts.',
                'notes' => 'Referred by DOST-7',
                'status' => 'pending',
            ],
            [
                'tracking_code' => 'MOA-2026-P4Q5R6',
                'company_name' => 'AI Solutions PH',
                'contact_person' => 'Miguel Lim',
                'email' => 'miguel@aisolutions.ph',
                'phone' => '09451112222',
                'type' => 'moa',
                'moa_purpose' => 'Technology Partnership',
                'moa_details' => 'Requesting MOA for technology partnership. We develop AI-powered solutions for small businesses and would like to collaborate on research initiatives.',
                'notes' => 'Interested in joint research projects',
                'status' => 'approved',
                'reviewed_at' => now()->subDays(5),
            ],
            [
                'tracking_code' => 'MOA-2026-S7T8U9',
                'company_name' => 'CleanEnergy Startup',
                'contact_person' => 'Elena Cruz',
                'email' => 'elena@cleanenergy.ph',
                'phone' => '09567778888',
                'type' => 'moa',
                'moa_purpose' => 'Renewable Energy Research',
                'moa_details' => 'We are developing solar panel solutions for rural communities. We would like to establish an MOA for research collaboration and access to laboratory facilities.',
                'status' => 'under_review',
            ],
        ];

        foreach ($moaRequests as $moa) {
            StartupSubmission::create($moa);
        }

        // Sample Payment Submissions
        $payments = [
            [
                'tracking_code' => 'FIN-2026-V1W2X3',
                'company_name' => 'AI Solutions PH',
                'contact_person' => 'Miguel Lim',
                'email' => 'miguel@aisolutions.ph',
                'phone' => '09451112222',
                'type' => 'finance',
                'invoice_number' => 'INV-2026-001',
                'amount' => 15000.00,
                'payment_proof_path' => 'payment-proofs/payment_proof_001.jpg',
                'notes' => 'Monthly incubation fee for January 2026',
                'status' => 'approved',
                'reviewed_at' => now()->subDays(3),
            ],
            [
                'tracking_code' => 'FIN-2026-Y4Z5A6',
                'company_name' => 'FoodTech Cebu',
                'contact_person' => 'Carlos Tan',
                'email' => 'carlos@foodtechcebu.ph',
                'phone' => '09321234567',
                'type' => 'finance',
                'invoice_number' => 'INV-2026-002',
                'amount' => 25000.00,
                'payment_proof_path' => 'payment-proofs/payment_proof_002.jpg',
                'notes' => 'Quarterly utility fee',
                'status' => 'pending',
            ],
            [
                'tracking_code' => 'FIN-2026-B7C8D9',
                'company_name' => 'TechStart Solutions',
                'contact_person' => 'Juan Dela Cruz',
                'email' => 'juan@techstart.ph',
                'phone' => '09171234567',
                'type' => 'finance',
                'invoice_number' => 'INV-2026-003',
                'amount' => 10000.00,
                'payment_proof_path' => 'payment-proofs/payment_proof_003.png',
                'notes' => 'Event space rental fee',
                'status' => 'under_review',
            ],
        ];

        foreach ($payments as $payment) {
            StartupSubmission::create($payment);
        }

        // Sample Room Issues
        $roomIssues = [
            [
                'tracking_code' => 'ROOM-2026-A1B2C3',
                'company_name' => 'TechStart Solutions',
                'contact_person' => 'Juan Dela Cruz',
                'email' => 'juan@techstart.ph',
                'phone' => '09171234567',
                'room_number' => '201',
                'issue_type' => 'aircon',
                'description' => 'The air conditioning unit is not cooling properly. Temperature stays at around 28°C even at maximum setting. Started happening yesterday.',
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'tracking_code' => 'ROOM-2026-D4E5F6',
                'company_name' => 'GreenFarm PH',
                'contact_person' => 'Maria Santos',
                'email' => 'maria@greenfarm.ph',
                'phone' => '09189876543',
                'room_number' => '105',
                'issue_type' => 'internet',
                'description' => 'WiFi connection is intermittent. Getting disconnected every 30 minutes. This is affecting our work productivity.',
                'priority' => 'urgent',
                'status' => 'in_progress',
                'admin_notes' => 'IT team dispatched. Router replacement scheduled.',
            ],
            [
                'tracking_code' => 'ROOM-2026-G7H8I9',
                'company_name' => 'EduTech Innovations',
                'contact_person' => 'Pedro Reyes',
                'email' => 'pedro@edutech.ph',
                'phone' => '09205551234',
                'room_number' => '302',
                'issue_type' => 'electrical',
                'description' => 'Two electrical outlets near the window are not working. Tested with different devices, no power.',
                'priority' => 'medium',
                'status' => 'resolved',
                'admin_notes' => 'Circuit breaker was tripped. Reset and outlets now working.',
                'resolved_at' => now()->subDays(1),
            ],
            [
                'tracking_code' => 'ROOM-2026-J1K2L3',
                'company_name' => 'FoodTech Cebu',
                'contact_person' => 'Carlos Tan',
                'email' => 'carlos@foodtechcebu.ph',
                'phone' => '09321234567',
                'room_number' => '204',
                'issue_type' => 'plumbing',
                'description' => 'Sink faucet is leaking. Water drips continuously even when turned off completely.',
                'priority' => 'medium',
                'status' => 'pending',
            ],
            [
                'tracking_code' => 'ROOM-2026-M4N5O6',
                'company_name' => 'AI Solutions PH',
                'contact_person' => 'Miguel Lim',
                'email' => 'miguel@aisolutions.ph',
                'phone' => '09451112222',
                'room_number' => '108',
                'issue_type' => 'furniture',
                'description' => 'Office chair wheel is broken. Chair wobbles and is unsafe to use.',
                'priority' => 'low',
                'status' => 'closed',
                'admin_notes' => 'Chair replaced with new unit.',
                'resolved_at' => now()->subDays(3),
            ],
            [
                'tracking_code' => 'ROOM-2026-P7Q8R9',
                'company_name' => 'CleanEnergy Startup',
                'contact_person' => 'Elena Cruz',
                'email' => 'elena@cleanenergy.ph',
                'phone' => '09567778888',
                'room_number' => '301',
                'issue_type' => 'cleaning',
                'description' => 'Request for deep cleaning of the office space. There is dust accumulation on ceiling vents and window sills.',
                'priority' => 'low',
                'status' => 'pending',
            ],
        ];

        foreach ($roomIssues as $issue) {
            RoomIssue::create($issue);
        }

        $this->command->info('Startup submissions and room issues seeded successfully!');
        $this->command->info('Created ' . StartupSubmission::count() . ' startup submissions');
        $this->command->info('Created ' . RoomIssue::count() . ' room issues');
    }
}
