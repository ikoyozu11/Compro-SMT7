<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{

    public function index()
    {
        $userId = (Auth::user())->id;

        // Get today's attendance; use driver-specific logic (SQLite lacks CURDATE())
        if (\DB::connection()->getDriverName() === 'sqlite') {
            $start = now('Asia/Jakarta')->startOfDay();
            $end = now('Asia/Jakarta')->endOfDay();
            $absensiTodayRecords = Absensi::where('user_id', $userId)
                ->whereBetween('time', [$start, $end])
                ->orderBy('time', 'asc')
                ->get();
        } else {
            $absensiTodayRecords = Absensi::where('user_id', $userId)
                ->whereRaw("DATE(time) = CURDATE()")
                ->orderBy('time', 'asc')
                ->get();
        }

        $absensiToday = (object) ['masuk' => null, 'pulang' => null, 'count_absen' => 0];
        
        if ($absensiTodayRecords->count() > 0) {
            $absensiToday->count_absen = $absensiTodayRecords->count();
            $firstRecord = $absensiTodayRecords->first();
            // Convert to WIB for display
            $absensiToday->masuk = $firstRecord->time->setTimezone('Asia/Jakarta')->format('H:i:s');
            
            if ($absensiTodayRecords->count() > 1) {
                $lastRecord = $absensiTodayRecords->last();
                $absensiToday->pulang = $lastRecord->time->setTimezone('Asia/Jakarta')->format('H:i:s');
            }
        }

        $absenList = Absensi::where('user_id',$userId)
                    ->orderBy('time','desc')
                    ->limit(10)
                    ->get()
                    ->map(function($absen) {
                        // Convert to WIB for display
                        $timeWib = $absen->time->setTimezone('Asia/Jakarta');
                        return (object) [
                            'id' => $absen->id,
                            'tgl' => $timeWib->format('d-m-Y'),
                            'waktu' => $timeWib->format('H:i:s'),
                            'time_wib' => $timeWib
                        ];
                    });

        // Add status logic for each attendance record
        foreach ($absenList as $index => $absen) {
            $timeWib = $absen->time_wib;
            $date = $timeWib->format('Y-m-d');
            
            // Count attendance for this date to determine if it's check-in or check-out
            // Use Asia/Jakarta local day range
            $dateStart = \Carbon\Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->startOfDay();
            $dateEnd = \Carbon\Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->endOfDay();
            $attendanceCount = Absensi::where('user_id', $userId)
                                    ->whereBetween('time', [$dateStart, $dateEnd])
                                    ->where('id', '<=', $absen->id)
                                    ->count();
            
            if ($attendanceCount == 1) {
                // First attendance of the day = Check-in
                if ($timeWib->hour >= 8) {
                    $absen->status = 'Masuk Terlambat';
                } else {
                    $absen->status = 'Masuk';
                }
            } else {
                // Second or later attendance = Check-out
                if ($timeWib->hour < 16) {
                    $absen->status = 'Pulang Awal';
                } else {
                    $absen->status = 'Pulang';
                }
            }
        }

        return view('home-magang',['absensiToday'=>$absensiToday, 'absenList'=>$absenList]);
    }

    public function saveAbsensi()
    {
        $userId = (Auth::user())->id;

        $absensi = new Absensi();
        $absensi->user_id = $userId;
        $absensi->time = now('Asia/Jakarta');
        $absensi->save();

        // Flash a success message to the session
        session()->flash('success', 'Berhasil menyimpan absensi.');

        // Redirect back to the previous page or to a specific route
        return redirect()->back();
    }

    public function absenMasuk()
    {
        $userId = (Auth::user())->id;

        // Check if user already has check-in today
        if (\DB::connection()->getDriverName() === 'sqlite') {
            $start = now('Asia/Jakarta')->startOfDay();
            $end = now('Asia/Jakarta')->endOfDay();
            $existingAbsen = Absensi::where('user_id', $userId)
                                    ->whereBetween('time', [$start, $end])
                                    ->first();
        } else {
            $existingAbsen = Absensi::where('user_id', $userId)
                                    ->whereRaw("DATE(time) = CURDATE()")
                                    ->first();
        }

        if ($existingAbsen) {
            session()->flash('error', 'Anda sudah melakukan absen masuk hari ini.');
            return redirect()->back();
        }

        $absensi = new Absensi();
        $absensi->user_id = $userId;
        $absensi->time = now('Asia/Jakarta');
        $absensi->save();

        session()->flash('success', 'Berhasil absen masuk.');
        return redirect()->back();
    }

    public function absenPulang()
    {
        $userId = (Auth::user())->id;

        // Check if user has check-in today
        if (\DB::connection()->getDriverName() === 'sqlite') {
            $start = now('Asia/Jakarta')->startOfDay();
            $end = now('Asia/Jakarta')->endOfDay();
            $absenMasuk = Absensi::where('user_id', $userId)
                                 ->whereBetween('time', [$start, $end])
                                 ->first();
        } else {
            $absenMasuk = Absensi::where('user_id', $userId)
                                 ->whereRaw("DATE(time) = CURDATE()")
                                 ->first();
        }

        if (!$absenMasuk) {
            session()->flash('error', 'Anda belum melakukan absen masuk hari ini.');
            return redirect()->back();
        }

        // Check if user already has multiple entries (already checked out)
        if (\DB::connection()->getDriverName() === 'sqlite') {
            $start = now('Asia/Jakarta')->startOfDay();
            $end = now('Asia/Jakarta')->endOfDay();
            $absenCount = Absensi::where('user_id', $userId)
                                 ->whereBetween('time', [$start, $end])
                                 ->count();
        } else {
            $absenCount = Absensi::where('user_id', $userId)
                                 ->whereRaw("DATE(time) = CURDATE()")
                                 ->count();
        }

        if ($absenCount >= 2) {
            session()->flash('error', 'Anda sudah melakukan absen pulang hari ini.');
            return redirect()->back();
        }

        $absensi = new Absensi();
        $absensi->user_id = $userId;
        $absensi->time = now('Asia/Jakarta');
        $absensi->save();

        session()->flash('success', 'Berhasil absen pulang.');
        return redirect()->back();
    }

    public function history()
    {
        $userId = (Auth::user())->id;
        $absenList = Absensi::where('user_id',$userId)
                    ->orderBy('time','desc')
                    ->get()
                    ->map(function($absen) {
                        // Convert from UTC (database) to WIB for display
                        $timeWib = $absen->time->setTimezone('Asia/Jakarta');
                        return (object) [
                            'id' => $absen->id,
                            'tgl' => $timeWib->format('d-m-Y'),
                            'waktu' => $timeWib->format('H:i:s'),
                            'time_wib' => $timeWib
                        ];
                    });

        // Add status logic for each attendance record
        foreach ($absenList as $index => $absen) {
            $timeWib = $absen->time_wib;
            $date = $timeWib->format('Y-m-d');
            
            // Count attendance for this date to determine if it's check-in or check-out
            // Use UTC range for the date in WIB
            $dateStart = \Carbon\Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->startOfDay()->utc();
            $dateEnd = \Carbon\Carbon::createFromFormat('Y-m-d', $date, 'Asia/Jakarta')->endOfDay()->utc();
            $attendanceCount = Absensi::where('user_id', $userId)
                                    ->whereBetween('time', [$dateStart, $dateEnd])
                                    ->where('id', '<=', $absen->id)
                                    ->count();
            
            if ($attendanceCount == 1) {
                // First attendance of the day = Check-in
                if ($timeWib->hour >= 8) {
                    $absen->status = 'Masuk Terlambat';
                } else {
                    $absen->status = 'Masuk';
                }
            } else {
                // Second or later attendance = Check-out
                if ($timeWib->hour < 16) {
                    $absen->status = 'Pulang Awal';
                } else {
                    $absen->status = 'Pulang';
                }
            }
        }

        return view('absensi-history',['absenList'=>$absenList]);
    }

    public function recap($dateStart, $dateEnd)
    {
        $userId = (Auth::user())->id;
        $data = [];

        if(!empty($dateStart) && !empty($dateEnd)){
            if (\DB::connection()->getDriverName() === 'sqlite') {
                // Build recap per day using Eloquent (no MySQL-specific functions)
                $start = \Carbon\Carbon::parse($dateStart, 'Asia/Jakarta')->startOfDay();
                $end = \Carbon\Carbon::parse($dateEnd, 'Asia/Jakarta')->endOfDay();
                $period = new \Carbon\CarbonPeriod($start, '1 day', $end);

                foreach ($period as $day) {
                    $dayStart = $day->copy()->startOfDay();
                    $dayEnd = $day->copy()->endOfDay();
                    $records = Absensi::where('user_id', $userId)
                                ->whereBetween('time', [$dayStart, $dayEnd])
                                ->orderBy('time', 'asc')
                                ->get();

                    $masuk = null; $pulang = null; $terlambat = 'Tidak'; $cepat = 'Tidak'; $ket = 'Tidak Hadir';
                    if ($records->count() > 0) {
                        $first = $records->first()->time->setTimezone('Asia/Jakarta');
                        $masuk = $first->format('H:i:s');
                        $terlambat = ($first->hour >= 8) ? 'Ya' : 'Tidak';
                        if ($records->count() > 1) {
                            $last = $records->last()->time->setTimezone('Asia/Jakarta');
                            $pulang = $last->format('H:i:s');
                            $cepat = ($last->hour < 16) ? 'Ya' : 'Tidak';
                            $ket = 'Hadir';
                        } else {
                            $ket = 'Belum Pulang';
                        }
                    }

                    $data[] = (object) [
                        'tgl' => $day->format('Y-m-d'),
                        'masuk' => $masuk,
                        'pulang' => $pulang,
                        'terlambat' => $terlambat,
                        'cepat_pulang' => $cepat,
                        'keterangan' => $ket,
                    ];
                }
            } else {
                // MySQL optimized recap query
                $query = "
                WITH RECURSIVE DateRange AS (
                    SELECT '".$dateStart."' AS date
                    UNION ALL
                    SELECT date + INTERVAL 1 DAY
                    FROM DateRange
                    WHERE date < '".$dateEnd."'
                )
                SELECT 
                    cal.date as tgl,
                    absen_summary.masuk,
                    absen_summary.pulang,
                    absen_summary.terlambat,
                    absen_summary.cepat_pulang,
                    absen_summary.keterangan
                FROM DateRange cal
                LEFT JOIN (
                    SELECT 
                        DATE(CONVERT_TZ(time, '+00:00', '+07:00')) as tgl_absen,
                        DATE_FORMAT(CONVERT_TZ(MIN(time), '+00:00', '+07:00'), '%H:%i:%s') as masuk,
                        CASE 
                            WHEN COUNT(*) > 1 THEN DATE_FORMAT(CONVERT_TZ(MAX(time), '+00:00', '+07:00'), '%H:%i:%s')
                            ELSE NULL 
                        END as pulang,
                        CASE 
                            WHEN TIME(CONVERT_TZ(MIN(time), '+00:00', '+07:00')) >= '08:00:00' THEN 'Ya'
                            ELSE 'Tidak'
                        END as terlambat,
                        CASE 
                            WHEN COUNT(*) > 1 AND TIME(CONVERT_TZ(MAX(time), '+00:00', '+07:00')) < '16:00:00' THEN 'Ya'
                            ELSE 'Tidak'
                        END as cepat_pulang,
                        CASE 
                            WHEN COUNT(*) = 0 THEN 'Tidak Hadir'
                            WHEN COUNT(*) = 1 THEN 'Belum Pulang'
                            ELSE 'Hadir'
                        END as keterangan
                    FROM absensi 
                    WHERE user_id = ".$userId."
                    GROUP BY DATE(CONVERT_TZ(time, '+00:00', '+07:00'))
                ) absen_summary ON cal.date = absen_summary.tgl_absen
                ORDER BY cal.date ASC";

                $data = DB::select($query);
            }
        }
        
        return view('absensi-recap', ['data'=>$data]);
    }

    public function recapMonthly(Request $request)
    {
        $year = $request->input('input-year');
        $month = $request->input('input-month');

        $dateStart = $this->createDateFormat($year,$month,'01');
        $dateEnd = $this->createDateFormat('0000','00','00');

        switch ($month) {
            case '1':
            case '3':
            case '5':
            case '7':
            case '8':
            case '10':
            case '12':
                $dateEnd = $this->createDateFormat($year,$month,'31');
                break;
            case '4':
            case '6':
            case '9':
            case '11':
                $dateEnd = $this->createDateFormat($year,$month,'30');
                break;
            default:
                if($this->isLeapYear($year)){
                    $dateEnd = $this->createDateFormat($year,$month,'29');
                }else{
                    $dateEnd = $this->createDateFormat($year,$month,'28');
                }
                break;
        }

        return redirect()->route('mg.recap',['start'=>$dateStart, 'end'=>$dateEnd])->withInput($request->all());
    }

    private function createDateFormat($year, $month, $date){
        return $year.'-'.str_pad($month,2,"0",STR_PAD_LEFT).'-'.$date;
    }

    private function isLeapYear($year) {
    if (($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0)) {
        return true;
    } else {
        return false;
    }
}
}