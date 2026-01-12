import { defineStore } from "pinia";
import axios from "../axios";

export const usePostStore = defineStore("posts", {
    state: () => ({
        posts: [],
        currentPost: null,
        loading: false,
        error: null,
    }),

    getters: {
        allPosts: (state) => state.posts,
        // Helper to get posts belonging to a specific user
        getUserPosts: (state) => (userId) => {
            return state.posts.filter((post) => post.user_id === userId);
        },
    },

    actions: {
        async fetchAllPosts() {
            this.loading = true;
            try {
                const response = await axios.get("/api/posts");
                this.posts = response.data;
            } catch (error) {
                this.error = error;
            } finally {
                this.loading = false;
            }
        },

        async fetchSinglePost(id) {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.get(`/api/posts/${id}`);
                this.currentPost = response.data;
                return response.data;
            } catch (error) {
                this.error = error;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createPost(postData) {
            this.loading = true;
            try {
                const response = await axios.post("/api/posts", postData);
                // Add the new post to the top of the list
                this.posts.unshift(response.data);
                return response.data;
            } catch (error) {
                this.error = error;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updatePost(id, postData) {
            this.loading = true;
            try {
                const response = await axios.put(`/api/posts/${id}`, postData);
                // Update the post in the local list
                const index = this.posts.findIndex((p) => p.id === id);
                if (index !== -1) {
                    this.posts[index] = response.data;
                }
                return response.data;
            } catch (error) {
                this.error = error;
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deletePost(id) {
            this.loading = true;
            try {
                await axios.delete(`/api/posts/${id}`);
                // Remove the post from the local list
                this.posts = this.posts.filter((p) => p.id !== id);
            } catch (error) {
                this.error = error;
                throw error;
            } finally {
                this.loading = false;
            }
        },
    },
});
