@if($submissions->isEmpty())
    <div class="alert alert-info text-center m-4 p-4 rounded-lg shadow-sm">
        Tidak ada submission kontak.
    </div>
@else
    <table class="modern-table">
        <thead>
            <tr>
                <th>S.N</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Dikirim Pada</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($submissions as $submission)
            <tr>
                <td data-label="S.N">{{ $loop->iteration + ($submissions->perPage() * ($submissions->currentPage() - 1)) }}</td>
                <td data-label="Nama" class="text-truncate" style="max-width: 150px;">{{ $submission->name }}</td>
                <td data-label="Email" class="text-truncate" style="max-width: 150px;">{{ $submission->email }}</td>
                <td data-label="Subjek" class="text-truncate" style="max-width: 200px;" title="{{ $submission->subject }}">
                    {{ $submission->subject }}
                </td>
                <td data-label="Pesan" class="text-truncate" style="max-width: 250px;" title="{{ $submission->message }}">
                    {{ $submission->message }}
                </td>
                <td data-label="Dikirim Pada">{{ \Carbon\Carbon::parse($submission->created_at)->format('d F Y, H:i') }}</td>
                <td data-label="Status" class="text-center">
                    @if($submission->is_replied)
                        <span title="Sudah dibalas" style="color:green;font-size:1.3em;">&#10003;</span>
                    @else
                        <span title="Belum dibalas" style="color:#aaa;font-size:1.3em;">&#10007;</span>
                    @endif
                </td>
                <td data-label="Aksi" class="actions-cell">
                    <div class="action-buttons-group">
                        <form action="{{ route('admin.contact.destroy', $submission->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus submission ini?')">
                                <i class='bx bx-trash'></i>
                            </button>
                        </form>
                        <button type="button" class="btn-action reply" style="background:linear-gradient(135deg,#38a169 0%,#48bb78 100%);color:#fff;" title="Balas" data-id="{{ $submission->id }}" data-email="{{ $submission->email }}" data-name="{{ $submission->name }}" data-message="{{ $submission->message }}" data-toggle="modal" data-target="#replyModal">
                            <i class='bx bx-reply'></i> Balas
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper">
        {{ $submissions->links('vendor.pagination.bootstrap-4') }}
    </div>
@endif 