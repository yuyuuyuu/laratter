<!-- resources/views/tweet/direct.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
      {{ __('Direct Message') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-8/12 md:w-1/2 lg:w-5/12">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800 ">
          @include('common.errors')
        <form class="mb-6" action="{{ route('messages.store') }}" method="POST">
            @csrf
            <div class="flex flex-col mb-4">
                <label for="recieving_id"><font color="white">ユーザーを選択:</font></label>
                <select name="recieving_id" id="recieving_id">
                    @foreach ($userNames as $userId => $userName)
                        <option value="{{ $userId }}" {{ session('selected_user') == $userId ? 'selected' : '' }}>{{ $userName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col mb-4">
                <x-input-label for="dm" :value="__('Message')" />
                <x-text-input id="dm" class="block mt-1 w-full" type="text" name="dm" :value="old('dm')" required autofocus />
                <x-input-error :messages="$errors->get('dm')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ml-3" type="submit">
                    {{ __('Send') }}
                </x-primary-button>
            </div>
        </form>
        <div class="messages">
            @foreach ($messages as $message)
                <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}">
                    @if ($message->user)
                        <p style="color: white; font-size: 16px;">{{ $message->user->name }}</p>
                    @endif
                    <p style="color: white; font-size: 24px;{{ $message->user_id === auth()->id() ? 'color: white;' : '' }}">{{ $message->dm }}</p>
                    <p class="timestamp" style="color: white; font-size: 14px;">{{ $message->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            @endforeach
        </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

