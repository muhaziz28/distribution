@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col d-flex items-center justify-content-between">
                    <h1 class="m-0">Bahan</h1>

                    <div class="justify-end">
                        <a href="{{ route('transaction.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
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
                            <table id="bahan-table" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">No</th>
                                        <th>Bahan</th>
                                        <th>Qty</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result->materialPurchaseItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1  }}</td>
                                        <td>{{ $item->bahan->nama_bahan }}</td>
                                        <td>{{ $item->qty }} {{ $item->bahan->satuan->satuan }}</td>
                                        <td>Rp {{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->harga_satuan * $item->qty, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th>Rp {{ number_format($total, 2, ',', '.') }}</th>
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