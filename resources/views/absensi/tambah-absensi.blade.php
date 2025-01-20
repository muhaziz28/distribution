@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tambah Absensi</h1>
                    </div>
                </div>
            </div>
        </div>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('detail-absensi.store', ['activityID' => $activityID]) }}"
                                    method="POST">
                                    @csrf
                                    <table class="table table-bordered table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">No</th>
                                                <th>Pekerja</th>
                                                <th>Durasi Kerja</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($worker as $i)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $i->tukang->nama_tukang }}</td>
                                                    <td>
                                                        <input type="hidden" name="worker_group_id[]"
                                                            value="{{ $i->id }}">
                                                        <select name="durasi_kerja[]" class="form-select form-control">
                                                            <option value="">Pilih durasi kerja</option>
                                                            <option value="1">1 hari</option>
                                                            <option value="0.5">1/2 hari</option>
                                                            <option value="0">0 (tidak hadir)</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
