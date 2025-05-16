@extends('layout.main')
@section('title')
    Detail Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-sub">
                            <a class="back-to" href="{{ route('admin.kelolapegawai') }}">
                                <em class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span>
                            </a>
                        </div>
                        <h2 class="nk-block-title fw-normal">Kordinat Absensi </h2>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview shadow-sm">
                            <div class="card-inner">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-4">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                            
                                                {{-- <th>TYPE</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td contenteditable="true" class="editable" data-id="{{ $data->id }}" data-field="latitude">
                                                        {{ $data->latitude }}
                                                    </td>
                                                    <td contenteditable="true" class="editable" data-id="{{ $data->id }}" data-field="longitude">
                                                        {{ $data->longitude }}
                                                    </td>

                                                    
                                                    {{-- <td>{{ $d->jenis == '0' ? 'Non Customer' : 'Customer' }}</td> --}}
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .editable {
            cursor: pointer;
            background-color: #f8f9fa;
        }

        .editable:focus {
            outline: 2px solid #007bff;
            background-color: #fff;
        }
    </style>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editableCells = document.querySelectorAll('.editable');

            editableCells.forEach(cell => {
                let originalContent = '';

                // Save the original content when cell is focused
                cell.addEventListener('focus', function() {
                    originalContent = this.textContent;
                });

                // Handle saving the updated value when focus is lost
                cell.addEventListener('blur', function() {
                    const newValue = this.textContent.trim();
                    const id = this.dataset.id;
                    const field = this.dataset.field;

                    if (newValue !== originalContent) {
                        fetch("/admin/kordinat/update", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    id: id,
                                    field: field,
                                    value: newValue
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Perubahan berhasil disimpan.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    // Kembalikan nilai asli
                                    cell.textContent = originalContent;

                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message ||
                                            'Terjadi kesalahan saat menyimpan.',
                                        icon: 'error',
                                        confirmButtonText: 'Coba Lagi'
                                    });
                                }
                            })

                            .catch(() => {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menyimpan.',
                                    icon: 'error',
                                    confirmButtonText: 'Coba Lagi'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
