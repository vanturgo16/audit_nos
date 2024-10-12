@extends('layouts.master')
@section('konten')
<link rel="stylesheet" href="{{ asset('assets/css/style-form.css') }}" type="text/css"/>

{{-- LOADING BLANK ACTION --}}
<div class="process-container hidden" id="processing">
    <div class="card p-4" style="background-color: rgba(0, 0, 0, 0.7);">
        <div class="col-12 text-center d-flex justify-content-center align-items-center">
            <div class="dotLoading"></div>
        </div>
        <div class="col-12 text-center d-flex justify-content-center align-items-center mt-4">
            <h4 class="text-white textLoading">
                Please Wait
            </h4>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        @include('layouts.alert')

        <div class="row" id="buildForm"></div>
        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Gagal Upload Gambar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                        <div class="row py-4">
                            <div class="col-12 text-center">
                                File Format Or Size Not Accepted, <br>
                                Accepted Format <b>(jpeg,png,jpg) Max 2MB</b>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initial load with data set to null
        var tabParent = idQuestion = idActive = responseAns = null;
        loadForm(tabParent, idQuestion, idActive, responseAns);
        // Function to load the form data
        function loadForm(tabParent, idQuestion, idActive, responseAns) {
            $('#processing').removeClass('hidden');

            $.ajax({
                url: "{{ route('formchecklist.getChecklistForm', encrypt($id)) }}",
                method: 'GET',
                data: {
                    tabParent: tabParent,
                    idQuestion: idQuestion,
                    idActive: idActive,
                    responseAns: responseAns,
                },
                success: function (response) {
                    // Clear the #buildForm content
                    $('#buildForm').html('');

                    // Build the form dynamically with the response data
                    var formHtml = `
                    <input type="hidden" name="idActive" value="${response.idQuestionAct}">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Tab Parent -->
                                <ul class="nav nav-tabs" role="tablist">
                                    ${response.tabLists.map(item => `
                                        <li class="nav-item">
                                            <a class="nav-link ${item.parent_point_checklist === response.tabParentAct ? 'active' : ''}" id="tabBtn" dataTab="${item.parent_point_checklist}" dataQuest="${item.firstIdQuestion}">
                                                <button type="button" class="lmt btn btn-sm ${item.isFullFilled === 1 ? 'btn-success' : 'btn-light'}">
                                                    ${item.parent_point_checklist}
                                                </button>
                                            </a>
                                        </li>
                                    `).join('')}
                                </ul>
                                <!-- Tab -->
                                <div class="tab-content p-2">
                                    <div class="row">
                                        <div class="col-7">
                                            <h4 class="text-bold">${response.tabParentAct}</h4>
                                            <!-- Question Number -->
                                            <div class="mt-3 p-0">
                                                ${response.points.map((item, index) => `
                                                    <a id="questBtn" dataTab="${response.tabParentAct}" dataQuest="${item.id}" class="btn pt-1 pb-1 mb-2 btn-outline-primary ${item.id === response.idQuestionAct ? 'active' : ''} ${item.status_response !== null ? 'btn-success' : ''}">
                                                        ${index + 1}
                                                    </a>
                                                `).join('')}
                                            </div>
                                            <!-- Question -->
                                        </div>
                                        <div class="col-2 text-end">
                                            <label>${response.question.path_input_response ? 'Perbaharui' : 'Upload'} :</label>
                                        </div>
                                        <div class="col-3">
                                            <input class="form-control me-auto" type="file" name="file_checklist">
                                            ${response.question.path_input_response ? `
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailRspImg" class="mt-1 btn btn-sm btn-info waves-effect waves-light loadButton">
                                                <i class="mdi mdi-eye label-icon"></i> | Preview File Anda
                                            </a>` : ''}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive" style="max-height: 70vh;">
                                                <table class="table custom-table w-100">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" class="py-0">
                                                                <h4><span class="badge bg-primary text-bold">${response.question.sub_point_checklist}</span></h4>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 80%;">
                                                                <div style="height: 15vh; overflow-y: auto; width: 100%; overflow-x-auto;">
                                                                    ${response.question.indikator}
                                                                </div>
                                                            </td>
                                                            <td rowspan="2" style="border-left: double 4px black; width: 20%;">
                                                                ${response.question.path_guide_checklist ? `
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label>Gambar Panduan</label>
                                                                        <div class="custom-image-container">
                                                                            <div class="card">
                                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#detailGuideImg">
                                                                                    <img src="{{ url('${response.question.path_guide_checklist}') }}" style="width: 100%; height: auto;" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                                                                    <div class="custom-overlay">
                                                                                        <div class="custom-text mt-4">Lihat Gambar</div>
                                                                                    </div>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>` : ''}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-bottom" style="width: 60%;">
                                                                <div class="row" style="height: 12vh; overflow-y: auto;">
                                                                    <div class="col-8">
                                                                        ${response.options.map((item, index) => `
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" id="option_${index}" name="options" value="${item.meta_name}" ${item.meta_name === response.question.response ? 'checked' : ''}>
                                                                                <label class="form-check-label">${item.meta_name}</label>
                                                                            </div>
                                                                        `).join('')}
                                                                    </div>
                                                                    <div class="col-4 d-flex justify-content-end align-items-end">
                                                                        ${(response.question.ms == 1 || response.question.mg == 1 || response.question.mp == 1) ? `
                                                                            <button type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light" disabled style="text-align: left; width: 100%;">
                                                                                <i class="mdi mdi-alert label-icon"></i> Mandatory : <br>
                                                                                ${response.question.ms == 1 ? '<span class="badge bg-danger">Silver</span>' : ''}
                                                                                ${response.question.mg == 1 ? '<span class="badge bg-danger">Gold</span>' : ''}
                                                                                ${response.question.mp == 1 ? '<span class="badge bg-danger">Platinum</span>' : ''}
                                                                            </button>
                                                                        ` : ''}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Button Nav -->
                                    <div class="row">
                                        <div class="col-3 text-start">
                                            <a id="exitBtn" type="button" href="{{ route('formchecklist.typeChecklistList', encrypt($idPeriod)) }}" class="btn btn-sm btn-danger waves-effect waves-light loadButton">
                                                <i class="mdi mdi-close-box label-icon"></i> | Exit
                                            </a>
                                        </div>
                                        <div class="col-6 d-flex justify-content-center align-items-center">
                                            <div class="btn-group">
                                                <button id="backBtn" dataTab="${response.tabParentPrev}" dataQuest="${response.idQuestionPrev}" 
                                                    style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;" 
                                                    type="button" class="btn btn-secondary waves-effect waves-light loadButton" ${response.idQuestionPrev ? '' : 'disabled'}>
                                                    <i class="mdi mdi-arrow-left-circle label-icon"></i> | Back
                                                </button>
                                                <span style="margin-right: 10px;"></span>
                                                <button id="nextBtn" dataTab="${response.tabParentNext}" dataQuest="${response.idQuestionNext}" 
                                                    style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;"
                                                    type="button" class="btn btn-primary waves-effect waves-light" ${response.idQuestionNext ? '' : 'disabled'}>
                                                    Next | <i class="mdi mdi-arrow-right-circle label-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-3 text-end">
                                            <button id="finishBtn" type="button" class="btn btn-sm btn-success waves-effect waves-light loadButton">
                                                Finish | <i class="mdi mdi-check-circle label-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div
                            </div>
                        </div>
                    </div>
                    <!-- Modals -->
                    ${response.question.path_guide_checklist ? `
                    <div class="modal fade" id="detailGuideImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Gambar Panduan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                                    <div class="row">
                                        <img src="{{ url('${response.question.path_guide_checklist}') }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                    </div>
                                </div>
                                <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    ${response.question.path_input_response ? `
                    <div class="modal fade" id="detailRspImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Gambar Anda</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                                    <div class="row">
                                        <img src="{{ url('${response.question.path_input_response}') }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                    </div>
                                </div>
                                <div class="modal-footer"></div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    `;
                    // Append the form HTML into the #buildForm div
                    $('#buildForm').html(formHtml);
                    $('#processing').addClass('hidden');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#processing').addClass('hidden');
                }
            });
        }

        // Event listener for navigation click (Tab, QuestionNumber, NextNav, PrevNav)
        $(document).on('click', '#tabBtn', function (e) { handleEvent(e, this); });
        $(document).on('click', '#questBtn', function (e) { handleEvent(e, this); });
        $(document).on('click', '#backBtn', function (e) { handleEvent(e, this); });
        $(document).on('click', '#nextBtn', function (e) { handleEvent(e, this); });
        // Function to handle action logic
        function handleEvent(e, btnType) {
            e.preventDefault();
            var tabParent = $(btnType).attr('dataTab');
            var idQuestion = $(btnType).attr('dataQuest');
            var idActive = $('input[name="idActive"]').val();
            var responseAns = $('input[name="options"]:checked').val();
            var responseFile = $('input[name="file_checklist"]')[0].files[0];

            uploadResponseFile(idActive, responseFile, function(success) {
                if (success) { loadForm(tabParent, idQuestion, idActive, responseAns);
                } else { $('#errorModal').modal('show'); }
            });
        }
        function uploadResponseFile(idActive, responseFile, callback) {
            if (responseFile) {
                var formData = new FormData();
                formData.append('idActive', idActive);
                formData.append('responseFile', responseFile);
                $.ajax({
                    url: "{{ route('formchecklist.storeChecklistFile') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response); callback(true);
                    },
                    error: function (error) {
                        console.log(error); callback(false);
                    }
                });
            } else { callback(true);  }
        }
        // Exit Button
        $(document).on('click', '#exitBtn', function (e) {
            // Load Button
            var button = this;button.disabled = true;
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Please wait...';
            setTimeout(function () {
                button.innerHTML = '<i class="mdi mdi-close-box label-icon"></i> | Exit';
                button.disabled = false;
            }, 2000);
        });
        // Finish Button
        $(document).on('click', '#finishBtn', function (e) { 
            e.preventDefault();
            // Load Button
            var button = this;button.disabled = true;
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Please wait...';
            setTimeout(function () {
                button.innerHTML = 'Finish | <i class="mdi mdi-check-circle label-icon"></i>';
                button.disabled = false;
            }, 2000);

            var idActive = $('input[name="idActive"]').val();
            var responseAns = $('input[name="options"]:checked').val();
            var responseFile = $('input[name="file_checklist"]')[0].files.length > 0 
                ? $('input[name="file_checklist"]')[0].files[0] : '';

            var formData = new FormData();
            formData.append('idActive', idActive);
            formData.append('responseAns', responseAns);
            formData.append('responseFile', responseFile);
            $.ajax({
                url: "{{ route('formchecklist.finishChecklist') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    window.location.href = "{{ route('formchecklist.typeChecklistList', encrypt($idPeriod)) }}";
                },
                error: function (error) {
                    console.log(error); 
                    $('#errorModal').modal('show');
                }
            });
        });
    });
</script>
@endsection
