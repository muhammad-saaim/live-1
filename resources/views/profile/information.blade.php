<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Information') }}
        </h2>
    </x-slot>


    <form action="{{ route('profile.information') }}" method="POST">
        @csrf
        <div class=" max-w-7xl mx-auto space-x-5">
            <div class="flex space-x-10 ml-10 mt-10 ">
                <div class=" flex justify-self-center rounded-xl p-2 bg-ml-color-lime border-dashed border-2">
                    <img src="http://placehold.it/250" class="rounded">
                </div>

                <div class="flex-1 rounded-xl p-6 bg-ml-color-lime">
                    <textarea class="w-full h-40 bg-white p-2 shadow outline-none resize-none bg-transparent placeholder-gray-400 rounded-xl" placeholder="{{__('Biography...')}}"></textarea>
                    <label class="flex justify-end" for="">0/1000</label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-5 justify-between mt-6 ">
                <div class="w-full space-y-10 p-3">
                    <!-- Change the input vaues to placeholder -->

                    <div class="justify-self-stretch">
                        <x-input-label class="mb-5" >{{__('Personal Informations')}}</x-input-label>
                        <x-form-input name="name" >{{__("Name lastname")}}</x-form-input>
                        <x-form-input name="city" >{{__('City')}}</x-form-input>
                        <x-form-input name="birthDate" >{{__('Birth Date')}}</x-form-input>
                        <x-form-input name="blood" >{{__('Blood Type')}}</x-form-input>
                    </div>

                    <div class="justify-self-stretch">
                        <x-input-label class="mb-5" >{{__('Contact Informations')}}</x-input-label>
                        <x-form-input  name="phone" >{{__("Phone")}}</x-form-input>
                    </div>

                    <div class="justify-self-stretch">
                        <x-input-label class="mb-5">{{__('Other')}}</x-input-label>
                        <x-form-input name="hobies" >{{__('Hobies')}}</x-form-input>
                        <x-form-input name="pet" >{{__('Pet Type')}}</x-form-input>
                    </div>



                </div>

                <div class="w-full p-3">
                    <div class="justify-self-stretch">
                        <x-input-label>{{__('Education Informations')}}</x-input-label>
                        <x-form-input name="gradDepartment" >{{__('Graduated Department')}}</x-form-input>
                        <x-form-input name="gradSchool" >{{__('Graduated School')}}</x-form-input>
                        <x-form-input name="gradYear" >{{__('Graduated Year')}}</x-form-input>
                    </div>

                    <div class="justify-self-stretch">
                        <x-input-label>{{__('Job Experience')}}</x-input-label>
                        <x-form-input name="profession" >{{__('Profession')}}</x-form-input>
                        <x-form-input name="currJob" >{{__('Current Job')}}</x-form-input>
                        <x-form-input name="pastJob" >{{__('Past Job')}}</x-form-input>
                    </div>

                    <div class="justify-self-stretch">
                        <x-input-label>{{__('Personal Skills')}}</x-input-label>
                        <x-form-input name="skill1" >{{__('Skill 1')}}</x-form-input>
                        <x-form-input name="skill2" >{{__('Skill 2')}}</x-form-input>
                        <x-form-input name="skill3" >{{__('Skill 3')}}</x-form-input>
                    </div>

                    <div class="flex justify-end space-x-3 ">
                        <a href="/dashboard" class="w-full w-1/2 border text-center rounded-xl bg-ml-color-orange p-4" >{{__('Complete Later')}}</a>
                        <button class="w-full w-1/2 border text-center rounded-xl bg-ml-color-green p-4" type="submit">{{__('Save')}}</button>
                    </div>
                </div>


            </div>
        </div>
    </form>




</x-app-layout>
