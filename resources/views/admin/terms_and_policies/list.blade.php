@extends('admin.layouts.master')
@section('title')
    List Terms And Policies
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Terms And Policies</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Terms And Policies</li>
            </ol>
        </nav>
    </div>

    <div class="w-100 d-flex justify-content-end align-items-center mt-3 mb-3">
        <a href="{{ route('admin.app.term.and.policies.create') }}" class="btn btn-primary">Create new term</a>
    </div>

    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="x">
                <col width="15%">
                <col width="15%">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Type</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->type }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.app.term.and.policies.detail', $item->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $item->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>

                            <form class="d-none" action="{{ route('admin.app.term.and.policies.delete', $item->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="btnDeleteItem{{$item->id}}">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $data->links('pagination::bootstrap-5') }}
    </section>

    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete item?')) {
                deleteItem(id);
            }
        })

        async function deleteItem(id) {
            $('#btnDeleteItem' + id).click();
        }
    </script>
@endsection
