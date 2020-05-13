@extends('layouts.dashboard_master')
@section('title', 'Verify')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-face"></i>Upload Identification Document</h3>
            </div>
            

            <div class="content with-padding padding-bottom-0">
                <form method="post" action="/update_freelancer_documents" enctype="multipart/form-data">
                    @csrf
                    <ul class="fields-ul">
                    <li>
                        <div class="row">

                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <h5>Attachments</h5>
                                    
                                    <!-- Upload Button -->
                                    <div class="uploadButton margin-top-0">
                                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload" name="documents[]" multiple required/>
                                        <label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                                        <span class="uploadButton-file-name">Maximum file size: 2 MB</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <button type="submit" class="button ripple-effect">Submit </button>
                                </div>
                            </div> 

                        </div>
                    </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection