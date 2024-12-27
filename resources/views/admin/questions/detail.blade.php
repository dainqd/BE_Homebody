@extends('admin.layouts.master')
@section('title')
    Detail Questions
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Questions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Question</li>
            </ol>
        </nav>
    </div>
    <div class="w-100 d-flex justify-content-end align-items-center mt-3 mb-3">
        <a href="{{ route('admin.qna.answers.create') . "?question=" . $question->id }}" class="btn btn-primary">
            Write new answer</a>
    </div>
    <section class="section">
        <form id="formUpdate" action="{{ route('admin.qna.questions.update',$question->id ) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-md-9">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id=title"
                           value="{{ $question->title }}"
                           placeholder="Please enter title">
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option {{  $question->status == \App\Enums\QuestionStatus::ACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\QuestionStatus::ACTIVE }}">{{ \App\Enums\QuestionStatus::ACTIVE }}</option>
                        <option {{  $question->status == \App\Enums\QuestionStatus::INACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\QuestionStatus::INACTIVE }}">{{ \App\Enums\QuestionStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="30">{{ $question->content }}</textarea>
            </div>

            <h5 class="mt-5">List Answer</h5>
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
                    @foreach($answers as $answer)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>{{ $answer->title }}</td>
                            <td>{{ $answer->status }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.qna.answers.detail', $answer->id) }}"
                                       class="btn btn-success">View</a>
                                    <button type="button" data-id="{{ $answer->id }}" class="btn btn-danger btnDelete">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $answers->links('pagination::bootstrap-5') }}
            </section>

            <button type="submit" id="btnUpdate" class="btn btn-primary mt-3">
                Save Changes
            </button>
        </form>
    </section>
    <script>
        $('.btnDelete').on('click', function () {
            let id = $(this).data('id');
            if (confirm('Are you want to delete answer?')) {
                deleteItem(id);
            }
        })

        async function deleteItem(id) {
            loadingPage();

            let url = `{{ route('admin.qna.answers.delete', ':id') }}`;
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
                    alert('Delete answers successfully');
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
