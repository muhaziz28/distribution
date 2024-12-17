@extends('layouts.app')

@section('title', "Detail Aktifitas")

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 row">
                </div>

            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">

                            </div>
                        </div>


                        <div class="card-body">
                            <table id="attendances-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Nama</th>
                                        <th>Absen</th>
                                        <th>Upah</th>
                                        <th>Pinjaman</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $item)
                                    <tr class="table-secondary">
                                        <td colspan="7"><strong>Activity: {{ $item->is_block_activity == 1 ? "Block" : $item->activity_name }}</strong></td>
                                    </tr>

                                    @foreach ($item->workerAttendances as $worker)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                        <td>{{ $worker->tukang->nama_tukang }}</td>
                                        <td>{{ $worker->durasi_kerja }}</td>
                                        <td>Rp {{ number_format($worker->upah, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($worker->pinjaman, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format(($worker->durasi_kerja * $worker->upah) - $worker->pinjaman, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th>Rp {{ number_format($total['upah'], 0, ',', '.') }}</th>
                                        <th>Rp {{ number_format($total['pinjaman'], 0, ',', '.') }}</th>
                                        <th>Rp {{ number_format($total['total_bersih'], 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection