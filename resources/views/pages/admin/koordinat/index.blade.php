@extends('layout.main')
@section('title')
    Coordinate
@endsection
@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Atur Koordinat</h5>
        </div>
        <div class="container">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>IT</th>
                            <th>Sales Enginer</th>
                            <th>Aplications</th>
                            <th>Admin</th>
                            <th>Manager</th>
                            {{-- <th>TYPE</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="it">
                                    {{ $d->it }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="se">
                                    {{ $d->se }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="as">
                                    {{ $d->as }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="adm">
                                    {{ $d->adm }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="mng">
                                    {{ $d->mng }}
                                </td>
                                {{-- <td>{{ $d->jenis == '0' ? 'Non Customer' : 'Customer' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        fetch(/direktur/calculation / update, {
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
