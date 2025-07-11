<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Tenant Info -->
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
            <h2 class="text-xl font-bold">Tenant: {{ $tenant->name }}</h2>
            <p class="text-sm">Subdomain: {{ $tenant->subdomain }}</p>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Posts Management</h1>
            @if (!$showForm)
                <button wire:click="showCreateForm"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Buat Post Baru
                </button>
            @endif
        </div>

        <!-- Create/Edit Form -->
        @if ($showForm)
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-6">
                <h3 class="text-xl font-bold mb-4">
                    {{ $editingPostId ? 'Edit Post' : 'Buat Post Baru' }}
                </h3>

                <form wire:submit.prevent="savePost">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Judul
                        </label>
                        <input wire:model="title"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                               id="title"
                               type="text"
                               placeholder="Masukkan judul post">
                        @error('title')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                            Konten
                        </label>
                        <textarea wire:model="content"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror"
                                  id="content"
                                  rows="5"
                                  placeholder="Masukkan konten post"></textarea>
                        @error('content')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ $editingPostId ? 'Update Post' : 'Simpan Post' }}
                        </button>
                        <button type="button"
                                wire:click="cancelEdit"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Posts List -->
        <div class="space-y-4">
            @forelse ($posts as $post)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $post->content }}</p>
                            <div class="text-sm text-gray-500">
                                <span>By: {{ $post->user->name ?? 'Unknown' }}</span> |
                                <span>{{ $post->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button wire:click="editPost({{ $post->id }})"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Edit
                            </button>
                            <button wire:click="deletePost({{ $post->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus post ini?"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <p class="text-gray-600 text-lg">Belum ada post yang dibuat.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($posts->hasPages())
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
