@if($notifications->isEmpty())
    <div class="alert alert-info text-center m-4 p-4 rounded-lg shadow-sm">
        Tidak ada notifikasi.
    </div>
@else
    <table class="modern-table">
        <thead>
            <tr>
                <th>S.N</th>
                <th>Tipe</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($notifications as $notification)
            <tr class="{{ $notification->is_read ? 'read-notification' : 'unread-notification' }}">
                <td data-label="S.N">{{ ($notifications->perPage() * ($notifications->currentPage() - 1)) + $loop->iteration }}</td>
                <td data-label="Tipe">
                    <div class="d-flex align-items-center">
                        @if($notification->notification_details['icon'] ?? false)
                            <i class="{{ $notification->notification_details['icon'] }} me-2"></i>
                        @endif
                        <span class="badge badge-info-custom">{{ Str::replace('_', ' ', Str::title($notification->type)) }}</span>
                    </div>
                </td>
                <td data-label="Pesan" class="text-truncate" style="max-width: 250px;">
                    <strong>{{ $notification->notification_details['title'] ?? 'Judul Tidak Tersedia' }}</strong><br>
                    <small>{{ $notification->notification_details['description'] ?? $notification->message }}</small>
                </td>
                <td data-label="Status">
                    <span class="badge {{ $notification->is_read ? 'badge-secondary-custom' : 'badge-primary-custom' }}">
                        {{ $notification->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                    </span>
                </td>
                <td data-label="Dibuat Pada">{{ \Carbon\Carbon::parse($notification->created_at)->format('d F Y, H:i') }}</td>
                <td data-label="Aksi" class="actions-cell">
                    <div class="action-buttons-group">
                        @if (!$notification->is_read)
                            <form id="markAsReadForm-{{ $notification->id }}" action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn-action success" title="Tandai Sudah Dibaca" onclick="showConfirmMarkAsReadModal({{ $notification->id }})">
                                    <i class='bx bx-check'></i>
                                </button>
                            </form>
                        @endif
                        @if($notification->notification_details['link'] ?? false)
                            <a href="{{ $notification->notification_details['link'] }}" class="btn-action view" title="Lihat Detail">
                                <i class='bx bx-show'></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper">
        {{ $notifications->links('vendor.pagination.bootstrap-4') }}
    </div>
@endif 