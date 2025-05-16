<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserControllers1;
use App\Http\Controllers\Admin\EmployeController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\DeletedController;
use App\Http\Controllers\ImportexcelController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\readQrController;
use App\Http\Controllers\ScheduleController as ControllersScheduleController;
use App\Http\Middleware\AutoLogout;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


// Routes for authentication
Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/resetpassword', [LoginController::class, 'resetpassword'])->name('resetpassword');
Route::post('/resetpassword', [LoginController::class, 'gantipassword'])->name('gantipassword');


//print with qr
Route::get('/qrcode/{id}', [readQrController::class, 'index'])->name('read.qr');

//auto Logout
Route::middleware([AutoLogout::class])->group(function () {

    Route::get('/download/{filename}', function ($filename) {
        // Decode filename if necessary
        $filename = urldecode($filename);

        // Path to the file in the storage/app/public folder
        $filePath = 'lampiran_cuti/' . $filename;

        // Check if the file exists
        if (!Storage::disk('public')->exists($filePath)) {
            abort(Response::HTTP_NOT_FOUND, 'File not found');
        }

        // Return the file for download
        return Storage::disk('public')->download($filePath);
    })->name('download');

    // Admin routes group with middleware and prefix
    Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'as' => 'admin.'], function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('pages.admin.dashboard');

        // Manage Employees
        Route::prefix('managepegawai.kelolapegawai')->group(function () {
            Route::get('/', [EmployeController::class, 'index'])->name('kelolapegawai');
            Route::get('/tambahpegawai', [EmployeController::class, 'create'])->name('tambahpegawai');
            Route::post('/tambahpegawai/store', [EmployeController::class, 'store'])->name('tambahpegawaistore');
            Route::get('/pegawai{id}', [EmployeController::class, 'show'])->name('pegawaidetail');
            Route::get('/editpegawai/{id}', [EmployeController::class, 'edit'])->name('editpegawai');
            Route::post('/updatepegawai/{id}', [EmployeController::class, 'update'])->name('editpegawaiupdate');
            Route::delete('/hapus/{id}', [EmployeController::class, 'destroy'])->name('deletepegawai');
            Route::get('/cetakpegawai', [EmployeController::class, 'cetakpegawai'])->name('print-kelolapegawai');
            // Route::get('restorepegawai/{id}', [AdminController::class, 'restore'])->name('restorepegawai');
            // Route::get('trashedpegawai', [AdminController::class, 'trashed'])->name('trashedpegawai');
            Route::post('/input-pegawai', [EmployeController::class, 'input'])->name('input-excel');
            //trashed user
            Route::get('/temporarydelete/{id}', [DeletedController::class, 'deleteuser'])->name('userdeleted');
            Route::get('/restoreuser/{id}', [DeletedController::class, 'restoreuser'])->name('userrestore');
            //destroy user
            Route::delete('/destroyuser/{id}', [DeletedController::class, 'destroyuser'])->name('userdestroyed');
        });

        // Manage Attendance
        Route::prefix('attendance.kelolakehadiranpegawai')->group(function () {
            Route::get('/', [AttendanceController::class, 'kehadiran'])->name('kelolakehadiranpegawai');
            Route::get('/filter-kehadiran', [AttendanceController::class, 'filterKehadiran'])->name('filterKehadiran');
            Route::get('/rekapitulasi', [AttendanceController::class, 'rekap'])->name('rekapitulasi');
            Route::get('/cetakrekapitulasi', [AttendanceController::class, 'cetakrekap'])->name('print-cetakrekapitulasi');
            Route::get('/cetakrekapitulasi/bulan', [AttendanceController::class, 'cetakrekapbulan'])->name('print-cetakrekapitulasi-bulan');
            Route::get('/cetakkehadiranpegawai', [AttendanceController::class, 'cetakkehadiran'])->name('print-kelolakehadiranpegawai');
            Route::get('/cetakkehadiranpegawaimasuk/{id}', [AttendanceController::class, 'cetakkehadiranmasuk'])->name('print-kelolakehadiranpegawai-masuk');
            Route::get('/cetakkehadiranpegawaikeluar/{id}', [AttendanceController::class, 'cetakkehadirankeluar'])->name('print-kelolakehadiranpegawai-keluar');
            Route::get('/print-selection', [AttendanceController::class, 'printSelection'])->name('print-selection');
            Route::post('/print-selected', [AttendanceController::class, 'printSelected'])->name('print-selected');
            Route::post('/send/{id}', [AttendanceController::class, 'send'])->name('kelolakehadiranpegawai.send');
            Route::get('/delete/{id}', [AttendanceController::class, 'delete'])->name('kelolakehadiranpegawai.delete');
            Route::get('/restore/{id}', [AttendanceController::class, 'restore'])->name('kelolakehadiranpegawai.restore');
            Route::delete('/forcedelete/{id}', [AttendanceController::class, 'forcedelete'])->name('kelolakehadiranpegawai.forcedelete');
            Route::get('/daftarsanksi', [AttendanceController::class, 'daftarsanksi'])->name('daftarsanksi');
            Route::get('/daftarsanksi/{id}', [AttendanceController::class, 'daftarsanksidetail'])->name('daftarsanksi.detail');
            Route::get('/daftarsanksi/preview/{id}', [AttendanceController::class, 'previewSuratPeringatan'])->name('daftarsanksi.preview');
        });

        // Manage Schedules
        Route::prefix('schedule.kelolajadwalpegawai')->group(function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('kelolajadwal');
            Route::get('/tambahjadwal', [ScheduleController::class, 'create'])->name('tambahjadwal');
            Route::post('/tambahjadwal/store', [ScheduleController::class, 'store'])->name('tambahjadwalstore');
            Route::get('/editjadwal/{id}', [ScheduleController::class, 'edit'])->name('editjadwal');
            Route::post('/updatejadwal/{id}', [ScheduleController::class, 'update'])->name('updatejadwal');
            Route::get('/printjadwal', [ScheduleController::class, 'print'])->name('print-jadwal');
            Route::get('/delete/{id}', [ScheduleController::class, 'delete'])->name('delete-jadwal');
            Route::get('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore-jadwal');
            Route::delete('/forcedelete/{id}', [ScheduleController::class, 'forceDelete'])->name('forcedelete-jadwal');
            Route::post('/update-sch', [ScheduleController::class, 'update_sch'])->name('update.sch-jadwal');
        });

        Route::prefix('leave.kelolacuti')->group(function () {
            Route::get('/', [LeaveController::class, 'index'])->name('kelolacuti');
            Route::get('/persetujuancuti', [LeaveController::class, 'create'])->name('persetujuancuti');
            Route::post('/update/{id}', [LeaveController::class, 'update'])->name('update-cuti');
            Route::get('/printkelolacuti', [LeaveController::class, 'cetakcuti'])->name('print-kelolacuti');
            Route::get('/printsatuancuti', [LeaveController::class, 'cetaksatuancuti'])->name('print-satuancuti');
            Route::get('/delete/{id}', [LeaveController::class, 'delete'])->name('delete-satuancuti');
            Route::get('/restore/{id}', [LeaveController::class, 'restore'])->name('restore-satuancuti');
            Route::delete('/forcedelete/{id}', [LeaveController::class, 'forcedelete'])->name('forcedelete-satuancuti');
        });

        Route::prefix('trashed.kelolasampah')->group(function () {
            Route::get('/', [DeletedController::class, 'index'])->name('trashed');
            //trashed user
            Route::get('/temporarydelete/{id}', [DeletedController::class, 'deleteuser'])->name('userdeleted');
            Route::get('/restoreuser/{id}', [DeletedController::class, 'restoreuser'])->name('userrestore');
            //destroy user
            Route::delete('/destroyuser/{id}', [DeletedController::class, 'destroyuser'])->name('userdestroyed');
        });
    });


    Route::get('/import', [ImportexcelController::class, 'index']);
    Route::post('/import/excel', [ImportexcelController::class, 'post'])->name('post-excel');

    //pegawai
    Route::group(['prefix' => 'pegawai', 'middleware' => ['pegawai'], 'as' => 'pegawai.'], function () {

        Route::get('/dashboard', [UserControllers1::class, 'index'])->name('pages.pegawai.dashboard');

        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('attendance');
            Route::post('/setup', [AttendanceController::class, 'setup'])->name('attendance-setup');
            Route::get('/tambahabsensi', [AttendanceController::class, 'create'])->name('tambah-attendance');
            Route::post('/tambahabsensi/store', [AttendanceController::class, 'store'])->name('store-attendance');
            Route::get('/attendance/{id}/print', [AttendanceController::class, 'print'])->name('print-attendance');
            Route::get('/attendance/printcutom', [AttendanceController::class, 'printcustom'])->name('printcustom-attendance');
        });

        Route::prefix('schedule')->group(function () {
            Route::get('/', [ControllersScheduleController::class, 'index'])->name('schedule');
        });

        Route::prefix('leaves')->group(function () {
            Route::get('/', [LeavesController::class, 'index'])->name('leaves');
            Route::get('/create', [LeavesController::class, 'create'])->name('create-cuti');
            Route::post('/store', [LeavesController::class, 'store'])->name('store-cuti');
            Route::get('/leaves/{id}/edit', [LeavesController::class, 'edit'])->name('edit-cuti');
            Route::put('/update/{id}', [LeavesController::class, 'update'])->name('update-cuti');
            Route::post('/filtercuti', [LeavesController::class, 'filtercuti'])->name('filtercuti');


            Route::get('/print', [LeavesController::class, 'printall'])->name('print-cuti');
            Route::get('/print/{id}', [LeavesController::class, 'print'])->name('print-cuti-satu');
        });
        Route::prefix('izin')->group(function () {
            Route::get('/', [IzinController::class, 'index'])->name('izin');
        });
        Route::prefix('profil')->group(function () {
            Route::get('/akun', [UserControllers1::class, 'profilakun'])->name('profilakun');
            Route::get('/biodata', [UserControllers1::class, 'profilbiodata'])->name('profilbiodata');
            Route::put('/biodata/update/{id}', [UserControllers1::class, 'updates'])->name('update');
            Route::patch('/user/{id}/avatar', [UserControllers1::class, 'updateAvatar'])->name('updateAvatar');
            Route::get('/identitas', [UserControllers1::class, 'profilidentitas'])->name('profilidentitas');
        });
    });
});
