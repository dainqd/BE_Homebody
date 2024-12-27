@extends('admin.layouts.master')
@section('title')
    Detail Answer
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Answer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Answer</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formUpdate" method="post" action="{{ route('admin.qna.answers.update', $answer->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="question">Question</label>
                <input type="text" class="form-control bg-secondary bg-opacity-25" name="question" id="question"
                       value="{{ $question->title }}" readonly
                       placeholder="Please enter name category">
                <input type="hidden" name="question_id" id="question_id" value="{{ $question->id }}">
            </div>
            <div class="row">
                <div class="form-group col-md-9">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id=title"
                           value="{{ $answer->title }}"
                           placeholder="Please enter title">
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option {{  $answer->status == \App\Enums\AnswerStatus::ACTIVE ?  'selected' : ''}}
                            value="{{ \App\Enums\AnswerStatus::ACTIVE }}">{{ \App\Enums\AnswerStatus::ACTIVE }}</option>
                        <option {{  $answer->status == \App\Enums\AnswerStatus::INACTIVE ?  'selected' : ''}}
                            value="{{ \App\Enums\AnswerStatus::INACTIVE }}">{{ \App\Enums\AnswerStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="20">{{ $answer->content }}</textarea>
            </div>
            <button type="submit" id="btnUpdate" class="btn btn-primary mt-3">
                Save Changes
            </button>
        </form>
    </section>
@endsection
