@extends('layouts.app')

@section('title', "Pembayaran Upah Pekerja")

@section("content")
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="d-flex">
                    <button onclick="history.back()" class="btn btn-default mr-3">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>

                    <h1 class="m-0"></h1>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="col">

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">No</th>
                                    <th>Pekerja</th>
                                    <th>Durasi Kerja</th>
                                    <th>Upah</th>
                                    <th>Pinjaman</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activity as $act)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td colspan="5">
                                        {{ $act->is_block_activity == 1 ? "Pengerjaan Blok" : $act->activity_name }}
                                    </td>
                                </tr>
                                @foreach ($act->workerGroups as $group)
                                <tr>
                                    <td></td>
                                    <td>{{ $group->tukang->nama_tukang }}</td>
                                    <td>{{ $group->workerAttendances[0]->durasi_kerja }}</td>
                                    <td><input class="form-control" type="number" name="upah" id="upah" /> </td>
                                    <td><input class="form-control" type="number" name="pinjaman" id="pinjaman" /></td>
                                    <td>
                                        <!-- total -->
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection