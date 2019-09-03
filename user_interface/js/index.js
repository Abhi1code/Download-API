$(document).ready(function() {
    $("#up").html("");

    $("#upload").click(function(e) {
        e.preventDefault();
        if ($("#file").val() != null && $("#file").val() != '') {
            con_file();
        } else {
            alert("Please upload file");
        }

    });

    function con_file() {
        $("#up").html("uploading..");
        var data = new FormData();
        var property = document.getElementById("file").files[0];
        var file_name = property.name;
        var file_extension = file_name.split('.').pop().toLowerCase();

        if (property.size > 10000000) {
            alert("Upload within 10 mb");
        } else {
            data.append("file", property);
            $.ajax({
                url: './../upload/index.php',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: 'POST',
                success: function(response) {
                    $("#up").html("");
                    if (response) {
                        var data = JSON.parse(response);
                        console.log(response);

                        if (data['code_status'] == '200') {
                            alert("File uploaded");
                            $("#id:text").val(data['unique_id']);
                        } else {
                            alert(response['error_status']);
                        }
                    } else {
                        alert("Resource not found");
                    }
                },
                error: function(error) {
                    $("#up").html("");
                    alert("something  went wrong");
                    //location.reload();
                }
            });
        }

    }

    $('#downloadfile').click(function(e) {
        e.preventDefault();
        var url = $('#url').val();
        var token = $('#token').val();

        if (/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url) && token) {

            var data = {
                url: $('#url').val(),
                token: $('#token').val()
            };

            $.post("./../download/index.php", data, function(data, status) {

                if (data) {
                    var response = JSON.parse(data);
                    console.log(data);
                    if (response && data) {

                        if (response['code_status'] == '200') {
                            alert("unique id generated");
                            $("#id:text").val(response['unique_id']);
                        } else {
                            alert(response['error_status']);
                        }

                    } else {
                        alert("something went wrong");
                    }
                } else {
                    alert("Resource not found");
                }
            });

        } else {
            alert("Invalid url or token");
        }
    });

    $('#status').click(function(e) {
        e.preventDefault();
        var id = $('#id').val();

        if (id) {

            $.get("./../status/index.php", { id: $('#id').val() }, function(data, status) {

                if (data) {
                    var response = JSON.parse(data);
                    console.log(data);
                    if (response && data) {

                        if (response['code_status'] == '200') {

                            $("#dstatus").html(response['file_status_info']);
                            $("#size").html(response['filesize']);
                            $("#downloadedsize").html(response['updatesize']);
                            $("#remainingsize").html(response['remainingsize']);
                            $("#time").html(response['estimatedtime']);

                        } else {
                            alert(response['error_status']);
                        }

                    } else {
                        alert("something went wrong");
                    }
                } else {
                    alert("Resource not found");
                }
            });

        } else {
            alert("Invalid unique id");
        }
    });

    $('#geturl').click(function(e) {
        e.preventDefault();
        var id = $('#id').val();

        if (id) {

            $.get("./../file/index.php", { id: $('#id').val() }, function(data, status) {

                if (data) {
                    var response = JSON.parse(data);
                    console.log(data);
                    if (response && data) {

                        if (response['code_status'] == '200') {

                            $("#download_url").html(response['filename']);

                        } else {
                            alert(response['error_status']);
                        }

                    } else {
                        alert("something went wrong");
                    }
                } else {
                    alert("Resource not found");
                }
            });

        } else {
            alert("Invalid unique id");
        }
    });

});