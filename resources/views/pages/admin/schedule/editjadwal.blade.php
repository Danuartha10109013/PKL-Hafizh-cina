@extends('layout.main')
@section('title')
    Jadwal Pegawai
@endsection
@section('content')
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-lg">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <div class="nk-block-head-sub"><a class="back-to" href="{{ route('admin.kelolajadwal') }}"><em
                                        class="icon ni ni-chevron-left-circle-fill"></em><span>Back</span></a></div>
                            <h2 class="nk-block-title fw-normal">Edit Jadwal Pegawai</h2>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <form action="{{ route('admin.updatejadwal', ['id' => $schedules->id]) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="preview-block">
                                        <span class="preview-title-lg overline-title">Edit Jadwal Pegawai</span>

                                        <div class="row gy-4">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="shift" class="form-label">Shift</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="shift"
                                                            name="shift" value="{{ $schedules->shift_name }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="abbreviation">Singkatan</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="abbreviation"
                                                            name="abbreviation" value="{{ $schedules->Singkatan }}"
                                                            required>
                                                        @error('abbreviation')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>

                                        @foreach ($dayes as $d)
                                            <div class="row gy-4">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="day">Nama Hari</label>
                                                        <div class="form-control-wrap">
                                                            <select
                                                                class="form-select js-select2 select2-hidden-accesible valid"
                                                                name="day[]" required>
                                                                <option value="Senin"
                                                                    {{ $d->days == 'Senin' ? 'selected' : '' }}>Senin
                                                                </option>
                                                                <option value="Selasa"
                                                                    {{ $d->days == 'Selasa' ? 'selected' : '' }}>Selasa
                                                                </option>
                                                                <option value="Rabu"
                                                                    {{ $d->days == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                                <option value="Kamis"
                                                                    {{ $d->days == 'Kamis' ? 'selected' : '' }}>Kamis
                                                                </option>
                                                                <option value="Jumat"
                                                                    {{ $d->days == 'Jumat' ? 'selected' : '' }}>Jumat
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="clock_in">Jam Masuk</label>
                                                        <div class="form-control-wrap">
                                                            <input type="time" class="form-control" id="clock_in"
                                                                name="clock_in[]"
                                                                value="{{ \Carbon\Carbon::parse($d->clock_in)->format('H:i') }}"
                                                                required>
                                                            @error('clock_in')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="break">Istirahat</label>
                                                        <div class="form-control-wrap">
                                                            <input type="time" class="form-control" id="break"
                                                                name="break[]"
                                                                value="{{ \Carbon\Carbon::parse($d->break)->format('H:i') }}"
                                                                required>
                                                            @error('break')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="clock_out">Jam Pulang</label>
                                                        <div class="form-control-wrap">
                                                            <input type="time" class="form-control" id="clock_out"
                                                                name="clock_out[]"
                                                                value="{{ \Carbon\Carbon::parse($d->clock_out)->format('H:i') }}"
                                                                required>
                                                            @error('clock_out')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div id="schedule-forms"></div>

                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary" id="add-schedule">+</button>
                                        </div>

                                    </div>

                                    <hr class="preview-hr">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary">Update Jadwal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- .card-preview -->
                </div><!-- .code-block -->
            </div><!-- .nk-block -->
        </div><!-- .nk-content-body -->
    </div><!-- .container-xl -->
    </div><!-- .nk-content -->

    <script>
        document.getElementById('add-schedule').addEventListener('click', function() {
            const scheduleForms = document.getElementById('schedule-forms');
            const newForm = document.createElement('div');
            newForm.classList.add('row', 'gy-4', 'schedule-form');

            newForm.innerHTML = `
                <div class="col-sm-3 mb-3">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <select class="form-select js-select2 select2-hidden-accesible valid" name="day[]" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                            </select>
                        </div>
                    </div>
                </div>
               
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="time" class="form-control" name="clock_in[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="time" class="form-control" name="break[]" required>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="time" class="form-control" name="clock_out[]" required>
                        </div>
                    </div>
                </div>
            `;

            scheduleForms.appendChild(newForm);
        });
    </script>
@endsection
