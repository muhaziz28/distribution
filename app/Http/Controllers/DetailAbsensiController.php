<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DetailAbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $startDate = Carbon::createFromFormat('d/m/Y', $request->startDate)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d');

        $activities = Activities::with(['workerAttendances.tukang'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $groupedActivities = $activities->groupBy('activity_name')->map(function ($activityGroup) {
            $mergedWorkers = collect();

            foreach ($activityGroup as $activity) {
                foreach ($activity->workerAttendances as $attendance) {
                    $workerId = $attendance->worker_id;

                    // Cek index worker_id di $mergedWorkers
                    $index = $mergedWorkers->search(function ($worker) use ($workerId) {
                        return $worker['worker_id'] === $workerId;
                    });

                    if ($index !== false) {
                        // Ambil elemen, modifikasi, lalu timpa kembali
                        $worker = $mergedWorkers[$index];
                        $worker['durasi_kerja'] += $attendance->durasi_kerja;
                        $worker['upah'] += $attendance->upah;

                        $mergedWorkers->splice($index, 1, [$worker]);
                    } else {
                        // Jika belum ada, tambahkan worker baru
                        $mergedWorkers->push([
                            'worker_id' => $workerId,
                            'nama_tukang' => $attendance->tukang->nama_tukang,
                            'durasi_kerja' => $attendance->durasi_kerja,
                            'upah' => $attendance->upah,
                            'pinjaman' => $attendance->pinjaman,
                        ]);
                    }
                }
            }

            return [
                'activity_name' => $activityGroup->first()->is_block_activity ? 'Block' : 'Tidak Ada Nama Aktivitas',
                'total_workers' => $mergedWorkers->count(),
                'workers' => $mergedWorkers,
            ];
        });


        return response()->json($groupedActivities);
    }
}
