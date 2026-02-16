<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use App\Models\DealerBranch;
use App\Models\SubCategory;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Get sample data
        $dealers = User::where('role', 'dealer')->get();
        $subCategories = SubCategory::all();

        // Create 20 sample tickets
        for ($i = 1; $i <= 20; $i++) {
            $dealer = $dealers->random();
            $subCategory = $subCategories->random();
            
            // Generate ticket number
            $date = now()->format('Ymd');
            $ticketNumber = "TKT-{$date}-" . str_pad($i, 4, '0', STR_PAD_LEFT);

            // Random status
            $statuses = ['new', 'assigned', 'in_progress', 'pending', 'resolved', 'closed'];
            $status = $statuses[array_rand($statuses)];

            // Calculate SLA deadline
            $createdAt = now()->subHours(rand(1, 48));
            $slaDeadline = $createdAt->copy()->addMinutes($subCategory->sla_minutes);

            // Create ticket
            $ticket = Ticket::create([
                'ticket_number' => $ticketNumber,
                'dealer_branch_id' => $dealer->dealer_branch_id,
                'created_by' => $dealer->id,
                'category_id' => $subCategory->category_id,
                'sub_category_id' => $subCategory->id,
                'subject' => $this->generateSubject($subCategory->sub_category_name),
                'description' => $this->generateDescription($subCategory->sub_category_name),
                'priority' => $subCategory->priority,
                'sla_minutes' => $subCategory->sla_minutes,
                'sla_deadline' => $slaDeadline,
                'assigned_to' => $subCategory->default_specialist_id,
                'status' => $status,
                'assigned_at' => $status != 'new' ? $createdAt->copy()->addMinutes(5) : null,
                'started_at' => in_array($status, ['in_progress', 'pending', 'resolved', 'closed']) 
                    ? $createdAt->copy()->addMinutes(10) : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) 
                    ? $createdAt->copy()->addMinutes(rand(30, 200)) : null,
                'closed_at' => $status == 'closed' 
                    ? $createdAt->copy()->addMinutes(rand(30, 200))->addHours(1) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Calculate SLA if resolved
            if (in_array($status, ['resolved', 'closed'])) {
                $ticket->calculateSlaStatus();
            }

            // Create log
            TicketLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'action' => 'created',
                'description' => 'Ticket created by ' . $dealer->name,
                'created_at' => $createdAt,
            ]);

            if ($status != 'new') {
                TicketLog::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => null,
                    'action' => 'assigned',
                    'new_value' => $ticket->assignedHelpdesk->name ?? 'N/A',
                    'description' => 'Auto-assigned to helpdesk',
                    'created_at' => $ticket->assigned_at,
                ]);
            }
        }

        $this->command->info('✅ Sample tickets seeded successfully!');
    }

    private function generateSubject($subCategoryName)
    {
        $subjects = [
            'Internet Down' => 'Internet mati total di cabang',
            'PC/Laptop Rusak' => 'Laptop tidak bisa menyala',
            'Printer Bermasalah' => 'Printer tidak bisa print',
            'DMS Down/Error' => 'DMS tidak bisa diakses',
            'Email Issue' => 'Tidak bisa kirim email',
        ];

        return $subjects[$subCategoryName] ?? "Masalah: {$subCategoryName}";
    }

    private function generateDescription($subCategoryName)
    {
        $descriptions = [
            'Internet Down' => 'Internet di cabang kami mati sejak tadi pagi. Sudah coba restart modem tapi tetap tidak bisa. Mohon segera ditangani karena mengganggu operasional.',
            'PC/Laptop Rusak' => 'Laptop saya tiba-tiba mati dan tidak bisa dinyalakan lagi. Sudah dicoba charge tapi tidak ada respon sama sekali. Butuh bantuan urgent.',
            'Printer Bermasalah' => 'Printer tidak bisa print padahal sudah ada kertas dan tinta. Muncul error di layar printer. Mohon bantuannya.',
            'DMS Down/Error' => 'DMS tidak bisa dibuka sama sekali. Muncul error message. Ini urgent karena ada customer yang harus dilayani.',
            'Email Issue' => 'Email saya tidak bisa mengirim pesan. Sudah dicoba beberapa kali tetap error. Mohon dicek.',
        ];

        return $descriptions[$subCategoryName] ?? "Deskripsi masalah terkait {$subCategoryName}. Mohon segera ditangani. Terima kasih.";
    }
}