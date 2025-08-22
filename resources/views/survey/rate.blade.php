<x-app-layout>
    <style>
        .checkedoption {
            background-color: #8EEB64 !important;
            border: 2px solid #5cb031 !important;
            color: white !important;
        }
    </style>
    <style>
        
/* =========================
   Mobile (Extra Small ≤576px)
   ========================= */
   @media (max-width: 540px) {
 #guidance-options {
        gap: 5px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

      .options-gap{
        gap:5px!important;
      }
    .rounded-full {
        width: 28px !important;
        height: 28px !important;
    }

    .disaggree-label p {
        margin-left: 15px;
    }
     .disaggree-label  {
        margin-left: 15px;
    }
        .agree-label  {
        margin-right: 15px;
    }

    .agree-label p {
        margin-right: 15px;
    }

    .text-sm {
        font-size: 0.75rem !important;
    }

    #options-container,
    .options-container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        gap: 0 !important;
    }

    .gap-4 {
        gap: 0 !important;
    }

    .gap-5 {
        gap: 2.5rem !important;
    }

    .usernames-column {
        padding-right: 26px;
        margin-top: 1rem !important;
    }

    .col-6 {
        flex: 0 0 auto;
        width: 81%;
    }

    .usernames-inner {
        margin-top: 1rem !important;
        margin-bottom: 0 !important;
    }

    .mx-auto {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .p-3 {
        padding: 0.3rem !important;
    }

    .col-3 {
        flex: 0 0 auto;
        width: 17%;
    }
   }
@media (max-width: 520px) {
    #guidance-options {
        gap: 5px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .rounded-full {
        width: 28px !important;
        height: 28px !important;
    }

    .disaggree-label p {
        margin-left: 15px;
    }

    .agree-label p {
        margin-right: 15px;
    }

    .text-sm {
        font-size: 0.75rem !important;
    }

    #options-container,
    .options-container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        gap: 0 !important;
    }

    .gap-4 {
        gap: 0 !important;
    }

    .gap-5 {
        gap: 0.5rem !important;
    }

    .usernames-column {
        padding-right: 26px;
        margin-top: 1rem !important;
    }

    .col-6 {
        flex: 0 0 auto;
        width: 81%;
    }

    .usernames-inner {
        margin-top: 1rem !important;
        margin-bottom: 0 !important;
    }

    .mx-auto {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .p-3 {
        padding: 0.3rem !important;
    }

    .col-3 {
        flex: 0 0 auto;
        width: 17%;
    }
}

/* =========================
   Tablet (Small to Medium: 577px–991px)
   ========================= */
@media (min-width: 577px) and (max-width: 991px) {
    #guidance-options {
        gap: 10px !important;
    }

    .rounded-full {
        width: 32px !important;
        height: 32px !important;
    }

    .text-sm {
        font-size: 0.85rem !important;
    }

    .col-6 {
        width: 75% !important;
    }

    .col-3 {
        width: 20% !important;
    }
}

/* =========================
   Laptop / Desktop (≥992px)
   ========================= */
/* =========================
   Universal Base (Fluid scaling)
   ========================= */
/* #guidance-options {
    gap: clamp(5px, 2vw, 20px) !important;
    padding-left: clamp(0px, 1vw, 20px) !important;
    padding-right: clamp(0px, 1vw, 20px) !important;
} */

/* .rounded-full {
    width: clamp(28px, 4vw, 40px) !important;
    height: clamp(28px, 4vw, 40px) !important;
} */

/* .text-sm {
    font-size: clamp(0.75rem, 1vw, 0.95rem) !important;
} */

/* #options-container,
.options-container {
    padding-left: clamp(0px, 1vw, 20px) !important;
    padding-right: clamp(0px, 1vw, 20px) !important;
    gap: clamp(0.3rem, 1vw, 1rem) !important;
} */

/* .gap-4,
.gap-5 {
    gap: clamp(0.3rem, 1vw, 1.2rem) !important;
} */

/* .usernames-column { */
    /* padding-right: clamp(10px, 2vw, 26px);
    margin-top: 1rem !important;
}

.col-6 {
    flex: 0 0 auto;
    width: clamp(70%, 80%, 81%);
}

.col-3 {
    flex: 0 0 auto;
    width: clamp(15%, 18%, 20%);
}

.usernames-inner {
    margin-top: 1rem !important;
    margin-bottom: 0 !important;
} */

/* .mx-auto {
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.p-3 {
    padding: clamp(0.3rem, 1vw, 1rem) !important;
} */

/* =========================
   Small Mobile (≤576px)
   ========================= */
/* @media (max-width: 576px) {
    .disaggree-label p {
        margin-left: 15px;
    }

    .agree-label p {
        margin-right: 15px;
    }
} */

/* =========================
   Tablet Portrait (577px–768px)
   ========================= */
@media (min-width: 476px) and (max-width: 768px) {
    #guidance-options {
        gap: 1.5rem !important; /* bigger gap for tablets */
    }
    .options-container {
        gap: 1.5rem !important;
    }
    .col-6 {
        width: 78% !important;
    }
    .col-3 {
        width: 18% !important;
    }
}

/* =========================
   Tablet Landscape & Small Laptops (769px–1199px)
   ========================= */
@media (min-width: 768px) and (max-width: 997px) {
    #guidance-options {
        gap: 3rem !important; /* spacing between guidance items */
    }
    .options-container {
        gap: 2.5rem !important; /* reduced from 32rem */
        align-items: center; /* keeps everything aligned vertically */
    }
    .col-6 {
        width: 60% !important;
    }
    .col-3 {
        width: 20% !important;
    }

    /* Ensure usernames align properly */
    .usernames-column {
        padding-right: 20px !important;
margin-top: -0.1rem !important;    }
    .usernames-inner {
        margin-top: 0rem !important;
        margin-bottom: 0 !important;
    }
}

