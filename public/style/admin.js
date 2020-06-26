$(document).ready(function () {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        showConfirmButton: false,
        timer: 3000
    });

    CKEDITOR.replace('editor1', {
        height : 50,
        language: 'ru',
        toolbarGroups : [
            { name: 'styles', groups: [ 'styles' ] },

        ],
    });

    CKEDITOR.replace('editor2', {
        language: 'ru',
        toolbarGroups : [

            { name: 'styles', groups: [ 'styles' ] },

        ],
    });
    CKEDITOR.replace('editor3', {
        language: 'ru',

        toolbarGroups : [
            { name: 'mode' },

            { name: 'insert' },
        ],
    });

    CKEDITOR.replace('editor4', {
        language: 'ru',
        codeSnippet_theme: 'monokai_sublime',
        height : 800,
        toolbarGroups : [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        extraPlugins : 'imageresizerowandcolumn, codesnippet, templates'


});





    $("#editor").change(function () {

    });


// сохранение поста
    $("#add-post").on("click", function () {
        let data = {};
        let meta = {};
        data["public"] = 0;
        let name = 0;
        let content = 0;
        $("#quickForm").find('input, textarea, select').each(function () {


            if (this.name === "groupName") {
                return
            }

            if ($(this).attr("type") === "checkbox") {
                if ($(this).prop("checked")) {
                    data[this.name] = 1;
                    return;
                } else {
                    data[this.name] = 0;
                    return;
                }
            }



            if (this.name === "monolog") {
                data[this.name] = this.value;
                return;
            }


            if (this.name === "image") {
                data[this.name] = CKEDITOR.instances.editor3.getData();
                return;
            }

            if (this.name === "content") {
                data[this.name] = CKEDITOR.instances.editor4.getData();
                return;
            }


            if (this.name !== "name[]") {
                data[this.name] = $(this).val()
            }

        });

        let got = $('textarea[name="name[]"]').map(function(){
            return this.value;
        }).get();

        let cont = $('textarea[name="content[]"]').map(function(){
            return this.value;
        }).get();


        data["meta"] = [];
        data['meta'] = {name:got, content: cont }
        $.ajax({
            type: "POST",
            url: "/admin/save-post",
            dataType: "script",
            data: data
        });
    });

    // добавление меты в интеорфейсе
    $(".fa-plus-circle").click(function () {
       let element = $(this).parents(".clone").clone();
       element.find(".textarea").val("");
       element.find(".fa-plus-circle").removeClass("fa-plus-circle").addClass("fa-minus-circle");
       element.appendTo(".meta");
    });

    // удаление меты в интеорфейсе
    $(".row").on("click", " .fa-minus-circle", function () {
        $(this).parents(".clone").remove();
    });

    // добавление категории
    $("#go").click(function () {
        let data = {name: $("#group-name").val()};
        if (data.name !== "") {
            $.ajax({
                type: "POST",
                url: "/admin/group",
                data: data,
                success: function (data) {
                    let contentAdmin = $("#content-admin");
                    contentAdmin.empty();
                    contentAdmin.html(data);
                    $("#group-name").val("");
                }
            });
        }
    });

    $("#list-posts").click(function () {
        let content = $("#content");
        // window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath)
        //
        // console.log(base);
        $.ajax({
            type: "GET",
            url: "/admin/lists",
            // data: data,
            success: function (data) {
                window.history.pushState(data, 'Title', '/lists');
                content.empty()
                content.html(data)
            }
        });
    });

    $("#sett-collapse").click(function () {
        let data;
        if ($(this).data("check") === 1) {
            data = {check: 0};
        } else {
            data = {check: 1}
        }

        $.ajax({
            type: "POST",
            url: "/admin/user-setting",
            // dataType: "JSON",
            data: data,
            success: function (data) {

            }
        });
    });


    $('#mySelect2').on('change', function (e) {
        $.ajax({
            type: "POST",
            url: "/admin/filter-group",
            // dataType: "JSON",
            data: {category_id: this.value},
            success: function (data) {
                let contentAdmin = $("#listPosts");
                contentAdmin.empty();
                contentAdmin.html(data);
            }
        });
    });

    // end listPosts

    // like \ dis like


    $("#like").click(function () {
        $.ajax({
            type: "POST",
            url: "/post/like",
            dataType: "script",
            data: {postId: $("#postId").val()}

        });
    });

    $("#dis_like").click(function () {
        $.ajax({
            type: "POST",
            url: "/post/dis-like",
            dataType: "script",
            data: {postId: $("#postId").val()}
        });
    })
});