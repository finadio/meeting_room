@extends('user.layouts.app')
@section('title', 'Bookmarks')
@section('content')
    <!-- Alert Container -->
    <div id="alert-container"></div>

    <!-- Modal Konfirmasi Hapus Bookmark -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus Bookmark</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span id="confirmDeleteText">Apakah Anda yakin ingin menghapus bookmark ini?</span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Bookmarks</h2>

        <form method="GET" action="" class="mb-4 d-flex justify-content-center">
            <input type="text" name="search" class="form-control w-50 mr-2" placeholder="Cari fasilitas..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if($bookmarkedFacilities->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Image</th>
                        <th>Facility Name</th>
                        <th>Location</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookmarkedFacilities as $facility)
                        <tr>
                            <td>{{ ($bookmarkedFacilities->currentPage() - 1) * $bookmarkedFacilities->perPage() + $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}">
                                    @if($facility->image_path)
                                        <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" class="rounded-4 img-fluid" alt="{{ $facility->name }}" style="max-width: 100px;">
                                    @else
                                        <img src="{{ asset('img/img-1.jpg') }}"  width="200" class="img-fluid" alt="{{ $facility->name }}">
                                    @endif
                                </a>
                            </td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->location }}</td>
                            <!-- <td>Rs. {{ number_format($facility->price_per_hour) }}</td> -->
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm unbookmark-btn" 
                                            data-facility-id="{{ $facility->id }}"
                                            data-facility-name="{{ $facility->name }}">
                                        <i class='bx bx-bookmark-minus'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $bookmarkedFacilities->links('vendor.pagination.bootstrap-4') }}
            </div>
        @else
            <div class="alert alert-info text-center mt-3">
                No bookmarked facilities available.
            </div>
        @endif
    </div>

@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('scripts')
    <script>
        // Debug jQuery
        console.log('jQuery version:', $.fn.jquery);
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        
        // Fungsi untuk menampilkan pesan alert
        function showAlert(type, message) {
            let alertHtml = `<div class="alert alert-${type} alert-dismissible fade show mb-4" role="alert">
                <i class='bx bx-${type === 'success' ? 'check' : 'error'}-circle me-2'></i>
                ${message}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>`;
            let alertContainer = document.getElementById('alert-container');
            if (!alertContainer) {
                alertContainer = document.createElement('div');
                alertContainer.id = 'alert-container';
                document.querySelector('.container').prepend(alertContainer);
            }
            alertContainer.innerHTML = alertHtml;
            setTimeout(function() {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Variabel global untuk menyimpan data tombol yang akan dihapus
        let deleteBookmarkBtn = null;
        let deleteFacilityName = '';

        // Event delegation untuk tombol unbookmark
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.unbookmark-btn');
            if (button) {
                e.preventDefault();
                e.stopPropagation();
                deleteBookmarkBtn = button;
                deleteFacilityName = button.getAttribute('data-facility-name');
                // Set text konfirmasi
                document.getElementById('confirmDeleteText').textContent = `Apakah Anda yakin ingin menghapus \"${deleteFacilityName}\" dari bookmark?`;
                // Tampilkan modal
                $('#confirmDeleteModal').modal('show');
            }
        });

        // Handler tombol konfirmasi hapus di modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteBookmarkBtn) return;
            const button = deleteBookmarkBtn;
            const facilityId = button.getAttribute('data-facility-id');
            const facilityName = button.getAttribute('data-facility-name');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            if (button.disabled) return;
            button.disabled = true;
            fetch(`/user/unbookmark/${facilityId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: `_token=${encodeURIComponent(csrfToken)}&_method=DELETE`
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    throw new Error('Server returned non-JSON response or an error occurred. Status: ' + response.status);
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    // Hapus baris dari tabel
                    const tr = button.closest('tr');
                    if (tr) {
                        tr.style.transition = 'opacity 0.3s';
                        tr.style.opacity = 0;
                        setTimeout(() => {
                            tr.remove();
                            // Update nomor urut S.N
                            const rows = document.querySelectorAll('tbody tr');
                            rows.forEach((row, idx) => {
                                const snCell = row.querySelector('td');
                                if (snCell) snCell.textContent = idx + 1;
                            });
                            if (rows.length === 0) {
                                const tableResponsive = document.querySelector('.table-responsive');
                                if (tableResponsive) {
                                    tableResponsive.innerHTML = `<div class=\"alert alert-info text-center mt-3\">No bookmarked facilities available.</div>`;
                                }
                            }
                        }, 300);
                    }
                } else {
                    showAlert('danger', data.message || 'Terjadi kesalahan.');
                }
            })
            .catch(err => {
                console.error("AJAX Error:", err);
                showAlert('danger', 'Terjadi kesalahan saat menghapus bookmark: ' + err.message);
            })
            .finally(() => {
                button.disabled = false;
                // Sembunyikan modal
                $('#confirmDeleteModal').modal('hide');
                deleteBookmarkBtn = null;
            });
        });
    </script>
@endsection
