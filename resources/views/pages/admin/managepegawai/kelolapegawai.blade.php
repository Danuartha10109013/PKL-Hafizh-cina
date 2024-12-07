@extends('layout.main')
@section('title')
    Kelola Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Pegawai</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Kamu mempunyai {{ count($data) }} Pegawai.</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li><a href="{{ route('admin.print-kelolapegawai') }}" class="btn btn-secondary"
                                                    target="_blank"><em
                                                        class="icon ni ni-printer"></em><span>Cetak</span></a></li>
                                            <a href="{{ route('admin.tambahpegawai') }}" class="btn btn-icon btn-secondary">
                                                <em class="icon ni ni-plus"></em>
                                            </a>
                                            <li>
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#uploadModal">
                                                    <em class="icon ni ni-plus"></em><span>Excel</span>
                                                </button>
                                            </li>
                                            </form>

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
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pegawai</th>
                                            {{-- <th>Role</th> --}}
                                            <th>Email</th>
                                            <th>Bergabung</th>
                                            <th>Jadwal</th>
                                            <th>Avatar</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $index => $d)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $d->name }}</td>
                                                {{-- <td>{{ $d->role }}</td> --}}
                                                <td>{{ $d->email }}</td>
                                                <td>{{ $d->created_at->format('d M Y') }}</td>
                                                <td>
                                                    {{ $d->shift_name ?? 'Belum mempunyai jadwal' }}
                                                </td>

                                                <td>
                                                    <div class="user-card">
                                                        <div class="user-avatar bg-secondary">
                                                            <img src="{{ asset($d->avatar) }}" class="img-fluid"
                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="tb-status {{ $d->status == 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $d->status == 0 ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
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
                                                                        <li><a
                                                                                href="{{ route('admin.pegawaidetail', ['id' => $d->id]) }}"><em
                                                                                    class="icon ni ni-eye"></em><span>Detail</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ route('admin.editpegawai', ['id' => $d->id]) }}"><em
                                                                                    class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                        </li>
                                                                        {{-- @if ($d->trashed())
                                                                            <li><a
                                                                                    href="{{ route('admin.restorepegawai', ['id' => $d->id]) }}"><em
                                                                                        class="icon ni ni-check-circle"></em><span>Restore</span></a>
                                                                            </li>
                                                                        @else --}}
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('admin.userdeleted', $d->id) }}"><em
                                                                                    class="icon ni ni-trash"></em><span>Hapus</span></a>
                                                                        </li>
                                                                        {{-- <li><a href="#"
                                                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $d->id }}').submit();"><em
                                                                                        class="icon ni ni-na"></em><span>Hapus</span></a>
                                                                                <form id="delete-form-{{ $d->id }}"
                                                                                    action="{{ route('admin.deletepegawai', ['id' => $d->id]) }}"
                                                                                    method="POST" style="display: none;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                </form> --}}
                                                                        {{-- </li> --}}
                                                                        {{-- @endif --}}
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

                    <!-- Button to trigger modal -->
                    <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal"
                        data-bs-target="#deletedEmployeesModal">
                        Show Deleted Employees
                    </button>

                    <!-- Deleted Employees Modal -->
                    <div class="modal fade" id="deletedEmployeesModal" tabindex="-1"
                        aria-labelledby="deletedEmployeesModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deletedEmployeesModalLabel">Pegawai yang Dihapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="datatable-init table table-hover table-bordered align-middle">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Name</th>
                                                <th>Deleted By</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($deletedUsers as $userd)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $userd->name }}</td>
                                                    <td>
                                                        @php
                                                            $nama = \App\Models\User::where(
                                                                'id',
                                                                $userd->deleted_by,
                                                            )->value('name');
                                                        @endphp
                                                        {{ $nama }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('admin.userrestore', $userd->id) }}"
                                                                class="btn btn-sm btn-success me-2">Restore</a>
                                                            <form action="{{ route('admin.userdestroyed', $userd->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Delete
                                                                    Permanently</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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
    <!-- Modal Filter Excel Add -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload File Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <li>
                        <form class="mt-3" id="uploadForm" action="{{ route('admin.input-excel') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <input type="file" accept=".xlsx, .csv, .xls" name="pegawaiexcel" id="excel"
                                    class="form-control">
                            </div>
                    </li>
                    <li class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-icon btn-secondary px-4">Save</button>
                    </li>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Excel upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload File Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" action="{{ route('admin.input-excel') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" accept=".xlsx, .csv, .xls" name="pegawaiexcel" id="excel"
                                class="form-control">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-icon btn-secondary px-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
