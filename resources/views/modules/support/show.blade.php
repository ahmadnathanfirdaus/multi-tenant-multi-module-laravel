<x-layouts.app>
    <div class="p-6">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-ticket-alt mr-3 text-red-500"></i>
                        {{ $ticket->ticket_number }}
                    </h1>
                    <p class="text-gray-600 mt-2">{{ $ticket->subject }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('modules.support.edit', $ticket) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('modules.support.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Tickets
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Ticket Details -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ticket Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ticket Number</label>
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $ticket->ticket_number }}</code>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-gray-900">{{ $ticket->category ?? 'General' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $ticket->subject }}</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ticket Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reporter</label>
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-red-600 text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                            <p class="text-gray-900">{{ $ticket->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $ticket->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        
                        @if($ticket->resolved_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Resolved</label>
                                <p class="text-gray-900">{{ $ticket->resolved_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        @endif
                        
                        @if($ticket->assignedAgent)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Agent</label>
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $ticket->assignedAgent->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('modules.support.edit', $ticket) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Ticket
                        </a>
                        
                        @if($ticket->status !== 'resolved')
                            <form action="{{ route('modules.support.update', $ticket) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="subject" value="{{ $ticket->subject }}">
                                <input type="hidden" name="description" value="{{ $ticket->description }}">
                                <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                                <input type="hidden" name="category" value="{{ $ticket->category }}">
                                <input type="hidden" name="status" value="resolved">
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark as Resolved
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('modules.support.destroy', $ticket) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this ticket?')">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
