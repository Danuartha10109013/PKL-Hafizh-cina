@extends('layout.main')
@section('title')
    Recycle Bin
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <h4 class="nk-block-title">Daftar Pegawai yang Dihapus</h4>
                </div>
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}'
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: '{{ session('error') }}'
                        });
                    </script>
                @endif

                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <table class="datatable-init table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th>Dihapus Oleh</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deletedUsers as $userd)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $userd->name }}</td>
                                        <td>
                                            @php
                                                $nama = \App\Models\User::where('id', $userd->deleted_by)->value(
                                                    'name',
                                                );
                                            @endphp
                                            {{ $nama }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.userrestore', $userd->id) }}"
                                                    class="btn btn-sm btn-success me-2"> <em
                                                        class="icon ni ni-undo"></em><span>Restore</span></a>
                                                <form action="{{ route('admin.userdestroyed', $userd->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-na"></em><span>Delete
                                                            Permanantly</span></button>
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
@endsection
