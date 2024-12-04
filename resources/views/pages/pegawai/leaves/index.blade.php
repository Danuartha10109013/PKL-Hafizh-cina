@extends('layout.pegawai.main')
@section('title')
    Cuti {{ auth()->user()->name }}
@endsection
@section('content-pegawai')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Data Cuti</h3>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="#filter" class="btn btn-secondary"" data-bs-toggle="modal"
                                                data-bs-target="#filterModal"><em
                                                    class="icon ni ni-filter"></em><span>Filter</span></a>
                                        </li>
                                        <li><a href="{{ route('pegawai.print-cuti') }}" class="btn btn-secondary"
                                                data-bs-toggle="modal" data-bs-target="#printModal" target="_blank"><em
                                                    class="icon ni ni-printer"></em><span>Cetak</span></a>
                                        </li>
                                        <a href="{{ route('pegawai.create-cuti') }}" class="btn btn-icon btn-secondary">
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ session('error') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                @endif
                <div class="nk-block">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner py-3 border-bottom border-light ">
                            <table class="datatable-init table">
                                <h4 class="card-title text-center">Riwayat Cuti Izin</h4>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        {{-- <th>Nama Pegawai</th> --}}
                                        <th>Alasan</th>
                                        <th>SubKategori</th>
                                        {{-- <th>Pengajuan</th> --}}
                                        <th>Mulai</th>
                                        <th>Berakhir</th>
                                        <th>Verifikasi</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->category == 'annual')
                                                    Tahunan
                                                @elseif ($item->category == 'other')
                                                    Lain-lain
                                                @endif
                                            </td>
                                            <td>{{ $item->reason }}</td>
                                            {{-- <td>{{ $item->user->name }}</td> --}}
                                            <td>
                                                @if (empty($item->subcategory))
                                                    Tahunan
                                                @elseif ($item->subcategory == 'sick')
                                                    Sakit
                                                @elseif ($item->subcategory == 'married')
                                                    Menikah
                                                @elseif ($item->subcategory == 'important_reason')
                                                    Beralasan Penting
                                                @elseif ($item->subcategory == 'pilgrimage')
                                                    Beribadah Haji
                                                @endif
                                            </td>

                                            {{-- <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td> --}}
                                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                                            <td>
                                                @if ($item->status === null)
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif ($item->status == '1')
                                                    {{ $item->reason_verification }}
                                                @else
                                                    Silahkan Cuti
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status == null)
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($item->status == '0')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($item->status == '1')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="nk-tb-actions gx-2">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#"
                                                                class="btn btn-sm btn-icon btn-trigger dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    @if ($item->status === null)
                                                                        <li><a
                                                                                href="{{ route('pegawai.edit-cuti', ['id' => $item->id]) }}"><em
                                                                                    class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                        </li>
                                                                    @endif
                                                                    <li><a href="{{ route('pegawai.print-cuti') }}"
                                                                            target="_blank"><em
                                                                                class="icon ni ni-printer"></em><span>Cetak</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
                <div class="card mt-4 card-bordered card-preview">
                    <div class="card-inner py-3 border-bottom border-light ">
                        <h4 class="card-title text-center">Calendar</h4>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Data Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pegawai.filtercuti') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select name="category" id="category"
                                class="form-select js-select2 select2-hidden-accesible valid">
                                <option value="annual">Cuti Tahunan</option>
                                <option value="other">Cuti Lain-lain</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status"
                                id="status"class="form-select js-select2 select2-hidden-accesible valid">
                                <option value="0">Disetujui</option>
                                <option value="1">Ditolak</option>
                                <option value="">Menunggu</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cetak -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Cetak Data Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pegawai.print-cuti') }}" method="GET">
                        <!-- Pilihan untuk mencetak semua atau berdasarkan filter -->
                        <div class="mb-3">
                            <label for="printType" class="form-label">Jenis Cetak</label>
                            <select name="print_type" id="printType"
                                class="form-select js-select2 select2-hidden-accesible valid" onchange="toggleFilters()">
                                <option value="all">Cetak Semua</option>
                                <option value="filtered">Cetak Berdasarkan Kategori dan Status</option>
                            </select>
                        </div>

                        <!-- Filter kategori dan status -->
                        <div id="filters" style="display: none;">
                            <div class="mb-3">
                                <label for="printCategory" class="form-label">Kategori</label>
                                <select name="category" id="printCategory"
                                    class="form-select js-select2 select2-hidden-accesible valid">
                                    <option value="annual">Cuti Tahunan</option>
                                    <option value="other">Cuti Lain-lain</option>
                                    <!-- Tambahkan kategori lain jika diperlukan -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="printStatus" class="form-label">Status</label>
                                <select name="status" id="printStatus"
                                    class="form-select js-select2 select2-hidden-accesible valid">
                                    <option value="0">Disetujui</option>
                                    <option value="1">Ditolak</option>
                                    {{-- <option value="">Menunggu</option> --}}
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" target="_blank">Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            console.log('Calendar Element:', calendarEl); // Menampilkan elemen kalender

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($leaves as $item)
                        {
                            title: '{{ $item->reason }}',
                            start: '{{ $item->date }}',
                            end: '{{ \Carbon\Carbon::parse($item->end_date)->addDay()->format('Y-m-d') }}',
                            backgroundColor: @if ($item->status === null)
                                '#f4bd0e'
                            @elseif ($item->status == '0')
                                '#1ee0ac'
                            @elseif ($item->status == '1')
                                '#e85347'
                            @endif ,
                            borderColor: @if ($item->status === null)
                                '#f4bd0e'
                            @elseif ($item->status == '0')
                                '#1ee0ac'
                            @elseif ($item->status == '1')
                                '#e85347'
                            @endif ,
                            textColor: '#ffffff'
                        },
                    @endforeach
                ],
                eventContent: function(info) {
                    console.log('Event Info:', info); // Melihat data event
                    return {
                        html: `<div style="display: flex; justify-content: center; align-items: center; height: 100%; padding: 0;">${info.event.title}</div>`
                    };
                }
            });

            calendar.render();
        });



        function toggleFilters() {
            const printType = document.getElementById('printType').value;
            const filters = document.getElementById('filters');

            if (printType === 'filtered') {
                filters.style.display = 'block';
            } else {
                filters.style.display = 'none';
            }
        }
    </script>
@endsection
