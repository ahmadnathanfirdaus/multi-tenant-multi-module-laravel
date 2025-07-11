<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PostManager extends Component
{
    use WithPagination;

    public $title = '';
    public $content = '';
    public $editingPostId = null;
    public $showForm = false;

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:10',
    ];

    public function showCreateForm()
    {
        $this->reset(['title', 'content', 'editingPostId']);
        $this->showForm = true;
    }

    public function editPost($postId)
    {
        $post = Post::findOrFail($postId);
        $this->title = $post->title;
        $this->content = $post->content;
        $this->editingPostId = $postId;
        $this->showForm = true;
    }

    public function savePost()
    {
        $this->validate();

        if ($this->editingPostId) {
            $post = Post::findOrFail($this->editingPostId);
            $post->update([
                'title' => $this->title,
                'content' => $this->content,
            ]);
            session()->flash('message', 'Post berhasil diperbarui!');
        } else {
            Post::create([
                'title' => $this->title,
                'content' => $this->content,
                'user_id' => Auth::id() ?? 1, // Default user for demo
            ]);
            session()->flash('message', 'Post berhasil dibuat!');
        }

        $this->reset(['title', 'content', 'editingPostId', 'showForm']);
    }

    public function deletePost($postId)
    {
        Post::findOrFail($postId)->delete();
        session()->flash('message', 'Post berhasil dihapus!');
    }

    public function cancelEdit()
    {
        $this->reset(['title', 'content', 'editingPostId', 'showForm']);
    }

    public function render()
    {
        $posts = Post::with('user')->latest()->paginate(5);
        $tenant = app('tenant');

        return view('livewire.post-manager', [
            'posts' => $posts,
            'tenant' => $tenant,
        ]);
    }
}
