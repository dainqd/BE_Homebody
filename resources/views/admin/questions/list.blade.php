@extends('admin.layouts.master')
@section('title')
    List Questions
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Questions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">List Questions</li>
            </ol>
        </nav>
    </div>
    <div class="w-100 d-flex justify-content-end align-items-center mt-3 mb-3">
        <a href="{{ route('admin.qna.questions.create') }}" class="btn btn-primary">Create new question</a>
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
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $question)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $question->title }}</td>
                    <td>{{ $question->status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.qna.questions.detail', $question->id) }}"
                               class="btn btn-success">View</a>
                            <button type="button" data-id="{{ $question->id }}" class="btn btn-danger btnDelete">
                                Delete
                            </button>

                            <form class="d-none" action="{{ route('admin.qna.questions.delete', $question->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="btnDeleteItem{{$question->id}}">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $questions->links('pagination::bootstrap-5') }}
    </section>

    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete question?')) {
                deleteItem(id);
            }
        })

        async function deleteItem(id) {
            $('#btnDeleteItem' + id).click();
        }
    </script>
@endsection
