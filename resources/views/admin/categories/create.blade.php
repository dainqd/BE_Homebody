@extends('admin.layouts.master')
@section('title')
    Create Category
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Category</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formCreate">
            <div class="form-group">
                <label for="name">Name Category</label>
                <input type="text" class="form-control" name="name" id="name"
                       placeholder="Please enter name category">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail" id="thumbnail">
                </div>
                <div class="form-group col-md-4">
                    <label for="parent_id">Parent</label>
                    <select id="parent_id" name="parent_id" class="form-control">
                        <option value="" selected>Choose...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option
                            value="{{ \App\Enums\CategoryStatus::ACTIVE }}">{{ \App\Enums\CategoryStatus::ACTIVE }}</option>
                        <option
                            value="{{ \App\Enums\CategoryStatus::INACTIVE }}">{{ \App\Enums\CategoryStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <button type="button" id="btnCreate" class="btn btn-primary mt-3" onclick="createCategory();">Create
            </button>
        </form>
    </section>
    <script>
        let token = `Bearer ` + accessToken;
        let headers = {
            "Authorization": token
        };

        async function createCategory() {
            let categoryUrl = '{{ route('api.admin.categories.create') }}';
            loadingPage();

            $('#btnCreate').prop('disabled', true).text('Creating...');

            let inputs = $('#formCreate input, #formCreate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val()) {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnCreate').prop('disabled', false).text('Create');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formCreate')[0]);

            await $.ajax({
                url: categoryUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Create success!');
                    window.location.href = `{{ route('admin.categories.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnCreate').prop('disabled', false).text('Create');
                }
            });
        }
    </script>
@endsection
