<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($balance)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Current Balance</h3>
                            <p class="text-3xl font-bold text-green-600">${{ number_format($balance->current_balance, 2) }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Opening Balance</h3>
                            <p class="text-xl text-gray-600">${{ number_format($balance->opening_balance, 2) }}</p>
                        </div>
                    @else
                        <div class="max-w-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Set Opening Balance</h3>
                            
                            @if(session('success'))
                                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('balance.store') }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="opening_balance" class="block text-sm font-medium text-gray-700 mb-1">
                                        Opening Balance
                                    </label>
                                    <input 
                                        type="number" 
                                        id="opening_balance" 
                                        name="opening_balance" 
                                        step="0.01" 
                                        min="0"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter your opening balance"
                                    >
                                    @error('opening_balance')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <button 
                                    type="submit" 
                                    class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    Save Opening Balance
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
