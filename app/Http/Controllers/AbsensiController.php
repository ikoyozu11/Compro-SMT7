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

        // Get today's attendance with proper null handling and WIB timezone
        $absensiToday = DB::table('absensi')
                    ->select(
                        DB::raw("DATE_FORMAT(CONVERT_TZ(MIN(time), '+00:00', '+07:00'),'%H:%i:%s') as masuk"), 
                        DB::raw("CASE WHEN COUNT(*) > 1 THEN DATE_FORMAT(CONVERT_TZ(MAX(time), '+00:00', '+07:00'),'%H:%i:%s') ELSE NULL END as pulang"),
                        DB::raw("COUNT(*) as count_absen")
                    )
                    ->where('user_id', $userId)
                    ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), now('Asia/Jakarta')->format('Y-m-d'))
                    ->first();

        // Debug: Check if we have attendance data
        if (!$absensiToday || $absensiToday->count_absen == 0) {
            $absensiToday = (object) ['masuk' => null, 'pulang' => null, 'count_absen' => 0];
        }

        $absenList = Absensi::where('user_id',$userId)
                    ->orderBy('time','desc')
                    ->limit(10)
                    ->get()
                    ->map(function($absen) {
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
            $timeWib = \Carbon\Carbon::parse($absen->time_wib);
            $date = $timeWib->format('Y-m-d');
            
            // Count attendance for this date to determine if it's check-in or check-out
            $attendanceCount = Absensi::where('user_id', $userId)
                                    ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), $date)
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
        $existingAbsen = Absensi::where('user_id', $userId)
                                ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), now('Asia/Jakarta')->format('Y-m-d'))
                                ->first();

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
        $absenMasuk = Absensi::where('user_id', $userId)
                             ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), now('Asia/Jakarta')->format('Y-m-d'))
                             ->first();

        if (!$absenMasuk) {
            session()->flash('error', 'Anda belum melakukan absen masuk hari ini.');
            return redirect()->back();
        }

        // Check if user already has multiple entries (already checked out)
        $absenCount = Absensi::where('user_id', $userId)
                             ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), now('Asia/Jakarta')->format('Y-m-d'))
                             ->count();

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
            $timeWib = \Carbon\Carbon::parse($absen->time_wib);
            $date = $timeWib->format('Y-m-d');
            
            // Count attendance for this date to determine if it's check-in or check-out
            $attendanceCount = Absensi::where('user_id', $userId)
                                    ->whereDate(DB::raw("CONVERT_TZ(time, '+00:00', '+07:00')"), $date)
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
            // Generate date range and get attendance data
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