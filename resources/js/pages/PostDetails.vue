<template>
    <div class="post-details-container">
        <div v-if="postStore.loading" class="loading">Loading post...</div>
        <div v-else-if="postStore.error" class="error">
            {{ postStore.error.message || "Failed to load post." }}
        </div>
        <div v-else-if="postStore.currentPost" class="post-details">
            <button @click="$router.push('/')" class="btn-back">&larr; Back to Home</button>
            
            <article>
                <h1 class="post-title">{{ postStore.currentPost.title }}</h1>
                <div class="post-meta">
                    <span class="author">By {{ postStore.currentPost.user ? postStore.currentPost.user.name : "Unknown" }}</span>
                    <span class="date"> &bull; {{ new Date(postStore.currentPost.created_at).toLocaleDateString() }}</span>
                </div>
                <div class="post-body">
                    {{ postStore.currentPost.body }}
                </div>
            </article>
        </div>
        <div v-else class="not-found">
            Post not found.
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import { usePostStore } from "../stores/posts";

const route = useRoute();
const postStore = usePostStore();

onMounted(() => {
    postStore.fetchSinglePost(route.params.id);
});

// Optional: Clear current post when leaving to avoid stale data flash
onUnmounted(() => {
    postStore.currentPost = null;
});
</script>

<style scoped>
.post-details-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.btn-back {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 1rem;
    margin-bottom: 2rem;
    padding: 0;
    text-decoration: underline;
}

.post-details {
    background: white;
    padding: 2.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.post-title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.post-meta {
    font-size: 1rem;
    color: #666;
    margin-bottom: 2rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}

.post-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
    white-space: pre-wrap;
}

.loading, .error, .not-found {
    text-align: center;
    margin-top: 3rem;
    font-size: 1.2rem;
    color: #666;
}

.error {
    color: #dc3545;
}
</style>
