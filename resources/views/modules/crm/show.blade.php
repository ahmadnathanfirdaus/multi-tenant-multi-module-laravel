<x-layouts.app>
    <div class="p-6">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-3 text-green-500"></i>
                        {{ $contact->full_name }}
                    </h1>
                    <p class="text-gray-600 mt-2">Contact Details</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('modules.crm.edit', $contact) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('modules.crm.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Contacts
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contact Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <p class="text-gray-900">{{ $contact->first_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <p class="text-gray-900">{{ $contact->last_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-gray-900">
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                            <p class="text-gray-900">{{ $contact->company ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <p class="text-gray-900">{{ $contact->position ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    
                    @if($contact->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $contact->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status & Deal Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Status & Deal</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($contact->status === 'customer') bg-green-100 text-green-800
                                @elseif($contact->status === 'prospect') bg-blue-100 text-blue-800
                                @elseif($contact->status === 'lead') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($contact->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deal Value</label>
                            <p class="text-2xl font-bold text-gray-900">
                                @if($contact->deal_value)
                                    ${{ number_format($contact->deal_value, 2) }}
                                @else
                                    <span class="text-gray-500 text-base font-normal">No deal value set</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                            <p class="text-gray-900">{{ $contact->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $contact->updated_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Added By</label>
                            <p class="text-gray-900">{{ $contact->user->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('modules.crm.edit', $contact) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Contact
                        </a>
                        
                        <form action="{{ route('modules.crm.destroy', $contact) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this contact?')">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Contact
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
