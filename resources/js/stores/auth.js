import { defineStore } from "pinia";
import axios from "../axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        isAuthenticated: false,
        loading: false,
        errors: null,
    }),

    getters: {
        getUser: (state) => state.user,
        isLoggedIn: (state) => state.isAuthenticated,
    },

    actions: {
        async getCsrfCookie() {
            await axios.get("/sanctum/csrf-cookie");
        },

        async checkAuth() {
            try {
                await this.getCsrfCookie();
                const response = await axios.get("/api/user");
                this.user = response.data.user;
                this.isAuthenticated = true;
            } catch (error) {
                this.user = null;
                this.isAuthenticated = false;
            }
        },

        async register(credentials) {
            this.loading = true;
            this.errors = null;
            try {
                await this.getCsrfCookie();
                const response = await axios.post("/api/register", credentials);
                this.user = response.data.user;
                this.isAuthenticated = true;
                return response.data;
            } catch (error) {
                if(error.response?.data?.errors){
                    this.errors = error.response.data.errors;
                } else if (error.response?.data?.message){
                    this.errors = { message: error.response.data.message };
                } else {
                    this.errors = { message: error.message || "An unexpected error occurred." };
                }
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            this.errors = null;
            try {
                await this.getCsrfCookie();
                const response = await axios.post("/api/login", credentials);
                this.user = response.data.user;
                this.isAuthenticated = true;
                return response.data;
            } catch (error) {
                if(error.response?.data?.errors){
                    this.errors = error.response.data.errors;
                } else if (error.response?.data?.message){
                    this.errors = { message: error.response.data.message };
                } else {
                    this.errors = { message: error.message || "An unexpected error occurred." };
                }
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await axios.post("/api/logout");
                this.user = null;
                this.isAuthenticated = false;
            } catch (error) {
                console.error("Logout error:", error);
            }
        },
        clearErrors() {
            this.errors = null;
        },
    },
});
