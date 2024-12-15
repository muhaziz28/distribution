@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 row">
                        <a href="{{ route('block.detail', $id) }}" class="btn btn-default btn-sm mr-3">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
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
                                            <th>Tukang</th>
                                            <th>Activity</th>
                                            <th>Durasi kerja</th>
                                            <th>Upah</th>
                                            <th>Pinjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->tukang->nama_tukang }}</td>
                                                <td>{{ $item->activity->activity_name }}</td>
                                                <td>{{ $item->durasi_kerja }} Jam</td>
                                                <td>Rp. {{ number_format($item->upah, 0, ',', '.') }}</td>
                                                <td>{{ $item->pinjaman }}</td>
                                                <td> </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="width: 10px;">No</th>
                                            <th>Tukang</th>
                                            <th>Activity</th>
                                            <th>Durasi kerja</th>
                                            <th>Upah</th>
                                            <th>Pinjaman</th>

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
