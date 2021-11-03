@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-center">Create Book</h5>
                <div class="col-md-10 mx-auto bg-color">
                    <div class="input-content">
                        <form name="createForm">
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <label for="title" class="col-md-2 text-md-right required">Title</label>
                                <div class="col-md-5">
                                    <input type="text" name="title" class="form-control input-margin mt-2">
                                    <span id="titleError"></span>
                                </div>
                            </div>
                            <div class="form-group mt-3 row">
                                <div class="col-md-1"></div>
                                <label for="author" class="col-md-2 text-md-right label-padding required">Author</label>
                                <div class="col-md-5">
                                    <input type="text" name="author" class="form-control input-margin mt-2">
                                    <span id="authorError"></span>
                                </div>
                            </div>
                            <br>
                            <div class="div-border"></div>
                            <br>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary add-btn">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="col-md-10 mx-auto" id="recordDiv">
                    <div class="col-md-5 mx-auto">
                        <div class="input-group">
                            <input class="form-control border-end-0 border" type="search" id="searchInput" placeholder="Search By Title or Author">
                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary bg-white border-start-0 border ms-n5" id="search" onclick="searchBook()">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <strong><i> Export a list with:</i></strong>
                        <div class="col-md-3 mb-2">
                            <select  class="form-select" name="export-list" id="selectBox">
                                <option value="1">Title and Author</option>
                                <option value="2">Only Titles</option>
                                <option value="3">Only Authors</option>
                            </select>
                        </div>
                        <button class="col-md-2 btn btn-success btn-sm  mb-2" onclick="csvExport()">
                            <i class="fas fa-file-download"></i>  Export in CSV
                        </button>
                        <button class="col-md-2 btn btn-success btn-sm  mb-2 ms-2" onclick="xmlExport()">
                            <i class="fas fa-file-download"></i>  Export in XML
                        </button>
                    </div>
                    <br>
                    <span id="successAlert"></span>
                    <span id="noDataAlert"></span>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-6">Title</th>
                                <th class="col-md-3">Author</th>
                                <th class="col-md-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit Author</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="editForm">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="author" class="col-md-2 text-md-right label-padding required">Author</label>
                            <div class="col-md-8">
                                <input type="text" name="author" class="form-control input-margin mt-2" required>
                                <span id="authorError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancle</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
    </div>
    
    <!-- Axios Link -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var tableBody = document.getElementById('tableBody');
        var authorList = document.getElementsByClassName('authorList');
        var titleList = document.getElementsByClassName('titleList');
        var buttonList = document.getElementsByClassName('buttonList');
        var recordDiv = document.getElementById('recordDiv');

        //============== READ ==============
        axios({
            method: 'GET',
            url: '/api/books'
        })
        .then(response => {
            if(response.data.bookList.length != 0) {
                recordDiv.style.display = 'block';
                response.data.bookList.forEach(item => {
                displayData(item);
            })
            }else {
                recordDiv.style.display = 'none';
            }
        })
        .catch(error => console.log(error));

        //============== CREATE ==============
        var createForm = document.forms['createForm'];
        var titleInput = createForm['title'];
        var authorInput = createForm['author'];
        createForm.onsubmit = function(event) {
            event.preventDefault();
            axios({
                method: 'POST',
                url: '/api/books',
                data: {
                    title: titleInput.value,
                    author: authorInput.value,
                }
            })
            .then(response => {
                var titleError = document.getElementById('titleError');
                var authorError = document.getElementById('authorError');
                if(response.data.message == 'Data created successfully') {
                    recordDiv.style.display = 'block';
                    alertMessage(response.data.message);
                    createForm.reset();
                    displayData(response.data.book);
                    titleError.innerHTML = authorError.innerHTML = '';
                }else {
                    recordDiv.style.display = 'none';
                    titleError.innerHTML = titleInput.value == '' ? '<i class="text-danger">'+response.data.msg.title+'</i>' : '';
                    authorError.innerHTML = authorInput.value == '' ? '<i class="text-danger">'+response.data.msg.author+'</i>' : '';
                }
            })
            .catch(error => {
                console.log(error)
            });
        }
        //==============EDIT & UPDATE==============
        var editForm = document.forms['editForm'];
        var editAuthorInput = editForm['author'];
        var id,oldAuthor,oldTitle;

        //============== EDIT ==============
        function editAuthor(idToUpdate) {
            id = idToUpdate;
            axios.get('api/books/'+id)
            .then(response => {
                editAuthorInput.value = oldAuthor = response.data.book.author;
                oldTitle = response.data.book.title;
            })
            .catch(error => console.log(error));
        }

        //============== UPDATE ==============
        editForm.onsubmit = function(event) {
            event.preventDefault();
            axios.put('api/books/'+id, {
                author : editAuthorInput.value,
                })
                .then(response => {
                    alertMessage(response.data.message);
                    $('#editModal').modal('hide');
                    for(var i=0; i<authorList.length; i++) {
                        if(authorList[i].innerHTML == oldAuthor && titleList[i].innerHTML == oldTitle) {
                            authorList[i].innerHTML = editAuthorInput.value;
                        }
                    }
                })
                .catch(error => console.log(error));
        }
        //============== DELETE ==============
        function deleteAuthor(id) {
            if(confirm('Are you sure to delete?')) {
                axios.delete('api/books/'+id)
                    .then(response => {
                        if(response.data.bookList.length != 0) {
                            recordDiv.style.display = 'block';
                        }else {
                            recordDiv.style.display = 'none';
                        }
                        alertMessage(response.data.message);
                        for(var i=0; i<titleList.length; i++) {
                            if(titleList[i].innerHTML == response.data.deletedBook.title){
                                titleList[i].style.display = authorList[i].style.display = buttonList[i].style.display = 'none';
                            }
                        }
                    })
                    .catch(error => console.log(error));
            }
        }
        //============== SEARCH ==============
        function searchBook() {
            var searchInput = document.getElementById('searchInput').value;
            tableBody.innerHTML = '';
            var noDataAlert = document.getElementById('noDataAlert');
            axios.post('/api/search', {
                searchWord : searchInput,
            }).then( response => {
                if(response.data.bookList.length == 0 ) {
                    noDataAlert.innerHTML = 
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>No records found!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }else{
                    noDataAlert.innerHTML = '';
                    response.data.bookList.forEach(item => {
                    tableBody.innerHTML +=
                    '<tr>'+
                        '<td class="titleList">'+item.title+'</td>'+
                        '<td class="authorList">'+item.author+'</td>'+
                        '<td class="buttonList"><button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editAuthor('+item.id+')"><i class="fa fa-edit"></i> </button>'+
                            '<button type="submit" class="btn btn-outline-secondary btn-sm ms-2" onclick="deleteAuthor('+item.id+')"> <i class="fa fa-trash" aria-hidden="true"></i></button>'+
                        '</td>'+
                    '</tr>';
                    })
                }
            }).catch(error => console.log(error));
        }
        //============== CSV EXPORT ==============
        function csvExport() {
            var selectedValue = document.getElementById('selectBox').value;
            var url = 'api/csv-export/'+selectedValue;
            window.location = url;
        }
        //============== XML EXPORT ==============
        function xmlExport() {
            var selectedValue = document.getElementById('selectBox').value;
            var url = 'api/xml-export/'+selectedValue;
            var fileName;
            if(selectedValue == 1) {
                fileName = 'title-author-list';
            }else if (selectedValue == 2) {
                fileName = 'title-list';
            }else {
                fileName = 'author-list';
            }
            download(url, fileName);
        }
        //============== HELPER FUNCTIONS ==============
        function displayData(data) {
            tableBody.innerHTML +=
                '<tr>'+
                    '<td class="titleList">'+data.title+'</td>'+
                    '<td class="authorList">'+data.author+'</td>'+
                    '<td class="buttonList"><button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editAuthor('+data.id+')"><i class="fa fa-edit"></i> </button>'+
                        '<button type="submit" class="btn btn-outline-secondary btn-sm ms-2" onclick="deleteAuthor('+data.id+')"> <i class="fa fa-trash" aria-hidden="true"></i></button>'+
                    '</td>'+
                '</tr>';
        }
        function alertMessage(message) {
            document.getElementById('successAlert').innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+message+'</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        function download(url, fileName) {
            var a = document.createElement('a');
            a.href = url;
            a.setAttribute('download', fileName);
            a.click();
        }

    </script>
@endsection