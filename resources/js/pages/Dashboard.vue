<template>
    <div class="dashboard">
        <div class="dashboard-card user-card">
            <h1>Dashboard</h1>
            <div v-if="authStore.user" class="user-info">
                <p><strong>Name:</strong> {{ authStore.user.name }}</p>
                <p><strong>Email:</strong> {{ authStore.user.email }}</p>
                <p class="welcome">Welcome to your dashboard!</p>
            </div>
        </div>

        <div class="dashboard-card post-management">
            <h2>{{ isEditing ? "Edit Post" : "Create New Post" }}</h2>
            <form @submit.prevent="handleSubmit" class="post-form">
                <div class="form-group">
                    <label>Title</label>
                    <input v-model="form.title" type="text" required />
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea v-model="form.body" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" :disabled="postStore.loading">
                        {{ isEditing ? "Update Post" : "Publish Post" }}
                    </button>
                    <button
                        v-if="isEditing"
                        type="button"
                        @click="cancelEdit"
                        class="btn-cancel"
                    >
                        Cancel
                    </button>
                </div>
                <p v-if="postStore.error" class="error-msg">
                    {{ postStore.error.message }}
                </p>
            </form>
        </div>

        <div class="dashboard-card">
            <h2>My Posts</h2>
            <div v-if="userPosts.length === 0" class="no-posts">
                You haven't created any posts yet.
            </div>
            <ul v-else class="post-list">
                <li v-for="post in userPosts" :key="post.id" class="post-item">
                    <div class="post-content">
                        <h3>{{ post.title }}</h3>
                        <p class="post-body">{{ post.body }}</p>
                        <small
                            >Posted on
                            {{
                                new Date(post.created_at).toLocaleDateString()
                            }}</small
                        >
                    </div>
                    <div class="post-actions">
                        <button @click="editPost(post)" class="btn-edit">
                            Edit
                        </button>
                        <button
                            @click="deletePost(post.id)"
                            class="btn-delete"
                            :disabled="postStore.loading"
                        >
                            Delete
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { usePostStore } from "../stores/posts";

const authStore = useAuthStore();
const postStore = usePostStore();

const form = ref({
    title: "",
    body: "",
});
const isEditing = ref(false);
const editingId = ref(null);

// Get posts for the current user
const userPosts = computed(() => {
    if (!authStore.user) return [];
    return postStore.allPosts.filter((p) => p.user_id === authStore.user.id);
});

onMounted(() => {
    postStore.fetchAllPosts();
});

const handleSubmit = async () => {
    try {
        if (isEditing.value) {
            await postStore.updatePost(editingId.value, form.value);
            alert("Post updated successfully!");
        } else {
            await postStore.createPost(form.value);
            alert("Post published successfully!");
        }
        resetForm();
    } catch (e) {
        console.error("Failed to save post", e);
    }
};

const editPost = (post) => {
    form.value = { title: post.title, body: post.body };
    isEditing.value = true;
    editingId.value = post.id;
};

const deletePost = async (id) => {
    if (confirm("Are you sure you want to delete this post?")) {
        await postStore.deletePost(id);
    }
};

const cancelEdit = () => {
    resetForm();
};

const resetForm = () => {
    form.value = { title: "", body: "" };
    isEditing.value = false;
    editingId.value = null;
};
</script>

<style scoped>
.dashboard {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.dashboard-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-info p {
    margin-bottom: 0.5rem;
    color: #555;
    font-size: 1.1rem;
}

.welcome {
    margin-top: 1rem;
    color: #4caf50;
    font-weight: 500;
}

h2 {
    margin-bottom: 1.5rem;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

/* Form Styles */
.post-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 600;
    color: #444;
}

.form-group input,
.form-group textarea {
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
    font-family: inherit;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 1rem;
}

.form-actions button {
    padding: 0.75rem 1.5rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}
.form-actions button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.form-actions button:hover:not(:disabled) {
    background-color: #0056b3;
}

.btn-cancel {
    background-color: #6c757d !important;
}
.btn-cancel:hover {
    background-color: #5a6268 !important;
}

.error-msg {
    color: red;
    font-size: 0.9rem;
}

/* Post List Styles */
.post-list {
    list-style: none;
    padding: 0;
}

.post-item {
    border-bottom: 1px solid #eee;
    padding: 1.5rem 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.post-item:last-child {
    border-bottom: none;
}

.post-content h3 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.post-body {
    color: #666;
    margin-bottom: 0.5rem;
    white-space: pre-wrap;
}

.post-item small {
    color: #999;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-edit,
.btn-delete {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    color: white;
}

.btn-edit {
    background-color: #ffc107;
    color: #333;
}

.btn-delete {
    background-color: #dc3545;
}

.no-posts {
    text-align: center;
    color: #777;
    padding: 1rem;
}
</style>
