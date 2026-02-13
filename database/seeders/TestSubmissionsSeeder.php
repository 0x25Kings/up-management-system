<?php

namespace Database\Seeders;

use App\Models\StartupSubmission;
use App\Models\RoomIssue;
use App\Models\Startup;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestSubmissionsSeeder extends Seeder
{
    public function run(): void
    {
        $startups = Startup::all();

        if ($startups->isEmpty()) {
            $this->command->error('No startups found. Create a startup first.');
            return;
        }

        $statuses = ['pending', 'in_progress', 'approved', 'rejected'];
        $documentTypes = ['Business Permit', 'SEC Registration', 'DTI Certificate', 'BIR Registration', 'Company Profile', 'Financial Statement', 'Board Resolution', 'Articles of Incorporation'];
        $moaPurposes = ['Office space rental agreement', 'Co-working space partnership', 'Incubation program enrollment', 'Technology partnership', 'Research collaboration'];
        $paymentMethods = ['bank_transfer', 'gcash', 'cash', 'check'];
        $issueTypes = ['electrical', 'plumbing', 'aircon', 'furniture', 'cleaning', 'internet', 'structural'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        $count = 0;

        foreach ($startups as $startup) {
            // 40 document submissions per startup
            for ($i = 1; $i <= 40; $i++) {
                $status = $statuses[array_rand($statuses)];
                $createdAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(0, 23));

                StartupSubmission::create([
                    'startup_id' => $startup->id,
                    'tracking_code' => StartupSubmission::generateTrackingCode('document'),
                    'company_name' => $startup->company_name,
                    'contact_person' => $startup->contact_person,
                    'email' => $startup->email,
                    'phone' => $startup->phone,
                    'type' => 'document',
                    'document_type' => $documentTypes[array_rand($documentTypes)],
                    'notes' => 'Sample document submission #' . $i . ' for testing pagination.',
                    'status' => $status,
                    'admin_notes' => $status !== 'pending' ? 'Reviewed by admin. ' . ($status === 'approved' ? 'Everything looks good.' : ($status === 'rejected' ? 'Please resubmit with correct documents.' : 'Currently being processed.')) : null,
                    'reviewed_at' => $status !== 'pending' ? $createdAt->copy()->addDays(rand(1, 5)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $count++;
            }

            // 15 MOA requests per startup
            for ($i = 1; $i <= 15; $i++) {
                $status = $statuses[array_rand($statuses)];
                $createdAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(0, 23));

                StartupSubmission::create([
                    'startup_id' => $startup->id,
                    'tracking_code' => StartupSubmission::generateTrackingCode('moa'),
                    'company_name' => $startup->company_name,
                    'contact_person' => $startup->contact_person,
                    'email' => $startup->email,
                    'phone' => $startup->phone,
                    'type' => 'moa',
                    'moa_purpose' => $moaPurposes[array_rand($moaPurposes)],
                    'moa_details' => 'Detailed MOA request #' . $i . ' for testing.',
                    'moa_duration' => rand(6, 24) . ' months',
                    'status' => $status,
                    'admin_notes' => $status !== 'pending' ? 'MOA request reviewed.' : null,
                    'reviewed_at' => $status !== 'pending' ? $createdAt->copy()->addDays(rand(1, 7)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $count++;
            }

            // 20 payment submissions per startup
            for ($i = 1; $i <= 20; $i++) {
                $status = $statuses[array_rand($statuses)];
                $createdAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(0, 23));

                StartupSubmission::create([
                    'startup_id' => $startup->id,
                    'tracking_code' => StartupSubmission::generateTrackingCode('finance'),
                    'company_name' => $startup->company_name,
                    'contact_person' => $startup->contact_person,
                    'email' => $startup->email,
                    'phone' => $startup->phone,
                    'type' => 'finance',
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'amount' => rand(500, 50000) + (rand(0, 99) / 100),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_date' => $createdAt->format('Y-m-d'),
                    'notes' => 'Monthly rental payment #' . $i,
                    'status' => $status,
                    'admin_notes' => $status !== 'pending' ? 'Payment verified.' : null,
                    'reviewed_at' => $status !== 'pending' ? $createdAt->copy()->addDays(rand(1, 3)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $count++;
            }

            // 25 room issues per startup
            for ($i = 1; $i <= 25; $i++) {
                $status = ['pending', 'in_progress', 'resolved', 'cancelled'][array_rand(['pending', 'in_progress', 'resolved', 'cancelled'])];
                $createdAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(0, 23));

                RoomIssue::create([
                    'startup_id' => $startup->id,
                    'tracking_code' => RoomIssue::generateTrackingCode(),
                    'company_name' => $startup->company_name,
                    'contact_person' => $startup->contact_person,
                    'email' => $startup->email,
                    'phone' => $startup->phone,
                    'room_number' => $startup->room_number ?? '101',
                    'issue_type' => $issueTypes[array_rand($issueTypes)],
                    'description' => 'Room issue report #' . $i . ': ' . ['Aircon not working properly', 'Light flickering in the office', 'Water leak near the window', 'Broken chair needs replacement', 'Internet connection is slow', 'Door lock is jammed', 'Ceiling paint peeling off'][array_rand(['Aircon not working properly', 'Light flickering in the office', 'Water leak near the window', 'Broken chair needs replacement', 'Internet connection is slow', 'Door lock is jammed', 'Ceiling paint peeling off'])],
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => $status,
                    'admin_notes' => $status !== 'pending' ? 'Maintenance team has been notified.' : null,
                    'resolved_at' => $status === 'resolved' ? $createdAt->copy()->addDays(rand(1, 10)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                $count++;
            }
        }

        $this->command->info("Created {$count} test submissions across {$startups->count()} startups.");
    }
}
