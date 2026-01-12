<template>
    <div class="home">
        <h1>Latest Blog Posts</h1>
        <div v-if="postStore.loading" class="loading">Loading posts...</div>
        <div v-else-if="postStore.error" class="error">
            {{ postStore.error.message || "Failed to load posts." }}
        </div>
        <div v-else class="post-list">
            <div
                v-for="post in postStore.allPosts"
                :key="post.id"
                class="post-card"
            >
                <h2>{{ post.title }}</h2>
                <div class="post-meta">
                    By {{ post.user ? post.user.name : "Unknown" }} on
                    {{ new Date(post.created_at).toLocaleDateString() }}
                </div>
                <p>{{ post.body }}</p>
            </div>
            <div v-if="postStore.allPosts.length === 0" class="no-posts">
                No posts found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { usePostStore } from "../stores/posts";

const postStore = usePostStore();

onMounted(() => {
    postStore.fetchAllPosts();
});
</script>

<style scoped>
.home {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
}

.post-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.post-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.post-card h2 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.post-meta {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
}

.loading,
.error,
.no-posts {
    text-align: center;
    font-size: 1.2rem;
    color: #555;
    margin-top: 2rem;
}

.error {
    color: #e74c3c;
}
</style>
