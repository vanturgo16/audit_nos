@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Form Checklist {{$type->type_checklist}} ( {{$period}} )</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Approval Layout New VinCi > Exterior</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">

                    </div><!-- end card header -->
                
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php $tab = 0;?> 
                            @foreach ($point as $poin)
                            <?php $tab++ ;?>
                            <li class="nav-item">
                                <a class="nav-link 
                                @if($tab === $tabo ? 'active' : '')
                                    active
                                @endif
                                " data-bs-toggle="tab" href="#point{{$tab}}" role="tab">

                                    <span class="d-block d-sm-none">{{$poin->parent_point}}</span>
                                    <!-- <span class="d-block d-sm-none"><i class="fas fa-home"></i></span> -->
                                    <span class="d-none d-sm-block">{{$poin->parent_point}}</span>    
                                </a>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <?php $tab = 0;?> 
                            @foreach ($point as $poin)
                            <?php $tab++ ;?>
                                <div class="tab-pane 
                                    @if($tab === $tabo ? 'active' : '')
                                        active
                                    @endif" id="point{{$tab}}" role="tabpanel">
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="{{ route('formchecklist.store', encrypt($id_period)) }}" method="post" enctype="multipart/form-data">
                                            
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
                                                <h5 class="font-size-14 mb-4"><i class="mdi mdi-arrow-right text-primary me-1"></i> File {{$poin->parent_point}}</h5>
                                                <div class="hstack gap-3">
                                                    <input class="form-control me-auto" type="file" name="file_parent" placeholder="input File">
                                                    <input type="hidden" name="parent_point" value="{{$poin->parent_point}}">
                                                    <input type="hidden" name="tabo" value="{{$tab}}">
                                                    <input type="hidden" name="id_jaringan" value="{{$id}}">
                                                    <input type="hidden" name="sum_point" value="{{count($point)}}">
                                                    @if($file != "")
                                                        <div class="vr"></div>
                                                        <a href="{{url($file)}}" class="btn btn-outline-success" download="File {{$type->type_checklist}}_{{$poin->parent_point}}">Download</a>
                                                    @endif
                                                </div>
                                        </div>
                                    </div>
                                    <!-- <div class="card"> -->
                                        <div class="card-header">
                                            <!-- <h5>Form Checklist {{$poin->parent_point}}</h5> -->
                                            <div class="mt-3">
                                                <?php $no = 0;?> 
                                                @foreach ($datas as $data)

                                                @if ($poin->parent_point == $data->parent_point)
                                                <?php $no++ ;?>
                                                    <a href="#{{$tab}}question{{$data->id_assign}}" class="btn btn{{$tab}} pt-1 pb-1 mb-1 btn-outline-primary" id="btn{{$tab}}">{{$no}}</a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table dt-responsive nowrap w-100">
                                                <tbody>
                                                    <!-- <form action="{{ route('formchecklist.store', encrypt($id_period)) }}" method="post" enctype="multipart/form-data"> -->
                                                    @csrf
                                                    <input type="hidden" name="id_checklist_jaringan" value="{{$id}}">
                                                    <?php $no = 0;?> 
                                                    @foreach ($datas as $data)
                                                    @if ($poin->parent_point == $data->parent_point)
                                                    <?php $no++ ;?>
                                                        <tr class="soal{{$tab}}">
                                                            <td>{{ $no }}</td>
                                                            <td>
                                                                <div style="max-height: 39vh; overflow-y: auto;">
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
                                                                
                                                            </td>
                                                            <td>
                                                                <img src="{{url($data->path_guide_premises)}}" class="img-thumbnail" width="200" alt="Thumbnail 1">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="col-12">
                                                <div class="btn-group mt-2" role="group" aria-label="Navigasi Quiz">
                                                    <button type="button" class="btn btn-info mx-1" id="backBtn{{$tab}}" style="display: none;">Kembali</button>
                                                    <button type="button" class="btn btn-primary mx-1" id="nextBtn{{$tab}}">Selanjutnya</button>
                                                    <button type="submit" class="btn btn-primary mx-1" id="submitBtn{{$tab}}" style="display: none;">Kirim</button>

                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    <!-- </div> -->
                                    
                                </div>
                            @endforeach
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->


    </div>
</div>
@php
$j = 0;
@endphp

@foreach($point as $point)
    @php
    $j++;
    @endphp
    <?php foreach($datas as $data){ 
        if($point->parent_point == $data->parent_point){
        ?>
    <script>
        // Mendefinisikan variabel JavaScript menggunakan nilai $j
        const questions{{$j}} = document.querySelectorAll('.soal{{$j}}');
        const buttons{{$j}} = document.querySelectorAll('.btn{{$j}}');
        const nextBtn{{$j}} = document.getElementById('nextBtn{{$j}}');
        const backBtn{{$j}} = document.getElementById('backBtn{{$j}}');
        const submitBtn{{$j}} = document.getElementById('submitBtn{{$j}}');
        let currentQuestionIndex{{$j}} = 0;

        // Fungsi untuk menampilkan pertanyaan berdasarkan indeks
        function showQuestion{{$j}}(index) {
            questions{{$j}}.forEach((question, i) => {
                question.style.display = i === index ? 'block' : 'none';
            });
        }

        // Fungsi untuk mengatur tampilan tombol navigasi
        function toggleButtons{{$j}}() {
            nextBtn{{$j}}.style.display = currentQuestionIndex{{$j}} < questions{{$j}}.length - 1 ? 'block' : 'none';
            backBtn{{$j}}.style.display = currentQuestionIndex{{$j}} > 0 ? 'block' : 'none';
            submitBtn{{$j}}.style.display = currentQuestionIndex{{$j}} === questions{{$j}}.length - 1 ? 'block' : 'none';
        }

        // Fungsi untuk menandai tombol yang aktif
        function setActiveButton{{$j}}(index) {
            buttons{{$j}}.forEach((button, i) => {
                if (i === index) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }

        // Fungsi untuk mengatur warna tombol berdasarkan jawaban yang dipilih
        function setButtonColor{{$j}}() {
            buttons{{$j}}.forEach((button, index) => {
                if (index <= currentQuestionIndex{{$j}} || index === currentQuestionIndex{{$j}}) {
                    //  document.querySelectorAll('input[name^='{{$j}}question']:checked');
                    // const selectedOption = document.querySelector('input[name={{$j}}question' + (index + 1)+']:checked');
                    
                    const selectedOption = document.querySelector("input[name='1question<?= $data->id_assign ?>']:checked");

                    // const selectedOption = document.querySelectorAll('input[name^='{{$j}}question']:checked');
                    // eror get question
                    console.log('button color cek');

                    // selectedOption.foreach((input)=>{
                    //     if(input.)
                    // })

                    if (selectedOption) {
                        button.classList.add('btn-success');
                    } else {
                        button.classList.remove('btn-success');
                        // button.classList.add('btn-success');
                    }
                } else {
                    // button.classList.remove('btn-success');
                    // button.classList.add('btn-success');

                }
            });
        }

        // Menampilkan pertanyaan pertama dan mengatur tampilan tombol
        showQuestion{{$j}}(currentQuestionIndex{{$j}});
        toggleButtons{{$j}}();
        setActiveButton{{$j}}(currentQuestionIndex{{$j}});
        setButtonColor{{$j}}();

        // Menambahkan event listener untuk tombol navigasi
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
            setButtonColor{{$j}}();
        });

        backBtn{{$j}}.addEventListener('click', () => {
            currentQuestionIndex{{$j}}--;
            showQuestion{{$j}}(currentQuestionIndex{{$j}});
            toggleButtons{{$j}}();
            setActiveButton{{$j}}(currentQuestionIndex{{$j}});
            setButtonColor{{$j}}();
        });
    </script>
    <?php }} ?>
@endforeach


@endsection