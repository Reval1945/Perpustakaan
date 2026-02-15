@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')   
                
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
                    <div class="row">

                        <!-- Anggota -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Anggota
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">120</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buku -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Buku
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">350</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaksi -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Transaksi
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">89</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row mb-4">

                            <!-- Info Perpustakaan -->
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2 border-left-primary">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-primary mb-3">
                                            Informasi Perpustakaan
                                        </h6>

                                        <p class="mb-1"><strong>Nama:</strong> Perpustakaan Digital SMK</p>
                                        <p class="mb-1"><strong>Alamat:</strong> Jl. Pendidikan No. 12</p>
                                        <p class="mb-1"><strong>Tahun Berdiri:</strong> 2022</p>
                                        <p class="mb-0"><strong>Status:</strong> Aktif</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Jam Operasional -->
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2 border-left-success">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-success mb-3">
                                            Jam Operasional
                                        </h6>

                                        <p class="mb-1">Senin – Jumat : 08.00 – 15.00</p>
                                        <p class="mb-1">Sabtu : 08.00 – 12.00</p>
                                        <p class="mb-0">Minggu : Libur</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                    <!-- Daftar Buku Terbaru -->
                    <div class="col-xl-4 col-md-4 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-info mb-3">
                                    Buku Terbaru
                                </h6>
                                <table class="table table-sm mb-0">
                                    <tr><td>Laravel Dasar</td></tr>
                                    <tr><td>PHP OOP</td></tr>
                                    <tr><td>Basis Data</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Peminjaman -->
                    <div class="col-xl-8 col-md-8 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header font-weight-bold text-primary">
                                Daftar Peminjaman
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="bg-light">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Nama Anggota</th>
                                            <th>Buku</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>Ani</td>
                                            <td>Laravel</td>
                                            <td>2025-01-01</td>
                                            <td>10:00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td>Budi</td>
                                            <td>PHP</td>
                                            <td>2025-01-02</td>
                                            <td>11:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
@endsection