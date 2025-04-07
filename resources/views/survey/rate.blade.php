<x-app-layout>
    <div class="p-3 max-w-7xl mx-auto space-y-4">
        <div class="bg-ml-color-lime border rounded-xl p-4 space-y-3">
            <h2 class="font-bold text-2xl text-center" id="survey-title">{{ $survey->title }}</h2>

            {{-- Success and Error Messages --}}
            <div id="message-container"></div>

            <div id="question-container" class="text-center">
                @if($unansweredQuestions->isNotEmpty())
                    <p id="question-text" class="text-lg font-semibold">{{ $unansweredQuestions->first()->question }}</p>
                    <p id="question-text" class="text-base font-semibold mb-2">{{ $unansweredQuestions->first()->description }}</p>

                    <form id="question-form">
                        <div id="options-container" class="flex justify-center gap-4 mt-4">
                            <div class="flex items-center space-x-2 text-sm text-red-500">
                                <p>Dis Agree</p>
                            </div>
                            @foreach($unansweredQuestions->first()->options as $option)
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="answer" value="{{ $option->id }}" id="option-{{ $option->id }}">
                                    <label for="option-{{ $option->id }}" class="cursor-pointer">{{ $option->name }}</label>
                                </div>
                            @endforeach
                            <div class="flex items-center space-x-2 text-sm text-green-500">
                                <p>Agree</p>
                            </div>
                        </div>

                        <input type="hidden" name="question_id" id="question-id" value="{{ $unansweredQuestions->first()->id }}">
                        <input type="hidden" name="survey_id" id="survey-id" value="{{ $survey->id }}">

                        <div class="flex justify-center space-x-4 mt-4">
                            <button type="button" id="next-button" class="bg-blue-500 text-white py-2 px-4 rounded">Next</button>
                        </div>
                    </form>
                    {{-- Question Status Tracker --}}
                    <div id="status-container" class="mt-4">
                        <p class="text-sm text-gray-500">
                            Question {{ $unansweredQuestions->keys()->first() + 1 }} of {{ $unansweredQuestions->count() }}
                        </p>
                    </div>
                @else
                    <p class="text-lg font-semibold text-center">All questions are completed. Thank you!</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("question-form");
            const nextButton = document.getElementById("next-button");
            const questionContainer = document.getElementById("question-container");
            const messageContainer = document.getElementById("message-container");

            nextButton.addEventListener("click", function() {
                const selectedOption = document.querySelector("input[name='answer']:checked");
                if (!selectedOption) {
                    messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">Please select an option before proceeding.</div>`;
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
                            messageContainer.innerHTML = `<div class="bg-green-500 text-white p-3 rounded">${data.message}</div>`;
                            setTimeout(() => {
                                location.reload(); // Refresh page to show the next question
                            }, 1000);
                        } else {
                            messageContainer.innerHTML = `<div class="bg-red-500 text-white p-3 rounded">${data.message}</div>`;
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>

</x-app-layout>
