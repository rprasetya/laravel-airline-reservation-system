@extends('layouts.master')

@section('title')
  Manajemen Slider
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Slider @endslot
    @slot('title') Manajemen Slider @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between bg-transparent">
                  <h4 class="card-title">Daftar Slider</h4>
                    <a href='{{ route("slider.create") }}' class="btn btn-success btn-sm">+ Tambah Slider</a>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama File Slider</th>
                        <th>Dibuat</th>
                        <th>Tampilkan di Halaman Utama</th>
                        <th>Tampilkan di Footer</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @notadmin
                      @notstaff
                        
                      @endnotstaff
                    @endnotadmin

                    @staff
                      <tbody>
                        @forelse ($sliders as $index => $slider)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $slider->documents ? preg_replace('/^\d+_/', '', basename($slider->documents)) : '-' }}</td>
                            <td>{{ $slider->created_at->format('d M Y H:i') }}</td>
                            <td>
                              @php
                                $status = $slider->submission_status;
                                $badgeClass = match($status) {
                                    'disetujui' => 'bg-success',
                                    'ditolak' => 'bg-danger',
                                    default => 'bg-info',
                                };
                              @endphp
                              <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>                          
                            <td>
                              @if ($slider->documents)
                                <div class="row">
                                  <form class="col">
                                    <a href="{{ asset('uploads/documents/' . basename($slider->documents)) }}" class="btn btn-sm btn-primary w-100">Lihat Berkas</a>
                                  </form>
                                  @if ($slider->submission_status == 'diajukan')
                                    <form class="col" action="{{ route('pengiklanan.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-danger btn-sm w-100">Hapus pengajuan</button>
                                    </form>
                                  @endif
                                </div>
                              @else
                                <span class="text-muted">Tidak ada berkas</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center">Belum ada slider</td>
                          </tr>
                        @endforelse
                      </tbody>
                    @endstaff
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>

  </script>
@endsection
