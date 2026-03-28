@extends('layouts.master')
@section('title')
    {{ __('admin.chats') }}
@endsection
@section('css')
    <style>
        #ChatBody {
            overflow: scroll;
            min-height: calc(100vh - 300px);
        }

        .main-content-body-chat {
            display: flex;
        }

        .main-chat-body {
            padding-bottom: 0;
            max-height: 535px;
        }

        .main-chat-footer {
            position: relative;
            bottom: auto;
            left: auto;
            right: auto;
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a class="btn bg-primary text-white btn-sm ml-2" title="{{ __('admin.back') }}"
                href="{{ route('services_app.admin.chats.index') }}">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endslot
        @slot('title')
            {{ __('admin.control') }} {{ __('admin.chats') }} !
        @endslot
    @endcomponent

    <!-- row -->
    <div class="row row-sm main-content-app mb-4">
        <div class="col">
            <div class="card">
                <a class="main-header-arrow" href="" id="ChatBodyHide"><i class="icon ion-md-arrow-back"></i></a>
                <div class="main-content-body main-content-body-chat">
                    <div class="main-chat-header">
                        <div class="main-img-user"><img alt="{{ __('admin.image') }}" src="{{ $user->imageLink }}">
                        </div>
                        <div class="main-chat-msg-name">
                            <h6>{{ $user->name }}</h6>
                        </div>
                    </div><!-- main-chat-header -->
                    <div class="main-chat-body" id="ChatBody">
                        <div class="content-inner">
                            @if (count($user->messages) > 0)
                                @foreach ($user->messages as $message)
                                    @if (!$message->user_id)
                                        <div class="media">
                                            <div class="main-img-user online"><img alt=""
                                                    src="{{ auth()->user()->imageLink }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="main-msg-wrapper left">
                                                    {{ $message->content }}
                                                </div>
                                                <div class="d-flex pd-0">
                                                    @foreach ($message->files as $file)
                                                        <div class="chat-file d-flex align-items-center justify-content-center border rounded bg-light m-2"
                                                            style="width: 200px; height: 150px; overflow: hidden; position: relative;">

                                                            @if ($file->fileExtension === 'mp4')
                                                                <video controls class="w-100 h-100"
                                                                    style="object-fit: cover;">
                                                                    <source src="{{ $file->pathLink }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            @elseif($file->fileExtension === 'pdf')
                                                                <a href="{{ $file->pathLink }}" target="_blank"
                                                                    class="d-flex flex-column align-items-center justify-content-center text-center w-100 h-100">
                                                                    <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                                                                    <span
                                                                        style="font-size: 12px;">{{ __('admin.view_pdf') }}</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ $file->pathLink }}" target="_blank"
                                                                    class="d-flex w-100 h-100">
                                                                    <img src="{{ $file->pathLink }}"
                                                                        class="img-fluid w-100 h-100"
                                                                        style="object-fit: cover; border-radius: 6px;"
                                                                        alt="image">
                                                                </a>
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <span>{{ $message->created_at_formatted }}</span>
                                                    @if ($message->read)
                                                        <i class="fa fa-check-double mr-2"></i>
                                                    @else
                                                        <i class="fa fa-check mr-2"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="media flex-row-reverse">
                                            <div class="main-img-user online"><img alt=""
                                                    src="{{ $user->imageLink }}"></div>
                                            <div class="media-body">
                                                <div class="main-msg-wrapper right">
                                                    {{ $message->content }}
                                                </div>
                                                <div class="d-flex pd-0">
                                                    @foreach ($message->files as $file)
                                                        @php
                                                            $extension = strtolower(
                                                                pathinfo($file->pathLink, PATHINFO_EXTENSION),
                                                            );
                                                            $url = $file->pathLink;
                                                        @endphp

                                                        <div class="chat-file">
                                                            @if ($extension === 'mp4')
                                                                <video controls class="rounded wd-100 ht-100 mr-2">
                                                                    <source src="{{ $url }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            @elseif($extension === 'pdf')
                                                                <a href="{{ $url }}" target="_blank"
                                                                    class="d-flex align-items-center p-2 border rounded bg-light wd-100 ht-100 mr-2">
                                                                    <i class="fas fa-file-pdf text-danger fa-2x ml-2"></i>
                                                                    <span>{{ __('admin.view_pdf') }}</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ $url }}" target="_blank">
                                                                    <img src="{{ $url }}"
                                                                        class="img-fluid rounded wd-100 ht-100 mr-2"
                                                                        style="object-fit: cover;" alt="image">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <span>{{ $message->created_at_formatted }}</span> <a href=""><i
                                                            class="icon ion-android-more-horizontal"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div>
                                    <label class="main-chat-time"><span
                                            style="font-size: 20px">{{ __('admin.no_messages_yet') }}</span></label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div id="filePreview" class="d-flex flex-wrap mb-2"></div>
                <div class="main-chat-footer">
                    <form id="sendMessageForm" method="POST" action="{{ route('services_app.admin.chats.send-message') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                    </form>
                    <nav class="nav">
                        <label for="fileInput" class="nav-link" data-toggle="tooltip" title="Attach a File"
                            style="cursor:pointer; margin-bottom:0; display: block;">
                            <i class="fas fa-paperclip"></i>
                        </label>
                        <input type="file" id="fileInput" name="files[]" style="display:none;" form="sendMessageForm"
                            multiple>
                    </nav>
                    <input class="form-control" placeholder="{{ __('admin.type_your_message_here') }}" type="text"
                        name="content" form="sendMessageForm">
                    <button type="submit" class="main-msg-send" style="background:none; border:none; cursor:pointer;"
                        form="sendMessageForm">
                        <i class="far fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const preview = document.getElementById('filePreview');
            const form = document.getElementById('sendMessageForm');
            const submitBtn = form.querySelector('button[type="submit"]') || document.querySelector(
                '.main-msg-send');
            // allowed extensions (lowercase)
            const allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp4'];
            const chatBody = document.getElementById('ChatBody');

            // improve UX: suggest allowed types to file picker
            fileInput.setAttribute('accept', '.jpg,.jpeg,.png,.gif,.webp,.pdf,.mp4');

            // helper: create preview box for a file item (flex view style)
            function makeFileItem(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                const fileItem = document.createElement('div');

                fileItem.classList.add('d-flex', 'align-items-center', 'justify-content-center', 'border',
                    'rounded', 'p-1', 'mr-2', 'mb-2');
                fileItem.style.width = "120px";
                fileItem.style.height = "100px";
                fileItem.style.overflow = "hidden";
                fileItem.style.position = "relative";
                fileItem.style.background = "#f8f9fa";

                if (ext === "mp4") {
                    const video = document.createElement("video");
                    video.controls = true;
                    video.src = URL.createObjectURL(file);
                    video.style.maxWidth = "100%";
                    video.style.maxHeight = "100%";
                    fileItem.appendChild(video);
                } else if (ext === "pdf") {
                    fileItem.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-file-pdf text-danger fa-2x"></i>
                    <div class="small text-truncate" style="max-width:100px;">${file.name}</div>
                </div>
            `;
                } else {
                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = "100%";
                    img.style.maxHeight = "100%";
                    img.style.objectFit = "cover";
                    img.classList.add("rounded");
                    fileItem.appendChild(img);
                }

                // add small remove button (×) on top-right
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '&times;';
                removeBtn.title = 'Remove';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '2px';
                removeBtn.style.right = '4px';
                removeBtn.style.border = 'none';
                removeBtn.style.background = 'rgba(0,0,0,0.4)';
                removeBtn.style.color = '#fff';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.width = '20px';
                removeBtn.style.height = '20px';
                removeBtn.style.lineHeight = '16px';
                removeBtn.style.padding = '0';
                removeBtn.style.cursor = 'pointer';

                removeBtn.addEventListener('click', () => {
                    removeFileFromInput(file);
                });

                fileItem.appendChild(removeBtn);
                return fileItem;
            }

            // Build new FileList by removing one file
            function removeFileFromInput(targetFile) {
                const dt = new DataTransfer();
                for (let f of fileInput.files) {
                    // keep only files that are not the targetFile (compare name+size+type)
                    if (!(f.name === targetFile.name && f.size === targetFile.size && f.type === targetFile.type)) {
                        dt.items.add(f);
                    }
                }

                fileInput.files = dt.files;
                handleFilesChange(); // refresh preview & validation
            }

            // Validate file extension boolean
            function isAllowedFile(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                return allowed.includes(ext);
            }

            // main logic to run when files changed
            function handleFilesChange() {
                const files = Array.from(fileInput.files);
                preview.innerHTML = "";

                if (fileInput.files.length === 0) {
                    chatBody.style.minHeight = "calc(100vh - 300px)";
                } else {
                    chatBody.style.minHeight = "calc(100vh - 400px)";
                }

                const invalidFiles = [];
                const validFiles = [];

                for (let f of files) {
                    if (!isAllowedFile(f)) {
                        invalidFiles.push(f);
                    } else {
                        validFiles.push(f);
                    }
                }

                // If there are invalid files, remove them from the FileList (so they won't be uploaded)
                if (invalidFiles.length > 0) {
                    const dt = new DataTransfer();
                    for (let f of validFiles) dt.items.add(f);
                    fileInput.files = dt.files;
                }

                // Show valid previews
                for (let f of Array.from(fileInput.files)) {
                    const item = makeFileItem(f);
                    preview.appendChild(item);
                }

                // Show errors for invalid files (if any)
                const existingError = document.getElementById('fileValidationErrors');
                if (existingError) existingError.remove();

                if (invalidFiles.length > 0) {
                    const err = document.createElement('div');
                    err.id = 'fileValidationErrors';
                    err.classList.add('text-danger', 'small', 'mt-1');
                    err.innerHTML = 'These file types are not allowed: ' + invalidFiles.map(f => f.name).join(', ');
                    preview.insertAdjacentElement('afterend', err);
                }

                // disable submit if no files and no message content? (we only handle file validity here)
                // Only disable submit when there was invalid file(s) to force user to fix it
                submitBtn.disabled = (invalidFiles.length > 0);
            }

            // initial handler
            fileInput.addEventListener('change', function() {
                handleFilesChange();
            });

            // final check on submit to be safe
            form.addEventListener('submit', function(e) {
                // re-check extensions before sending
                for (let f of fileInput.files) {
                    if (!isAllowedFile(f)) {
                        e.preventDefault();
                        alert(
                            'One or more files are not allowed. Allowed: jpg, jpeg, png, gif, webp, pdf, mp4'
                        );
                        return false;
                    }
                }
            });

            // Optional: if you have a message input and want to enable send only when either content or files exist:
            // const msgInput = form.querySelector('input[name="content"]');
            // function toggleSubmitByContentOrFiles() {
            //     const hasContent = msgInput && msgInput.value.trim().length > 0;
            //     const hasFiles = fileInput.files.length > 0;
            //     submitBtn.disabled = !(hasContent || hasFiles);
            // }
            // if (msgInput) {
            //     msgInput.addEventListener('input', toggleSubmitByContentOrFiles);
            //     fileInput.addEventListener('change', toggleSubmitByContentOrFiles);
            //     toggleSubmitByContentOrFiles();
            // }

            chatBody.scrollTop = chatBody.scrollHeight;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let checkInterval = setInterval(() => {
                fetch(
                        "{{ route('services_app.admin.chats.check-new-messages', ['user_id' => $user->id]) }}"
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data === true) {
                            clearInterval(checkInterval);

                            const newBtn = `
                                <a class="btn bg-warning text-white btn-sm ml-2"
                                title="{{ __('admin.refresh') }}"
                                href="">
                                    {{ __('admin.there_are_new_messages') }}
                                    <i class="mdi mdi-refresh"></i>
                                </a>
                            `;

                            const container = document.getElementById("pageCustomButtons");
                            if (container) {
                                container.insertAdjacentHTML("afterbegin", newBtn);
                            }
                        }
                    })
                    .catch(err => {
                        console.error("Error checking new messages:", err);
                    });
            }, 5000);
        });
    </script>
@endsection
