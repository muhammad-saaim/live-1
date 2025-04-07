<div class="w-1/3 bg-ml-color-lime space-y-2 rounded-xl border border-color-gray shadow-xl">
    <h1 class="p-2 font-semibold text-xl">{{__('Group Name')}}</h1>
    <p class="ml-2"> {{__('User number')}}: 10 </p>
    <p class="ml-2 text-sm"> {{__('Group created')}}: 06/09/2024 </p>

    <div>
        <x-group-progressbar class="mb-2" num="100"> Me </x-group-progressbar>
        <x-group-progressbar num="40"> Family </x-group-progressbar>
    </div>
      

    <div class="space-y-2 p-2">
        <div class="flex items-center justify-between space-x-2">
            <label for="loyalty-survey" class="w-1/3">Loyalty Sur.</label>
            <button class="w-1/4 bg-white border border-black rounded-xl px-2 py-1">Solve</button>
            <select id="loyalty-survey" class="w-1/2 border border-black rounded-xl px-2 py-1">
                <option value="status"> {{__("Participant Status")}} </option>
            </select>
        </div>
        <div class="flex items-center justify-between space-x-2">
            <label for="confidence-survey" class="w-1/3">Confidence Sur.</label>
            <button class="w-1/4 bg-white border border-black rounded-xl px-2 py-1">Solve</button>
            <select id="confidence-survey" class="w-1/2 border border-black rounded-xl px-2 py-1">
                <option value="status">{{__("Participant Status")}}</option>
            </select>
        </div>
    </div>

    <div class="p-3">
        <button class="flex justify-center mt-6 bg-red text-white py-2 w-full rounded-xl font-semibold"> <img class="flex" src="https://cdn3.emoji.gg/emojis/9963-trashcan.png " height="28" width="28"> Delete Group</button>
    </div>
    
</div>