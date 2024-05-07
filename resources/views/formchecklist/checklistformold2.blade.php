@extends('layouts.master')

@section('konten')

<style>
    .btn-success {
        color: rgb(255, 255, 255);
        background-color: #2ab57d;
    }
    .btn-outline-primary.active {
        color: #5156be;
        background-color: #ffffff;
        box-shadow: inset 0 0 0 3px #5156be;
        transform: scale(1.15); 
    }
    .btn-success.active {
        color: rgb(255, 255, 255);
        background-color: #2ab57d;
        box-shadow: inset 0 0 0 3px #5156be;
        transform: scale(1.15); 
    }

    /* Style Image Hover */
    .custom-image-container {
        position: relative;
        width: 100%;
    }
    .custom-image-container:hover .custom-overlay {
        opacity: 1;
    }
    .custom-image-container img {
        width: 100%;
        display: block;
    }
    .custom-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .custom-text {
        color: white;
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Form Checklist {{$type->type_checklist}} ( {{$period}} )</h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('formchecklist.typechecklist', encrypt($id_period)) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php $tab = 0;?> 
                            @foreach ($point as $poin)
                            <?php $tab++ ;?>
                            <li class="nav-item">
                                <a class="nav-link @if(in_array($poin->parent_point, $ansfull)) complete @endif @if($tab === $tabo ? 'active' : '') active @endif" data-bs-toggle="tab" href="#point{{$tab}}" role="tab">
                                    @if(in_array($poin->parent_point, $ansfull))
                                        <button type="button" class="d-block d-sm-none lmt btn btn-sm btn-success" >{{$poin->parent_point}}</button>
                                        <button type="button" class="d-none d-sm-block lmt btn btn-sm btn-success" >{{$poin->parent_point}}</button>
                                    @else
                                        <button type="button" class="d-block d-sm-none lmt btn btn-sm btn-light" >{{$poin->parent_point}}</button>
                                        <button type="button" class="d-none d-sm-block lmt btn btn-sm btn-light" >{{$poin->parent_point}}</button>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted" >

                            <?php $tab = 0;?>
                            @foreach ($point as $poin)
                                <?php $tab++ ;?>

                                <div class="tab-pane @if($tab === $tabo ? 'active' : '') active @endif" id="point{{$tab}}" role="tabpanel">
                                    <form action="{{ route('formchecklist.store', encrypt($id_period)) }}" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" name="parent_point" value="{{$poin->parent_point}}">
                                                <input type="hidden" name="tabo" value="{{$tab}}">
                                                <input type="hidden" name="id_jaringan" value="{{$id}}">
                                                <input type="hidden" name="sum_point" value="{{count($point)}}">

                                                @php
                                                    $file = "";
                                                    foreach($file_point as $file){
                                                        if($poin->parent_point == $file->parent_point){
                                                            $file = $file->path_url;
                                                            break;
                                                        }else{
                                                            $file= "";
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                <h4 class="mb-3">
                                                    <i class="mdi mdi-arrow-right text-primary me-1"></i>
                                                    <span class="badge bg-primary"> {{$poin->parent_point}}</span>
                                                </h4>
                                                <div class="card px-2 py-2 mb-0">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            @if($file != "")
                                                                <label for="">Update File</label>
                                                            @else
                                                                <label for="">Upload File *(If Any)</label>
                                                            @endif
                                                            <input class="form-control me-auto" type="file" name="file_parent" placeholder="input File">
                                                        </div>
                                                        <div class="col-6">
                                                            @if($file != "")
                                                                <label for="">File Before</label>
                                                                <br>
                                                                <a href="{{ url($file) }}"
                                                                    type="button" class="btn btn-info waves-effect btn-label waves-light" download="File {{$type->type_checklist}}_{{$poin->parent_point}}">
                                                                    <i class="mdi mdi-download label-icon"></i> Download
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-header px-1">
                                            <?php $no = 0;?> 
                                            @foreach ($datas as $data)
                                                @if ($poin->parent_point == $data->parent_point)
                                                    <?php $no++ ;?>
                                                    <a href="#{{$tab}}question{{$data->id_assign}}" class="btn btn{{$tab}} pt-1 pb-1 mb-1 btn-outline-primary" id="btn{{$tab}}" data-ind="{{ $data->id_assign }}">{{$no}}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        <input type="hidden" name="id_checklist_jaringan" value="{{$id}}">

                                        <div class="row">
                                            <div class="@if($poin->path_guide != null) col-lg-9 @else col-lg-12 @endif">
                                                <table class="table w-100" style="height: 43vh;">
                                                    <tbody>
                                                        <?php $no = 0;?> 
                                                        @foreach ($datas as $data)
                                                            @if ($poin->parent_point == $data->parent_point)
                                                            <?php $no++ ;?>
    
                                                            <tr class="soal{{$tab}}">
                                                                <td style="text-align: left; border-bottom: 0px;">
                                                                    <h3><span class="badge bg-primary"><b>{{ $no }}</b></span></h3>
                                                                </td>
                                                                <td style="border-bottom: 0px;">
                                                                    <h5><b>{{ $data->sub_point_checklist }}</b></h5>
                                                                    <div style="max-height: 25vh; overflow-y: auto;">
                                                                        {!! $data->indikator !!}
                                                                    </div>
                                                                    
                                                                    @foreach($data->mark as $mark)
                                                                        @php 
                                                                            $checked = "";
                                                                            foreach($respons as $respon)
                                                                            {
                                                                                if($data->id_assign == $respon->id_assign_checklist && $mark['meta_name'] == $respon->response){
                                                                                    $checked = "checked";
                                                                                    break;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="{{$tab}}question{{$data->id_assign}}" id="{{$tab}}question{{$data->id_assign}}_{{$mark['id']}}" value="{{$mark['meta_name']}}" {{$checked}} >
                                                                            <label class="form-check-label" for="{{$tab}}question{{$data->id_assign}}_{{$mark['id']}}">{{$mark['meta_name']}}</label>
                                                                        </div>
    
                                                                    @endforeach
                                                                    <br>
                                                                    @if($data->mandatory_silver == 0 && $data->mandatory_gold == 0 && $data->mandatory_platinum == 0)
                                                                    @else
                                                                    <button type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light" disabled>
                                                                        <i class="mdi mdi-alert label-icon"></i>Mandatory : 
                                                                        @if($data->mandatory_silver == 1)
                                                                            <span class="badge bg-danger">Silver</span>
                                                                        @endif
                                                                        @if($data->mandatory_gold == 1)
                                                                            <span class="badge bg-danger">Gold</span>
                                                                        @endif
                                                                        @if($data->mandatory_platinum == 1)
                                                                            <span class="badge bg-danger">Platinum</span>
                                                                        @endif
                                                                    </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
    
                                                            @endif
                                                        @endforeach
    
                                                    </tbody>
                                                </table>
        
                                                <hr class="mb-0">
                                                <div class="card-body">
                                                    @php
                                                        $totalCount = 0;
                                                    @endphp
                                                    @foreach ($datas as $data)
                                                        @if ($poin->parent_point == $data->parent_point)
                                                            @php
                                                                $totalCount++;
                                                            @endphp
                                                            <input type="hidden" id="bpoin{{ $tab }}[]" value="{{ $data->id_assign }}">
                                                        @endif
                                                    @endforeach
                                                    <input type="hidden" id="totalCount{{$tab}}" value="{{ $totalCount }}">
                                                    
                                                    <div class="col-12">
                                                        <div class="btn-group mt-2" role="group" aria-label="Navigasi Quiz">
                                                            <button id="backBtnback{{$tab}}"
                                                                type="submit" name="back" value="1" class="btn btn-secondary waves-effect waves-light loadButton"
                                                                style="border-top-left-radius: 20px; border-bottom-left-radius: 20px; display: none;">
                                                                <i class="mdi mdi-arrow-left-circle label-icon"></i> | Back
                                                            </button>
                                                            <button id="backBtn{{$tab}}"
                                                                type="button" class="btn btn-secondary waves-effect waves-light"
                                                                style="border-top-left-radius: 20px; border-bottom-left-radius: 20px; display: none;">
                                                                <i class="mdi mdi-arrow-left-circle label-icon"></i> | Back
                                                            </button>
                                                            <span style="margin-right: 10px;"></span>
                                                            <button id="nextBtn{{$tab}}"
                                                                type="button" class="btn btn-primary waves-effect waves-light"
                                                                style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; display: none;">
                                                                Next | <i class="mdi mdi-arrow-right-circle label-icon"></i>
                                                            </button>
        
                                                            @if(count($point) == $tab)
                                                                <button id="submitBtn{{$tab}}"
                                                                    type="submit" class="btn btn-success waves-effect waves-light loadButton"
                                                                    style="border-top-right-radius: 5px; border-bottom-right-radius: 5px; display: none;">
                                                                    Finish | <i class="mdi mdi-check-circle label-icon"></i>
                                                                </button>
                                                            @else
                                                                <button id="submitBtn{{$tab}}"
                                                                    type="submit" class="btn btn-primary waves-effect waves-light loadButton"
                                                                    style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; display: none;">
                                                                    Next | <i class="mdi mdi-arrow-right-circle label-icon"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($poin->path_guide != null)
                                                <div class="col-lg-3">
                                                    <div class="custom-image-container">
                                                        <a href="{{url($poin->path_guide)}}" target="_blank">
                                                            <img src="{{url($poin->path_guide)}}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{url('path_to_placeholder_image')}}'; this.alt='Image not found';">
                                                            <div class="custom-overlay">
                                                                <div class="custom-text">View Full Image</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                
                                    </form>
                                </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


@php
    $j = 0;
@endphp

@foreach($point as $point)
    @php
        $j++;
    @endphp
    @foreach($datas as $data)
        @if($point->parent_point == $data->parent_point)
            <script>
                var indx{{$j}} = document.getElementById('totalCount'+{{ $j }}).value;
                const questions{{$j}} = document.querySelectorAll('.soal{{$j}}');
                const buttons{{$j}} = document.querySelectorAll('.btn{{$j}}');
                const nextBtn{{$j}} = document.getElementById('nextBtn{{$j}}');
                const backBtn{{$j}} = document.getElementById('backBtn{{$j}}');
                const submitBtn{{$j}} = document.getElementById('submitBtn{{$j}}');

                // var indx = document.getElementById('totalCount').value;
                console.log(indx{{$j}});

                let currentQuestionIndex{{$j}};
                if({{ $lastindex }} === 0){
                    currentQuestionIndex{{$j}} = 0;
                } else {
                    currentQuestionIndex{{$j}} = indx{{$j}}-1;
                }


                let ind{{ $j }} = 0;

                function showQuestion{{$j}}(index) {
                    questions{{$j}}.forEach((question, i) => {
                        question.style.display = i === index ? 'block' : 'none';
                    });
                }

                function toggleButtons{{$j}}() {
                    nextBtn{{$j}}.style.display = currentQuestionIndex{{$j}} < questions{{$j}}.length - 1 ? 'block' : 'none';

                    if (currentQuestionIndex{{$j}} <= 0) {
                        document.getElementById('backBtnback1').disabled = true;
                    } else {
                        document.getElementById('backBtnback1').disabled = false;
                    }
                    backBtn{{$j}}.style.display = currentQuestionIndex{{$j}} > 0 ? 'block' : 'none';
                    backBtnback{{$j}}.style.display = currentQuestionIndex{{$j}} > 0 ? 'none' : 'block';
                    submitBtn{{$j}}.style.display = currentQuestionIndex{{$j}} === questions{{$j}}.length - 1 ? 'block' : 'none';
                }

                function setActiveButton{{$j}}(index) {
                    buttons{{$j}}.forEach((button, i) => {
                        const dataInd = button.getAttribute('data-ind');

                        if($('input[name="{{$j}}question'+dataInd+'"]').is(':checked')) {
                            var element = $('a[data-ind="'+dataInd+'"]');
                            if (element.length > 0) {
                                element.addClass('btn-success');
                            } else {
                                element.removeClass('btn-success');
                            }
                        } else {
                            var element = $('a[data-ind="'+dataInd+'"]');
                            if (element.length > 0) {
                                element.removeClass('btn-success');
                            } else {
                                element.removeClass('btn-success');
                            }
                        }

                        if (i === index) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    });
                }

                function setButtonColor{{$j}}(idAssgn) {
                    if($('input[name="{{$j}}question'+idAssgn+'"]').is(':checked')) {
                        var element = $('a[data-ind="'+idAssgn+'"]');
                        if (element.length > 0) {
                            element.addClass('btn-success');
                        } else {
                            element.removeClass('btn-success');
                        }
                    } else {
                        var element = $('a[data-ind="'+idAssgn+'"]');
                        if (element.length > 0) {
                            element.removeClass('btn-success');
                        } else {
                            element.removeClass('btn-success');
                        }
                    }
                }

                showQuestion{{$j}}(currentQuestionIndex{{$j}});
                toggleButtons{{$j}}();
                setActiveButton{{$j}}(currentQuestionIndex{{$j}});
                setButtonColor{{$j}}();

                buttons{{$j}}.forEach((button, index) => {
                    button.addEventListener('click', () => {
                        currentQuestionIndex{{$j}} = index;
                        showQuestion{{$j}}(currentQuestionIndex{{$j}});
                        toggleButtons{{$j}}();
                        setActiveButton{{$j}}(currentQuestionIndex{{$j}});
                        setButtonColor{{$j}}();
                    });
                });

                nextBtn{{$j}}.addEventListener('click', () => {
                    currentQuestionIndex{{$j}}++;
                    showQuestion{{$j}}(currentQuestionIndex{{$j}});
                    toggleButtons{{$j}}();
                    setActiveButton{{$j}}(currentQuestionIndex{{$j}});

                    const currentInputs = document.querySelectorAll(`input[id="bpoin{{ $j }}[]"]`);
                    idAssgn = currentInputs[ind{{$j}}].value;
                    setButtonColor{{$j}}(idAssgn);
                    ind{{$j}}++;
                });

                backBtn{{$j}}.addEventListener('click', () => {
                    currentQuestionIndex{{$j}}--;
                    showQuestion{{$j}}(currentQuestionIndex{{$j}});
                    toggleButtons{{$j}}();
                    setActiveButton{{$j}}(currentQuestionIndex{{$j}});
                    
                    const currentInputs = document.querySelectorAll(`input[id="bpoin{{ $j }}[]"]`);
                    idAssgn = currentInputs[ind{{$j}}].value;
                    setButtonColor{{$j}}(idAssgn);
                    ind{{$j}}--;
                });
            </script>
        @endif
    @endforeach
@endforeach

@endsection