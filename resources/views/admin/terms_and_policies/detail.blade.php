@extends('admin.layouts.master')
@section('title')
    Detail Terms And Policies
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Terms And Policies</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Terms And Policies</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formUpdate" method="post" action="{{ route('admin.app.term.and.policies.update', $data->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-9">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id=title"
                           value="{{ $data->title }}"
                           placeholder="Please enter title">
                </div>
                <div class="form-group col-md-3">
                    <label for="type">Type</label>
                    <select id="type" name="type" class="form-select">
                        <option {{  $data->type == \App\Enums\TermsAndPolicies::TERM ?  'selected' : ''}}
                                value="{{ \App\Enums\TermsAndPolicies::TERM }}">{{ \App\Enums\TermsAndPolicies::TERM }}</option>
                        <option {{  $data->type == \App\Enums\TermsAndPolicies::POLICY ?  'selected' : ''}}
                                value="{{ \App\Enums\TermsAndPolicies::POLICY }}">{{ \App\Enums\TermsAndPolicies::POLICY }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="30">{{ $data->content }}</textarea>
            </div>
            <button type="submit" id="btnUpdate" class="btn btn-primary mt-3">
                Save Changes
            </button>
        </form>
    </section>
@endsection
