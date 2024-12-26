@extends('admin.layouts.master')
@section('title')
    Create Questions
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Questions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Questions</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formCreate" method="post" action="{{ route('admin.qna.questions.store') }}">
            @csrf
            <div class="row">
                <div class="form-group col-md-9">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id=title"
                           placeholder="Please enter title">
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option
                            value="{{ \App\Enums\QuestionStatus::ACTIVE }}">{{ \App\Enums\QuestionStatus::ACTIVE }}</option>
                        <option
                            value="{{ \App\Enums\QuestionStatus::INACTIVE }}">{{ \App\Enums\QuestionStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="30">

                </textarea>
            </div>
            <button type="submit" id="btnCreate" class="btn btn-primary mt-3">
                Create
            </button>
        </form>
    </section>
@endsection
