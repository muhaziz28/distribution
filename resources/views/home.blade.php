@extends('layouts.app')

@section('title', "Dashboard")

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($project as $item)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$item->kegiatan}}</h3>
                            <p>Tahun Anggaran : {{$item->tahun_anggaran}}</p>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <th>No</th>
                                    <th>Block</th>
                                    <th>Total Pembelian Bahan</th>
                                </thead>
                                <tbody>
                                    @foreach ($item->block as $b)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$b->block}}</td>
                                        <td>
                                            <?php
                                            $sumTotal = 0;
                                            foreach ($b->blockMaterialDistribution as $i) {
                                                $sumTotal = $i->distributed_qty * $i->material->materialPurchaseItem->harga_satuan;
                                            }
                                            ?>
                                            Rp {{ number_format($sumTotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection