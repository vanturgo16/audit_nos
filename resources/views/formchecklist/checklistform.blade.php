@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Master Form H1 Premises</h4>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Form Checklist</h5>
                        <div class="mt-3">
                            <?php $no = 0;?> 
                            @foreach ($datas as $data)
                            <?php $no++ ;?>
                                <a href="#question{{$data->id}}" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn1">{{$no}}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table dt-responsive nowrap w-100">
                            <tbody>
                                <form action="{{ route('formchecklist.store', encrypt($id_period)) }}" method="post">
                                @csrf
                                <input type="hidden" name="id_checklist_jaringan" value="{{$id}}">
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr class="soal">
                                        <td>{{ $no }}</td>
                                        <td>
                                            <div style="max-height: 39vh; overflow-y: auto;">
                                                {!! $data->indikator !!}
                                            </div>
                                            @foreach($data->mark as $mark)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="question{{$data->id}}" id="question{{$data->id}}_{{$mark['id']}}" value="{{$mark['meta_name']}}">
                                                    <label class="form-check-label" for="question{{$data->id}}_{{$mark['id']}}">{{$mark['meta_name']}}</label>
                                                </div>
                                            @endforeach
                                            
                                        </td>
                                        <td>
                                            <img src="{{url($data->path_guide_premises)}}" class="img-thumbnail" alt="Thumbnail 1">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-12">
                            <div class="btn-group mt-2" role="group" aria-label="Navigasi Quiz">
                                <button type="button" class="btn btn-info mx-1" id="backBtn" style="display: none;">Kembali</button>
                                <button type="button" class="btn btn-primary mx-1" id="nextBtn">Selanjutnya</button>
                                <button type="submit" class="btn btn-primary mx-1" id="submitBtn" style="display: none;">Kirim</button>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <script>
        const questions = document.querySelectorAll('.soal');
        const buttons = document.querySelectorAll('.btn-outline-primary');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const submitBtn = document.getElementById('submitBtn');
        let currentQuestionIndex = 0;

        function showQuestion(index) {
            questions.forEach((question, i) => {
                question.style.display = i === index ? 'block' : 'none';
            });
        }

        function toggleButtons() {
            nextBtn.style.display = currentQuestionIndex < questions.length - 1 ? 'block' : 'none';
            backBtn.style.display = currentQuestionIndex > 0 ? 'block' : 'none';
            submitBtn.style.display = currentQuestionIndex === questions.length - 1 ? 'block' : 'none';
        }

        function setActiveButton(index) {
            buttons.forEach((button, i) => {
                if (i === index) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }

        function setButtonColor() {
            buttons.forEach((button, index) => {
                if (index <= currentQuestionIndex || index === currentQuestionIndex) {
                    const selectedOption = document.querySelector('input[name=question' + (index + 1) + ']:checked');
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


        showQuestion(currentQuestionIndex);
        toggleButtons();
        setActiveButton(currentQuestionIndex);
        setButtonColor();

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => {
                currentQuestionIndex = index;
                showQuestion(currentQuestionIndex);
                toggleButtons();
                setActiveButton(currentQuestionIndex);
                setButtonColor();
                window.location.hash = 'question' + (currentQuestionIndex + 1);
            });
        });

        nextBtn.addEventListener('click', () => {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
            toggleButtons();
            setActiveButton(currentQuestionIndex);
            setButtonColor();
            window.location.hash = 'question' + (currentQuestionIndex + 1);
        });

        backBtn.addEventListener('click', () => {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
            toggleButtons();
            setActiveButton(currentQuestionIndex);
            setButtonColor();
            window.location.hash = 'question' + (currentQuestionIndex + 1);
        });
    </script>

@endsection