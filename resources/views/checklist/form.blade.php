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
                            <a href="#question1" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn1">1</a>
                            <a href="#question2" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn2">2</a>
                            <a href="#question3" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn3">3</a>
                            <a href="#question4" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn4">4</a>
                            <a href="#question5" class="btn pt-1 pb-1 mb-1 btn-outline-primary" id="btn5">5</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <form action="#" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="question1">1. Sesuai dengan gambar tampak depan yang sudah diapprove bersama</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question1" id="question1_1" value="option1">
                                                <label class="form-check-label" for="question1_1">Gold</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question1" id="question1_2" value="option2">
                                                <label class="form-check-label" for="question1_2">Platinum</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <img src="http://localhost:8000/assets/images/logosamping.png" class="img-thumbnail" alt="Thumbnail 1">
                                        </div>

                                    </div>
                                    

                                </div>
                                <div class="form-group" style="display: none;">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="question2">2. Gambar eksterior yang sudah diapproved, dicetak, ditempel di ruang Kacab</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question2" id="question2_1" value="option1">
                                                <label class="form-check-label" for="question2_1">Gold</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question2" id="question2_2" value="option2">
                                                <label class="form-check-label" for="question2_2">Platinum</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <img src="http://localhost:8000/assets/images/logosamping.png" class="img-thumbnail" alt="Thumbnail 2">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-group" style="display: none;">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="question3">3. Area / Zoning Showroom sesuai dengan gambar yang sudah diapprove bersama : 
                                                Item Interior yang ada di gambar approval berada didalam showroom dan digunakan sesuai fungsinya, khususnya:
                                                    - Sales & Finance Front Desk 
                                                    - Negotiation Table  minimal sejumlah yang ada didalam gambar approval
                                                    - Khusus Wing Dealer maka Posisi Wing Corner harus sesuai dengan gambar approval dan Item Interior yang ada didalam Wing Corner harus sesuai dan tidak boleh dipindahkan , terkecuali meja negosiasi bila harus digantikan oleh unit motor
                                            </label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question3" id="question3_1" value="option1">
                                                <label class="form-check-label" for="question3_1">Gold</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question3" id="question3_2" value="option2">
                                                <label class="form-check-label" for="question3_2">Platinum</label>
                                            </div>
                                        </div>
                                        <dov class="col-3">
                                            <img src="http://localhost:8000/assets/images/logosamping.png" class="img-thumbnail" alt="Thumbnail 3">
                                        </dov>
                                    </div>
                                    
                                </div>
                                <div class="form-group" style="display: none;">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="question4">Pertanyaan 4 (Pilihan Ganda)</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question4" id="question4_1" value="option1">
                                                <label class="form-check-label" for="question4_1">Opsi 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question4" id="question4_2" value="option2">
                                                <label class="form-check-label" for="question4_2">Opsi 2</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <img src="http://localhost:8000/assets/images/logosamping.png" class="img-thumbnail" alt="Thumbnail 4">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-group" style="display: none;">
                                    <div class="row">
                                        <div class="col-9">
                                            <label for="question5">Pertanyaan 5 (Pilihan Ganda)</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question5" id="question5_1" value="option1">
                                                <label class="form-check-label" for="question5_1">Opsi 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="question5" id="question5_2" value="option2">
                                                <label class="form-check-label" for="question5_2">Opsi 2</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <img src="http://localhost:8000/assets/images/logosamping.png" class="img-thumbnail" alt="Thumbnail 5">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="btn-group mt-2" role="group" aria-label="Navigasi Quiz">
                                    <button type="button" class="btn btn-info mx-1" id="backBtn" style="display: none;">Kembali</button>
                                    <button type="button" class="btn btn-primary mx-1" id="nextBtn">Selanjutnya</button>
                                    <button type="submit" class="btn btn-primary mx-1" id="submitBtn" style="display: none;">Kirim</button>

                                </div>                    
                                
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <script>
        const questions = document.querySelectorAll('.form-group');
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