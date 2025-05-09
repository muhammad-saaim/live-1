<x-app-layout>
<style>
.checkedoption {
    background-color: #8EEB64 !important;
    border: 2px solid #5cb031 !important;
    color: white !important;
}


</style>
    <div class="p-3 max-w-7xl mx-auto space-y-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-between w-100 p-2">
                <button class="btn btn-danger">Leave the Survey</button>

                <div class="btn-group" role="group" aria-label="Toggle View">
                    <!-- Removed 'checked' from questionView and added to defaultView -->
                    <input type="radio" class="btn-check" name="viewToggle" id="questionView" autocomplete="off">
                    <label class="btn btn-outline-primary rounded-start-pill" for="questionView">Question View</label>

                    <input type="radio" class="btn-check" name="viewToggle" id="defaultView" autocomplete="off"
                        checked>
                    <label class="btn btn-outline-primary rounded-end-pill" for="defaultView">Default View</label>
                </div>
            </div>
        </div>


        <div class="bg-ml-color-lime border rounded-xl p-4 space-y-3">
            <h2 class="font-bold text-2xl text-center" id="survey-title">{{ $survey->title }}</h2>

            {{-- Success and Error Messages --}}
            <div id="message-container"></div>

            <div id="question-container" class="text-center">
                @if ($unansweredQuestions->isNotEmpty())
                    <p id="question-text" class="text-lg font-semibold">{{ $unansweredQuestions->first()->question }}
                    </p>

                    <p id="question-text" class="text-base mb-2">{{ $unansweredQuestions->first()->description }}</p>
                    <hr class="border-t-2 border-black my-4">
                    <form id="question-form">
                        <div id="options-container" class="flex justify-center items-center mt-5 gap-4 px-4">
                            <!-- Disagree label -->
                            <div class="flex items-center text-sm text-red-500">
                                <p>Disagree</p>
                            </div>

                            <!-- Options container with line -->
                            <div class=" flex items-center relative max-w-xl">
                                <!-- Grey connecting line -->
                                {{-- <div class="absolute h-[2px] w-full bg-gray-300 top-1/2 left-0 -translate-y-1/2 z-0"></div> --}}

                                <!-- Radio buttons container -->
                                <div id="options-container" class="flex justify-center items-center  px-4 "
                                    style="gap:35px">
                                    @foreach ($unansweredQuestions->first()->options as $option)
                                        <label for="option-{{ $option->id }}"
                                            class="cursor-pointer flex justify-center">
                                            <input type="radio" name="answer" value="{{ $option->id }}"
                                                id="option-{{ $option->id }}" class="hidden peer"
                                                onchange="updateSelectedOption(this)">
                                            <div style="width: 60px; height: 60px;"
                                                class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 mx-auto fw-bold fs-5">
                                                {{ $option->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Agree label -->
                            <div class="flex items-center text-sm text-green-500">
                                <p>Agree</p>
                            </div>
                        </div>


                        <input type="hidden" name="question_id" id="question-id"
                            value="{{ $unansweredQuestions->first()->id }}">
                        <input type="hidden" name="survey_id" id="survey-id" value="{{ $survey->id }}">

                        <div class="flex justify-center space-x-4 mt-4">
                            <button type="button" id="next-button"
                                class="bg-blue-500 text-white py-2 px-4 rounded">Next</button>
                        </div>
                    </form>
                    {{-- Question Status Tracker --}}
                    <div id="status-container" class="mt-4">
                        <p class="text-sm text-gray-500">
                            Question {{ $unansweredQuestions->keys()->first() + 1 }} of
                            {{ $unansweredQuestions->count() }}
                        </p>
                    </div>
                @else
                    <p class="text-lg font-semibold text-center">All questions are completed. Thank you!</p>
                @endif
            </div>

            <div id="group-container" class="text-center">
                <p id="question-text" class="text-lg font-semibold">{{ $unansweredQuestions->first()->question }}</p>
                <p id="question-text" class="text-base mb-2">{{ $unansweredQuestions->first()->description }}</p>
                <hr class="border-t-2 border-black my-4">
                <div id="options-container" class="flex justify-center items-center mt-5 gap-4 px-4">
                    <!-- Disagree label -->
                    <div class="flex items-center text-sm text-red-500">
                        <p>Disagree</p>
                    </div>
                    <!-- Options container with line -->
                    <div class=" flex items-center relative max-w-xl">

                        <!-- Radio buttons container -->
                        <div id="options-container" class="flex justify-center items-center px-4" style="gap:35px">
                            @foreach ($unansweredQuestions->first()->options as $index => $option)
                                <div class="flex flex-col items-center">
                                    <label for="option-{{ $option->id }}" class="cursor-pointer flex justify-center">
                                        <input type="radio" name="answer" value="{{ $option->id }}"
                                            id="option-{{ $option->id }}" class="hidden peer"
                                            onchange="updateSelectedOption(this)">
                                        <div style="width: 60px; height: 60px;"
                                            class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 mx-auto fw-bold fs-5">
                                            {{ $option->name }}
                                        </div>
                                    </label>
                                    <div class="mt-1 text-sm text-gray-600">
                                        {{ $index + 1 }} Cevap
                                        {{ (($index + 1) * 100) / $unansweredQuestions->first()->options->count() }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Agree label -->
                    <div class="flex items-center text-sm text-green-500">
                        <p>Agree</p>
                    </div>
                </div>

                <div class="p-3 max-w-7xl mx-auto space-y-6 mt-5">
                    @foreach ($usersurvey as $survey)
                    {{-- {{dd($survey)}} --}}
                    <div class="flex justify-center items-center gap-5"  style="transform: translateX(-50px);">
                        <!-- Username label -->
                        <div class="col-1 flex text-sm text-red-500">
                            <p>{{ \Illuminate\Support\Str::limit($survey?->user?->name, 15) }}</p>

                        </div>
                    
                        <!-- Options container -->
                        <div class=" flex items-center relative max-w-xl">
                            <!-- Radio buttons container -->
                            <div id="options-container" class="flex justify-center items-center px-4" style="gap:35px">
                                @foreach ($unansweredQuestions->first()->options as $option)
                                    <div class="flex flex-col items-center">
                                        <div style="width: 60px; height: 60px;"
                                            class="rounded-full border-2 flex items-center justify-center fw-bold fs-5 
                                            {{ $survey->options_id == $option->id ? 'checkedoption' : 'border-gray-300 bg-white' }}">
                                            {{ $option->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionView = document.getElementById('questionView');
            const defaultView = document.getElementById('defaultView');
            const questionContainer = document.getElementById('question-container');
            const groupContainer = document.getElementById('group-container');

            // Set initial state based on which radio is checked
            if (defaultView.checked) {
                questionContainer.style.display = 'block';
                groupContainer.style.display = 'none';
            }

            questionView.addEventListener('change', function() {
                if (this.checked) {
                    groupContainer.style.display = 'block';
                    questionContainer.style.display = 'none';
                }
            });

            defaultView.addEventListener('change', function() {
                if (this.checked) {
                    questionContainer.style.display = 'block';
                    groupContainer.style.display = 'none';
                }
            });
        });


        function updateSelectedOption(radioInput) {
            // Uncheck all radio buttons and reset their styles
            document.querySelectorAll('input[name="answer"]').forEach(radio => {
                radio.checked = false;
                const circle = radio.nextElementSibling;
                circle.style.backgroundColor = '';
                circle.style.borderColor = '';
                circle.style.color = '';
                circle.classList.add('bg-white', 'border-gray-300');
            });

            // Check the selected radio button and apply custom green styles
            radioInput.checked = true;
            const selectedCircle = radioInput.nextElementSibling;
            selectedCircle.style.backgroundColor = '#8EEB64';
            selectedCircle.style.borderColor = '#5cb031'; // Slightly darker green for border
            selectedCircle.style.color = 'white';
            selectedCircle.classList.remove('bg-white', 'border-gray-300');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners for all radio button labels
            document.querySelectorAll('label[for^="option-"]').forEach(label => {
                label.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    updateSelectedOption(radioInput);
                });
            });

            // Initialize any pre-selected option
            const selectedRadio = document.querySelector('input[name="answer"]:checked');
            if (selectedRadio) {
                updateSelectedOption(selectedRadio);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("question-form");
            const nextButton = document.getElementById("next-button");
            const questionContainer = document.getElementById("question-container");
            const messageContainer = document.getElementById("message-container");

            nextButton.addEventListener("click", function() {
                const selectedOption = document.querySelector("input[name='answer']:checked");
                if (!selectedOption) {
                    messageContainer.innerHTML =
                        `<div class="bg-red-500 text-white p-3 rounded">Please select an option before proceeding.</div>`;
                    return;
                }

                const questionId = document.getElementById("question-id").value;
                const surveyId = document.getElementById("survey-id").value;
                const optionId = selectedOption.value;

                fetch("{{ route('survey.submitAnswer') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            survey_id: surveyId,
                            question_id: questionId,
                            options_id: optionId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            messageContainer.innerHTML =
                                `<div class="bg-green-500 text-white p-3 rounded">${data.message}</div>`;
                            setTimeout(() => {
                                location.reload(); // Refresh page to show the next question
                            }, 1000);
                        } else {
                            messageContainer.innerHTML =
                                `<div class="bg-red-500 text-white p-3 rounded">${data.message}</div>`;
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>

</x-app-layout>
