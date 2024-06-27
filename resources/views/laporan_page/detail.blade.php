<x-app-layout>
    @section('namefitur', 'Dashboard')

    @push('styles')
        <style>
            .img-fluid {
                width: 100%;
                height: auto;
                object-fit: cover;
            }

            .video-fluid {
                width: 100%;
                height: auto;
            }

            .carousel-item {
                transition: opacity 0.5s ease-in-out;
            }

            .opacity-0 {
                opacity: 0;
            }

            .opacity-100 {
                opacity: 1;
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

            /* Customizing carousel height */
            .carousel-container {
                height: 600px; /* Adjust this value as needed */
                max-width: 100%;
                overflow: hidden;
                position: relative;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .carousel-content {
                width: 100%;
                height: 100%;
                display: flex;
                overflow: hidden;
                position: relative;
            }

            .carousel-slide {
                min-width: 100%;
                flex-shrink: 0;
                transition: transform 0.5s ease;
            }
        </style>
    @endpush

                      @php
                            $status = '';
                            $color = '';
                            switch ($laporan->status_laporan) {
                                case "perlu-diatasi" :
                                    $status = "Perlu Diatasi";
                                    $color = "text-red-700 border-red-700";
                                    break;
                                case "perlu-dukungan" :
                                    $status = "Perlu Dukungan";
                                    $color = "text-yellow-400  border-yellow-400 ";
                                    break;
                                case  "sedang-diatasi" :
                                    $status = "Sedang Diatasi";
                                    $color = "text-blue-700 border-blue-700";
                                    break;
                                default:
                                    $status = "Sudah Teratsi";
                                    $color = "text-green-600 border-green-600";
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
                <div class="carousel-container">
                    <div class="carousel-content">
                        <!-- Item 1 -->
                        @foreach ($laporan->BuktiLaporan as $image)
                            <div class="carousel-slide">
                                @if (str_contains($image->bukti_laporan, ".mp4"))
                                    <video controls class="block w-full video-fluid">
                                        <source src="{{ asset('storage/' . $image->bukti_laporan) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $image->bukti_laporan) }}" class="block w-full img-fluid" alt="Bukti Laporan">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <!-- Controls -->
                    <button class="carousel-control-prev" onclick="prevSlide()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="carousel-control-next" onclick="nextSlide()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="container d-flex justify-content-between align-items-start">
            <div class="col-lg-8 bg-white flex justify-between rounded-lg my-3 p-3 mr-3">
                <div>
                    <h3 class="text-black">{{ $laporan->judul_laporan }}</h3>
                    <i style="color: #FE8235;" class="bi bi-geo-alt"></i> {{ $laporan->alamat }}
                    <h5>Description</h5>
                    <p>{{ $laporan->deskripsi_laporan }}</p>
                </div>
                <span class="border-2 rounded-xl p-2 h-10 {{ $color }}">
                    {{ $status }}
                </span>              
            </div>
            <div class="col-lg-4 bg-white rounded-lg my-3 p-3">
                <h5>Detail Laporan</h5>
                <a href="{{ route('laporan.comment',$laporan->id) }}">
                <div class="flex justify-between my-2">
                    <div class="flex"> 
                        <img src={{ asset("/images/support.png") }} alt="" width="30" height="30" class="mr-5">
                        <p class="align-center m-auto text-gray-600">{{ count($laporan->VoteLaporan) }} Orang mendukung</p>
                    </div>
                    <img src={{ asset("/images/Arrow.png") }} alt="" width="26" height="27">
                </div>
             </a>
             <a href="{{ route('laporan.comment',$laporan->id) }}">
                <div class="flex justify-between my-2">
                    <div class="flex"> 
                        <img src={{ asset("/images/comment.png") }} alt="" width="30" height="30" class="mr-5">
                        <p class="align-center m-auto text-gray-600">{{ count($laporan->CommentLaporan) }} Orang mengomentari</p>
                    </div>
                    <img src={{ asset("/images/Arrow.png") }} alt="" width="26" height="27">
                </div>
             </a>
                <a href="{{ route('laporan.reports', $laporan->id) }}">
                    <div class="flex justify-between my-2">
                        <div class="flex"> 
                            <img src={{ asset("/images/report.png") }} alt="" width="30" height="30" class="mr-5">
                            <p class="align-center m-auto  text-gray-600">{{ count($laporan->ReportLaporan) }} Orang Melaporkan</p>
                        </div>
                        <img src={{ asset("/images/Arrow.png") }} alt="" width="26" height="27">
                    </div>
                </a>
                <button class="w-full p-3 bg-indigo-700 rounded-xl cursor-pointer text-white my-3 " data-bs-toggle="modal" data-bs-target="#editModalLabel">Edit Laporan</button>
                <button class="w-full p-3 bg-red-600 rounded-xl cursor-pointer text-white" data-bs-toggle="modal" data-bs-target="#deleteModalLabel">Hapus Laporan</button>
            </div>
        </div>
        
        {{-- modal Update--}}<!-- Modal Update Status Laporan -->
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
                        <option value="sedang-diatasi" {{ old('status_laporan', $laporan->status_laporan) == 'sedang-diatasi' ? 'selected' : '' }}>Sedang Diatasi</option>
                        <option value="sudah-teratasi" {{ old('status_laporan', $laporan->status_laporan) == 'sudah-teratasi' ? 'selected' : '' }}>Sudah Teratasi</option>
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
<div class="modal fade" id="deleteModalLabel" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-bs-centered="true">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Alert</h1>
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

    @push('scripts')
        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.carousel-slide');

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.transform = `translateX(${(i - index) * 100}%)`;
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            setInterval(nextSlide, 3000); // Auto play, change slide every 3 seconds
        </script>
    @endpush
</x-app-layout>
