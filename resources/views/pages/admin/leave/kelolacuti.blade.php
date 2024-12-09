@extends('layout.main')
@section('title')
    Kelola Cuti / Izin Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <!-- Your existing content -->

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Kelola Cuti atau Izin Pegawai</h3>
                            <div class="nk-block-des text-soft">
                                <p>Kamu mempunyai {{ count($leaves) }} Pengajuan Cuti atau Izin.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                    data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="{{ route('admin.print-kelolacuti') }}" class="btn btn-secondary"
                                                data-bs-toggle="modal" data-bs-target="#printModal" target="_blank"><em
                                                    class="icon ni ni-printer"></em><span>Cetak</span></a></li>
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
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <table class="datatable-init table">
                                <h4 class="card-title text-center">Cuti Izin Tahunan</h4>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Alasan</th>
                                        <th>Pengajuan</th>
                                        <th>Mulai</th>
                                        <th>Berakhir</th>
                                        <th>Jumlah Cuti</th>
                                        <th>Verifikasi</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp <!-- Initialize counter for annual leaves -->
                                    @foreach ($leaves as $item)
                                        @if ($item->category == 'annual')
                                            <!-- Filter for annual leaves -->
                                            <tr>
                                                <td>{{ $no++ }}</td> <!-- Increment the counter for each row -->
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->reason }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->diffInDays($item->end_date) }}
                                                    hari</td>
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
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif($item->status == '1')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    <ul class="nk-tb-actions gx-2">
                                                        <li>
                                                            <a href="#" class="btn btn-sm btn-icon btn-trigger"
                                                                data-bs-toggle="modal" data-bs-target="#confirmationModal"
                                                                data-id="{{ $item->id }}"
                                                                data-enhancer="{{ $item->enhancer }}"
                                                                data-status="{{ $item->status }}"
                                                                data-reason="{{ $item->reason_verification }}">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td> --}}
                                                <td>
                                                    <ul class="nk-tb-actions gx-2">
                                                        <li>
                                                            <a href="#"
                                                                class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li>
                                                                        <a href="#" class="dropdown-item"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#confirmationModal"
                                                                            data-id="{{ $item->id }}"
                                                                            data-enhancer="{{ $item->enhancer }}"
                                                                            data-status="{{ $item->status }}"
                                                                            data-reason="{{ $item->reason_verification }}">
                                                                            <em class="icon ni ni-check-circle-cut"></em>
                                                                            <span>Konfirmasi</span>
                                                                            <!-- Optional: Add label for clarity -->
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{route('admin.delete-satuancuti',$item->id)}}" class="dropdown-item">
                                                                            <em class="icon ni ni-trash"></em>
                                                                            <span>Hapus</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- .card-preview -->
                </div> <!-- nk-block -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <table class="datatable-init table">
                                <h4 class="card-title text-center">Cuti Izin Lain lain</h4>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Alasan</th>
                                        <th>Pengajuan</th>
                                        <th>Mulai</th>
                                        <th>Berakhir</th>
                                        <th>Jumlah Cuti</th>
                                        <th>Verifikasi</th>
                                        <th>Surat Cuti</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp <!-- Initialize counter for other leaves -->
                                    @foreach ($leaves as $item)
                                        @if ($item->category == 'other')
                                            <!-- Filter for other leaves -->
                                            <tr>
                                                <td>{{ $no++ }}</td> <!-- Increment the counter for each row -->
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->reason }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->diffInDays($item->end_date) }}
                                                    hari</td>
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
                                                    <a class="btn btn-icon"
                                                        href="{{ route('download', $item->leave_letter) }}">
                                                        <em class="icon ni ni-file-pdf"></em>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($item->status == null)
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif($item->status == '0')
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif($item->status == '1')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="nk-tb-actions gx-2">
                                                        <li>
                                                            <a href="#"
                                                                class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <em class="icon ni ni-more-h"></em>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li>
                                                                        <a href="#" class="dropdown-item"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#confirmationModal"
                                                                            data-id="{{ $item->id }}"
                                                                            data-status="{{ $item->status }}"
                                                                            data-reason="{{ $item->reason }}">
                                                                            <em class="icon ni ni-check-circle"></em>
                                                                            <span>Konfirmasi</span>
                                                                            <!-- Optional: Add label for clarity -->
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{route('admin.delete-satuancuti',$item->id)}}" class="dropdown-item">
                                                                            <em class="icon ni ni-trash"></em>
                                                                            <span>Hapus</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>

                                            </tr>
                                        @endif
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



                <!-- Modal Konfirmasi Cuti-->
                <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Cuti</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @foreach ($leaves as $item)
                                <form id="confirmationForm"
                                    action="{{ route('admin.update-cuti', ['id' => $item->id]) }}" method="POST">
                            @endforeach
                            @csrf
                            <input type="hidden" name="id" id="leave_id">
                            {{-- <input type="hidden" name="enhancer" id="enhancer_id"> --}}
                            <!-- Menambahkan hidden field untuk enhancer -->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="status">Status Pengajuan Cuti</label>
                                    <input type="text" name="enhancer" value="{{ $item->enhancer }} " hidden>
                                    <select name="status" id="status"
                                        class="form-select js-select2 select2-hidden-accesible valid"
                                        onchange="toggleReasonField()" required>
                                        <option value="0">Diterima</option>
                                        <option value="1">Ditolak</option>
                                    </select>
                                </div>

                                <div class="form-group" id="reason-group" style="display: none;">
                                    <label for="reason">Alasan Penolakan</label>
                                    <textarea name="reason" id="reason" class="form-control form-control-lg" placeholder="Alasan penolakan"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-secondary">Simpan Persetujuan</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Modal for printing single leave request -->
                <!-- Modal for printing single leave request -->
                <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="printModalLabel">Cetak Cuti</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="printForm" action="" method="GET" target="_blank">
                                <div class="modal-body">
                                    <!-- Pilih Jenis Cetak -->
                                    <div class="form-group">
                                        <label for="print_option">Pilih Jenis Cetak</label>
                                        <select name="print_option" id="print_option"
                                            class="form-select js-select2 select2-hidden-accesible valid" required>

                                            <option value="all">Cetak Semua</option>
                                            <option value="individual">Cetak Individu</option>
                                        </select>
                                    </div>

                                    <!-- Pilih Pegawai (untuk Cetak Individu) -->
                                    <div class="form-group" id="individual-option" style="display: none;">
                                        <label for="leave_id_print">Pilih Pegawai</label>
                                        <select name="leave_id" id="leave_id_print"
                                            class="form-select js-select2 select2-hidden-accesible valid">

                                            @foreach ($leaves as $item)
                                                <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Pilih Kategori -->
                                    <div class="form-group" id="category-option" style="display: none;">
                                        <label for="category">Pilih Kategori</label>
                                        <select name="category" id="category"
                                            class="form-select js-select2 select2-hidden-accesible valid">

                                            <option value="tahunan">Tahunan</option>
                                            <option value="lain-lain">Lain-lain</option>
                                        </select>
                                    </div>

                                    <!-- Pilih Status -->
                                    <div class="form-group" id="status-option" style="display: none;">
                                        <label for="status">Pilih Status</label>
                                        <select name="status" id="status2"
                                            class="form-select js-select2 select2-hidden-accesible valid">

                                            <option value="approved">Diterima</option>
                                            <option value="rejected">Ditolak</option>
                                            <option value="pending">Menunggu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Cetak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#status1').select2();
            $('#status2').select2(); // Inisialisasi Select2 pada elemen dengan id "status"
            $('#printOption').select2(); // Inisialisasi Select2 pada elemen dengan id "printOption"
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Function to generate random colors
            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            // Function to calculate the brightness of a color and determine the appropriate text color
            function getTextColor(bgColor) {
                // Remove the "#" and convert the hex to RGB values
                var r = parseInt(bgColor.substring(1, 3), 16);
                var g = parseInt(bgColor.substring(3, 5), 16);
                var b = parseInt(bgColor.substring(5, 7), 16);

                // Calculate brightness using the luminance formula
                var brightness = (r * 299 + g * 587 + b * 114) / 1000;

                // If brightness is higher than 128, it's a light color, return black text, otherwise white
                return brightness > 128 ? '#000000' : '#ffffff';
            }

            // Store random colors for each user
            var userColors = {};

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($leaves as $item)
                        {
                            title: '{{ $item->user->name }}',
                            start: '{{ $item->date }}',
                            end: '{{ \Carbon\Carbon::parse($item->end_date)->addDay()->format('Y-m-d') }}',

                            // Assign random colors based on user name
                            backgroundColor: (function() {
                                if (!userColors['{{ $item->user->name }}']) {
                                    userColors['{{ $item->user->name }}'] =
                                        getRandomColor();
                                }
                                return userColors['{{ $item->user->name }}'];
                            })(),
                            borderColor: (function() {
                                return userColors['{{ $item->user->name }}'];
                            })(),
                            // Set text color dynamically based on the background color brightness
                            textColor: (function() {
                                return getTextColor(userColors[
                                    '{{ $item->user->name }}']);
                            })()
                        },
                    @endforeach
                ],
                eventContent: function(info) {
                    // Menambahkan gaya inline untuk memusatkan teks
                    return {
                        html: `<div style="display: flex; justify-content: center; align-items: center; height: 100%; padding: 0;">${info.event.title}</div>`
                    };
                }
            });

            calendar.render();
        });


        $(document).ready(function() {
            // Toggle print options based on dropdown selection
            $('#print_option').on('change', function() {
                const printOption = $(this).val();
                const individualOption = $('#individual-option');
                const categoryOption = $('#category-option');
                const statusOption = $('#status-option');

                // Reset visibility
                individualOption.hide();
                categoryOption.hide();
                statusOption.hide();

                // Set form action URL based on selection
                const form = $('#printForm');
                if (printOption === 'all') {
                    form.attr('action', '{{ route('admin.print-kelolacuti') }}');
                } else if (printOption === 'individual') {
                    form.attr('action', '/printindividual');
                }

                // If 'Cetak Individu' is selected
                if (printOption === 'individual') {
                    individualOption.show(); // Show employee selection
                    categoryOption.show(); // Show category selection
                }
            });

            // Toggle status based on category selection
            $('#category').on('change', function() {
                const statusOption = $('#status-option');

                // Show status after category selection
                statusOption.show();
            });

            // Reset the modal content when it is closed, without refreshing the page
            $('#printModal').on('hidden.bs.modal', function() {
                // Reset the form inside the modal
                $('#printForm')[0].reset();

                // Hide any options that were previously shown
                $('#individual-option').hide();
                $('#category-option').hide();
                $('#status-option').hide();
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            var confirmationModal = document.getElementById('confirmationModal');
            confirmationModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var leaveId = button.getAttribute('data-id');
                var status = button.getAttribute('data-status');
                var reason = button.getAttribute('data-reason');
                // var enhancerId = button.getAttribute('data-enhancer'); // Ambil ID pengguna

                var form = confirmationModal.querySelector('form');
                form.action = form.action.replace(/\/\d+$/, '/' +
                    leaveId); // Update URL action dengan leaveId
                form.querySelector('#leave_id').value = leaveId;
                // form.querySelector('#enhancer_id').value = enhancerId; // Set enhancer ID

                var statusSelect = form.querySelector('#status');
                statusSelect.value = status;

                if (status === '1') {
                    form.querySelector('#reason-group').style.display = 'block';
                    form.querySelector('#reason').value = reason;
                } else {
                    form.querySelector('#reason-group').style.display = 'none';
                    form.querySelector('#reason').value = '';
                }
            });
        });

        function toggleReasonField() {
            var status = document.getElementById('status').value;
            var reasonGroup = document.getElementById('reason-group');
            if (status === '1') { // 1 untuk 'Ditolak'
                reasonGroup.style.display = 'block';
            } else {
                reasonGroup.style.display = 'none';
            }
        }
    </script>
@endsection
