@extends('backend.master')

@section('maincontent')
    @section('title')
        Ayebazar- Basicinfo
    @endsection

<div class="container-fluid pt-4 px-4">
    <div class="row">

        <div class="col-sm-12 col-md-12 col-xl-12 mb-4">
            <div class="bg-secondary rounded h-100 p-4">
                <h2 class="mb-4" style="text-align: center;color:red">Website Basic Information Update</h2>
                <form action="{{ route('admin.basicinfos.update',$webinfo->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" value="{{ $webinfo->email }}" id="floatingInput"
                                    placeholder="name@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="phone_one" value="{{ $webinfo->phone_one }}" id="floatingPassword"
                                    placeholder="Phone One">
                                <label for="floatingPassword">Phone One</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="phone_two" value="{{ $webinfo->phone_two }}" id="floatingPassword"
                                    placeholder="Phone Two">
                                <label for="floatingPassword">Phone Two</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Office Address" name="address"
                                    id="floatingTextarea" style="height: 100px;">{{ $webinfo->address }}</textarea>
                                <label for="floatingTextarea">Office Address</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <input class="form-control form-control-lg bg-dark" name="logo" id="formFileLg" type="file">
                            </div>
                            <div class="m-3 ms-0" style="text-align: center;height: 156px;margin-top:50px !important">
                                <h4 style="width:30%;float: left;text-align: left;">LOGO : </h4>
                                <img src="{{ asset($webinfo->logo) }}" alt="" srcset="" style="max-height: 100px;">
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12 mb-4">
            <div class="bg-secondary rounded h-100 p-4">
                <h2 class="mb-4" style="text-align: center;color:red">Pixel & Analytics</h2>
                <form action="{{ url('/admin/pixel/analytics',$webinfo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Facebook Pixel" name="facebook_pixel"
                                    id="floatingTextarea" style="height: 150px;">{{ $webinfo->facebook_pixel }}</textarea>
                                <label for="floatingTextarea">Facebook Pixel</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Google Analytics" name="google_analytics"
                                    id="floatingTextarea" style="height: 150px;">{{ $webinfo->google_analytics }}</textarea>
                                <label for="floatingTextarea">Google Analytics</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h2 class="mb-4" style="text-align: center;color:red">Social Links Update</h2>
                <form action="{{ url('/admin/basicinfo/update',$webinfo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="facebook" value="{{ $webinfo->facebook }}" id="floatingInput"
                                    placeholder="https://www.facebook.com/">
                                <label for="floatingInput">Facebook</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="twitter" value="{{ $webinfo->twitter }}" id="floatingInput"
                                    placeholder="https://www.twitter.com/">
                                <label for="floatingInput">Twitter</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="google" value="{{ $webinfo->google }}" id="floatingInput"
                                    placeholder="https://google.com">
                                <label for="floatingInput">Google</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="rss" value="{{ $webinfo->rss }}" id="floatingInput"
                                    placeholder="https://www.rss.org/">
                                <label for="floatingInput">RSS</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="pinterest" value="{{ $webinfo->pinterest }}" id="floatingInput"
                                    placeholder="https://www.pinterest.com/">
                                <label for="floatingInput">Penterest</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="linkedin" value="{{ $webinfo->linkedin }}" id="floatingInput"
                                    placeholder="https://www.linkedin.com/">
                                <label for="floatingInput">Linkedin</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="youtube" value="{{ $webinfo->youtube }}" id="floatingInput"
                                    placeholder="https://www.Youtube.com/">
                                <label for="floatingInput">Youtube</label>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