@media (max-width: 1180px) {
  .col-6 {
        width: 65% !important;
    }

}

    </style>
    <div class="p-3 max-w-7xl mx-auto space-y-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-between w-100 p-2">
           <a href="/dashboard" class="btn btn-danger">Leave the Survey</a>

                <div class="btn-group d-none" role="group" aria-label="Toggle View">
                    <!-- Check if coming from group (group_id present) to determine which tab to open -->
                    @if($request->has('group_id') || isset($groupUsers) && count($groupUsers) > 0)
                        <!-- Group context: show group evaluation, disable self-evaluation -->
                        <input type="radio" class="btn-check" name="viewToggle" id="questionView" autocomplete="off" checked>
                        <label class="btn btn-outline-primary rounded-start-pill" for="questionView">Question View</label>

                        <input type="radio" class="btn-check" name="viewToggle" id="defaultView" autocomplete="off">
                        <label class="btn btn-outline-primary rounded-end-pill" for="defaultView">Default View</label>
                    @else
                        <input type="radio" class="btn-check" name="viewToggle" id="questionView" autocomplete="off">
                        <label class="btn btn-outline-primary rounded-start-pill" for="questionView">Question View</label>

                        <input type="radio" class="btn-check" name="viewToggle" id="defaultView" autocomplete="off" checked>
                        <label class="btn btn-outline-primary rounded-end-pill" for="defaultView">Default View</label>
                    @endif
                </div>
            </div>
        </div>

             
        <div class="bg-ml-color-lime border rounded-xl p-4 space-y-3">
            @if ($survey->title === 'Self-Awareness & Motivation')
                 <div class="text-center my-3">
<form action="{{ route('survey.personal_index') }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="group_id" value="{{ $request->group_id  }}">
    <input type="hidden" name="survey_id" value="{{ $request->survey_id  }}">
    <button type="submit" class="btn btn-success mx-2">Personal View</button>
</form>  
<form action="{{ route('rate.survey') }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="group_id" value="{{ $request->group_id  }}">
    <input type="hidden" name="survey_id" value="{{ $request->survey_id  }}">
    <button type="submit" class="btn btn-secondary mx-2">Group View</button>
