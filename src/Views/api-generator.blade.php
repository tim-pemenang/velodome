<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <style>
        body {
            background-color: whitesmoke;
        }

        .file-upload {
            background-color: #ffffff;
            width: 600px;
            border-radius: 8px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 12px 14px 35px -12px rgba(0,0,0,0.32);
            -webkit-box-shadow: 12px 14px 35px -12px rgba(0,0,0,0.32);
            -moz-box-shadow: 12px 14px 35px -12px rgba(0,0,0,0.32);
        }

        .file-generate-btn {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-generate-btn:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-generate-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-analyze-btn {
            width: 100%;
            margin: 20px 0;
            color: #fff;
            background: #48bbdb;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #23758c;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-analyze-btn:hover {
            background: #197892;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-analyze-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-upload-content {
            display: none;
            text-align: center;
        }

        .file-upload-input {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-wrap {
            background-color: #f3f6f9;
            margin-top: 20px;
            border: 2px dashed #d7e9fb;
            border-radius: 8px;
            position: relative;
        }

        .image-dropping,
        .image-upload-wrap:hover {
            background-color: rgb(216, 216, 216);
            border: 1px dashed grey;
        }

        .image-title-wrap {
            padding: 0 15px 15px 15px;
            color: #000;
        }

        .drag-text {
            text-align: center;
        }

        .drag-text h3 {
            font-weight: 100;
            color: #555;
            padding: 60px 0;
        }

        .file-upload-image {
            max-height: 400px;
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }

        .remove-image {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image:active {
            border: 0;
            transition: all .2s ease;
        }

        .output-analyze-wrap {
            position: relative;
            background-color: #ffffff;
            width: 600px;
            min-height: 300px;
            border: 1px solid whitesmoke;
            border-radius: 8px;
            margin: 30px auto;
            padding: 20px;
        }
    </style>

    <title>Velodome</title>
</head>

<body>

    <!-- Nav -->
    <nav class="navbar navbar-dark" style="background-color: #343541;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://cdn.iconscout.com/icon/free/png-256/free-openai-1524262-1290809.png" alt="" width="30"
                    height="30" class="d-inline-block align-text-top">
                    Velodome
            </a>
        </div>
    </nav>

    @if(!$props)
    <!-- Image input -->
    <div class="file-upload">
        <form action="/velodome/api-generator/analize" method="post" enctype="multipart/form-data">

        <div class="image-upload-wrap">
            <input class="file-upload-input" type='file' name="file" id="file" onchange="readURL(this);" accept="image/*" />
            <div class="drag-text">
                <h3>Drag and drop a file or select add Image</h3>
            </div>
        </div>
        <div class="file-upload-content">
            <img class="file-upload-image" src="#" alt="your image" />
            <div class="image-title-wrap">
                <button type="button" onclick="removeUpload()" class="remove-image">Remove <span
                        class="image-title">Uploaded Image</span> </button>
            </div>
        </div>
        <button  type="submit"  class="file-analyze-btn">Analyze Image</button>
    </form>
    </div>

    @else
    {{-- Form input --}}
    <form action="/velodome/api-generator/generate" method="post" enctype="multipart/form-data">
        <div class="output-analyze-wrap">
            <div class="form-group">
                <label for="object_name" class="form-label">Model name</label>
                <input class="form-control" name="object_name" id="object_name" placeholder="Enter name model..." value="{{ $object_name ?? '' }}">
            </div>
        
            <div class="form-group">
                <label for="object_name" class="form-label">Model property</label>
            <textarea class="form-control mb-3" name="props" id="props" rows="9" >{{ $props }}</textarea>
            </div>
            <div class="align-self-end">
                <button type="submit" class=" file-generate-btn">Generate</button>
            </div>
        </div>
    </form>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
       
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.image-upload-wrap').hide();

                    $('.file-upload-image').attr('src', e.target.result);
                    $('.file-upload-content').show();

                    $('.image-title').html(input.files[0].name);
                };

                reader.readAsDataURL(input.files[0]);

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
            $('.image-upload-wrap').removeClass('image-dropping');
        });
    </script>
</body>

</html>
