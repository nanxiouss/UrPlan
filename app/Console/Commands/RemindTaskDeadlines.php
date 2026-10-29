<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Notification;
use App\Mail\DeadlineReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RemindTaskDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:remind-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirimkan notifikasi dan email pengingat tenggat waktu (deadline) tugas hari ini & mendatang kepada anggota tim.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todayStr = now()->toDateString();
        $tomorrowStr = now()->addDay()->toDateString();

        $this->info("Memeriksa tugas yang mendekati deadline ({$todayStr} s/d {$tomorrowStr})...");

        $tasks = Task::with(['assignees', 'project.user'])
            ->whereNotNull('deadline')
            ->whereIn('deadline', [$todayStr, $tomorrowStr])
            ->where('status', '!=', 'Published')
            ->get();

        if ($tasks->isEmpty()) {
            $this->info("Tidak ada tugas aktif yang mendekati deadline hari ini.");
            return 0;
        }

        $notifiedCount = 0;

        foreach ($tasks as $task) {
            $isToday = $task->deadline->isToday();
            $timeLabel = $isToday ? 'Hari Ini' : 'Besok';

            // Ambil seluruh target penerima (assignees atau pemilik project)
            $recipients = $task->assignees->isNotEmpty() ? $task->assignees : collect([$task->project->user]);

            foreach ($recipients as $recipient) {
                if (!$recipient) continue;

                // 1. Buat record Notification di database
                $notifTitle = "⏰ Pengingat: Tugas '{$task->title}' Deadline {$timeLabel}!";
                
                // Cegah duplikasi notifikasi pada hari yang sama
                $alreadyNotified = Notification::where('user_id', $recipient->id)
                    ->where('title', $notifTitle)
                    ->whereDate('created_at', $todayStr)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::create([
                        'user_id' => $recipient->id,
                        'title' => $notifTitle,
                        'message' => "Tugas '{$task->title}' pada proyek '{$task->project->name}' memiliki tenggat waktu {$timeLabel} (" . $task->deadline->translatedFormat('d M Y') . "). Status saat ini: {$task->status}.",
                        'is_read' => false,
                    ]);

                    // 2. Kirim Notifikasi Email
                    try {
                        Mail::to($recipient->email)->send(new DeadlineReminderMail($task, $recipient));
                    } catch (\Exception $e) {
                        Log::warning("Gagal mengirim email deadline ke {$recipient->email}: " . $e->getMessage());
                    }

                    $notifiedCount++;
                }
            }
        }

        $this->info("Berhasil mengirimkan {$notifiedCount} pengingat deadline!");
        return 0;
    }
}
