@extends('admin.layouts.master')
@section('title')
    List Category
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Category</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <table class="table table-bordered">
            <colgroup>
                <col width="5%">
                <col width="x">
                <col width="15%">
                <col width="15%">
                <col width="15%">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Parent</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $category->name }}</td>
                    <td>
                        @php
                            $parent = \App\Models\Categories::find($category->parent_id);
                        @endphp
                        {{ $parent->name ?? ''  }}
                    </td>
                    <td>{{ $category->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.detail', $category->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $category->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete categories?')) {
                deletePartner(id);
            }
        })

        async function deletePartner(id) {
            loadingPage();

            let url = `{{ route('api.admin.categories.delete', ':id') }}`;
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    'id': id
                },
                dataType: 'json',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + accessToken,
                },
                async: false,
                success: function (data, textStatus) {
                    alert('Delete user successfully');
                    loadingPage();
                    console.log(data)
                    window.location.reload();
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                    loadingPage();
                }
            });
        }
    </script>
@endsection
