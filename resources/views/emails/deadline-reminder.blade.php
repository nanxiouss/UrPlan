<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Deadline Tugas</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .card { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #e11d48, #be123c); padding: 24px; text-align: center; color: #ffffff; }
        .content { padding: 24px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-yt { background: #fee2e2; color: #dc2626; }
        .badge-tt { background: #0f172a; color: #ffffff; }
        .badge-ig { background: #fce7f3; color: #db2777; }
        .badge-other { background: #f1f5f9; color: #475569; }
        .details-box { background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .btn { display: inline-block; background: #e11d48; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: bold; font-size: 14px; text-align: center; margin-top: 16px; }
        .footer { padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">⏰ Pengingat Deadline Our Plan</h1>
            <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.9;">Tenggat Waktu Tugas Mendekat / Hari Ini</p>
        </div>
        <div class="content">
            <p style="font-size: 15px; margin-top: 0;">Halo <strong>{{ $user->name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.5; color: #475569;">
                Ini adalah pengingat otomatis bahwa tugas berikut yang ditugaskan kepada Anda akan segera jatuh tempo:
            </p>

            <div class="details-box">
                <div style="margin-bottom: 8px;">
                    <span class="badge 
                        @if($task->platform_distribution == 'YouTube') badge-yt
                        @elseif($task->platform_distribution == 'TikTok') badge-tt
                        @elseif($task->platform_distribution == 'Instagram') badge-ig
                        @else badge-other @endif">
                        {{ $task->platform_distribution }}
                    </span>
                    <span style="font-size: 12px; font-weight: bold; color: #e11d48; margin-left: 6px;">
                        ⚠️ Deadline: {{ $task->deadline ? $task->deadline->translatedFormat('l, d F Y') : 'Hari Ini' }}
                    </span>
                </div>

                <h2 style="font-size: 17px; margin: 8px 0; color: #0f172a; font-weight: 700;">
                    {{ $task->title }}
                </h2>

                @if($task->description)
                <p style="font-size: 13px; color: #64748b; margin: 8px 0; line-height: 1.4;">
                    {{ $task->description }}
                </p>
                @endif

                <div style="margin-top: 12px; font-size: 13px; border-top: 1px dashed #f43f5e; padding-top: 10px; color: #334155;">
                    <div>📂 <strong>Proyek:</strong> {{ $task->project ? $task->project->name : 'Workspace' }}</div>
                    <div style="margin-top: 4px;">📊 <strong>Status Saat Ini:</strong> {{ $task->status }}</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="btn">🚀 Buka Dashboard & Update Status</a>
            </div>
        </div>
        <div class="footer">
            Email pengingat ini dikirim otomatis oleh sistem Scheduler Our Plan.
        </div>
    </div>
</body>
</html>
