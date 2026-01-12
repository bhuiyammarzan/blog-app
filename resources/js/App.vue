<script setup>
import { useAuthStore } from "./stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
    await authStore.logout();
    localStorage.removeItem("token");
    router.push("/login");
};
</script>

<template>
    <div id="app">
        <nav class="navbar">
            <div class="container">
                <router-link to="/" class="logo">Laravel Vue SPA</router-link>
                <div class="nav-links">
                    <template v-if="!authStore.isAuthenticated">
                        <router-link to="/login">Login</router-link>
                        <router-link to="/register">Register</router-link>
                    </template>
                    <template v-else>
                        <router-link to="/dashboard">Dashboard</router-link>
                        <button @click="handleLogout" class="btn-logout">
                            Logout
                        </button>
                    </template>
                </div>
            </div>
        </nav>

        <main class="main-content">
            <router-view />
        </main>
    </div>
</template>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

.navbar {
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1rem 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

.nav-links {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.nav-links a {
    color: #666;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.nav-links a:hover {
    background-color: #f0f0f0;
}

.btn-logout {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
}

.main-content {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}
</style>
