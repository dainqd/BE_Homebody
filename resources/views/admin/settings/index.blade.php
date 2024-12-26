@extends('admin.layouts.master')
@section('title')
    App Setting
@endsection
@section('content')
    <div class="pagetitle">
        <h1>App Setting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">App Setting</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="post" action="{{ route('admin.save.setting') }}" enctype="multipart/form-data">
            @csrf
            <table class="table table-bordered">
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
                </colgroup>
                <tbody>
                <tr>
                    <td colspan="4">
                        SEO Information
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="logo">logo</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="logo" id="logo" class="form-control w-75">
                            <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleLogo">View Logo
                            </button>
                        </div>
                    </td>
                    <th class="align-middle">
                        <label for="favicon">favicon</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="favicon" id="favicon" class="form-control w-75">
                            <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleFavicon">View Favicon
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_title">og_title</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_title" id="og_title" class="form-control w-100"
                               value="{{ setting() ? setting()->og_title : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_des">og_des</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_des" id="og_des" class="form-control w-100"
                               value="{{ setting() ? setting()->og_des : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_url">og_url</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_url" id="og_url" class="form-control w-100"
                               value="{{ setting() ? setting()->og_url : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_site">og_site</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="og_site" id="og_site" class="form-control w-100"
                               value="{{ setting() ? setting()->og_site : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="og_img">og_img</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" name="og_img" id="og_img" class="form-control w-75">
                            <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleog_img">View og_img
                            </button>
                        </div>
                    </td>
                    <th class="align-middle">
                        <label for="thumbnail">thumbnail</label>
                    </th>
                    <td colspan="">
                        <div class="d-flex justify-content-between flex-wrap">
                            <input type="file" multiple name="thumbnail[]" id="thumbnail" class="form-control w-75">
                            <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                    data-bs-target="#examplethumbnail">View thumbnail
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="meta_tag">meta_tag</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="meta_tag" id="meta_tag" class="form-control w-100"
                               value="{{ setting() ? setting()->meta_tag : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="meta_keyword">meta_keyword</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="meta_keyword" id="meta_keyword" class="form-control w-100"
                               value="{{ setting() ? setting()->meta_keyword : ''}}">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        App Information
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="home_name">home_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="home_name" id="home_name" class="form-control w-100"
                               value="{{ setting() ? setting()->home_name : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="brand_name">brand_name</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="brand_name" id="brand_name" class="form-control w-100"
                               value="{{ setting() ? setting()->brand_name : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="browser_title">browser_title</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="browser_title" id="browser_title" class="form-control w-100"
                               value="{{ setting() ? setting()->browser_title : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="domain_url">domain_url</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="domain_url" id="domain_url" class="form-control w-100"
                               value="{{ setting() ? setting()->domain_url : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="email">email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="email" id="email" class="form-control w-100"
                               value="{{ setting() ? setting()->email : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="phone">phone</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="phone" id="phone" class="form-control w-100"
                               value="{{ setting() ? setting()->phone : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="address">address</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="address" id="address" class="form-control w-100"
                               value="{{ setting() ? setting()->address : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="address_detail">address_detail</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="address_detail" id="address_detail" class="form-control w-100"
                               value="{{ setting() ? setting()->address_detail : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="zip">zip</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="zip" id="zip" class="form-control w-100"
                               value="{{ setting() ? setting()->zip : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="fax">fax</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="fax" id="fax" class="form-control w-100"
                               value="{{ setting() ? setting()->fax : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="qna_email">qna_email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="qna_email" id="qna_email" class="form-control w-100"
                               value="{{ setting() ? setting()->qna_email : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="business_number">business_number</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="business_number" id="business_number" class="form-control w-100"
                               value="{{ setting() ? setting()->business_number : ''}}">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Owner Information
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_name">owner_name</label>
                    </th>
                    <td colspan="3">
                        <input type="text" name="owner_name" id="owner_name" class="form-control w-100"
                               value="{{ setting() ? setting()->owner_name : ''}}">
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">
                        <label for="owner_phone">owner_phone</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_phone" id="owner_phone" class="form-control w-100"
                               value="{{ setting() ? setting()->owner_phone : ''}}">
                    </td>
                    <th class="align-middle">
                        <label for="owner_email">owner_email</label>
                    </th>
                    <td colspan="">
                        <input type="text" name="owner_email" id="owner_email" class="form-control w-100"
                               value="{{ setting() ? setting()->owner_email : ''}}">
                    </td>
                </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleFavicon" tabindex="-1" aria-labelledby="exampleFaviconLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleFaviconLabel">View Favicon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ setting() ? setting()->favicon : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleLogo" tabindex="-1" aria-labelledby="exampleLogoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleLogoLabel">View Logo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ setting() ? setting()->logo : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleog_img" tabindex="-1" aria-labelledby="exampleog_imgLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleog_imgLabel">Modal og_img</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ setting() ? setting()->og_img : '' }}" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="examplethumbnail" tabindex="-1" aria-labelledby="examplethumbnailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="examplethumbnailLabel">View thumbnail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
