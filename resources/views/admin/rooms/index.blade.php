@extends('layouts.app')

@section('content')
    @include('components.show-success')
    <div class="card">
        <div class="card-header">
            <h3>Semua Kamar
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-success rounded-circle">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Kamar</th>
                    <th scope="col">Jumlah Kamar</th>
                    <th scope="col">Jumlah Tempat Tidur</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $room->roomtype->name }}</td>
                        <td>{{ $room->total_room }}</td>
                        <td>{{ $room->no_beds }}</td>
                        <td>{{ $room->price }}</td>
                        <td><img src="{{ Storage::url($room->image) }}" width="50" height="40"></td>

                        @if($room->status)
                        <td class="text-success">Aktif</td>
                        @else
                            <td class="text-danger"> disabilitas</td>
                        @endif
                        <td>
                            <div class="btn-group" role="group">
                                <form method="post"
                                      action="{{ route('admin.rooms.destroy', ['room' => $room->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                <a class="btn btn-warning"
                                   href="{{ route('admin.rooms.edit', ['room' => $room->id]) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p class="text-primary fw-bold">Anda belum membuat Ruangan apa pun.</p>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
