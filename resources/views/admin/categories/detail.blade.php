@extends('admin.layouts.master')
@section('title')
    Detail Category
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Detail Category</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <form id="formUpdate">
            <div class="form-group">
                <label for="name">Name Category</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{ $category->name }}"
                       placeholder="Please enter name category">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail" id="thumbnail">
                    <img src="{{ $category->thumbnail }}" alt="" class="mt-2" style="width: 200px">
                </div>
                <div class="form-group col-md-4">
                    <label for="parent_id">Parent</label>
                    <select id="parent_id" name="parent_id" class="form-control">
                        <option value="">Choose...</option>
                        @foreach($categories as $item)
                            <option
                                {{ isset($category) && $category->parent_id == $item->id ? 'selected' : '' }}
                                value="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option {{  $category->status == \App\Enums\CategoryStatus::ACTIVE ?  'selected' : ''}}
                                value="{{ \App\Enums\CategoryStatus::ACTIVE }}">{{ \App\Enums\CategoryStatus::ACTIVE }}</option>
                        <option {{  $category->status == \App\Enums\CategoryStatus::INACTIVE ? 'selected' : ''}}
                                value="{{ \App\Enums\CategoryStatus::INACTIVE }}">{{ \App\Enums\CategoryStatus::INACTIVE }}</option>
                    </select>
                </div>
            </div>
            <button type="button" id="btnUpdate" class="btn btn-primary mt-3" onclick="updateCategory();">
                Save Changes
            </button>
        </form>
    </section>
    <script>
        let token = `Bearer ` + accessToken;
        let headers = {
            "Authorization": token
        };

        async function updateCategory() {
            let categoryUrl = '{{ route('api.admin.categories.update', $category->id) }}';
            loadingPage();

            $('#btnUpdate').prop('disabled', true).text('Saving...');

            let inputs = $('#formUpdate input, #formUpdate textarea');
            for (let i = 0; i < inputs.length; i++) {
                if (!$(inputs[i]).val() && $(inputs[i]).attr('type') !== 'file' && $(inputs[i]).attr('type') !== 'hidden') {
                    let text = $(inputs[i]).prev().text();
                    alert(text + ' cannot be left blank!');
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                    loadingPage();
                    return;
                }
            }

            const formData = new FormData($('#formUpdate')[0]);

            await $.ajax({
                url: categoryUrl,
                method: 'POST',
                headers: headers,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    alert('Update success!');
                    window.location.href = `{{ route('admin.categories.list') }}`;
                },
                error: function (error) {
                    console.log(error);
                    alert(error.responseJSON.message);
                    loadingPage();
                    $('#btnUpdate').prop('disabled', false).text('Save Changes');
                }
            });
        }
    </script>
@endsection