</form>
</div>
            @endif
            

            <h2 class="font-bold text-2xl text-center" id="survey-title">{{ $survey->title }}</h2>

            {{-- Success and Error Messages --}}
            <div id="message-container"></div>

            <div id="question-container" class="text-center">
                @if ($unansweredQuestions->isNotEmpty())
                <p id="question-text" class="text-lg font-semibold">{{ $unansweredQuestions->first()->question }}
                </p>

                <p id="question-text" class="text-base mb-2">{{ $unansweredQuestions->first()->description }}</p>
                <hr class="border-t-2 border-black my-4">
                
                <!-- Self Evaluation Form -->
                <form id="question-form">
                    <div id="options-container" class="flex justify-center items-center mt-5 gap-4 px-4">
                        <!-- Disagree label -->
                        <div class="flex items-center text-sm text-red-500 disaggree-label">
                            <p>Disagree</p>
                        </div>

                        <!-- Options container with line -->
                        <div class=" flex items-center relative max-w-xl">
                            <!-- Grey connecting line -->
                            {{-- <div class="absolute h-[2px] w-full bg-gray-300 top-1/2 left-0 -translate-y-1/2 z-0">
                            </div> --}}

                            <!-- Radio buttons container -->
                            <div id="options-container" class="flex justify-center items-center  px-4 "
                                style="gap:35px">
                                @php
                                    $selfAnswer = isset($selfAnswers) ? $selfAnswers->get($unansweredQuestions->first()->id) : null;
                                    // Check if this is a Rosenberg survey to limit options to 4
                                    $isRosenberg = strcasecmp(trim(optional($survey->model)->title), 'rosenberg') === 0;
                                    $optionsToShow = $isRosenberg ? $unansweredQuestions->first()->options->take(4) : $unansweredQuestions->first()->options;
                                @endphp
                                @foreach ($optionsToShow as $option)
                                <label for="option-{{ $option->id }}" class="cursor-pointer flex justify-center">
                                    <input type="radio" name="answer" value="{{ $option->id }}"
                                        id="option-{{ $option->id }}" class="hidden peer"
                                        onchange="updateSelectedOption(this)"
                                        {{ $selfAnswer && $selfAnswer->options_id == $option->id ? 'checked' : '' }}>
                                    <div style="width: 60px; height: 60px;"
                                        class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 mx-auto fw-bold fs-5">
                                        {{ $option->name }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Agree label -->
                        <div class="flex items-center text-sm text-green-500 agree-label">
                            <p>Agree</p>
                        </div>
                    </div>


                    <input type="hidden" name="question_id" id="question-id"
                        value="{{ $unansweredQuestions->first()->id }}">
                    <input type="hidden" name="survey_id" id="survey-id" value="{{ $survey->id }}">
                    <input type="hidden" name="evaluatee_id" id="evaluatee-id" value="{{ Auth::id() }}">
                    <input type="hidden" name="survey_model_title" id="survey-model-title" value="{{ optional($survey->model)->title ?? '' }}">

                    <div class="flex justify-center space-x-4 mt-4">
                        <button type="button" id="previous-button"
                            class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Previous</button>
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
                    <div class="flex items-center text-sm text-red-500 disaggree-label">
                        <p>Disagree</p>
                    </div>
                    <!-- Options container with line -->
                    <div class=" flex items-center relative max-w-xl">
                        <div id="guidance-options" class="flex justify-center items-center px-4 options-gap" style="gap:35px">
                            @php
                                // Check if this is a Rosenberg survey to limit options to 4
                                $isRosenberg = strcasecmp(trim(optional($survey->model)->title), 'rosenberg') === 0;
                                $guidanceOptionsToShow = $isRosenberg ? $unansweredQuestions->first()->options->take(4) : $unansweredQuestions->first()->options;
                            @endphp
                            @foreach ($guidanceOptionsToShow as $index => $option)
                                {{-- @if ($index >= 0 ) <!-- Adjust range as needed for guidance options --> --}}
                                    <div class="flex flex-col items-center opacity-60 select-none">
                                        <div style="width: 60px; height: 60px; background: #ffffff; border: 2px dashed #ccc;"
                                            class="rounded-full flex items-center justify-center fw-bold fs-5">
                                            {{ $option->name }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-600">
                                            {{ $index + 1 }} Cevap
                                            {{ (($index + 1) * 100) / $guidanceOptionsToShow->count() }}%
                                        </div>
                                    </div>
                                {{-- @endif --}}
                            @endforeach
                        </div>
                    </div>
                    <!-- Agree label -->
                    <div class="flex items-center text-sm text-green-500 agree-label">
                        <p>Agree</p>
                    </div>
                </div>

                <!-- Group Evaluation -->
                <form id="group-evaluation-form">
                    <div id="group-options" class="p-3 max-w-7xl mx-auto space-y-6 mt-5 d-flex">
                        <!-- Usernames Column -->
                        <div class="col-3 d-flex flex-column justify-start usernames-column" style="margin-top: -4.5rem;" >
                            @foreach ($groupUsers as $user)  
                            <div class="p-3 max-w-7xl mx-auto usernames-inner" style="margin-top: 1.85rem; margin-bottom:0.43rem;">
                                <div class="mb-3 text-md text-red-500">
                                    @if($user->id !== Auth::id())
                                        {{-- <p>{{ \Illuminate\Support\Str::limit($user->name, 15) }} (Self)</p>
                                    @else --}}
                                        <p>{{ \Illuminate\Support\Str::limit($user->name, 15) }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Options Column -->
                        <div class="col-6 py-4 max-w-7xl space-y-6 border" style="border: 1px solid rgb(184, 184, 184) !important; border-radius:30px;">
                            @foreach ($groupUsers as $user)  
                            @if($user->id !== Auth::id())
                            <div class="flex justify-center items-center relative max-w-xl options-container">
                                <div class="flex justify-center items-center px-4 gap-5" >
                                    @php
                                        $previousRating = $usersurvey->where('evaluatee_id', $user->id)
                                            ->where('question_id', $unansweredQuestions->first()->id)
                                            ->where('users_id', Auth::id())
                                            ->first();
                                        // Check if this is a Rosenberg survey to limit options to 4
                                        $isRosenberg = strcasecmp(trim(optional($survey->model)->title), 'rosenberg') === 0;
                                        $groupOptionsToShow = $isRosenberg ? $unansweredQuestions->first()->options->take(4) : $unansweredQuestions->first()->options;
                                    @endphp
                                    @foreach ($groupOptionsToShow as $option)
                                    <label for="option-{{ $user->id }}-{{ $option->id }}" class="flex flex-col items-center cursor-pointer">
                                        <input type="radio"
                                            name="answer[{{ $user->id }}]"
                                            value="{{ $option->id }}"
                                            id="option-{{ $user->id }}-{{ $option->id }}"
                                            class="hidden peer"
                                            {{ $previousRating && $previousRating->options_id == $option->id ? 'checked' : '' }} />
                                        <div style="width: 60px; height: 60px;"
                                            class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 fw-bold fs-5">
                                            {{ $option->name }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Add separator unless it's the last item --}}
                            @if (!$loop->last)
                                <div style="display: flex; justify-content: center;">
                                    <hr style="border: 1px solid rgb(184, 184, 184); width: 90%;">
                                </div> 
                            @endif
                            @endif
                            @endforeach
                        </div>
                    </div>


                    <input type="hidden" name="question_id" value="{{ $unansweredQuestions->first()->id }}">
                    <input type="hidden" name="survey_id" value="{{ $survey->id }}">
                    <input type="hidden" name="group_id" value="{{ $request->group_id ?? '' }}">

                    <div class="flex justify-center space-x-4 mt-4">
                        <button type="button" id="previous-group-button"
                            class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Previous</button>
                        <button type="button" id="next-group-button"
                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Next</button>
                    </div>
                    
                    {{-- Question Status Tracker for Group --}}
                    @php
                        $allQuestionIds = $survey->questions->pluck('id')->values();
                        $currentQuestionId = $unansweredQuestions->first()->id ?? null;
                        $currentPosition = $currentQuestionId ? $allQuestionIds->search($currentQuestionId) : 0;
                        $currentIndexDisplay = ($currentPosition === false ? 1 : ($currentPosition + 1));
                        $totalQuestionsDisplay = $survey->questions->count();
                    @endphp
                    <div id="group-status-container" class="mt-4">
                        <p class="text-sm text-gray-500">
                            Question {{ $currentIndexDisplay }} of
                            {{ $totalQuestionsDisplay }}
                        </p>
                    </div>
                </form>

                <div id="message-container" class="mt-4"></div>

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
            if (questionView.checked) {
                groupContainer.style.display = 'block';
                questionContainer.style.display = 'none';
            } else if (defaultView.checked) {
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

          // Global function to render self-evaluation questions
    function renderSelfQuestion(questionObj, currentIndex, totalCount) {
        // Update question text and ID
        document.getElementById("question-text").textContent = questionObj.question;
        document.getElementById("question-id").value = questionObj.id;
        // Update question status tracker
        if (typeof currentIndex !== 'undefined' && typeof totalCount !== 'undefined') {
            // document.getElementById("status-container").innerHTML = `<p class="text-sm text-gray-500">Question ${currentIndex} of ${totalCount}</p>`;
        }
        // Render options row with Disagree/Agree labels
        const optionsRow = document.createElement('div');
        optionsRow.className = 'flex justify-center items-center mt-5 gap-4 px-4';
        // Disagree label
        const disagreeDiv = document.createElement('div');
        disagreeDiv.className = 'flex items-center text-sm text-red-500';
        disagreeDiv.innerHTML = '<p>Disagree</p>';
        optionsRow.appendChild(disagreeDiv);
        // Options radio buttons
        const radiosDiv = document.createElement('div');
        radiosDiv.className = 'flex items-center relative max-w-xl';
        const radiosInner = document.createElement('div');
        radiosInner.className = 'flex justify-center items-center px-4 options-gap';
        radiosInner.style.gap = '35px';
        
        // Check if this is a Rosenberg survey to limit options to 4
        const surveyModelTitle = document.getElementById("survey-model-title")?.value || '';
        const isRosenberg = surveyModelTitle.toLowerCase().trim() === 'rosenberg';
        const optionsToShow = isRosenberg ? (questionObj.options || []).slice(0, 4) : (questionObj.options || []);
        
        optionsToShow.forEach(option => {
            // Check if this option was previously rated
            const isPreRated = questionObj.pre_rated_options && questionObj.pre_rated_options.includes(option.id);
            const checked = isPreRated ? 'checked' : '';
            const dataAttributes = isPreRated ? 'data-previously-rated="true"' : '';
            
            radiosInner.innerHTML += `
                <label for="option-${option.id}" class="cursor-pointer flex justify-center">
                    <input type="radio" name="answer" value="${option.id}"
                        id="option-${option.id}" class="hidden peer" ${checked} ${dataAttributes}>
                    <div style="width: 60px; height: 60px;"
                        class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 mx-auto fw-bold fs-5">
                        ${option.name}
                    </div>
                </label>
            `;
        });
        radiosDiv.appendChild(radiosInner);
        optionsRow.appendChild(radiosDiv);
        // Agree label
        const agreeDiv = document.createElement('div');
        agreeDiv.className = 'flex items-center text-sm text-green-500';
        agreeDiv.innerHTML = '<p>Agree</p>';
        optionsRow.appendChild(agreeDiv);
        // Replace the options-container content
        const optionsContainer = document.getElementById("options-container");
        optionsContainer.innerHTML = '';
        optionsContainer.appendChild(optionsRow);
        // Bind event listeners ONCE to new radios
        document.querySelectorAll('input[name="answer"]').forEach(radioInput => {
            radioInput.addEventListener('change', function() {
                updateSelectedOption(radioInput);
                // Submit answer via AJAX
                const questionId = document.getElementById("question-id").value;
                const surveyId = document.getElementById("survey-id").value;
                const optionId = radioInput.value;
                const evaluateeId = document.getElementById("evaluatee-id").value;
                const messageContainer = document.getElementById("message-container");
                const groupId = document.getElementById("group-id")?.value || '';
                fetch("/survey/submit-answer", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({
                        survey_id: surveyId,
                        question_id: questionId,
                        options_id: optionId,
                        evaluatee_id: evaluateeId,
                        group_id: groupId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">${data.message}</div>`;
                        if (data.next_question) {
                            // If backend provides current/total, use them; else increment
                            let nextIndex = currentIndex + 1;
                            renderSelfQuestion(data.next_question, nextIndex, totalCount);
                        } else {
                            // ✅ REDIRECT VIA FORM
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('rate.survey') }}";

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfInput);

                            const surveyInput = document.createElement('input');
                            surveyInput.type = 'hidden';
                            surveyInput.name = 'survey_id';
                            surveyInput.value = surveyId;
                            form.appendChild(surveyInput);

                            const groupInput = document.createElement('input');
                            groupInput.type = 'hidden';
                            groupInput.name = 'group_id';
                            groupInput.value = groupId;
                            form.appendChild(groupInput);

                            document.body.appendChild(form);
                            form.submit();
                            document.getElementById("question-container").innerHTML = '<p class="text-lg font-semibold text-center">All questions are completed. Thank you!</p>';
                        }
                    } else {
                        messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">${data.message}</div>`;
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
        
        // Initialize any pre-selected options
        document.querySelectorAll('input[name="answer"]:checked').forEach(radio => {
            updateSelectedOption(radio);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Set up event listeners for all radio button labels (self-evaluation only)
        
    // Initial bind for first question
    const totalQuestions = parseInt(document.getElementById("status-container")?.textContent.match(/of\s+(\d+)/)?.[1] || '1', 10);
    renderSelfQuestion({
        question: document.getElementById("question-text").textContent,
        id: document.getElementById("question-id").value,
        options: Array.from(document.querySelectorAll('input[name="answer"]')).map(radio => ({
            id: radio.value,
            name: radio.nextElementSibling.textContent
        }))
    }, 1, totalQuestions);

    // Initialize any pre-selected option for self-evaluation
    const selectedRadio = document.querySelector('input[name="answer"]:checked');
    if (selectedRadio) {
        updateSelectedOption(selectedRadio);
    }
});

        // Self Evaluation
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("question-form");
            const nextButton = document.getElementById("next-button");
            const previousButton = document.getElementById("previous-button");
            const questionContainer = document.getElementById("question-container");
            const messageContainer = document.getElementById("message-container");

            previousButton.addEventListener("click", function() {
    const questionId = document.getElementById("question-id").value;
    const surveyId = document.getElementById("survey-id").value;
    fetch(`{{ route('survey.previousQuestion') }}?question_id=${questionId}&survey_id=${surveyId}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
                .then(response => response.json())
                .then(data => {
                    if (data.question) {
                        // Update question text
                        document.querySelector("#question-text").textContent = data.question.question;
                        // Update question ID
                        document.getElementById("question-id").value = data.question.id;
                        // Update options
                        const optionsContainer = document.querySelector("#options-container");
                        optionsContainer.innerHTML = '';
                        // Determine selected option from either selected_option_id or user_rating.options_id
                        let selectedOptionId = null;
                        if (data.question.selected_option_id) {
                            selectedOptionId = data.question.selected_option_id;
                        } else if (data.question.user_rating && data.question.user_rating.options_id) {
                            selectedOptionId = data.question.user_rating.options_id;
                        }

                        optionsContainer.innerHTML = `
    <div class="flex justify-between items-center w-full max-w-lg mx-auto">
        <!-- Disagree Label -->
        <span class="text-red-500 font-extralight disaggree-label" style="margin-right: 1rem;">Disagree</span>

        <!-- Options wrapper -->
        <div class="flex gap-6 options-gap" id="rating-buttons"></div>

        <!-- Agree Label -->
        <span class="text-green-500 font-extralight agree-label" style="margin-left: 1rem;">Agree</span>
    </div>
`;

const ratingButtonsContainer = document.getElementById("rating-buttons");

data.question.options.forEach(option => {
    const checked = (data.question.selected_option_id && option.id == data.question.selected_option_id) ? 'checked' : '';
    const ratedLabel = (checked) ? ` <span class="text-green-600 font-bold">${option.name}</span>` : '';

    ratingButtonsContainer.innerHTML += `
        <label for="option-${option.id}" class="cursor-pointer flex justify-center">
            <input type="radio" name="answer" value="${option.id}"
                id="option-${option.id}" class="hidden peer"
                onchange="updateSelectedOption(this)" ${checked}>
            <div style="width: 60px; height: 60px;"
                class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center
                       peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white
                       transition-all duration-200 font-bold text-lg">
                ${option.name}${ratedLabel}
            </div>
        </label>
    `;
});

                        
                        // If a radio is checked, trigger updateSelectedOption for UI
                        if (selectedOptionId) {
                            const checkedRadio = document.querySelector(`input[name='answer'][value='${selectedOptionId}']`);
                            if (checkedRadio) updateSelectedOption(checkedRadio);
                        }
                    } else {
                        messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">No previous question available.</div>`;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">An error occurred while loading the previous question.</div>`;
                });
            });

            nextButton.addEventListener("click", function() {
    const selectedOption = document.querySelector("input[name='answer']:checked");
    const questionId = document.getElementById("question-id").value;
    const surveyId = document.getElementById("survey-id").value;
    const evaluateeId = document.getElementById("evaluatee-id").value;
    const groupId = "{{ $request->group_id ?? '' }}";

    if (!selectedOption) {
                    messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Please select an option before proceeding.</div>`;
        return;
    }

                // Check if the current question is already rated
                // We can detect this by checking if the option has a data attribute indicating it was previously rated
                // or by checking if the option was pre-selected from the server
                const isAlreadyRated = selectedOption && (
                    selectedOption.hasAttribute('data-previously-rated') || 
                    selectedOption.defaultChecked ||
                    selectedOption.getAttribute('data-already-saved') === 'true'
                );

                if (isAlreadyRated) {
                    // Question is already rated, find next rated question
                    fetch("/survey/get-next-rated-question", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            current_question_id: questionId,
                            survey_id: surveyId,
                            group_id: groupId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.question) {
                            // Render the next rated question
                            renderSelfQuestion(data.question, data.current_index, data.total_count);
                            // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">Next rated question loaded.</div>`;
                        } else {
                            // No more rated questions found, fetch unrated questions
                            fetchSelfUnratedQuestions();
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching next rated question:", error);
                        // Fallback to fetching unrated questions
                        fetchSelfUnratedQuestions();
                    });
                } else {
                    // Question is not rated, proceed with normal flow
    fetch("/survey/get-next-question", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            question_id: questionId,
            selected_option_id: selectedOption.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.question) {
                            // Render the next question dynamically
                            renderSelfQuestion(data.question, data.current_index, data.total_count);
                            // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">Next question loaded.</div>`;
                        } else {
                            // No more questions in current flow, try unrated questions
                            fetchSelfUnratedQuestions();
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Error loading next question. Please try again.</div>`;
                    });
                }

                // Helper function to fetch unrated questions for self-evaluation
                function fetchSelfUnratedQuestions() {
                    console.log('Fetching unrated questions for self-evaluation:', { surveyId, groupId });
                    
                    fetch("/survey/get-unrated-questions", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            survey_id: surveyId,
                            group_id: groupId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Unrated questions response:', data);
                        
                        if (data.questions && data.questions.length > 0) {
                            // Render the first unrated question
                            renderSelfQuestion(data.questions[0], data.current_index, data.total_count);
                            // messageContainer.innerHTML = `<div class="bg-blue-500 text-white p-3 rounded">Unrated question loaded.</div>`;
        } else {
            // ✅ REDIRECT VIA FORM
            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('rate.survey') }}";

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfInput);

                            const surveyInput = document.createElement('input');
                            surveyInput.type = 'hidden';
                            surveyInput.name = 'survey_id';
                            surveyInput.value = surveyId;
                            form.appendChild(surveyInput);

                            const groupInput = document.createElement('input');
                            groupInput.type = 'hidden';
                            groupInput.name = 'group_id';
                            groupInput.value = groupId;
                            form.appendChild(groupInput);

                            document.body.appendChild(form);
                            form.submit();
            document.getElementById("question-container").innerHTML = '<p class="text-lg font-semibold text-center">All questions are completed. Thank you!</p>';
        }
    })
                    .catch(error => {
                        console.error("Error fetching unrated questions:", error);
                        messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Error loading next question. Please try again.</div>`;
                    });
                }
});
        });

        // Global function to update selected option for group evaluation
        function updateGroupSelectedOption(radioInput) {
            const name = radioInput.name;
            // Uncheck all radio buttons with the same name
            document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
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
            selectedCircle.style.borderColor = '#5cb031';
            selectedCircle.style.color = 'white';
            selectedCircle.classList.remove('bg-white', 'border-gray-300');
        }

        // Global function to render group evaluation options
        function renderGroupEvaluationOptions(groupUsers, options, preRatedOptions) {
            console.log('Rendering group evaluation options:', { groupUsers, options, preRatedOptions });
            
            const groupOptions = document.getElementById("group-options");
            if (!groupOptions) {
                console.error('Group options container not found');
                return;
            }
            
            // Clear existing content
            groupOptions.innerHTML = '';
            
            // Usernames Column
            const usernamesColumn = document.createElement('div');
            usernamesColumn.className = 'col-4 d-flex flex-column justify-start usernames-column';
            usernamesColumn.style.marginTop = '0rem';
            
            groupUsers.forEach((user, index) => {
                const userDiv = document.createElement('div');
                userDiv.className = 'p-3 max-w-7xl mx-auto';
                userDiv.style.marginTop = index === 0 ? '1.85rem' : '1.85rem';
                userDiv.style.marginBottom = '0.43rem';
                
                userDiv.innerHTML = `
                    <div class="mb-3 text-md text-red-500">
                        <p>${user.name.length > 15 ? user.name.substring(0, 15) + '...' : user.name}</p>
                    </div>
                `;
                usernamesColumn.appendChild(userDiv);
            });
            
            // Options Column
            const optionsColumn = document.createElement('div');
            optionsColumn.className = 'col-6 py-4 max-w-7xl space-y-6 border';
            optionsColumn.style.border = '1px solid rgb(184, 184, 184) !important';
            optionsColumn.style.borderRadius = '30px';
            
            groupUsers.forEach((user, userIndex) => {
                if (user.id !== {{ Auth::id() }}) {
                    const userOptionsDiv = document.createElement('div');
                    userOptionsDiv.className = 'flex justify-center items-center relative max-w-xl';
                    
                    const optionsInnerDiv = document.createElement('div');
                    optionsInnerDiv.className = 'flex justify-center items-center px-4 gap-5';
                    
                    // Check if this is a Rosenberg survey to limit options to 4
                    const surveyModelTitle = document.getElementById("survey-model-title")?.value || '';
                    const isRosenberg = surveyModelTitle.toLowerCase().trim() === 'rosenberg';
                    const optionsToShow = isRosenberg ? options.slice(0, 4) : options;
                    
                    optionsToShow.forEach((option, optionIndex) => {
                        const isPreRated = preRatedOptions && preRatedOptions.some(preRated => 
                            preRated.user_id === user.id && preRated.option_id === option.id
                        );
                        const checked = isPreRated ? 'checked' : '';
                        const dataAttributes = isPreRated ? 'data-previously-rated="true"' : '';
                        
                        const label = document.createElement('label');
                        label.className = 'flex flex-col items-center cursor-pointer';
                        label.setAttribute('for', `option-${user.id}-${option.id}`);
                        
                        label.innerHTML = `
                            <input type="radio"
                                name="answer[${user.id}]"
                                value="${option.id}"
                                id="option-${user.id}-${option.id}"
                                class="hidden peer"
                                ${checked} ${dataAttributes} />
                            <div style="width: 60px; height: 60px;"
                                class="rounded-full border-2 border-gray-300 bg-white flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white transition-all duration-200 fw-bold fs-5">
                                ${option.name}
                            </div>
                        `;
                        
                        optionsInnerDiv.appendChild(label);
                    });
                    
                    userOptionsDiv.appendChild(optionsInnerDiv);
                    optionsColumn.appendChild(userOptionsDiv);
                    
                    // Add separator unless it's the last item
                    if (userIndex < groupUsers.length - 1) {
                        const separator = document.createElement('div');
                        separator.style.display = 'flex';
                        separator.style.justifyContent = 'center';
                        separator.innerHTML = '<hr style="border: 1px solid rgb(184, 184, 184); width: 90%;">';
                        optionsColumn.appendChild(separator);
                    }
                }
            });
            
            // Append both columns to group options
            groupOptions.appendChild(usernamesColumn);
            groupOptions.appendChild(optionsColumn);
            
            // Re-bind event listeners for new radio buttons
            document.querySelectorAll('input[name^="answer["]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateGroupSelectedOption(this);
                });
            });
            
            // Initialize any pre-selected options
            document.querySelectorAll('input[name^="answer["]:checked').forEach(radio => {
                updateGroupSelectedOption(radio);
            });
        }

        // Global function to render group evaluation questions
        function renderGroupQuestion(questionObj, currentIndex, totalCount) {
            console.log('Rendering group question:', questionObj, { currentIndex, totalCount });
            
            // Update question text and ID
            document.querySelector("#group-container #question-text").textContent = questionObj.question;
            document.querySelector("#group-container input[name='question_id']").value = questionObj.id;
            
            // Update question description if it exists
            const descriptionElement = document.querySelector("#group-container #question-text + p");
            if (descriptionElement && questionObj.description) {
                descriptionElement.textContent = questionObj.description;
            }
            
            // Update question status tracker
            if (typeof currentIndex !== 'undefined' && typeof totalCount !== 'undefined') {
                // document.getElementById("group-status-container").innerHTML = `<p class="text-sm text-gray-500">Question ${currentIndex} of ${totalCount}</p>`;
            }
            
            // Update guidance options
            const guidanceOptions = document.getElementById("guidance-options");
            guidanceOptions.innerHTML = '';
            
            // Check if this is a Rosenberg survey to limit options to 4
            const surveyModelTitle = document.getElementById("survey-model-title")?.value || '';
            const isRosenberg = surveyModelTitle.toLowerCase().trim() === 'rosenberg';
            const guidanceOptionsToShow = isRosenberg ? (questionObj.options || []).slice(0, 4) : (questionObj.options || []);
            
            guidanceOptionsToShow.forEach((option, index) => {
                guidanceOptions.innerHTML += `
                    <div class="flex flex-col items-center opacity-60 select-none">
                        <div style="width: 60px; height: 60px; background: #ffffff; border: 2px dashed #ccc;"
                            class="rounded-full flex items-center justify-center fw-bold fs-5">
                            ${option.name}
                        </div>
                        <div class="mt-1 text-sm text-gray-600">
                            ${index + 1} Cevap
                            ${((index + 1) * 100) / guidanceOptionsToShow.length}%
                        </div>
                    </div>
                `;
            });
            
            // Update group evaluation options
            const groupOptions = document.getElementById("group-options");
            console.log('Group options container:', groupOptions);
            console.log('Question group_users:', questionObj.group_users);
            
            if (groupOptions && questionObj.group_users) {
                // Re-render group options with new question data
                renderGroupEvaluationOptions(questionObj.group_users, questionObj.options, questionObj.pre_rated_options);
            } else {
                console.warn('Missing group_users or groupOptions container. Group users:', questionObj.group_users, 'Group options container:', groupOptions);
            }
            
            // Mark pre-rated options if question is already rated
            if (questionObj.pre_rated_options && questionObj.pre_rated_options.length > 0) {
                questionObj.pre_rated_options.forEach(preRated => {
                    // pre_rated_options contains objects with user_id and option_id
                    const optionId = preRated.option_id || preRated; // Handle both object and simple value
                    const userId = preRated.user_id;
                    
                    if (userId) {
                        // For group evaluation, find the radio button for this specific user and option
                        const radio = document.querySelector(`input[name="answer[${userId}]"][value="${optionId}"]`);
                        if (radio) {
                            radio.checked = true;
                            updateGroupSelectedOption(radio);
                        }
                    } else {
                        // Fallback for simple optionId (self-evaluation)
                    const radio = document.querySelector(`input[name^="answer["][value="${optionId}"]`);
                    if (radio) {
                        radio.checked = true;
                        updateGroupSelectedOption(radio);
                        }
                    }
                });
            }
        }

        // Group Evaluation 
        document.addEventListener("DOMContentLoaded", function () {
            const nextButton = document.getElementById("next-group-button");
            const previousButton = document.getElementById("previous-group-button");
            const messageContainer = document.getElementById("message-container");



            // Set up event listeners for group evaluation radio buttons (input only)
            document.querySelectorAll('input[name^="answer["]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateGroupSelectedOption(this);
                    // Auto-submit for group evaluation
                    const questionId = document.querySelector("#group-container input[name='question_id']").value;
                    const surveyId = document.querySelector("#group-container input[name='survey_id']").value;
                    const optionId = this.value;
                    const evaluateeId = this.name.match(/\[(\d+)\]/)[1];
                    const groupId = document.querySelector("#group-container input[name='group_id']").value;
                    console.log('[AJAX] Submitting group answer', {
                        question_id: questionId,
                        survey_id: surveyId,
                        evaluatee_id: evaluateeId,
                        options_id: optionId,
                        group_id: groupId
                    });
                    fetch("{{ route('survey.submitGroupAnswer') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            survey_id: surveyId,
                            evaluatee_id: evaluateeId,
                            options_id: optionId,
                            group_id: groupId
                        })
                    })
                    .then(async res => {
                        const contentType = res.headers.get("content-type");
                        if (contentType && contentType.includes("application/json")) {
                            const data = await res.json();
                            if (!res.ok) {
                                throw new Error(data.message || 'An error occurred');
                            }
                            return data;
                        }
                        throw new Error('Server returned non-JSON response');
                    })
   
                    .then(data => {
                        if (data.status === "success" || data.skipped) {
                            messageContainer.innerHTML = `<div class=\"bg-green-500 text-white p-3 rounded\">${data.message || 'Rating submitted.'}</div>`;

                            // ✅ REDIRECT VIA FORM
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('rate.survey') }}";

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfInput);

                            const surveyInput = document.createElement('input');
                            surveyInput.type = 'hidden';
                            surveyInput.name = 'survey_id';
                            surveyInput.value = surveyId;
                            form.appendChild(surveyInput);

                            const groupInput = document.createElement('input');
                            groupInput.type = 'hidden';
                            groupInput.name = 'group_id';
                            groupInput.value = groupId;
                            form.appendChild(groupInput);

                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            messageContainer.innerHTML = `<div class=\"bg-red-500 text-white p-3 rounded\">${data.message}</div>`;
                        }
                    })

                    .catch(err => {
                        console.error("[AJAX] Error submitting group answer:", err);
                        const errorMessage = err.message || 'An error occurred. Please try again.';
                        messageContainer.innerHTML = `<div class=\"bg-red-500 text-white p-3 rounded\">${errorMessage}</div>`;
                    });
                });
            });

            // Initialize any pre-selected options in group evaluation
            document.querySelectorAll('input[name^="answer["]:checked').forEach(radio => {
                console.log('Initializing pre-selected option:', radio);
                updateGroupSelectedOption(radio);
            });
 
            // Previous button functionality for group evaluation
            previousButton.addEventListener("click", function() {
                const questionId = document.querySelector("#group-container input[name='question_id']").value;
                const surveyId = document.querySelector("#group-container input[name='survey_id']").value;
                const groupId = document.querySelector("#group-container input[name='group_id']").value;
                
                console.log('Previous group button clicked:', { questionId, surveyId, groupId });
                
                fetch(`{{ route('survey.previousQuestion') }}?question_id=${questionId}&survey_id=${surveyId}&group_id=${groupId}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Previous question response:', data);
                    
                    if (data.question) {
                        console.log('Rendering previous question:', data.question.id, 'Current question ID:', questionId);
                        
                        // Use the renderGroupQuestion function to properly update the UI
                        renderGroupQuestion(data.question, data.current_index, data.total_count);
                        
                        // Verify the question ID was updated
                        const updatedQuestionId = document.querySelector("#group-container input[name='question_id']").value;
                        console.log('Question ID after render:', updatedQuestionId);
                        
                        // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">Previous question loaded.</div>`;
                    } else {
                        messageContainer.innerHTML = `<div class="bg-blue-500 text-white p-3 rounded">No previous question available.</div>`;
                    }
                })
                .catch(error => {
                    console.error("Error loading previous question:", error);
                    messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Error: ${error.message || 'An error occurred while loading the previous question.'}</div>`;
                });
            });

            // Next button functionality for group evaluation
            nextButton.addEventListener("click", function () {
                const questionId = document.querySelector("#group-container input[name='question_id']").value;
                const surveyId = document.querySelector("#group-container input[name='survey_id']").value;
                const groupId = document.querySelector("#group-container input[name='group_id']").value;
                const answers = document.querySelectorAll("input[name^='answer[']:checked");

                console.log('Next group button clicked:', { questionId, surveyId, groupId, answersCount: answers.length });

                // First, check if all users are rated for the current question
                fetch("{{ route('survey.checkAllUsersRated') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        survey_id: surveyId,
                        group_id: groupId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Check all users rated response:', data);
                    
                    if (data.all_rated) {
                        // All users are rated for this question, find next rated question
                        fetch("/survey/get-next-rated-question", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                current_question_id: questionId,
                                survey_id: surveyId,
                                group_id: groupId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.question) {
                                // Render the next rated question
                                renderGroupQuestion(data.question, data.current_index, data.total_count);
                                // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">Next rated question loaded.</div>`;
                            } else {
                                // No more rated questions found, fetch unrated questions
                                fetchGroupUnratedQuestions();
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching next rated question:", error);
                            // Fallback to fetching unrated questions
                            fetchGroupUnratedQuestions();
                        });
                    } else {
                        // Not all users are rated, proceed with normal submission
                        if (answers.length === 0) {
                            messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Please rate all users before proceeding.</div>`;
                        return;
                    }
                    
                        // Submit the selected answers
                    submitSelectedAnswers();
                    }
                })
                .catch(error => {
                    console.error("Error checking if all users are rated:", error);
                    messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Error checking ratings. Please try again.</div>`;
                });

                // Helper function to fetch unrated questions for group
                function fetchGroupUnratedQuestions() {
                    console.log('Fetching unrated questions for group:', { surveyId, groupId });
                    console.log('Current question ID when fetching unrated:', document.querySelector("#group-container input[name='question_id']").value);
                    
                    fetch("/survey/get-unrated-questions", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            survey_id: surveyId,
                            group_id: groupId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Unrated questions response:', data);
                        
                        if (data.questions && data.questions.length > 0) {
                            // Render the first unrated question
                            renderGroupQuestion(data.questions[0], data.current_index, data.total_count);
                         // ✅ REDIRECT VIA FORM
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('rate.survey') }}";

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfInput);

                            const surveyInput = document.createElement('input');
                            surveyInput.type = 'hidden';
                            surveyInput.name = 'survey_id';
                            surveyInput.value = surveyId;
                            form.appendChild(surveyInput);

                            const groupInput = document.createElement('input');
                            groupInput.type = 'hidden';
                            groupInput.name = 'group_id';
                            groupInput.value = groupId;
                            form.appendChild(groupInput);

                            document.body.appendChild(form);
                            form.submit();
                            // messageContainer.innerHTML = `<div class="bg-blue-500 text-white p-3 rounded">Unrated question loaded.</div>`;
                        } else {
                            // ✅ REDIRECT VIA FORM
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('rate.survey') }}";

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = "{{ csrf_token() }}";
                            form.appendChild(csrfInput);

                            const surveyInput = document.createElement('input');
                            surveyInput.type = 'hidden';
                            surveyInput.name = 'survey_id';
                            surveyInput.value = surveyId;
                            form.appendChild(surveyInput);

                            const groupInput = document.createElement('input');
                            groupInput.type = 'hidden';
                            groupInput.name = 'group_id';
                            groupInput.value = groupId;
                            form.appendChild(groupInput);

                            document.body.appendChild(form);
                            form.submit();
                            document.getElementById("group-container").innerHTML = '<p class="text-lg font-semibold text-center">All questions are completed. Thank you!</p>';
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching unrated questions:", error);
                        // messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Error loading next question. Please try again.</div>`;
                    });
                }

                function submitSelectedAnswers() {
                    // Filter out already answered questions
                    const newAnswers = Array.from(answers).filter(answer => {
                        const evaluateeId = answer.name.match(/\[(\d+)\]/)[1];
                        // Check if this user has already been rated for this question
                        const existingRating = document.querySelector(`input[name="answer[${evaluateeId}]"]:checked`);
                        if (existingRating) {
                            // Check if this rating was already saved (has data attribute or is pre-selected)
                            return !existingRating.hasAttribute('data-already-saved') && !existingRating.defaultChecked;
                        }
                        return true;
                    });

                    if (newAnswers.length === 0) {
                        messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">No new ratings to submit. Selected users have already been rated.</div>`;
                        return;
                    }

                    let completed = 0;
                    let failed = 0;
                    let skipped = 0;

                    newAnswers.forEach((answer) => {
                        // Extract user ID from the name attribute (answer[user_id])
                        const evaluateeId = answer.name.match(/\[(\d+)\]/)[1];
                        const optionId = answer.value;
                        const groupId = "{{ $request->group_id ?? '' }}";

                        fetch("{{ route('survey.submitGroupAnswer') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                question_id: questionId,
                                survey_id: surveyId,
                                evaluatee_id: evaluateeId,
                                options_id: optionId,
                                group_id: groupId
                            })
                        })
                        .then(async res => {
                            const contentType = res.headers.get("content-type");
                            if (contentType && contentType.includes("application/json")) {
                                const data = await res.json();
                                if (!res.ok) {
                                    throw new Error(data.message || 'An error occurred');
                                }
                                return data;
                            }
                            throw new Error('Server returned non-JSON response');
                        })
                        .then(data => {
                            if (data.skipped) {
                                skipped++;
                            } else {
                                completed++;
                            }
                            // Mark this answer as already saved
                            answer.setAttribute('data-already-saved', 'true');
                            
                            if (completed + failed + skipped === newAnswers.length) {
                                let message = '';
                                if (completed > 0) {
                                    message += `Successfully submitted ${completed} new rating(s). `;
                                }
                                if (skipped > 0) {
                                    message += `Skipped ${skipped} existing rating(s). `;
                                }
                                if (failed > 0) {
                                    message += `Failed to submit ${failed} rating(s).`;
                                }
                                
                                if (failed === 0) {
                                    messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">${message.trim()}</div>`;
                                    
                                    // Add a small delay to ensure server has processed the ratings
                                    setTimeout(() => {
                                        // After successful submission, check if all users are now rated
                                        const currentQuestionId = document.querySelector("#group-container input[name='question_id']").value;
                                        console.log('Checking if all users are rated for question:', currentQuestionId);
                                        
                                        fetch("{{ route('survey.checkAllUsersRated') }}", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Accept": "application/json"
                                            },
                                            body: JSON.stringify({
                                                question_id: currentQuestionId,
                                                survey_id: surveyId,
                                                group_id: groupId
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log('Check all users rated response after submission:', data);
                                            
                                            if (data.all_rated) {
                                                // console.log('All users rated, fetching next question...');
                                                // All users are now rated, find next rated question
                                                fetch("/survey/get-next-rated-question", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
 body: JSON.stringify({
                                                        current_question_id: currentQuestionId,
                                                        survey_id: surveyId,
                                                        group_id: groupId
                                                    })
                                                })
                                                .then(response => response.json())
.then(data => {
                                                    console.log('Next rated question response:', data);
                                                    
                                                    if (data.question) {
                                                        // Render the next rated question
                                                        renderGroupQuestion(data.question, data.current_index, data.total_count);
                                                        // messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">Next rated question loaded.</div>`;
                                                    } else {
                                                        // No more rated questions found, fetch unrated questions
                                                        console.log('No more rated questions, fetching unrated questions...');
                                                        fetchGroupUnratedQuestions();
                                                    }
})
.catch(error => {
                                                    console.error("Error fetching next rated question:", error);
                                                    // Fallback to fetching unrated questions
                                                    fetchGroupUnratedQuestions();
                                                });
                                            } else {
                                                // Not all users rated yet, stay on current question
                                                console.log('Not all users rated yet:', data.rated_count, '/', data.total_count);
                                                messageContainer.innerHTML = `<div class="bg-blue-500 text-white p-3 rounded">${data.rated_count}/${data.total_count} users rated. Please rate all users.</div>`;
                                                
                                                // Add debugging info
                                                console.log('Current question ID:', currentQuestionId);
                                                console.log('Survey ID:', surveyId);
                                                console.log('Group ID:', groupId);
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Error checking if all users are rated after submission:", error);
                                        });
                                    }, 500); // 500ms delay to ensure server processing

                                } else {
                                    messageContainer.innerHTML = `<div class=\"bg-red-500 text-white p-3 rounded\">${message.trim()}</div>`;
                                }
                            }
                        })
                        .catch(err => {
                            console.error("Error:", err);
                            failed++;
                            const errorMessage = err.message || 'An error occurred. Please try again.';
                            if (completed + failed + skipped === newAnswers.length) {
                                messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">${errorMessage}</div>`;
                            }
                        });
                    });
                }
            });
        });

    </script>
 


</x-app-layout>