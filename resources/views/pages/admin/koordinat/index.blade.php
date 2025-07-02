@extends('layout.main')
@section('title')
    Kordinat Absensi
@endsection

@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <h2 class="nk-block-title fw-semibold ">Kordinat Absensi</h2>
                    </div>

                    <div class="nk-block nk-block-lg">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0">Data Lokasi</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr class="text-center align-middle">
                                                <th style="width: 60px;">No</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center align-middle">
                                                <td>1</td>
                                                <td contenteditable="true" class="editable" data-id="{{ $data->id }}"
                                                    data-field="latitude">
                                                    {{ $data->latitude }}
                                                </td>
                                                <td contenteditable="true" class="editable" data-id="{{ $data->id }}"
                                                    data-field="longitude">
                                                    {{ $data->longitude }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-muted small px-3 py-2">
                                Klik pada sel untuk mengedit koordinat secara langsung.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editableCells = document.querySelectorAll('.editable');

            editableCells.forEach(cell => {
                let originalContent = '';

                cell.addEventListener('focus', function() {
                    originalContent = this.textContent.trim();
                });

                cell.addEventListener('blur', function() {
                    const newValue = this.textContent.trim();
                    const id = this.dataset.id;
                    const field = this.dataset.field;

                    if (newValue !== originalContent) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false
                        });

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
                                Swal.close();
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Data berhasil diperbarui.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    cell.style.backgroundColor = "#d1e7dd"; // green
                                } else {
                                    cell.textContent = originalContent;
                                    cell.style.backgroundColor = "#f8d7da"; // red

                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message ||
                                            'Terjadi kesalahan saat menyimpan.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.close();
                                cell.textContent = originalContent;
                                cell.style.backgroundColor = "#f8d7da"; // red

                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal terhubung ke server.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
