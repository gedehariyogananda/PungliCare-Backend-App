<x-app-layout>
    @section('namefitur', 'Dashboard')

    @push('styles')
    <style>
        .img-fluid {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .video-fluid {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel-control-prev,
        .carousel-control-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .carousel-container {
            height: 500px;
            width: 100%;
            overflow: hidden;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .carousel-content {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-slide {
            min-width: 100%;
            flex-shrink: 0;
        }
    </style>
    @endpush

    @php
    $status = '';
    $color = '';
    switch ($laporan->status_laporan) {
    case "perlu-diatasi" :
    $status = "Perlu Diatasi";
    $color = "text-red-700 bg-danger";
    break;
    case "perlu-dukungan" :
    $status = "Perlu Dukungan";
    $color = "text-yellow-400 bg-warning ";
    break;
    case "sedang-diatasi" :
    $status = "Sedang Diatasi";
    $color = "text-blue-700 bg-info";
    break;
    default:
    $status = "Sudah Teratasi";
    $color = "text-green-600 bg-success";
    break;
    }
    @endphp
    <x-slot name="pages">Laporan</x-slot>
    <x-slot name="pagesInit">Semua Laporan</x-slot>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    


    <section class="section">
        <div class="row">
            <div class="mx-auto">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach ($laporan->BuktiLaporan as $image)
                            @if (str_contains($image->bukti_laporan, ".mp4"))
                                <div class="carousel-item">
                                    <video controls autoplay src="{{ asset('storage/' . $image->bukti_laporan) }}" class="d-block w-100 video-fluid" alt="...">
                                  </div>
                            @else
                                <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $image->bukti_laporan) }}" class="d-block w-100 img-fluid active" alt="">
                                </div>
                            @endif
                    @endforeach
                      
                 </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
            </div>
        </div>
        <div class="container d-flex justify-content-between align-items-start">
            <div class="col-lg-8 bg-white flex justify-between rounded-lg my-3 p-3 mr-3">
                <div>
                    <h5 class="text-black">{{ $laporan->judul_laporan }}</h5>
                    <i style="color: #FE8235;" class="bi bi-geo-alt"></i> {{ $laporan->alamat_laporan }}
                    <br><br>
                    <h6>Description</h6>
                    <p>{{ $laporan->deskripsi_laporan }}</p>
                </div>
                <span class="badge {{ $color }} h-6">
                    {{ $status }}
                </span>
            </div>

            <div class="col-lg-4 bg-white rounded-lg my-3 p-3">
                <h5 class="text-black">Detail Laporan</h5>
                <a href="{{ route('laporan.pendukung',$laporan->id) }}">
                    <div class="flex justify-between my-2">
                        <div class="flex">
                            <img src={{ asset("/images/support.png") }} alt="" width="20" height="20" class="mr-5">
                            <h6 class="align-center m-auto text-gray-600" style="font-size: 14px">{{ count($laporan->VoteLaporan) }} Orang
                                Mendukung</h6>
                        </div>
                        <img src={{ asset("/images/Arrow.png") }} alt="" width="20" height="20">
                    </div>
                </a>
                <a href="{{ route('laporan.comment',$laporan->id) }}">
                    <div class="flex justify-between my-2">
                        <div class="flex">
                            <img src={{ asset("/images/comment.png") }} alt="" width="20" height="20" class="mr-5">
                            <h6 class="align-center m-auto text-gray-600" style="font-size: 14px">{{ count($laporan->CommentLaporan) }} Orang
                                Mengomentari</h6>
                        </div>
                        <img src={{ asset("/images/Arrow.png") }} alt="" width="20" height="20">
                    </div>
                </a>
                <a href="{{ route('laporan.reports', $laporan->id) }}">
                    <div class="flex justify-between my-2">
                        <div class="flex">
                            <img src={{ asset("/images/report.png") }} alt="" width="20" height="20" class="mr-5">
                            <h6 class="align-center m-auto  text-gray-600" style="font-size: 14px">{{ count($laporan->ReportLaporan) }} Orang
                                Melaporkan</h6>
                        </div>
                        <img src={{ asset("/images/Arrow.png") }} alt="" width="20" height="20">
                    </div>
                </a>
                <button class="w-full p-3 bg-indigo-700 rounded-xl cursor-pointer text-white my-3 "
                    data-bs-toggle="modal" data-bs-target="#editModalLabel">Edit Laporan</button>
                <button class="w-full p-3 bg-red-600 rounded-xl cursor-pointer text-white" data-bs-toggle="modal"
                    data-bs-target="#deleteModalLabel">Hapus Laporan</button>
            </div>
        </div>

        {{-- modal Update--}}
        <!-- Modal Update Status Laporan -->
        <div class="modal fade" id="editModalLabel" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" data-bs-centered="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Status Laporan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('laporan.update', $laporan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status_laporan" id="status_laporan" class="form-control">
                                <option value="sedang-diatasi" {{ old('status_laporan', $laporan->status_laporan) ==
                                    'sedang-diatasi' ? 'selected' : '' }}>Sedang Diatasi</option>
                                <option value="sudah-teratasi" {{ old('status_laporan', $laporan->status_laporan) ==
                                    'sudah-teratasi' ? 'selected' : '' }}>Sudah Teratasi</option>
                            </select>
                            @error('status_laporan')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div class="modal fade" id="deleteModalLabel" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" data-bs-centered="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Peringatan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <form action="{{ route('laporan.delete', $laporan->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- akhir modal delete --}}
    </section>

</x-app-layout>