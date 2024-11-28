@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 items-center">
                <div class="col">
                    <h1 class="m-0">{{ $result->kegiatan }}</h1>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Pembelian Material</a>
                                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Profile</a>
                                    </div>
                                </div>
                                <div class="col-7 col-sm-9">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                            <div class="d-flex justify-content-between">
                                                <h4>Pembelian Material</h4>
                                                <a href="{{ route('transaction-materials.index', $result->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Pembelian Material
                                                </a>
                                            </div>
                                            <div class="mt-3">
                                                <table id="purchase-table" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5px;"> No</th>
                                                            <th>Vendor</th>
                                                            <th>Total</th>
                                                            <th>Bukti Transaksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($materialPurchases as $index => $item)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $item->vendor->nama_vendor }}</td>
                                                            <td>Rp {{ number_format($item->total, 2, ',', '.') }}</td>
                                                            <td>
                                                                <a href="{{ $item->attachment }}" class="btn btn-default" target="_blank"><i class="fas fa-eye mr-2"></i>Lihat</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="2">Total</th>

                                                            <th>Rp {{ number_format($total, 2, ',', '.') }}</th>

                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                            Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endpush